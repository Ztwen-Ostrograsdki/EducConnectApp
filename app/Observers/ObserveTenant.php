<?php

namespace App\Observers;

use App\Events\TenantSpaceRestrictedOnlyForDirectorEvent;
use App\Models\Tenant;

class ObserveTenant
{
    /**
     * Handle the Tenant "created" event.
     */
    public function created(Tenant $tenant): void
    {
        
    }

    /**
     * Handle the Tenant "updated" event.
     */
    public function updated(Tenant $tenant): void
    {
        if($tenant->wasChanged('open_only_for_tenant') && $tenant->open_only_for_tenant){

            broadcast(new TenantSpaceRestrictedOnlyForDirectorEvent(tenant($tenant->id)));

        }
    }

    /**
     * Handle the Tenant "deleted" event.
     */
    public function deleting(Tenant $tenant): void
    {
        if($tenant){

            broadcast(new TenantSpaceRestrictedOnlyForDirectorEvent(tenant($tenant->id)));

        }
    }

    /**
     * Handle the Tenant "deleted" event.
     */
    public function deleted(Tenant $tenant): void
    {
        //
    }

    /**
     * Handle the Tenant "restored" event.
     */
    public function restored(Tenant $tenant): void
    {
        //
    }

    /**
     * Handle the Tenant "force deleted" event.
     */
    public function forceDeleted(Tenant $tenant): void
    {
        //
    }
}
