<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InitProcessToCreateOrUpdateModelWithRoleEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public string $tenantId,
        public string $role,
        public int $userId,
        public array $data,
    )
    {
        //
    }

    
}
