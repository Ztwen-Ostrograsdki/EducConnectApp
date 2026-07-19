<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

/**
 * À utiliser sur tout modèle possédant une colonne classe_id
 * dont les mutations doivent invalider le cache d'effectifs de la classe.
 */

/**
 * @method static void saved(\Closure|string $callback)
 * @method static void updated(\Closure|string $callback)
 * @method static void deleted(\Closure|string $callback)
 * @method static void created(\Closure|string $callback)
 * @method static void restored(\Closure|string $callback)
 */
trait InvalidatesClasseEffectifsCache
{
    protected static function bootInvalidatesClasseEffectifsCache(): void
    {
        static::saved(function ($model) {
            static::flushClasseEffectifsCache($model);
        });
        
        static::created(function ($model) {
            static::flushClasseEffectifsCache($model);
        });

        static::deleted(function ($model) {
            static::flushClasseEffectifsCache($model);
        });

    }

    protected static function flushClasseEffectifsCache($model): void
    {
        if (! $model->classe_id) {
            return;
        }

        Cache::tags(["classe:{$model->classe_id}", 'effectifs'])->flush();
    }
}