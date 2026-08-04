<?php

namespace App\Services\ClassesServices;


use App\Models\SchoolYear;
use App\Services\ClassesServices\ActiveClasseStudentsResolver;
use App\Services\ClassesServices\ClasseEffectifsService;
use App\Services\MarksServices\ClasseAveragesCacheService;
use App\Services\MentionService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ClasseYearlyAveragesCacheService
{
    protected const CACHE_TTL = 3600;

    protected const CACHE_PREFIX = 'classe_yearly_averages';

    /**
     * Données annuelles groupées par classe. Structure :
     * [
     *   'students' => [
     *     $studentId => [
     *       'periods'          => [1 => [...donnée ClasseAveragesCacheService période 1...], 2 => [...], ...],
     *       'moy_general'      => 13.45,
     *       'rang_general'     => 3,
     *       'mention_generale' => 'Bien',
     *     ],
     *   ],
     *   'total'                        => 42,
     *   'premier'                      => ['student_id' => .., 'moyenne' => 16.2],
     *   'dernier'                      => ['student_id' => .., 'moyenne' => 6.8],
     *   'success_percentage_annuelle'  => 71.4,
     *   'effectifs'                    => [...ClasseEffectifsService::getEffectifs()...],
     * ]
     */
    public function get(int $classeId, ?int $schoolYearId = null): array
    {
        $schoolYearId ??= SchoolYear::current()?->first()?->id;

        return Cache::remember(
            $this->cacheKey($classeId, $schoolYearId),
            self::CACHE_TTL,
            fn () => $this->compute($classeId, $schoolYearId)
        );
    }

    public function forStudent(int $classeId, int $studentId, ?int $schoolYearId = null): ?array
    {
        return $this->get($classeId, $schoolYearId)['students'][$studentId] ?? null;
    }

    public function forget(int $classeId, ?int $schoolYearId = null): void
    {
        $schoolYearId ??= SchoolYear::current()?->first()?->id;

        Cache::forget($this->cacheKey($classeId, $schoolYearId));
    }

    protected function compute(int $classeId, ?int $schoolYearId): array
    {
        $schoolYear = SchoolYear::find($schoolYearId);

        if (!$schoolYear) {
            return $this->emptyResult($classeId, $schoolYearId);
        }

        $periodIndexes = collect($schoolYear->getPeriods())
            ->pluck('index')
            ->sort()
            ->values();

        $periodCount = $periodIndexes->count();

        $studentIds = app(ActiveClasseStudentsResolver::class)->ids($classeId, $schoolYearId);

        if ($studentIds->isEmpty()) {
            return $this->emptyResult($classeId, $schoolYearId);
        }

        $averagesService = app(ClasseAveragesCacheService::class);

        // Une lecture de cache par période (HIT si déjà consultée ailleurs, ex : bulletin de période).
        $periodsData = $periodIndexes->mapWithKeys(fn ($p) => [
            $p => $averagesService->get($classeId, $p, $schoolYearId),
        ]);

        $classeTotalNotes = 0;
        $classeSuccessNotes = 0;

        $students = $studentIds->mapWithKeys(function (int $studentId) use ($periodsData, $periodIndexes, $periodCount, &$classeTotalNotes, &$classeSuccessNotes) {

            $periodEntries = $periodIndexes->mapWithKeys(fn ($p) => [
                $p => $periodsData[$p][$studentId] ?? null,
            ]);

            // Cumul propre à CET apprenant, toutes matières et toutes périodes
            // confondues sur l'année (indépendant du cumul global de la classe).
            $studentTotalNotes = 0;
            $studentSuccessNotes = 0;

            foreach ($periodEntries as $entry) {
                if ($entry) {
                    $studentTotalNotes += $entry['total_notes'] ?? 0;
                    $studentSuccessNotes += $entry['success_notes'] ?? 0;
                }
            }

            // Alimente aussi le cumul de la classe entière.
            $classeTotalNotes += $studentTotalNotes;
            $classeSuccessNotes += $studentSuccessNotes;

            $successPercentageAnnuel = $studentTotalNotes > 0
                ? round(($studentSuccessNotes / $studentTotalNotes) * 100, 2)
                : null;

            return [$studentId => [
                'periods'                    => $periodEntries->all(),
                'moy_general'                => $this->computeYearlyAverage($periodEntries, $periodCount),
                'success_percentage_annuel'  => $successPercentageAnnuel, // par apprenant
            ]];
        });

        $students = $this->applyRankingAndMentions($students);

        $withMoy = $students->filter(fn ($s) => !is_null($s['moy_general']));

        $premierId = $withMoy->sortByDesc(fn ($s) => $s['moy_general'])->keys()->first();
        $dernierId = $withMoy->sortBy(fn ($s) => $s['moy_general'])->keys()->first();

        // Pourcentage global de la classe, toutes matières et tous apprenants confondus.
        $successPercentageAnnuelleClasse = $classeTotalNotes > 0
            ? round(($classeSuccessNotes / $classeTotalNotes) * 100, 2)
            : null;

        return [
            'students' => $students->all(),
            'total' => $studentIds->count(),
            'premier' => $premierId ? [
                'student_id' => $premierId,
                'moyenne'    => $students[$premierId]['moy_general'],
            ] : null,
            'dernier' => $dernierId ? [
                'student_id' => $dernierId,
                'moyenne'    => $students[$dernierId]['moy_general'],
            ] : null,
            'success_percentage_annuelle' => $successPercentageAnnuelleClasse, // niveau classe
            'effectifs' => app(ClasseEffectifsService::class)->getEffectifs($classeId, $schoolYearId),
        ];
    }

    /**
     * Poids fixes : 2 périodes (semestre) => moy_sem1 + 2×moy_sem2 sur 3 ;
     * 3 périodes (trimestre) => moy_trim1 + moy_trim2 + 2×moy_trim3 sur 4.
     *
     * Si une période n'a pas de moyenne (aucune note saisie), elle est ignorée
     * ET son poids est retiré du dénominateur — même logique d'exclusion que
     * pour les matières sans note dans ClasseAveragesCacheService.
     */
    protected function computeYearlyAverage(Collection $periodEntries, int $periodCount): ?float
    {
        $weights = match ($periodCount) {
            2 => [1 => 1, 2 => 2],
            3 => [1 => 1, 2 => 1, 3 => 2],
            default => [],
        };

        if (empty($weights)) {
            return null;
        }

        $sumWeighted = 0.0;
        $sumWeights = 0.0;

        foreach ($weights as $periodIndex => $weight) {

            $moy = $periodEntries[$periodIndex]['moyenne'] ?? null;

            if (is_null($moy)) {
                continue;
            }

            $sumWeighted += $moy * $weight;
            $sumWeights += $weight;
        }

        return $sumWeights > 0 ? round($sumWeighted / $sumWeights, 2) : null;
    }

    protected function applyRankingAndMentions(Collection $students): Collection
    {
        $mentionService = app(MentionService::class);

        $ranked = $students->sortByDesc(fn ($s) => $s['moy_general'] ?? -1);

        $rank = 0;
        $lastMoy = null;
        $position = 0;

        return $ranked->map(function ($s) use (&$rank, &$lastMoy, &$position, $mentionService) {

            $position++;

            if (is_null($s['moy_general'])) {
                $s['rang_general'] = null;
                $s['mention_generale'] = null;
                return $s;
            }

            if ($s['moy_general'] !== $lastMoy) {
                $rank = $position;
                $lastMoy = $s['moy_general'];
            }

            $s['rang_general'] = $rank;
            $s['mention_generale'] = $mentionService->forValue($s['moy_general']);

            return $s;
        })
        // Remet dans l'ordre d'origine (par student_id) — sortByDesc préserve les clés
        // mais réordonne l'itération ; on garde la Collection triée par student_id
        // pour un accès prévisible via $students[$studentId] ensuite.
        ->sortKeys();
    }

    protected function emptyResult(int $classeId, ?int $schoolYearId): array
    {
        return [
            'students' => [],
            'total' => 0,
            'premier' => null,
            'dernier' => null,
            'success_percentage_annuelle' => null,
            'effectifs' => app(ClasseEffectifsService::class)->getEffectifs($classeId, $schoolYearId),
        ];
    }

    protected function cacheKey(int $classeId, ?int $schoolYearId): string
    {
        return self::CACHE_PREFIX . ":{$classeId}:sy:{$schoolYearId}";
    }
}