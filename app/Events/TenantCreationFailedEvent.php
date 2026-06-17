<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TenantCreationFailedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public ?string $name, 
        public ?string $domain_name, 
        public ?string $error,
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
        return [
            new PrivateChannel('central-admin'),
        ];
    }


    public function broadcastWith(): array
    {
        return ['tenant' => $this->name, 'error' => $this->error, 'domain_name' => $this->domain_name];
    }

    public function broadcastQueue(): string
    {
        return 'broadcasting';
    }

    public function broadcastConnection(): string
    {
        return 'redis';
    }

}
