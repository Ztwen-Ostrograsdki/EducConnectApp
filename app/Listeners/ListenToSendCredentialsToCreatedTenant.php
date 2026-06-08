<?php

namespace App\Listeners;

use App\Events\SendCredentialsToCreatedTenantEvent;
use App\Events\SomesErrorsOccurWhenInitializeTenantSpaceEvent;
use App\Jobs\JobToCreateTenantDirectories;
use App\Jobs\JobToSeedRolesAndPermissionsIntoTenantDB;
use App\Jobs\JobToSendCredentialsToCreatedTenant;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Bus;
use Throwable;

class ListenToSendCredentialsToCreatedTenant
{

    /**
     * Handle the event.
     */
    public function handle(SendCredentialsToCreatedTenantEvent $event): void
    {
        $jobs = [
            
            new JobToSendCredentialsToCreatedTenant($event->tenantId, null, $event->space_url),

            new JobToCreateTenantDirectories($event->tenantId),

        ];

        if($event->seedRoles){

            $jobs[] = new JobToSeedRolesAndPermissionsIntoTenantDB($event->tenantId);

        }

        Bus::chain($jobs)
        ->catch(function (Throwable $e) use ($event) {
            
            broadcast(new SomesErrorsOccurWhenInitializeTenantSpaceEvent($event->tenantId, $e->getMessage()));
        })
        ->dispatch();
    }
}
