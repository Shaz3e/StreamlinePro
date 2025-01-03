<?php

namespace App\Http\Controllers\User\PaymentMethods\NgeniusNetwork;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\NgeniusGateway;
use App\Models\Payment;
use Illuminate\Http\Request;
use Jeybin\Networkintl\Ngenius;

class NgeniusNetworkController extends Controller
{
    protected $conversionAmount = 3.67;

    public function hostedCheckout(Request $request)
    {
        $invoice = Invoice::find($request->invoice_id);

        if (!$invoice) {
            return response()->json(['error' => 'Invoice not found'], 404);
        }

        // Get currency
        $currency = 'AED'; //currency(DiligentCreators('currency'), ['name'])['name'];

        // Get max amount
        $maxAmount = $invoice->total - $invoice->total_paid;

        // Convert amount to cents
        $amountInCent = $maxAmount * 100;
        $amountInCents = $amountInCent * $this->conversionAmount;

        // return $amountInCents;

        $apikey = config('ngenius.api_key');
        $outlet = config('ngenius.outlet');
        $checkout_url = config('ngenius.' . config('ngenius.environment') . '.checkout_url');

        // Disable SSL verification on production
        if (config('app.env') == 'local') {
            $verify = false;
        } else {
            $verify = true;
        }

        // Generate Access Token
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', $checkout_url . '/identity/auth/access-token', [
            'headers' => [
                'Authorization' => 'Basic ' . $apikey,
                'accept' => 'application/vnd.ni-identity.v1+json',
                'content-type' => 'application/vnd.ni-identity.v1+json',
            ],
            'verify' => $verify,
        ]);


        $responseData = json_decode($response->getBody(), true);

        // Get Access Token
        $accessToken = $responseData['access_token'];

        // Step 2: Send the second request with the access token
        $response = $client->request('POST', $checkout_url . '/transactions/outlets/' . $outlet . '/orders', [
            'json' => [
                'action' => 'PURCHASE',
                'amount' => [
                    'currencyCode' => $currency,
                    'value' => $amountInCents,
                ],
                "language" => "en",
                "emailAddress" => $invoice->user->email,
                "billingAddress" => [
                    "firstName" => $invoice->user->first_name,
                    "lastName" => $invoice->user->last_name,
                    "address" => $invoice->user->address,
                    "city" => $invoice->user->city,
                    "countryCode" => $invoice->country->alpha2
                ],
                "merchantOrderReference" => $invoice->id,
                "merchantAttributes" => [
                    "cancelUrl" => route('invoice.show', $invoice->id),
                    "cancelText" => "Cancel",
                    "redirectUrl" => config('ngenius.domain') . "/payment-method/ngenius-network?",
                    // "offerOnly" => "VISA", // Only visa card accepted // https://docs.ngenius-payments.com/reference/pre-populate-cardholders-name-on-pay-page
                    // "showPayerName" => true, // Payer can enter name
                    // "maskPaymentInfo" => true
                ],
            ],
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'accept' => 'application/vnd.ni-payment.v2+json',
                'content-type' => 'application/vnd.ni-payment.v2+json',
            ],
            'verify' => $verify
        ]);

        $output = json_decode($response->getBody(), true);

        if (isset($output['_links']['payment']['href'])) {
            $payment_link = $output['_links']['payment']['href'] . "&slim=" . config('ngenius.slim_mode');

            // \Log::info($output);

            // Check if invoice exists
            $ngeniusGatewayInvoice = NgeniusGateway::where('invoice_id', $invoice->id)->first();

            if ($ngeniusGatewayInvoice) {
                // Update data into ngenius_gateways table
                $ngeniusGatewayInvoice->update([
                    'reference' => $output['_embedded']['payment'][0]['orderReference'],
                    'amount' => $maxAmount,
                ]);
            } else {
                // Add data into ngenius_gateways table
                $ngenius = new NgeniusGateway();
                $ngenius->invoice_id = $invoice->id;
                $ngenius->reference = $output['_embedded']['payment'][0]['orderReference'];
                $ngenius->outlet_id = $outlet;
                $ngenius->amount = $maxAmount;
                $ngenius->save();
            }
        } else {
            echo "Error fetching payment link.";
            exit();
        }
        return redirect()->away($payment_link);
    }

    public function processPayment(Request $request)
    {
        //
    }

    public function handlePaymentConfirmation(Request $request)
    {
        return 'handle payment confirmation';
    }
    public function handleHostedPaymentConfirmation(Request $request)
    {
        // ref=f1cea9e0-c08f-4441-94ac-efec767f51dc
        if ($request->has('ref')) {

            $ref = $request->ref;

            $checkout_url = config('ngenius.' . config('ngenius.environment') . '.checkout_url');


            // Disable SSL verification on production
            if (config('app.env') == 'local') {
                $verify = false;
            } else {
                $verify = true;
            }

            $client = new \GuzzleHttp\Client();
            $response = $client->request('POST', $checkout_url . '/identity/auth/access-token', [
                'headers' => [
                    'Authorization' => 'Basic ' . config('ngenius.api_key'),
                    'accept' => 'application/vnd.ni-identity.v1+json',
                    'content-type' => 'application/vnd.ni-identity.v1+json',
                ],
                'verify' => $verify,
            ]);

            $responseData = json_decode($response->getBody(), true);

            $accessToken = $responseData['access_token'];
            $tokenType = $responseData['token_type'];

            $response = $client->request('GET', $checkout_url . '/transactions/outlets/' . config('ngenius.outlet') . '/orders/' . $ref, [
                'headers' => [
                    'Authorization' => $tokenType . ' ' . $accessToken,
                    'accept' => 'application/vnd.ni-payment.v2+json',
                ],
                'verify' => $verify,
            ]);

            // echo $response->getBody();
            $responseData = json_decode($response->getBody(), true);

            $merchantOrderReference = $responseData['merchantOrderReference'];
            $orderReference = $responseData['_embedded']['payment'][0]['orderReference'];
            $reference = $responseData['_embedded']['payment'][0]['reference'];
            $resultCode = $responseData['_embedded']['payment'][0]['authResponse']['resultCode'];
            $amount = $responseData['amount']['value'] / $this->conversionAmount;

            if ($resultCode == '00') {
                // Create new payment instance and save
                $payment = Payment::where('transaction_number', $reference)->first();

                if (!$payment) {
                    $payment = new Payment();
                    $payment->transaction_number = $reference;
                    $payment->invoice_id = $merchantOrderReference;
                    $payment->transaction_date = now();

                    $payment->amount = $amount / 100;
                    $payment->payment_method = DiligentCreators('ngenius_hosted_checkout_display_name');
                    $payment->save();

                    $ngeniusGateway = NgeniusGateway::where('reference', $orderReference)->first();
                    if ($ngeniusGateway) {
                        $ngeniusGateway->delete();
                    }

                    session()->flash('success', 'Payment successful!');
                    return redirect()->route('invoice.show', $merchantOrderReference);
                }
            } else {
                session()->flash('error', 'Payment failed!');
                return redirect()->route('invoice.show', $merchantOrderReference);
            }
        }

        session()->flash('error', 'Invalid Reference Code!');
        return redirect()->route('dashboard');
    }
}
