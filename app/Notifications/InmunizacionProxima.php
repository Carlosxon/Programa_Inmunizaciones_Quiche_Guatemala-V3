<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Inmunizacion;

class InmunizacionProxima extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $inmunizacion;

    public function __construct(Inmunizacion $inmunizacion)
    {
        $this->inmunizacion = $inmunizacion;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    // ejemplo:
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!')
                   // ejemplo construido
                    ->line('Tienes una inmunización programada próximamente.')
                    ->line('Paciente: ' . $this->inmunizacion->nombre_paciente)
                    ->line('Tipo de vacuna: ' . $this->inmunizacion->tipo_vacuna)
                    ->line('Fecha de aplicación: ' . $this->inmunizacion->fecha_aplicacion->toFormattedDateString())
                    ->line('Gracias por usar nuestro sistema de inmunizaciones.');
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
