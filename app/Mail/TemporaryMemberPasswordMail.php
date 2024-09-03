<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\URL;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;
use App\Models\Membresia;
use Illuminate\Support\Carbon;

class TemporaryMemberPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $temporaryPassword;
    public $membresia;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $temporaryPassword, Membresia $membresia)
    {
        $this->user = $user;
        $this->temporaryPassword = $temporaryPassword;
        $this->membresia = $membresia;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: 'Detalles de su cuenta y membresía',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            ['id' => $this->user->idUsuario, 'hash' => sha1($this->user->email)]
        );

        return new Content(
            view: 'emails.temporary-member-password',
            with: [
                'user' => $this->user,
                'temporaryPassword' => $this->temporaryPassword,
                'membresia' => $this->membresia,
                'verificationUrl' => $verificationUrl,
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
