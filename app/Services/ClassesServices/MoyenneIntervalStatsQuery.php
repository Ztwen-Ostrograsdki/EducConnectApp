<?php

namespace App\Services\ClassesServices;

use App\Models\Classe;
use App\Models\Student;
use App\Services\MarksServices\ClasseAveragesCacheService;
use Illuminate\Database\Eloquent\Builder;

class MoyenneIntervalStatsQuery
{
    public static function classesQuery(array $config, int $schoolYearId): Builder
    {
        $query = Classe::where('school_year_id', $schoolYearId)
            ->where('is_active', true)
            ->with(['filiar', 'serial', 'promotion']);

        if (! empty($config['classe_id']))       $query->where('id', $config['classe_id']);
        if (! empty($config['filiar_id']))       $query->where('filiar_id', $config['filiar_id']);
        if (! empty($config['serial_id']))       $query->where('serial_id', $config['serial_id']);
        if (! empty($config['promotion_id']))    $query->where('promotion_id', $config['promotion_id']);

        if (! empty($config['promotionInGroups'])) {
            $query->whereHas('promotion', fn ($q) => $q->where('name', $config['promotionInGroups']));
        }

        return $query->orderBy('name');
    }

    public static function count(array $config, int $schoolYearId): int
    {
        return self::classesQuery($config, $schoolYearId)->count();
    }

    // ─── Construction des intervalles à partir des points de coupure ──

    public static function buildIntervals(array $breakpoints): array
    {
        $sorted = collect($breakpoints)
            ->map(fn ($b) => (float) $b)
            ->filter(fn ($b) => $b > 0 && $b < 20)
            ->unique()
            ->sort()
            ->values();

        $intervals = [];
        $start = 0.0;

        foreach ($sorted as $bp) {
            $intervals[] = ['min' => $start, 'max' => $bp, 'closedMax' => false];
            $start = $bp;
        }

        $intervals[] = ['min' => $start, 'max' => 20.0, 'closedMax' => true];

        return $intervals;
    }

    public static function intervalLabels(array $intervals): array
    {
        $total = count($intervals);

        return collect($intervals)->map(function ($interval, $i) use ($total) {
            if ($i === 0) return '< ' . self::fmt($interval['max']);
            if ($i === $total - 1) return '≥ ' . self::fmt($interval['min']);
            return '[' . self::fmt($interval['min']) . ' - ' . self::fmt($interval['max']) . '[';
        })->all();
    }

    protected static function fmt(float $v): string
    {
        return $v == (int) $v ? (string) (int) $v : (string) $v;
    }

    public static function classifyMoyenne(?float $moyenne, array $intervals): ?int
    {
        if (is_null($moyenne)) return null;

        foreach ($intervals as $i => $interval) {
            if ($interval['closedMax']) {
                if ($moyenne >= $interval['min'] && $moyenne <= $interval['max']) return $i;
            } elseif ($moyenne >= $interval['min'] && $moyenne < $interval['max']) {
                return $i;
            }
        }

        return null;
    }

    // ─── Statistiques par classe ──

    protected static function computeClasseStat(Classe $classe, int $schoolYearId, int $period, array $intervals, int $totalIntervals): array
    {
        $averages = app(ClasseAveragesCacheService::class)->get($classe->id, $period, $schoolYearId);

        $studentIds = array_keys($averages);
        $genders = Student::whereIn('id', $studentIds)->pluck('gender', 'id');

        $intervalCounts = array_fill(0, $totalIntervals, 0);
        $garcons = 0; $filles = 0;
        $bestMoy = null; $bestStudentId = null;
        $worstMoy = null;
        $successCount = 0;

        foreach ($averages as $studentId => $data) {
            $moy = $data['moyenne'] ?? null;

            $idx = self::classifyMoyenne($moy, $intervals);
            if (! is_null($idx)) $intervalCounts[$idx]++;

            if (! is_null($moy) && $moy >= 10) $successCount++;

            $g = strtoupper(substr($genders[$studentId] ?? '', 0, 1));
            if ($g === 'M') $garcons++;
            elseif ($g === 'F') $filles++;

            if (! is_null($moy)) {
                if (is_null($bestMoy) || $moy > $bestMoy) { $bestMoy = $moy; $bestStudentId = $studentId; }
                if (is_null($worstMoy) || $moy < $worstMoy) { $worstMoy = $moy; }
            }
        }

        return [
            'label'          => $classe->code ?: $classe->name,
            'classeId'       => $classe->id,
            'promotionLabel' => $classe->promotion?->name ?? 'Sans promotion',
            'total'          => count($studentIds),
            'garcons'        => $garcons,
            'filles'         => $filles,
            'abandons'       => $classe->getClasseStudentsLeavesCount(),
            'intervalCounts' => $intervalCounts,
            'successCount'   => $successCount,
            'bestMoy'        => $bestMoy,
            'bestStudentId'  => $bestStudentId,
            'worstMoy'       => $worstMoy,
        ];
    }

