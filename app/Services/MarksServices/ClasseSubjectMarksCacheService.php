<?php

namespace App\Services\MarksServices;


use App\Models\Classe;
use App\Models\Mark;
use App\Models\SchoolYear;
use App\Services\ClassesServices\ActiveClasseStudentsResolver;
use App\Services\MarksServices\SubjectAverageCalculator;
use App\Services\MentionService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ClasseSubjectMarksCacheService
{
    protected const CACHE_TTL = 1800;

    protected const CACHE_PREFIX = 'classe_marks';

    /**
     * Notes + moyennes + rang d'une classe, pour une matière et une période données.
     *
     * Structure retournée, une entrée PAR APPRENANT ACTIF de la classe (pas seulement
     * ceux ayant des notes) :
     * [
     *   $studentId => [
     *     'marks'       => ['interro1' => ['id'=>..,'value'=>..,'editable'=>..,'locked_at'=>..], ...],
     *     'moy_interro' => 14.5,
     *     'moy_devoirs' => 16.0,
     *     'moy'         => 15.25,
     *     'moy_coef'    => 45.75,
     *     'rank'        => 3,
     *     'total'       => 42,
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

    public function forStudent(int $classeId, int $subjectId, int $studentId, int $period, ?int $schoolYearId = null): ?array
    {
        return $this->get($classeId, $subjectId, $period, $schoolYearId)[$studentId] ?? null;
    }

    public function forget(int $classeId, int $subjectId, int $period, ?int $schoolYearId = null): void
    {
        $schoolYearId ??= SchoolYear::current()?->first()?->id;

        Cache::forget($this->cacheKey($classeId, $subjectId, $period, $schoolYearId));
    }

    public function forgetFor(Mark $mark): void
    {
        $this->forget($mark->classe_id, $mark->subject_id, $mark->period, $mark->school_year_id);
    }

    protected function compute(int $classeId, int $subjectId, int $period, ?int $schoolYearId): array
    {
        $studentIds = app(ActiveClasseStudentsResolver::class)->ids($classeId, $schoolYearId);

        if ($studentIds->isEmpty()) {
            return [];
        }

        $classe = Classe::find($classeId);

        $coefficient = $classe->getCoefValueOfSubject($subjectId);

        $devoirColumns = SubjectAverageCalculator::devoirColumns();

        $rawMarks = Mark::query()
            ->select(['id', 'student_id', 'type', 'value', 'editable', 'locked_at'])
            ->where('classe_id', $classeId)
            ->where('subject_id', $subjectId)
            ->where('period', $period)
            ->where('school_year_id', $schoolYearId)
            ->whereIn('student_id', $studentIds)
            ->get()
            ->groupBy('student_id');

        $rows = $studentIds->mapWithKeys(function (int $studentId) use ($rawMarks, $devoirColumns, $coefficient) {

            $marksForStudent = ($rawMarks->get($studentId) ?? collect())
                ->mapWithKeys(fn (Mark $mark) => [
                    $mark->type => [
                        'id'        => $mark->id,
                        'value'     => (float) $mark->value,
                        'editable'  => $mark->editable,
                        'locked_at' => $mark->locked_at?->toISOString(),
                    ],
                ])->all();

            $moyInterro = SubjectAverageCalculator::moyInterro($marksForStudent);
            $moyDevoirs = SubjectAverageCalculator::moyDevoirs($marksForStudent, $devoirColumns);
            $moy = SubjectAverageCalculator::moy($marksForStudent, $devoirColumns);
            $moyCoef = SubjectAverageCalculator::moyCoef($moy, $coefficient);
            $mentionService = app(MentionService::class);

            return [$studentId => [
                'marks'       => $marksForStudent,
                'coefficient' => $coefficient,
                'moy_interro' => $moyInterro,
                'moy_devoirs' => $moyDevoirs,
                'moy'         => $moy,
                'moy_coef'    => $moyCoef,
                'mention'      => $mentionService->forValue($moy),
            ]];
        });

        return $this->applyRanking($rows, $studentIds->count());
    }

    /**
     * Rang par moyenne de matière décroissante ; ex-aequo partagent le même rang ;
     * apprenants sans moyenne (aucune note saisie) n'ont pas de rang.
     */
    protected function applyRanking(Collection $rows, int $total): array
    {
        $ranked = $rows->sortByDesc(fn ($r) => $r['moy'] ?? -1);

        $rank = 0;
        $lastMoy = null;
        $position = 0;

        foreach ($ranked as $studentId => $r) {

            $position++;

            $item = $rows->get($studentId);

            if (is_null($r['moy'])) {
                $item['rank'] = null;
                $item['total'] = $total;

                $rows->put($studentId, $item);
                continue;
            }

            if ($r['moy'] !== $lastMoy) {
                $rank = $position;
                $lastMoy = $r['moy'];
            }

            $item['rank'] = $rank;
            $item['total'] = $total;

            $rows->put($studentId, $item);

        }

        return $rows->all();
    }

    protected function cacheKey(int $classeId, int $subjectId, int $period, ?int $schoolYearId): string
    {
        return self::CACHE_PREFIX . ":{$classeId}:{$subjectId}:sy:{$schoolYearId}:period:{$period}";
    }
}