<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PDFIsReady extends Notification implements ShouldQueue, ShouldBroadcast
{
    use Queueable;

    /**
     * @param string $title      Le titre de la notification
     * @param string $message    Le corps du message
     * @param string $type       ex: 'info' | 'success' | 'warning' | 'error'
     * @param string|null $url   Lien optionnel (redirection au clic)
     */
    public function __construct(
        public readonly string  $title,
        public readonly string  $message,
        public readonly string  $type = 'info',
        public readonly ?string $url = null,
        public readonly ?string  $tenantId = null,
        public readonly ?string  $userEmail = null,
        public readonly ?string  $target = null,
        public readonly ?string  $eventName = null,
    ) {
        $this->onQueue('pdf');
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
            'title'      => $this->title,
            'message'    => $this->message,
            'type'       => $this->type,
            'url'        => $this->url,
            'name'       => 'make.pdf',
            'target'     => $this->target,
            'eventName'  => $this->eventName,
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
            'name'       => 'make.pdf',
            'target'     => $this->target,
            'eventName'  => $this->eventName,
        ]);
    }

    /**
     * Canal privé par utilisateur dans le tenant.
     */
    public function broadcastOn(): array
    {
        if($this->tenantId){

            if($this->userEmail){

                $userId = User::where('email', $this->userEmail)->value('id');
                
                return [
                    new PrivateChannel('tenant.' . $this->tenantId . '.user.' . $userId),
                ];
            }
            else{

                return [
                    new PrivateChannel('tenant.' . $this->tenantId . '.directeur')
                ];
            }
        }

        return [
            new PrivateChannel('central-admin'),
        ];
    }

    
}