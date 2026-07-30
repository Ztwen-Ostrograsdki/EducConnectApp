<?php

namespace App\Traits;

use App\Services\SerialsServices\SerialDetailsCacheService;

/**
 * @method static void saved(\Closure|string $callback)
 * @method static void updated(\Closure|string $callback)
 * @method static void deleted(\Closure|string $callback)
 * @method static void restored(\Closure|string $callback)
 */
trait InvalidatesSerialDetailsCache
{
    protected static function bootInvalidatesSerialDetailsCache(): void
    {
        static::saved(fn ($model) => static::invalidateSerialDetailsCache($model));
		
        static::deleted(fn ($model) => static::invalidateSerialDetailsCache($model));

        if (method_exists(static::class, 'restored')) {
            static::restored(fn ($model) => static::invalidateSerialDetailsCache($model));
        }
    }

    protected static function invalidateSerialDetailsCache($model): void
    {
        // Cas direct : le modèle porte serial_id (Serial lui-même, Classe).
        // Cas indirect : le modèle ne porte que classe_id (ClasseSubjectOfSchoolYear, YearlyClasseStudent),
        // on remonte via la relation classe().
        $serialId = $model->serial_id ?? $model->classe?->serial_id;

        if (!$serialId) {
            return;
        }

        app(SerialDetailsCacheService::class)->forget(
            $serialId,
            $model->school_year_id ?? null
        );
    }
}