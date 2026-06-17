<?php

namespace App\Events;

use App\Models\ImportTask;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ATeacherCreationFailedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public string $tenantId, 
        public int $taskId,
        public string $teacherName,
        public ?string $error = null,
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
            new PrivateChannel('tenant.' . $this->tenantId . '.directeur'),
        ];
    }

    public function broadcastWith(): array
    {
        return ['tenantId' => $this->tenantId, 'error' => $this->error, 'userName' => $this->teacherName];
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
