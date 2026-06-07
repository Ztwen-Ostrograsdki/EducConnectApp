<?php

namespace App\Events;

use App\Models\Tenant;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FailedToSeedRolesAndPermissionsIntoCreatedTenantDBEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public string $tenantId,
        public string $error,
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
            new PrivateChannel('tenant.' . $this->tenantId . '.directeur')
        ];
    }


    public function broadcastAs(): string
    {
        return 'tenant.roles.seed.failed'; 
    }

    public function broadcastWith(): array
    {
        return ['tenant' => $this->tenantId, 'error' => $this->error];
    }
}
