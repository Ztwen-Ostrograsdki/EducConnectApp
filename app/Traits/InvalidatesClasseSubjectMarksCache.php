<?php

namespace App\Traits;

use App\Services\ClassesServices\ClasseYearlyAveragesCacheService;
use App\Services\MarksServices\ClasseAveragesCacheService;
use App\Services\MarksServices\ClasseSubjectMarksCacheService;
use Illuminate\Support\Facades\DB;


/**
 * @method static void saved(\Closure|string $callback)
 * @method static void restored(\Closure|string $callback)
 * @method static void deleted(\Closure|string $callback)
 */
trait InvalidatesClasseSubjectMarksCache
{
    protected static function bootInvalidatesClasseSubjectMarksCache(): void
    {
        static::saved(fn ($model) => static::scheduleClasseSubjectMarksCacheInvalidation($model));
        static::deleted(fn ($model) => static::scheduleClasseSubjectMarksCacheInvalidation($model));

        if (method_exists(static::class, 'restored')) {
            static::restored(fn ($model) => static::scheduleClasseSubjectMarksCacheInvalidation($model));
        }
    }

    protected static function scheduleClasseSubjectMarksCacheInvalidation($model): void
    {
        $classeId = $model->classe_id;
        $subjectId = $model->subject_id;
        $period = $model->period;
        $schoolYearId = $model->school_year_id;

        DB::afterCommit(function () use ($classeId, $subjectId, $period, $schoolYearId) {

            app(ClasseSubjectMarksCacheService::class)->forget($classeId, $subjectId, $period, $schoolYearId);

            // Une note qui change modifie potentiellement la moyenne générale
            // ET le classement de tous les autres apprenants de la classe.
            app(ClasseAveragesCacheService::class)->forget($classeId, $period, $schoolYearId);

            app(ClasseYearlyAveragesCacheService::class)->forget($classeId, $schoolYearId); // ajouté
        });
    }
}