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

class AStudentCreationFailedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public string $tenantId, 
        public int $taskId,
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
        $task = ImportTask::findOrFail($this->taskId);

        $data = $task->payload;

        $error = null;

        if($this->error){

            $error = $this->error;
        }
        else{

            $this->error = $task->error;
        }

        $name = $data['name'] . ' ' . $data['prenames'];

        return ['tenantId' => $this->tenantId, 'error' => $error, 'userName' => $name];
    }


    public function broadcastAs(): string
    {
        return 'student.creation.failed'; 
    }
}
