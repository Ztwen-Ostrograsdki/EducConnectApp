<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StudentsCreationProcessProgressEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

     /**
     * Create a new event instance.
     */
    public function __construct(
        public string $tenantId, 
        public string $batchId, 
        public int $totalJobs, 
        public float $processed, 
        public float $percentage,
        public int $failed,
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

    public function broadcastWith() : array
    {

        return [
            'tenantId' => $this->tenantId,
            'batchId' => $this->batchId,
            'totalJobs' => $this->totalJobs,
            'processed' => $this->processed,
            'percentage' => $this->percentage,
            'failed' => $this->failed,

        ];
    }
    public function broadcastAs()
    {
        return 'students.creation.tasks.progress';
    }
}
