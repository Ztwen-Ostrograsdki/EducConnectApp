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

class TenantDirectorDataUpdatedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public ?string $tenantId = null,
        public ?string $message = null,
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
        if($this->tenantId){

            return [
                new PrivateChannel('tenant.' . $this->tenantId . '.directeur'),
            ];
        }

        else{

            $tenantsId = Tenant::all()->pluck('id')->toArray();

            $channels = [];

            foreach($tenantsId as $id){

                $channels[] = new PrivateChannel('tenant.' . $id . '.directeur');
            }

            return $channels;
            
        }
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
