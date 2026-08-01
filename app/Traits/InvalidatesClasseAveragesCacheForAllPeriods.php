<?php

namespace App\Traits;

use App\Services\MarksServices\ClasseAveragesCacheService;
use Illuminate\Support\Facades\DB;


/**
 * @method static void saved(\Closure|string $callback)
 * @method static void restored(\Closure|string $callback)
 * @method static void deleted(\Closure|string $callback)
 */
trait InvalidatesClasseAveragesCacheForAllPeriods
{
    protected static function bootInvalidatesClasseAveragesCacheForAllPeriods(): void
    {
        static::saved(fn ($model) => static::scheduleClasseAveragesCacheInvalidation($model));
        static::deleted(fn ($model) => static::scheduleClasseAveragesCacheInvalidation($model));

        if (method_exists(static::class, 'restored')) {
            static::restored(fn ($model) => static::scheduleClasseAveragesCacheInvalidation($model));
        }
    }

    protected static function scheduleClasseAveragesCacheInvalidation($model): void
    {
        $classeId = $model->classe_id;
        $schoolYearId = $model->school_year_id;

        if (!$classeId) return;

        DB::afterCommit(function () use ($classeId, $schoolYearId) {
            app(ClasseAveragesCacheService::class)->forgetAllPeriods($classeId, $schoolYearId);
        });
    }
}