<?php

namespace App\Jobs;

use App\Contracts\RefreshableSchoolYearCache;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class JobToPutOrUpdateCacheData implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @param  class-string<RefreshableSchoolYearCache>  $serviceClass
     */
    public function __construct(
        public readonly string $serviceClass,
        public readonly int $schoolYearId,
    ) {}

    public function handle(): void
    {
        if ($this->batch()?->cancelled()) {
            return;
        }

        /** @var RefreshableSchoolYearCache $service */
        $service = app($this->serviceClass);

        $service->refreshForSchoolYear($this->schoolYearId);
    }

    public function failed(Throwable $exception): void
    {
        report($exception);
    }
}