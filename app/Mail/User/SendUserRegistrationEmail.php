<?php

namespace App\Mail\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class SendUserRegistrationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $password;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Registration Email',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $token = Str::random(64);
        $this->user->update([
            'remember_token' => $token
        ]);

        return new Content(
            markdown: 'emails.user.send-user-registration-email',
            with: [
                'user' => $this->user,
                'password' => $this->password,
                'url' => config('app.url') . '/login',
                // 'url' => config('app.url') . '/login?token=' . $token,
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
