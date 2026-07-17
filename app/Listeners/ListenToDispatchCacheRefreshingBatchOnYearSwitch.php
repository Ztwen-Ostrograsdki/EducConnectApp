<?php

namespace App\Listeners;

use App\Events\SchoolYearActivatedEvent;
use App\Jobs\JobToPutOrUpdateCacheData;
use App\Services\ClassesServices\ClasseEffectifsService;
use App\Services\DashboardCounterService;
use App\Services\FiliarsServices\FiliarDetailsCacheService;
use App\Services\SubjectsServices\SubjectDetailsCacheService;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Throwable;

class DispatchCacheRefreshBatchOnYearSwitch
{
    protected const CACHE_SERVICES = [
        SubjectDetailsCacheService::class,
        DashboardCounterService::class,
        ClasseEffectifsService::class,
        FiliarDetailsCacheService::class,
    ];

    public function handle(SchoolYearActivatedEvent $event): void
    {
        $jobs = collect(self::CACHE_SERVICES)
            ->map(fn (string $serviceClass) => new JobToPutOrUpdateCacheData(
                $serviceClass,
                $event->schoolYearId
            ))
            ->all();

        Bus::batch($jobs)
            ->name("cache-refresh:school-year:{$event->schoolYearId}")
            ->allowFailures()
            ->then(function (Batch $batch) {
                // tous les jobs ont réussi
            })
            ->catch(function (Batch $batch, Throwable $e) {
                report($e);
            })
            ->dispatch();
    }
}