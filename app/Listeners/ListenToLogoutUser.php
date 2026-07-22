<?php

namespace App\Listeners;

use App\Events\UserAccountWasBlockedEvent;
use App\Models\User;
use App\Notifications\RealTimeNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ListenToLogoutUser
{

    /**
     * Handle the event.
     */
    public function handle(UserAccountWasBlockedEvent $event): void
    {
        


    }
}
