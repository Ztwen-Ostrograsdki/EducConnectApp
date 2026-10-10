<?php

namespace App\Observers;

use App\Events\DataUpdatedEvent;
use App\Models\TimePlanSlot;
use App\Models\User;

class ObserveTimePlanSlot
{
    /**
     * Handle the TimePlanSlot "created" event.
     */
    public function created(TimePlanSlot $timePlanSlot): void
    {
        $tenantId = User::first()->tenant_id;
        
        broadcast(new DataUpdatedEvent($tenantId));
    }

    /**
     * Handle the TimePlanSlot "updated" event.
     */
    public function updated(TimePlanSlot $timePlanSlot): void
    {
        $tenantId = User::first()->tenant_id;
        
        broadcast(new DataUpdatedEvent($tenantId));
    }

    /**
     * Handle the TimePlanSlot "deleted" event.
     */
    public function deleted(TimePlanSlot $timePlanSlot): void
    {
        $tenantId = User::first()->tenant_id;
        
        broadcast(new DataUpdatedEvent($tenantId));
    }

    /**
     * Handle the TimePlanSlot "restored" event.
     */
    public function restored(TimePlanSlot $timePlanSlot): void
    {
        //
    }

    /**
     * Handle the TimePlanSlot "force deleted" event.
     */
    public function forceDeleted(TimePlanSlot $timePlanSlot): void
    {
        
    }
}
