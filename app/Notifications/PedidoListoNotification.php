<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Queue\ShouldQueue;

class PedidoListoNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $recibo;

    public function __construct($recibo)
    {
        $this->recibo = $recibo;
    }

    public function via($notifiable): array
    {
        $channels = [];

        // Si el notifiable (DatosRecibo) tiene email, usa mail
        if (!empty($notifiable->email)) {
            $channels[] = 'mail';
        }

        // Si tiene teléfono, podrías añadir un canal personalizado para SMS
        // Por ahora solo usamos 'mail', pero dejamos espacio para extender
        // Ej: $channels[] = TwilioChannel::class;

        return $channels;
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Tu pedido está listo')
            ->markdown('emails.pedido-listo', [
                'recibo' => $this->recibo,
            ]);
    }

    public function toArray($notifiable): array
    {
        return [
            'recibo_id' => $this->recibo->id,
            'mensaje' => "Su pedido #{$this->recibo->codigo} está listo.",
        ];
    }
}