    protected static function aggregateStats(array $statsList, string $label): array
    {
        $total = 0; $garcons = 0; $filles = 0; $abandons = 0; $successCount = 0;
        $intervalCounts = null;
        $bestMoy = null; $bestStudentId = null;
        $worstMoy = null;

        foreach ($statsList as $s) {
            $total += $s['total'];
            $garcons += $s['garcons'];
            $filles += $s['filles'];
            $abandons += $s['abandons'];
            $successCount += $s['successCount'];

            $intervalCounts = is_null($intervalCounts)
                ? $s['intervalCounts']
                : array_map(fn ($a, $b) => $a + $b, $intervalCounts, $s['intervalCounts']);

            if (! is_null($s['bestMoy']) && (is_null($bestMoy) || $s['bestMoy'] > $bestMoy)) {
                $bestMoy = $s['bestMoy'];
                $bestStudentId = $s['bestStudentId'];
            }

            if (! is_null($s['worstMoy']) && (is_null($worstMoy) || $s['worstMoy'] < $worstMoy)) {
                $worstMoy = $s['worstMoy'];
            }
        }

        return [
            'label'          => $label,
            'total'          => $total,
            'garcons'        => $garcons,
            'filles'         => $filles,
            'abandons'       => $abandons,
            'intervalCounts' => $intervalCounts ?? [],
            'successCount'   => $successCount,
            'bestMoy'        => $bestMoy,
            'bestStudentId'  => $bestStudentId,
            'worstMoy'       => $worstMoy,
        ];
    }


    protected static function isSingleGroupScope(array $config): bool
    {
        return ! empty($config['classe_id'])
            || ! empty($config['filiar_id'])
            || ! empty($config['serial_id'])
            || ! empty($config['promotion_id'])
            || ! empty($config['promotionInGroups']);
    }

    /**
     * Point d'entrée principal. Retourne :
     * ['rows' => [...], 'intervals' => [...], 'intervalLabels' => [...]]
     */
    public static function computeRows(array $config, int $schoolYearId, int $period, array $breakpoints, string $groupedBy): array
    {
        $intervals = self::buildIntervals($breakpoints);
        $totalIntervals = count($intervals);

        $classes = self::classesQuery($config, $schoolYearId)->get()->values();

        $pairs = $classes->map(fn ($classe) => [
            'classe' => $classe,
            'stat'   => self::computeClasseStat($classe, $schoolYearId, $period, $intervals, $totalIntervals),
        ]);

        $allStats = $pairs->pluck('stat')->all();

        $rows = [];

        if ($groupedBy === 'classe_id') {
            if (self::isSingleGroupScope($config)) {
                foreach ($allStats as $stat) $rows[] = ['type' => 'classe'] + $stat;

                if (count($allStats) > 1) {
                    $rows[] = ['type' => 'total'] + self::aggregateStats($allStats, 'Total général');
                }
            } else {
                $byPromotion = $pairs->groupBy(fn ($p) => $p['stat']['promotionLabel'])->sortKeys();

                foreach ($byPromotion as $promoLabel => $group) {
                    foreach ($group as $p) $rows[] = ['type' => 'classe'] + $p['stat'];

                    $rows[] = ['type' => 'subtotal'] + self::aggregateStats($group->pluck('stat')->all(), "Sous-total — {$promoLabel}");
                }

                if ($byPromotion->count() > 1) {
                    $rows[] = ['type' => 'total'] + self::aggregateStats($allStats, 'Total général');
                }
            }
        } else {
            $groupLabelResolver = match ($groupedBy) {
                'filiar_id'         => fn ($c) => $c->filiar ? ($c->filiar->code ?: $c->filiar->name) : 'Sans filière',
                'serial_id'         => fn ($c) => $c->serial ? ($c->serial->code ?: $c->serial->name) : 'Sans série',
                'promotionInGroups' => fn ($c) => $c->promotion?->name ?? 'Sans promotion',
            };

            $grouped = $pairs->groupBy(fn ($p) => $groupLabelResolver($p['classe']))->sortKeys();

            foreach ($grouped as $label => $group) {
                $rows[] = ['type' => 'group'] + self::aggregateStats($group->pluck('stat')->all(), $label);
            }

            if ($grouped->count() > 1) {
                $rows[] = ['type' => 'total'] + self::aggregateStats($allStats, 'Total général');
            }
        }

        // Résolution des noms en un seul lot
        $studentIds = collect($rows)->pluck('bestStudentId')->filter()->unique()->values();
        $names = Student::whereIn('id', $studentIds)->get()->mapWithKeys(fn ($s) => [$s->id => $s->getFullName()]);

        foreach ($rows as &$row) {
            $row['bestStudentName'] = $row['bestStudentId'] ? ($names[$row['bestStudentId']] ?? null) : null;
        }

        return [
            'rows'           => $rows,
            'intervals'      => $intervals,
            'intervalLabels' => self::intervalLabels($intervals),
        ];
    }

    public static function resolveDocTitle(array $config, string $groupedBy, int $period, ?\App\Models\SchoolYear $schoolYear): string
    {
        $groupLabel = match ($groupedBy) {
            'promotionInGroups' => 'par promotion',
            'filiar_id'         => 'par filière',
            'serial_id'         => 'par série',
            default             => 'par classe',
        };

        $doc_title = "Statistiques des moyennes {$groupLabel}";

        if ($schoolYear) $doc_title .= " - {$schoolYear->periodLabel()} {$period}";

        if (! empty($config['classe_id'])) {
            $classe = Classe::find($config['classe_id']);
            if ($classe) $doc_title .= " de la classe {$classe->name}";
        }
        if (! empty($config['filiar_id'])) {
            $filiar = \App\Models\Filiar::find($config['filiar_id']);
            if ($filiar) $doc_title .= " de la filière {$filiar->name}";
        }
        if (! empty($config['serial_id'])) {
            $serial = \App\Models\Serial::find($config['serial_id']);
            if ($serial) $doc_title .= " de la série {$serial->name}";
        }
        if (! empty($config['promotion_id'])) {
            $promo = \App\Models\Promotion::find($config['promotion_id']);
            if ($promo) $doc_title .= " de la promotion {$promo->name}";
        }
        if (! empty($config['promotionInGroups'])) {
            $doc_title .= " de la promotion {$config['promotionInGroups']}";
        }

        return $doc_title;
    }
}