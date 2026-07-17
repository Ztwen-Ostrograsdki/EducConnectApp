<?php

namespace App\Services\PromotionsServices;


use App\Contracts\RefreshableSchoolYearCache;
use App\Models\Classe;
use App\Models\ClasseSubjectOfSchoolYear;
use App\Models\Promotion;
use App\Models\SchoolYear;
use App\Models\YearlyClasseStudent;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class PromotionDetailsCacheService implements RefreshableSchoolYearCache
{
    protected const CACHE_TTL = 3600;

    protected const CACHE_PREFIX = 'promotion_details';

    public function get(int $promotionId, ?int $schoolYearId = null): array
    {
        return $this->getMany([$promotionId], $schoolYearId)[$promotionId];
    }

    public function getMany(array $promotionIds, ?int $schoolYearId = null): array
    {
        if (empty($promotionIds)) {
            return [];
        }

        $schoolYearId ??= SchoolYear::current()?->first()?->id;

        $promotionIds = array_values(array_unique($promotionIds));

        $keysByPromotion = collect($promotionIds)->mapWithKeys(
            fn (int $id) => [$id => $this->cacheKey($id, $schoolYearId)]
        );

        $cached = Cache::many($keysByPromotion->values()->all());

        $missingPromotionIds = $keysByPromotion
            ->filter(fn (string $key) => is_null($cached[$key]))
            ->keys()
            ->all();

        $computed = [];

        if (!empty($missingPromotionIds)) {
            $computed = $this->computeMany($missingPromotionIds, $schoolYearId);

            Cache::putMany(
                collect($computed)->mapWithKeys(
                    fn (array $data, int $promotionId) => [$keysByPromotion[$promotionId] => $data]
                )->all(),
                self::CACHE_TTL
            );
        }

        $result = [];

        foreach ($promotionIds as $promotionId) {
            $key = $keysByPromotion[$promotionId];
            $result[$promotionId] = $cached[$key] ?? $computed[$promotionId];
        }

        return $result;
    }

    public function forget(int $promotionId, ?int $schoolYearId = null): void
    {
        $schoolYearId ??= SchoolYear::current()?->first()?->id;

        Cache::forget($this->cacheKey($promotionId, $schoolYearId));
    }

    public function forgetMany(array $promotionIds, ?int $schoolYearId = null): void
    {
        foreach ($promotionIds as $promotionId) {
            $this->forget($promotionId, $schoolYearId);
        }
    }

    /**
     * Recharge en cache les détails de toutes les promotions actives
     * pour une année scolaire donnée (appelé au switch d'année).
     */
    public function refreshForSchoolYear(int $schoolYearId): void
    {
        $promotionIds = Promotion::query()
            ->where('is_active', true)
            ->pluck('id')
            ->all();

        if (empty($promotionIds)) {
            return;
        }

        $computed = $this->computeMany($promotionIds, $schoolYearId);

        Cache::putMany(
            collect($computed)->mapWithKeys(
                fn (array $data, int $promotionId) => [$this->cacheKey($promotionId, $schoolYearId) => $data]
            )->all(),
            self::CACHE_TTL
        );
    }

    protected function cacheKey(int $promotionId, ?int $schoolYearId): string
    {
        return self::CACHE_PREFIX . ":{$promotionId}:{$schoolYearId}";
    }

    /**
     * Calcule les détails de plusieurs promotions en 4 requêtes max,
     * peu importe le nombre de promotions demandées.
     */
    protected function computeMany(array $promotionIds, ?int $schoolYearId): array
    {
        $teachersCounts = $this->computeTeachersCounts($promotionIds, $schoolYearId);
        $classesCounts  = $this->computeClassesCounts($promotionIds, $schoolYearId);
        $studentsCounts = $this->computeStudentsCounts($promotionIds, $schoolYearId);

        $result = [];

        foreach ($promotionIds as $promotionId) {
            $result[$promotionId] = [
                'teachers_count' => $teachersCounts[$promotionId] ?? 0,
                'classes_count'  => $classesCounts[$promotionId] ?? 0,
                'students_count' => $studentsCounts[$promotionId] ?? 0,
                'best_student'   => null,
                'weak_student'   => null,
            ];
        }

        return $result;
    }

    protected function computeTeachersCounts(array $promotionIds, ?int $schoolYearId): Collection
    {
        return ClasseSubjectOfSchoolYear::query()
            ->join('classes', 'classes.id', '=', 'classe_subject_of_school_years.classe_id')
            ->select('classes.promotion_id')
            ->selectRaw('COUNT(DISTINCT classe_subject_of_school_years.teacher_id) as teachers_count')
            ->where('classe_subject_of_school_years.school_year_id', $schoolYearId)
            ->where('classe_subject_of_school_years.is_active', true)
            ->whereNull('classe_subject_of_school_years.ended_at')
            ->whereIn('classes.promotion_id', $promotionIds)
            ->where('classes.is_active', true)
            ->groupBy('classes.promotion_id')
            ->pluck('teachers_count', 'promotion_id');
    }

    protected function computeClassesCounts(array $promotionIds, ?int $schoolYearId): Collection
    {
        return Classe::query()
            ->select('promotion_id')
            ->selectRaw('COUNT(*) as classes_count')
            ->whereIn('promotion_id', $promotionIds)
            ->where('school_year_id', $schoolYearId)
            ->where('is_active', true)
            ->where('is_locked', false)
            ->groupBy('promotion_id')
            ->pluck('classes_count', 'promotion_id');
    }

    protected function computeStudentsCounts(array $promotionIds, ?int $schoolYearId): Collection
    {
        return YearlyClasseStudent::query()
            ->join('classes', 'classes.id', '=', 'yearly_classe_students.classe_id')
            ->select('classes.promotion_id')
            ->selectRaw('COUNT(*) as students_count')
            ->where('yearly_classe_students.school_year_id', $schoolYearId)
            ->where('yearly_classe_students.is_active', true)
            ->whereNull('yearly_classe_students.ended_at')
            ->whereIn('classes.promotion_id', $promotionIds)
            ->where('classes.is_active', true)
            ->groupBy('classes.promotion_id')
            ->pluck('students_count', 'promotion_id');
    }


   
}