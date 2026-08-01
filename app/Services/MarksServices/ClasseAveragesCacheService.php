<?php

namespace App\Services\MarksServices;


use App\Models\ClasseSubjectOfSchoolYear;
use App\Models\SchoolYear;
use App\Models\YearlyClasseStudent;
use App\Services\MarksServices\ClasseSubjectMarksCacheService;
use App\Services\MarksServices\SubjectAverageCalculator;
use App\Services\MentionService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ClasseAveragesCacheService
{
    protected const CACHE_TTL = 1800;

    protected const CACHE_PREFIX = 'classe_averages';

    /**
     * Moyennes de fin de période pour TOUTE la classe, indexées par student_id.
     * Structure : [
     *   $studentId => [
     *     'sum_moy_coef' => 152.4,
     *     'sum_coef'     => 12.0,
     *     'moyenne'      => 12.7,
     *     'mention'      => 'Bien',
     *     'rank'         => 3,
     *     'total'        => 42,
     *   ],
     * ]
     */
    public function get(int $classeId, int $period, ?int $schoolYearId = null): array
    {
        $schoolYearId ??= SchoolYear::current()?->first()?->id;

        return Cache::remember(
            $this->cacheKey($classeId, $period, $schoolYearId),
            self::CACHE_TTL,
            fn () => $this->compute($classeId, $period, $schoolYearId)
        );
    }

    public function forStudent(int $classeId, int $studentId, int $period, ?int $schoolYearId = null): ?array
    {
        return $this->get($classeId, $period, $schoolYearId)[$studentId] ?? null;
    }

    public function forget(int $classeId, int $period, ?int $schoolYearId = null): void
    {
        $schoolYearId ??= SchoolYear::current()?->first()?->id;

        Cache::forget($this->cacheKey($classeId, $period, $schoolYearId));
    }

    /**
     * Invalide TOUTES les périodes d'une classe. Nécessaire quand un changement
     * affecte le calcul indépendamment de la période : coefficient d'une matière,
     * abandon/réintégration d'un apprenant (impacte la population et les rangs
     * de tous les autres pour toutes les périodes déjà calculées).
     */
    public function forgetAllPeriods(int $classeId, ?int $schoolYearId = null): void
    {
        $schoolYearId ??= SchoolYear::current()?->first()?->id;

        $schoolYear = SchoolYear::find($schoolYearId);

        if (!$schoolYear) {
            return;
        }

        foreach ($schoolYear->getPeriods() as $p) {
            $this->forget($classeId, $p['index'], $schoolYearId);
        }
    }

    protected function compute(int $classeId, int $period, ?int $schoolYearId): array
    {
        $studentIds = $this->activeStudentIds($classeId, $schoolYearId);

        if ($studentIds->isEmpty()) {
            return [];
        }

        $classeSubjects = ClasseSubjectOfSchoolYear::query()
            ->where('classe_id', $classeId)
            ->where('school_year_id', $schoolYearId)
            ->where('is_active', true)
            ->with(['classe', 'subject'])
            ->whereNull('ended_at')
            ->get();

        $devoirColumns = SubjectAverageCalculator::devoirColumns();

        $marksCacheService = app(ClasseSubjectMarksCacheService::class);

        // Accumulateurs par apprenant : somme des moy. coef et somme des coef
        // RETENUES (une matière ignorée n'apporte ni sa moy. coef, ni son coef).
        $sums = $studentIds->mapWithKeys(fn ($id) => [
            $id => ['sum_moy_coef' => 0.0, 'sum_coef' => 0.0],
        ]);

        foreach ($classeSubjects as $classeSubject) {

			$coefficient = $classeSubject->classe->getCoefValueOfSubject($classeSubject->subject_id);

            // Réutilise le cache déjà en place — aucune requête Mark directe ici.
            $marksData = $marksCacheService->get($classeId, $classeSubject->subject_id, $period, $schoolYearId);

            foreach ($studentIds as $studentId) {

                $studentMarks = $marksData[$studentId] ?? [];

                $moy = SubjectAverageCalculator::moy($studentMarks, $devoirColumns);

                $moyCoef = SubjectAverageCalculator::moyCoef($moy, $coefficient);

                // Moy. coef nulle (ou moy nulle) => matière ignorée, coef non compté.
                if (is_null($moyCoef) || $moyCoef == 0.0) {
                    continue;
                }

                $item = $sums->get($studentId);

                $item['sum_moy_coef'] += $moyCoef;

                $item['sum_coef'] += $coefficient;

                $sums->put($studentId, $item);
            }
        }

        $mentionService = app(MentionService::class);

        $results = $sums->map(function (array $data) use ($mentionService) {

            $moyenne = $data['sum_coef'] > 0
                ? round($data['sum_moy_coef'] / $data['sum_coef'], 2)
                : null;

            return [
                'sum_moy_coef' => round($data['sum_moy_coef'], 2),
                'sum_coef'     => round($data['sum_coef'], 2),
                'moyenne'      => $moyenne,
                'mention'      => $mentionService->forValue($moyenne),
            ];
        });

        return $this->applyRanking($results, $studentIds->count());
    }

    /**
     * Calcule le rang par moyenne décroissante, ex-aequo partagent le même rang.
     * Les apprenants sans moyenne (aucune matière suivie) n'ont pas de rang.
     */
    protected function applyRanking(Collection $results, int $total): array
    {
        $ranked = $results->sortByDesc(fn ($r) => $r['moyenne'] ?? -1); // conserve les clés student_id

        $rank = 0;
        $lastMoy = null;
        $position = 0;

        foreach ($ranked as $studentId => $r) {

            $position++;

            $item = $results->get($studentId);

            if (is_null($r['moyenne'])) {
                $item['rank'] = null;
                $item['total'] = $total;
                continue;
            }

            if ($r['moyenne'] !== $lastMoy) {
                $rank = $position;
                $lastMoy = $r['moyenne'];
            }

            $item['rank'] = $rank;
            $item['total'] = $total;

            $results->put($studentId, $item);
        }

        return $results->all();
    }

    /**
     * Apprenants n'ayant pas abandonné : is_active=true, ended_at=null,
     * même critère que ClasseEffectifsService::countActiveStudents().
     */
    protected function activeStudentIds(int $classeId, ?int $schoolYearId): Collection
    {
        return YearlyClasseStudent::query()
            ->where('classe_id', $classeId)
            ->where('school_year_id', $schoolYearId)
            ->where('is_active', true)
            ->whereNull('ended_at')
			->whereHas('student', fn($q) => 
				$q->whereDoesntHave('yearlyStudentsLeaves', fn ($qs) => 
					$qs->where('school_year_id', $schoolYearId)
				)
			)
            ->pluck('student_id');
    }

    protected function cacheKey(int $classeId, int $period, ?int $schoolYearId): string
    {
        return self::CACHE_PREFIX . ":{$classeId}:sy:{$schoolYearId}:period:{$period}";
    }
}