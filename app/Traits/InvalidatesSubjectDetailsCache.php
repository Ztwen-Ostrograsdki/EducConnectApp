<?php

namespace App\Traits;

use App\Services\SubjectsServices\SubjectDetailsCacheService;


/**
 * @method static void saved(\Closure|string $callback)
 * @method static void restored(\Closure|string $callback)
 * @method static void created(\Closure|string $callback)
 * @method static void updated(\Closure|string $callback)
 * @method static void deleted(\Closure|string $callback)
 */
trait InvalidatesSubjectDetailsCache
{
    protected static function bootInvalidatesSubjectDetailsCache(): void
    {
        static::saved(fn ($model) => static::invalidateSubjectDetailsCache($model));
        static::deleted(fn ($model) => static::invalidateSubjectDetailsCache($model));

        if (method_exists(static::class, 'restored')) {
            static::restored(fn ($model) => static::invalidateSubjectDetailsCache($model));
        }
    }

    protected static function invalidateSubjectDetailsCache($model): void
    {
        if (!$model->subject_id) {
            return;
        }

        app(SubjectDetailsCacheService::class)->forget(
            $model->subject_id,
            $model->school_year_id ?? null
        );
    }
}