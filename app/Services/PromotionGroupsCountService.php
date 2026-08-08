<?php

namespace App\Services;

use App\Contracts\RefreshableSchoolYearCache;
use App\Models\SchoolYear;
use App\Models\YearlyClasseStudent;
use Illuminate\Support\Facades\Cache;

class PromotionGroupsCountService implements RefreshableSchoolYearCache
{
    protected const CACHE_TTL = 3600;

    protected const CACHE_PREFIX = 'promotion_groups_counts';

    /**
     * Structure retournée :
     * [
     *   'total' => 320, // effectif total actif (toutes promotions confondues)
     *   'promotions' => [
     *     'Sixième'  => ['count' => 45, 'percentage' => 14.06],
     *     'Cinquième'=> ['count' => 38, 'percentage' => 11.88],
     *     ...
     *   ],
     * ]
     */
    public function get(?int $schoolYearId = null): array
    {
        $schoolYearId ??= SchoolYear::current()?->first()?->id;

        return Cache::remember(
            $this->cacheKey($schoolYearId),
            self::CACHE_TTL,
            fn () => $this->compute($schoolYearId)
        );
    }

    public function forget(?int $schoolYearId = null): void
    {
        $schoolYearId ??= SchoolYear::current()?->first()?->id;

        Cache::forget($this->cacheKey($schoolYearId));
    }

    public function refreshForSchoolYear(int $schoolYearId): void
    {
        Cache::put($this->cacheKey($schoolYearId), $this->compute($schoolYearId), self::CACHE_TTL);
    }

    protected function compute(?int $schoolYearId): array
    {
        $promotionNames = config('app.promotionInGroups', []);

        // Effectif total des apprenants actifs (n'ayant pas abandonné), toutes promotions confondues.
        $totalActive = YearlyClasseStudent::query()
            ->where('school_year_id', $schoolYearId)
            ->where('is_active', true)
            ->whereNull('ended_at')
            ->distinct('student_id')
            ->count('student_id');

        // Une seule requête groupée pour compter toutes les promotions du tableau de config d'un coup.
        $countsByPromotion = YearlyClasseStudent::query()
            ->join('classes', 'classes.id', '=', 'yearly_classe_students.classe_id')
            ->join('promotions', 'promotions.id', '=', 'classes.promotion_id')
            ->where('yearly_classe_students.school_year_id', $schoolYearId)
            ->where('yearly_classe_students.is_active', true)
            ->whereNull('yearly_classe_students.ended_at')
            ->whereIn('promotions.name', $promotionNames)
            ->selectRaw('promotions.name as promotion_name, COUNT(DISTINCT yearly_classe_students.student_id) as total')
            ->groupBy('promotions.name')
            ->pluck('total', 'promotion_name');

        $result = [];

        foreach ($promotionNames as $name) {

            $count = (int) ($countsByPromotion[$name] ?? 0);

            $result[$name] = [
                'count'      => $count,
                'percentage' => $totalActive > 0 ? round(($count / $totalActive) * 100, 2) : null,
            ];
        }

        return [
            'total'      => $totalActive,
            'promotions' => $result,
        ];
    }

    protected function cacheKey(?int $schoolYearId): string
    {
        return self::CACHE_PREFIX . ":sy:{$schoolYearId}";
    }
}