<?php

namespace App\Traits;

use App\Services\FiliarsServices\FiliarDetailsCacheService;

/**
 * @method static void saved(\Closure|string $callback)
 * @method static void updated(\Closure|string $callback)
 * @method static void deleted(\Closure|string $callback)
 * @method static void restored(\Closure|string $callback)
 */
trait InvalidatesFiliarDetailsCache
{
    protected static function bootInvalidatesFiliarDetailsCache(): void
    {
        static::saved(fn ($model) => static::invalidateFiliarDetailsCache($model));
		
        static::deleted(fn ($model) => static::invalidateFiliarDetailsCache($model));

        if (method_exists(static::class, 'restored')) {
            static::restored(fn ($model) => static::invalidateFiliarDetailsCache($model));
        }
    }

    protected static function invalidateFiliarDetailsCache($model): void
    {
        // Cas direct : le modèle porte filiar_id (Filiar lui-même, YearlyFiliarChief, Classe).
        // Cas indirect : le modèle ne porte que classe_id (ClasseSubjectOfSchoolYear, YearlyClasseStudent),
        // on remonte via la relation classe().
        $filiarId = $model->filiar_id ?? $model->classe?->filiar_id;

        if (!$filiarId) {
            return;
        }

        app(FiliarDetailsCacheService::class)->forget(
            $filiarId,
            $model->school_year_id ?? null
        );
    }
}