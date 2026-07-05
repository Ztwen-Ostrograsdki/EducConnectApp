<?php

namespace App\Traits;

use App\Services\DashboardCounterService;

/**
 * @method static void created(\Closure|string $callback)
 * @method static void updated(\Closure|string $callback)
 * @method static void deleted(\Closure|string $callback)
 */
trait InvalidatesDashboardCounters
{
    protected static function bootInvalidatesDashboardCounters(): void
    {
        static::created(fn ($model) => app(DashboardCounterService::class)->flushModel(static::class));

        static::updated(fn ($model) => app(DashboardCounterService::class)->flushModel(static::class));

        static::deleted(fn ($model) => app(DashboardCounterService::class)->flushModel(static::class));
    }
}