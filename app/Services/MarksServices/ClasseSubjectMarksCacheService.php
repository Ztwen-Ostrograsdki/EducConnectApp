<?php

namespace App\Services\MarksServices;


use App\Models\Mark;
use App\Models\SchoolYear;
use Illuminate\Support\Facades\Cache;

class ClasseSubjectMarksCacheService
{
    protected const CACHE_TTL = 1800; // 30 min : les notes bougent souvent pendant la saisie

    protected const CACHE_PREFIX = 'classe_marks';

    /**
     * Notes d'une classe, pour une matière et une période données,
     * groupées par élève puis par type de note.
     *
     * Structure retournée :
     * [
     *   $studentId => [
     *     'interro1' => ['id' => 12, 'value' => 14.5, 'editable' => true, 'locked_at' => null],
     *     'devoir1'  => ['id' => 13, 'value' => 16.0, 'editable' => false, 'locked_at' => '...'],
     *     ...
     *   ],
     * ]
     */
    public function get(int $classeId, int $subjectId, int $period, ?int $schoolYearId = null): array
    {
        $schoolYearId ??= SchoolYear::current()?->first()?->id;

        return Cache::remember(
            $this->cacheKey($classeId, $subjectId, $period, $schoolYearId),
            self::CACHE_TTL,
            fn () => $this->compute($classeId, $subjectId, $period, $schoolYearId)
        );
    }

    public function forget(int $classeId, int $subjectId, int $period, ?int $schoolYearId = null): void
    {
        $schoolYearId ??= SchoolYear::current()?->first()?->id;

        Cache::forget($this->cacheKey($classeId, $subjectId, $period, $schoolYearId));
    }

    /**
     * À appeler explicitement après un traitement en masse (import Excel, job de création
     * groupée) qui ne passe pas par les événements Eloquent individuels.
     */
    public function forgetFor(Mark $mark): void
    {
        $this->forget($mark->classe_id, $mark->subject_id, $mark->period, $mark->school_year_id);
    }

    protected function compute(int $classeId, int $subjectId, int $period, ?int $schoolYearId): array
    {
        return Mark::query()
            ->select(['id', 'student_id', 'type', 'value', 'editable', 'locked_at'])
            ->where('classe_id', $classeId)
            ->where('subject_id', $subjectId)
            ->where('period', $period)
            ->where('school_year_id', $schoolYearId)
            ->get()
            ->groupBy('student_id')
            ->map(fn ($marksForStudent) => $marksForStudent->mapWithKeys(fn (Mark $mark) => [
                $mark->type => [
                    'id'        => $mark->id,
                    'value'     => (float) $mark->value,
                    'editable'  => $mark->editable,
                    'locked_at' => $mark->locked_at?->toISOString(),
                ],
            ])->all())
            ->all();
    }

    protected function cacheKey(int $classeId, int $subjectId, int $period, ?int $schoolYearId): string
    {
        return self::CACHE_PREFIX . ":{$classeId}:{$subjectId}:sy:{$schoolYearId}:period:{$period}";
    }
}