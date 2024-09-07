<?php

namespace App\Mail\User\Invoice;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentConfirmationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $viewInvoice;

    /**
     * Create a new message instance.
     */
    public function __construct(public $invoice, public $payment)
    {
        $this->subject = 'Invoice Payment Confirmation';
        $this->invoice = $invoice;
        $this->payment = $payment;
        $this->viewInvoice = route('invoice.show', $this->invoice->id);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.user.invoice.payment-confirmation',
            with: [
                'subject' => $this->subject,
                'invoice' => $this->invoice,
                'payment' => $this->payment,
                'viewInvoice' => $this->viewInvoice,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
