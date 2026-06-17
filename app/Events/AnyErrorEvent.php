<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AnyErrorEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public ?string $target,
        public ?string $message,
        public ?string $tenantId = null,
        public ?int $userId = null,
    )
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        if($this->tenantId && $this->userId){
            return [
                new PrivateChannel('tenant.' . $this->tenantId . '.user.' . $this->userId),
            ];
        }
        else{

            return [
                new PrivateChannel('central-admin'),
            ];
        }
    }

    public function broadcastWith(): array
    {
        return ['target' => $this->target, 'error' => $this->message];
    }


     // 👇 Forcer la queue pour le broadcasting
    public function broadcastQueue(): string
    {
        return 'broadcasting';
    }

    // 👇 Forcer la connexion
    public function broadcastConnection(): string
    {
        return 'redis';
    }
}
