<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class RealTimeNotification extends Notification implements ShouldQueue, ShouldBroadcast
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
        public readonly string  $userEmail,
        public readonly string  $tenantId,
        public readonly string  $title,
        public readonly string  $message,
        public readonly string  $type = 'info',
        public readonly ?array   $meta = null,
    ) {
        $this->onQueue('notifications');
    }

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
            'meta'    => $this->meta,
            'name'    => 'default',
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
            'created_at' => now()->toISOString(),
            'name'       => 'default',
        ]);
    }

    /**
     * Canal privé par utilisateur dans le tenant.
     * Format: private-tenant.{tenantId}.user.{userId}
     */
    public function broadcastOn(): array
    {
        $userId = User::where('email', $this->userEmail)->value('id');

        return [
            new PrivateChannel(
                'tenant.' . $this->tenantId . '.user.' . $userId,
            ),
        ];
    }

    
}