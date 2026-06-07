<?php

namespace App\Observers;

use App\Events\SomesErrorsOccurWhenInitializeTenantSpaceEvent;
use App\Jobs\JobToCreateTenantDirectories;
use App\Jobs\JobToSeedRolesAndPermissionsIntoTenantDB;
use App\Jobs\JobToSendCredentialsToCreatedTenant;
use App\Models\Tenant;
use Illuminate\Support\Facades\Bus;
use Throwable;

class ObserveTenant
{
    /**
     * Handle the Tenant "created" event.
     */
    public function created(Tenant $tenant): void
    {
        Bus::chain([

            new JobToSendCredentialsToCreatedTenant($tenant->id),

            new JobToSeedRolesAndPermissionsIntoTenantDB($tenant->id),

            new JobToCreateTenantDirectories($tenant->id),
        ])
        ->catch(function (Throwable $e) use ($tenant) {
            broadcast(new SomesErrorsOccurWhenInitializeTenantSpaceEvent($tenant->id, $e->getMessage()));
        })
        ->dispatch();
    }

    /**
     * Handle the Tenant "updated" event.
     */
    public function updated(Tenant $tenant): void
    {
        //
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
