<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Mail\Mailables\Address;
class TemporaryPasswordMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
     //ShouldQueue  este metodo hace colasSi estás usando colas 
     //para el envío de correos (lo cual es recomendado y parece 
     //ser tu caso ya que TemporaryPasswordMail implementa ShouldQueue), el comportamiento será diferente:
    //a. El trabajo se añadirá a la cola como de costumbre.
    //b. El worker de la cola intentará procesar el trabajo.
    //c. Cuando el worker intente enviar el correo, encontrará el mismo problema de conexión.
    //d. Dependiendo de tu configuración de colas, el trabajo fallido se manejará de la siguiente manera:

    //Se reintentará después de un cierto intervalo.
    //Después de varios intentos fallidos, se moverá a la cola de trabajos fallidos.
    public $user;
    public $temporaryPassword;
    public function __construct($user, $temporaryPassword)
    {
        $this->user = $user;
        $this->temporaryPassword = $temporaryPassword;
    }
    
    

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: 'Your Temporary Password',
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
            view: 'emails.temporary-password',
            with: [
                'user' => $this->user,
                'temporaryPassword' => $this->temporaryPassword,
                'verificationUrl' => $verificationUrl,
            ],
        );
    }

    /**
     * 
     * 
     * 
     * 
     * 
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
