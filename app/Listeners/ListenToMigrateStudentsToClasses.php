<?php

namespace App\Listeners;

use App\Events\InitProcessToMigrateStudentsToClassesEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ListenToMigrateStudentsToClasses
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(InitProcessToMigrateStudentsToClassesEvent $event): void
    {
        //
    }
}
