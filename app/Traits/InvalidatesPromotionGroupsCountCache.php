<?php

namespace App\Traits;

use App\Services\PromotionGroupsCountService;
use Illuminate\Support\Facades\DB;


/**
 * @method static void saved(\Closure|string $callback)
 * @method static void updated(\Closure|string $callback)
 * @method static void deleted(\Closure|string $callback)
 * @method static void restored(\Closure|string $callback)
 */
trait InvalidatesPromotionGroupsCountCache
{
    protected static function bootInvalidatesPromotionGroupsCountCache(): void
    {
        static::saved(fn ($model) => static::schedulePromotionGroupsCountInvalidation($model));
        static::deleted(fn ($model) => static::schedulePromotionGroupsCountInvalidation($model));

        if (method_exists(static::class, 'restored')) {
            static::restored(fn ($model) => static::schedulePromotionGroupsCountInvalidation($model));
        }
    }

    protected static function schedulePromotionGroupsCountInvalidation($model): void
    {
        $schoolYearId = $model->school_year_id ?? null;

        if (!$schoolYearId) return;

        DB::afterCommit(function () use ($schoolYearId) {
            app(PromotionGroupsCountService::class)->forget($schoolYearId);
        });
    }
}