<?php

namespace App\Events;

use App\Models\RequestToCreateNewTenant;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProcessToCreateTenantSpaceFailedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public RequestToCreateNewTenant $demande_request
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


    public function broadcastAs()
    {
        return 'tenant.space.creation.failed';
    }

    public function broadcastWith(): array
    {
        return ['request_email' => $this->demande_request->email];
    }
}
