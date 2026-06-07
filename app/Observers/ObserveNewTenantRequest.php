<?php

namespace App\Observers;

use App\Jobs\JobToNotifyCentralAdminOfNewTenantRequest;
use App\Jobs\JobToNotifyUserOfNewTenantRequest;
use App\Models\RequestToCreateNewTenant;

class ObserveNewTenantRequest
{
    /**
     * Handle the RequestToCreateNewTenant "created" event.
     */
    public function created(RequestToCreateNewTenant $requestToCreateNewTenant): void
    {
        JobToNotifyUserOfNewTenantRequest::dispatch($requestToCreateNewTenant->id);

        JobToNotifyCentralAdminOfNewTenantRequest::dispatch($requestToCreateNewTenant->id);
    }

    /**
     * Handle the RequestToCreateNewTenant "updated" event.
     */
    public function updated(RequestToCreateNewTenant $requestToCreateNewTenant): void
    {
        //
    }

    /**
     * Handle the RequestToCreateNewTenant "deleted" event.
     */
    public function deleted(RequestToCreateNewTenant $requestToCreateNewTenant): void
    {
        //
    }

    /**
     * Handle the RequestToCreateNewTenant "restored" event.
     */
    public function restored(RequestToCreateNewTenant $requestToCreateNewTenant): void
    {
        //
    }

    /**
     * Handle the RequestToCreateNewTenant "force deleted" event.
     */
    public function forceDeleted(RequestToCreateNewTenant $requestToCreateNewTenant): void
    {
        //
    }
}
