<?php

namespace App\Listeners;

use App\Events\InitProcessToCreateOrUpdateModelWithRoleEvent;
use App\Jobs\JobToCreateOrUpdateModelWithRole;

class ListenToCreateOrUpdateModelWithRole
{

    /**
     * Handle the event.
     */
    public function handle(InitProcessToCreateOrUpdateModelWithRoleEvent $event): void
    {
        JobToCreateOrUpdateModelWithRole::dispatch(
            tenantId: $event->tenantId, 
            role:     $event->role, 
            userId:   $event->userId, 
            data:     $event->data
        );
    }
}
