<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Admins extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @param array $data Datos del administrador
     * @param string $action Tipo de acción: 'creado' o 'modificado'
     */
    public function __construct(
        public array $data,
        public string $action = 'creado' // valor por defecto
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->action === 'modificado' ? 'Admin Modificado' : 'Admin Creado',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $view = $this->action === 'modificado'
            ? 'Admin.Email.modificacion_admin'
            : 'Admin.Email.ingreso_admin';

        return new Content(view: $view);
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
