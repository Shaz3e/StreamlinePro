<?php

namespace App\Http\Controllers\Admin\AppSetting;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;

class MailSettingController extends Controller
{
    public function mail()
    {
        // Check authorize
        Gate::authorize('mail', AppSetting::class);

        return view('admin.app-setting.main');
    }

    public function mailStore(Request $request)
    {
        // Check authorize
        Gate::authorize('mailStore', AppSetting::class);

        // Mail From Setting
        if ($request->has('mailFromSetting')) {
            // Validate the request data based on the rules
            $validated = $request->validate([
                'mail_from_name' => 'required|max:255',
                'mail_from_address' => 'required|max:255',
            ]);

            $envPath = base_path('.env');
            $envContent = File::get($envPath);

            $envData = [
                'MAIL_FROM_NAME' => "\"{$validated['mail_from_name']}\"",
                'MAIL_FROM_ADDRESS' => "\"{$validated['mail_from_address']}\"",
            ];

            // Update the key-value pairs
            foreach ($envData as $key => $value) {
                $envContent = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $envContent);
            }

            File::put($envPath, $envContent);
        }

        // Mail SMTP Setting
        if ($request->has('mailSmtpSetting')) {
            // Validate the request data based on the rules
            $validated = $request->validate([
                'smtp_email' => 'required|email|max:255',
                'smtp_password' => 'required|max:255',
                'smtp_host' => 'required|max:255',
                'smtp_port' => 'required|max:255',
                'smtp_encryptions' => 'required|max:255',
            ]);

            $envPath = base_path('.env');
            $envContent = File::get($envPath);

            $envData = [
                'MAIL_USERNAME' => $validated['smtp_email'],
                'MAIL_PASSWORD' => "\"{$validated['smtp_password']}\"",
                'MAIL_HOST' => $validated['smtp_host'],
                'MAIL_PORT' => $validated['smtp_port'],
                'MAIL_ENCRYPTION' => $validated['smtp_encryptions'],
            ];

            // Update the key-value pairs
            foreach ($envData as $key => $value) {
                $envContent = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $envContent);
            }

            File::put($envPath, $envContent);
        }

        // Optimize Clear
        Artisan::call('optimize:clear');

        // Redirect back with success message
        return back()->with('success', 'Setting Saved');
    }
}
