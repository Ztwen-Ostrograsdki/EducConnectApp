<?php

namespace App\Listeners;

use App\Events\InitProcessToCreateTenantSpaceEvent;
use App\Events\ProcessToCreateTenantSpaceFailedEvent;
use App\Events\TenantCreatedEvent;
use App\Jobs\JobToCreateTenantSpace;
use Illuminate\Bus\Batch;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Bus;
use Throwable;

class ListenToCreateTenantSpace
{

    /**
     * Handle the event.
     */
    public function handle(InitProcessToCreateTenantSpaceEvent $event): void
    {
        $batch = Bus::batch([

            new JobToCreateTenantSpace($event->demande_request),

        ])->then(function(Batch $batch) use ($event){

            TenantCreatedEvent::dispatch($event->demande_request->email);
        })
        ->catch(function(Batch $batch, Throwable $er) use ($event){

            ProcessToCreateTenantSpaceFailedEvent::dispatch($event->demande_request);

        })->finally(function(Batch $batch){


        })->name('tenant_creation')->dispatch();
    }
}
