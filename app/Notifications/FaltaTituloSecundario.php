<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FaltaTituloSecundario extends Notification
{
    use Queueable;

    public $alumnos;


    /**
     * Create a new notification instance.
     */
    public function __construct($alumnos)
    {
        $this->alumnos = $alumnos;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }


    public function toDatabase(object $notifiable): array
    {
        return [
            'titulo' => 'Alumnos sin título secundario',
            'mensaje' => 'Hay ' . $this->alumnos->count() . ' alumnos sin entregar su título secundario.',
            'url' => '/admin/alumnos?filtro=titulo_no_entregado', // o la ruta que quieras
            'entity_id' => $this->alumnos->pluck('id')->toArray(),
            'tipo' => 'warning'
        ];
    }
    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
