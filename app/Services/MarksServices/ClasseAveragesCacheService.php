<?php

namespace App\Services\MarksServices;


use App\Models\ClasseSubjectOfSchoolYear;
use App\Models\SchoolYear;
use App\Services\ClassesServices\ActiveClasseStudentsResolver;
use App\Services\MarksServices\ClasseSubjectMarksCacheService;
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
        $studentIds = app(ActiveClasseStudentsResolver::class)->ids($classeId, $schoolYearId);

        if ($studentIds->isEmpty()) {
            return [];
        }

        $classeSubjects = ClasseSubjectOfSchoolYear::query()
            ->where('classe_id', $classeId)
            ->where('school_year_id', $schoolYearId)
            ->where('is_active', true)
            ->whereNull('ended_at')
            ->get();

        $marksCacheService = app(ClasseSubjectMarksCacheService::class);

        $sums = $studentIds->mapWithKeys(fn ($id) => [
            $id => ['sum_moy_coef' => 0.0, 'sum_coef' => 0.0, 'total_notes' => 0, 'success_notes' => 0],
        ]);

        foreach ($classeSubjects as $classeSubject) {

            $marksData = $marksCacheService->get($classeId, $classeSubject->subject_id, $period, $schoolYearId);

            $coefficient = $classeSubject->classe->getCoefValueOfSubject($classeSubject->subject_id);

            foreach ($studentIds as $studentId) {

                $studentData = $marksData[$studentId] ?? null;

                if (!$studentData) continue;

                $counts = SubjectAverageCalculator::successCounts($studentData['marks']);

                $item = $sums->get($studentId);
                $item['total_notes'] += $counts['total'];
                $item['success_notes'] += $counts['success'];

                $moyCoef = $studentData['moy_coef'] ?? null;

                if (!is_null($moyCoef) && $moyCoef != 0.0) {
                    $item['sum_moy_coef'] += $moyCoef;
                    $item['sum_coef'] += $coefficient;
                }

                $sums->put($studentId, $item);
            }
        }

        $mentionService = app(MentionService::class);

        $results = $sums->map(function (array $data) use ($mentionService) {

            $moyenne = $data['sum_coef'] > 0
                ? round($data['sum_moy_coef'] / $data['sum_coef'], 2)
                : null;

            $successPercentage = $data['total_notes'] > 0
                ? round(($data['success_notes'] / $data['total_notes']) * 100, 2)
                : null;

            return [
                'sum_moy_coef'       => round($data['sum_moy_coef'], 2),
                'sum_coef'           => round($data['sum_coef'], 2),
                'moyenne'            => $moyenne,
                'mention'            => $mentionService->forValue($moyenne),
                'success_percentage' => $successPercentage,
                'total_notes'        => $data['total_notes'],
                'success_notes'      => $data['success_notes'],
            ];
        });

        $ranked = $this->applyRanking($results, $studentIds->count());

        return $this->injectClasseLevelStats($ranked, $studentIds->count());
    }

    /**
     * Ajoute à CHAQUE entrée apprenant les stats de niveau classe pour la période :
     * moyenne du premier, du dernier, et taux de réussite (% d'apprenants avec
     * moyenne >= 10, sur l'effectif total actif — abandons déjà exclus en amont).
     * Dénormalisé volontairement pour que forStudent() reste une lecture directe
     * sans recalcul ni appel supplémentaire.
     */
    protected function injectClasseLevelStats(array $rows, int $totalActive): array
    {
        $withMoy = collect($rows)->filter(fn ($r) => !is_null($r['moyenne']));

        $premierId = $withMoy->sortByDesc(fn ($r) => $r['moyenne'])->keys()->first();
        $dernierId = $withMoy->sortBy(fn ($r) => $r['moyenne'])->keys()->first();

        $premier = !is_null($premierId) ? ['student_id' => $premierId, 'moyenne' => $rows[$premierId]['moyenne']] : null;
        $dernier = !is_null($dernierId) ? ['student_id' => $dernierId, 'moyenne' => $rows[$dernierId]['moyenne']] : null;

        $classSuccessRate = $totalActive > 0
            ? round(($withMoy->filter(fn ($r) => $r['moyenne'] >= 10)->count() / $totalActive) * 100, 2)
            : null;

        foreach ($rows as $studentId => &$row) {
            $row['premier'] = $premier;
            $row['dernier'] = $dernier;
            $row['class_success_rate'] = $classSuccessRate;
        }

        return $rows;
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

                $results->put($studentId, $item);
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

    

    protected function cacheKey(int $classeId, int $period, ?int $schoolYearId): string
    {
        return self::CACHE_PREFIX . ":{$classeId}:sy:{$schoolYearId}:period:{$period}";
    }
}