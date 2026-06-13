<?php

namespace App\Notifications;

use App\Models\CentralUser;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CentralRealTimeNotification extends Notification implements ShouldQueue, ShouldBroadcast
{
    use Queueable;

    /**
     * @param string $titre      Le titre de la notification
     * @param string $message    Le corps du message
     * @param string $type       ex: 'info' | 'success' | 'warning' | 'error'
     * @param string|null $url   Lien optionnel (redirection au clic)
     * @param array  $meta       Données supplémentaires
     */
    public function __construct(
        public readonly string  $title,
        public readonly string  $message,
        public readonly string  $type = 'info',
        public readonly ?string $url = null,
        public readonly ?array   $meta = null,
        public readonly ?string  $tenantId = null,
    ) {}

    /**
     * Canaux de livraison.
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Payload stocké en DB (table notifications).
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'title'   => $this->title,
            'message' => $this->message,
            'type'    => $this->type,
            'url'     => $this->url,
            'meta'    => $this->meta,
        ];
    }

    /**
     * Payload broadcasté via Reverb.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'id'         => $this->id,   
            'title'      => $this->title,
            'message'    => $this->message,
            'type'       => $this->type,
            'url'        => $this->url,
            'created_at' => now()->toISOString(),
        ]);
    }

    /**
     * Canal privé par utilisateur dans le tenant.
     * Format: private-tenant.{tenantId}.user.{userId}
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('central-admin'),
        ];
    }

    /**
     * Nom de l'event broadcasté (écouté côté JS).
     */
    public function broadcastAs(): string
    {
        return 'central.notifications';
    }
}
