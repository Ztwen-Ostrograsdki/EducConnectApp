<?php

namespace App\Traits;

use App\Services\PromotionsServices\PromotionDetailsCacheService;

/**
 * @method static void saved(\Closure|string $callback)
 * @method static void updated(\Closure|string $callback)
 * @method static void deleted(\Closure|string $callback)
 * @method static void restored(\Closure|string $callback)
 */
trait InvalidatesPromotionDetailsCache
{
    protected static function bootInvalidatesPromotionDetailsCache(): void
    {
        static::saved(fn ($model) => static::invalidatePromotionDetailsCache($model));
		
        static::deleted(fn ($model) => static::invalidatePromotionDetailsCache($model));

        if (method_exists(static::class, 'restored')) {
            static::restored(fn ($model) => static::invalidatePromotionDetailsCache($model));
        }
    }

    protected static function invalidatePromotionDetailsCache($model): void
    {
        // Cas indirect : le modèle ne porte que classe_id (ClasseSubjectOfSchoolYear, YearlyClasseStudent),
        // on remonte via la relation classe().
        $promotionId = $model->promotion_id ?? $model->classe?->promotion_id;

        if (!$promotionId) {
            return;
        }

        app(PromotionDetailsCacheService::class)->forget(
            $promotionId,
            $model->school_year_id ?? null
        );
    }
}