<?php

namespace App\Observers;

use App\Events\DataUpdatedEvent;
use App\Models\TimePlan;
use App\Models\User;

class ObserveTimePlan
{
    /**
     * Handle the TimePlan "created" event.
     */
    public function created(TimePlan $timePlan): void
    {
        $tenantId = User::first()->tenant_id;
        
        broadcast(new DataUpdatedEvent($tenantId));
    }

    /**
     * Handle the TimePlan "updated" event.
     */
    public function updated(TimePlan $timePlan): void
    {
        $tenantId = User::first()->tenant_id;
        
        broadcast(new DataUpdatedEvent($tenantId));
    }

    /**
     * Handle the TimePlan "deleted" event.
     */
    public function deleted(TimePlan $timePlan): void
    {
        $tenantId = User::first()->tenant_id;
        
        broadcast(new DataUpdatedEvent($tenantId));
    }

    /**
     * Handle the TimePlan "restored" event.
     */
    public function restored(TimePlan $timePlan): void
    {
        $tenantId = User::first()->tenant_id;
        
        broadcast(new DataUpdatedEvent($tenantId));
    }

    /**
     * Handle the TimePlan "force deleted" event.
     */
    public function forceDeleted(TimePlan $timePlan): void
    {
        $tenantId = User::first()->tenant_id;
        
        broadcast(new DataUpdatedEvent($tenantId));
    }
}
