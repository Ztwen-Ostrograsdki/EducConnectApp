<?php

namespace App\Listeners;

use App\Events\NewSchoolYearActivatedEvent;
use App\Jobs\JobToDesactivateAllOtherSchoolYearsAfterSetCurrentSchoolYear;
use App\Jobs\JobToPutOrUpdateCacheData;
use App\Services\ClassesServices\ClasseEffectifsService;
use App\Services\DashboardCounterService;
use App\Services\FiliarsServices\FiliarDetailsCacheService;
use App\Services\PromotionGroupsCountService;
use App\Services\SubjectsServices\SubjectDetailsCacheService;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Throwable;

class ListenToDispatchCacheRefreshingBatchOnYearSwitch
{
    protected const CACHE_SERVICES = [
        SubjectDetailsCacheService::class,
        DashboardCounterService::class,
        ClasseEffectifsService::class,
        FiliarDetailsCacheService::class,
        PromotionGroupsCountService::class,
    ];




    public function handle(NewSchoolYearActivatedEvent $event): void
    {
        $jobs = collect(self::CACHE_SERVICES)
            ->map(fn (string $serviceClass) => new JobToPutOrUpdateCacheData(
                tenantId:     $event->tenantId,
                serviceClass: $serviceClass,
                schoolYearId: $event->schoolYearId
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
            ->finally(function(Batch $batch) use ($event){

                JobToDesactivateAllOtherSchoolYearsAfterSetCurrentSchoolYear::dispatch(
                    tenantId:       $event->tenantId, 
                    schoolYearSlug: $event->school_year_slug);
            })
            ->dispatch();
    }
}