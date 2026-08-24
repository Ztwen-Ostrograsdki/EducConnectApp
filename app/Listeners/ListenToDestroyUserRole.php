<?php

namespace App\Listeners;

use App\Events\InitProcessToDestroyUserRoleEvent;
use App\Jobs\JobToDestroyUserRole;

class ListenToDestroyUserRole
{
    /**
     * Handle the event.
     */
    public function handle(InitProcessToDestroyUserRoleEvent $event): void
    {
        JobToDestroyUserRole::dispatch(
            tenantId: $event->tenantId, 
            role:     $event->role, 
            userId:   $event->userId, 
            data:     $event->data
        );
    }
}
