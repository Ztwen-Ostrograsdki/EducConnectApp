<?php

namespace App\Services\FiliarsServices;


use App\Contracts\RefreshableSchoolYearCache;
use App\Models\Classe;
use App\Models\ClasseSubjectOfSchoolYear;
use App\Models\Filiar;
use App\Models\SchoolYear;
use App\Models\YearlyClasseStudent;
use App\Models\YearlyFiliarChief;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class FiliarDetailsCacheService implements RefreshableSchoolYearCache
{
    protected const CACHE_TTL = 3600;

    protected const CACHE_PREFIX = 'filiar_details';

    public function get(int $filiarId, ?int $schoolYearId = null): array
    {
        return $this->getMany([$filiarId], $schoolYearId)[$filiarId];
    }

    public function getMany(array $filiarIds, ?int $schoolYearId = null): array
    {
        if (empty($filiarIds)) {
            return [];
        }

        $schoolYearId ??= SchoolYear::current()?->first()?->id;

        $filiarIds = array_values(array_unique($filiarIds));

        $keysByFiliar = collect($filiarIds)->mapWithKeys(
            fn (int $id) => [$id => $this->cacheKey($id, $schoolYearId)]
        );

        $cached = Cache::many($keysByFiliar->values()->all());

        $missingFiliarIds = $keysByFiliar
            ->filter(fn (string $key) => is_null($cached[$key]))
            ->keys()
            ->all();

        $computed = [];

        if (!empty($missingFiliarIds)) {
            $computed = $this->computeMany($missingFiliarIds, $schoolYearId);

            Cache::putMany(
                collect($computed)->mapWithKeys(
                    fn (array $data, int $filiarId) => [$keysByFiliar[$filiarId] => $data]
                )->all(),
                self::CACHE_TTL
            );
        }

        $result = [];

        foreach ($filiarIds as $filiarId) {
            $key = $keysByFiliar[$filiarId];
            $result[$filiarId] = $cached[$key] ?? $computed[$filiarId];
        }

        return $result;
    }

    public function forget(int $filiarId, ?int $schoolYearId = null): void
    {
        $schoolYearId ??= SchoolYear::current()?->first()?->id;

        Cache::forget($this->cacheKey($filiarId, $schoolYearId));
    }

    public function forgetMany(array $filiarIds, ?int $schoolYearId = null): void
    {
        foreach ($filiarIds as $filiarId) {
            $this->forget($filiarId, $schoolYearId);
        }
    }

    /**
     * Recharge en cache les détails de toutes les filières actives
     * pour une année scolaire donnée (appelé au switch d'année).
     */
    public function refreshForSchoolYear(int $schoolYearId): void
    {
        $filiarIds = Filiar::query()
            ->where('is_active', true)
            ->pluck('id')
            ->all();

        if (empty($filiarIds)) {
            return;
        }

        $computed = $this->computeMany($filiarIds, $schoolYearId);

        Cache::putMany(
            collect($computed)->mapWithKeys(
                fn (array $data, int $filiarId) => [$this->cacheKey($filiarId, $schoolYearId) => $data]
            )->all(),
            self::CACHE_TTL
        );
    }

    protected function cacheKey(int $filiarId, ?int $schoolYearId): string
    {
        return self::CACHE_PREFIX . ":{$filiarId}:{$schoolYearId}";
    }

    /**
     * Calcule les détails de plusieurs filières en 4 requêtes max,
     * peu importe le nombre de filières demandées.
     */
    protected function computeMany(array $filiarIds, ?int $schoolYearId): array
    {
        $teachersCounts = $this->computeTeachersCounts($filiarIds, $schoolYearId);
        $classesCounts  = $this->computeClassesCounts($filiarIds, $schoolYearId);
        $studentsCounts = $this->computeStudentsCounts($filiarIds, $schoolYearId);
        $chiefs         = $this->computeChiefs($filiarIds, $schoolYearId);

        $result = [];

        foreach ($filiarIds as $filiarId) {
            $result[$filiarId] = [
                'teachers_count' => $teachersCounts[$filiarId] ?? 0,
                'classes_count'  => $classesCounts[$filiarId] ?? 0,
                'students_count' => $studentsCounts[$filiarId] ?? 0,
                'best_student'   => null,
                'weak_student'   => null,
                'chief'          => $chiefs[$filiarId] ?? ['principal' => null, 'adjoint' => null],
            ];
        }

        return $result;
    }

    /**
     * Mirroir de Filiar::getFiliarTeachersOfSchoolYear(), groupé par filiar_id.
     */
    protected function computeTeachersCounts(array $filiarIds, ?int $schoolYearId): Collection
    {
        return ClasseSubjectOfSchoolYear::query()
            ->join('classes', 'classes.id', '=', 'classe_subject_of_school_years.classe_id')
            ->select('classes.filiar_id')
            ->selectRaw('COUNT(DISTINCT classe_subject_of_school_years.teacher_id) as teachers_count')
            ->where('classe_subject_of_school_years.school_year_id', $schoolYearId)
            ->where('classe_subject_of_school_years.is_active', true)
            ->whereNull('classe_subject_of_school_years.ended_at')
            ->whereIn('classes.filiar_id', $filiarIds)
            ->where('classes.is_active', true)
            ->groupBy('classes.filiar_id')
            ->pluck('teachers_count', 'filiar_id');
    }

    /**
     * Mirroir de Filiar::getFiliarClassesOfSchoolYear(), groupé par filiar_id.
     */
    protected function computeClassesCounts(array $filiarIds, ?int $schoolYearId): Collection
    {
        return Classe::query()
            ->select('filiar_id')
            ->selectRaw('COUNT(*) as classes_count')
            ->whereIn('filiar_id', $filiarIds)
            ->where('school_year_id', $schoolYearId)
            ->where('is_active', true)
            ->where('is_locked', false)
            ->groupBy('filiar_id')
            ->pluck('classes_count', 'filiar_id');
    }

    /**
     * Mirroir de Filiar::getFiliarStudentsOfSchoolYear(), groupé par filiar_id.
     */
    protected function computeStudentsCounts(array $filiarIds, ?int $schoolYearId): Collection
    {
        return YearlyClasseStudent::query()
            ->join('classes', 'classes.id', '=', 'yearly_classe_students.classe_id')
            ->select('classes.filiar_id')
            ->selectRaw('COUNT(*) as students_count')
            ->where('yearly_classe_students.school_year_id', $schoolYearId)
            ->where('yearly_classe_students.is_active', true)
            ->whereNull('yearly_classe_students.ended_at')
            ->whereIn('classes.filiar_id', $filiarIds)
            ->where('classes.is_active', true)
            ->groupBy('classes.filiar_id')
            ->pluck('students_count', 'filiar_id');
    }

    /**
     * @return array<int, array{principal: ?array, adjoint: ?array}>
     */
    protected function computeChiefs(array $filiarIds, ?int $schoolYearId): array
    {
        $chiefsByFiliar = YearlyFiliarChief::query()
            ->select(['id', 'filiar_id', 'teacher_id', 'is_master'])
            ->whereIn('filiar_id', $filiarIds)
            ->where('school_year_id', $schoolYearId)
            ->where('is_active', true)
            ->with(['teacher.user:id,name,prenames'])
            ->get()
            ->groupBy('filiar_id');

        $result = [];

        foreach ($filiarIds as $filiarId) {
            $group = $chiefsByFiliar->get($filiarId, collect());

            $principal = $group->firstWhere('is_master', true);
            $adjoint   = $group->firstWhere('is_master', false);

            $result[$filiarId] = [
                'principal' => $principal ? $this->formatChief($principal) : null,
                'adjoint'   => $adjoint ? $this->formatChief($adjoint) : null,
            ];
        }

        return $result;
    }

    protected function formatChief(YearlyFiliarChief $chief): array
    {
        return [
            'teacher_id' => $chief->teacher_id,
            'full_name'  => trim(($chief->teacher?->getFullName() ?? '')),
        ];
    }
}