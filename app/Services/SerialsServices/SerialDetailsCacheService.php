<?php

namespace App\Services\SerialsServices;


use App\Contracts\RefreshableSchoolYearCache;
use App\Models\Classe;
use App\Models\ClasseSubjectOfSchoolYear;
use App\Models\SchoolYear;
use App\Models\Serial;
use App\Models\YearlyClasseStudent;
use App\Models\YearlyFiliarChief;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class SerialDetailsCacheService implements RefreshableSchoolYearCache
{
    protected const CACHE_TTL = 3600;

    protected const CACHE_PREFIX = 'serial_details';

    public function get(int $serialId, ?int $schoolYearId = null): array
    {
        return $this->getMany([$serialId], $schoolYearId)[$serialId];
    }

    public function getMany(array $serialIds, ?int $schoolYearId = null): array
    {
        if (empty($serialIds)) {
            return [];
        }

        $schoolYearId ??= SchoolYear::current()?->first()?->id;

        $serialIds = array_values(array_unique($serialIds));

        $keysBySerial = collect($serialIds)->mapWithKeys(
            fn (int $id) => [$id => $this->cacheKey($id, $schoolYearId)]
        );

        $cached = Cache::many($keysBySerial->values()->all());

        $missingSerialIds = $keysBySerial
            ->filter(fn (string $key) => is_null($cached[$key]))
            ->keys()
            ->all();

        $computed = [];

        if (!empty($missingSerialIds)) {
            $computed = $this->computeMany($missingSerialIds, $schoolYearId);

            Cache::putMany(
                collect($computed)->mapWithKeys(
                    fn (array $data, int $serialId) => [$keysBySerial[$serialId] => $data]
                )->all(),
                self::CACHE_TTL
            );
        }

        $result = [];

        foreach ($serialIds as $serialId) {
            $key = $keysBySerial[$serialId];
            $result[$serialId] = $cached[$key] ?? $computed[$serialId];
        }

        return $result;
    }

    public function forget(int $serialId, ?int $schoolYearId = null): void
    {
        $schoolYearId ??= SchoolYear::current()?->first()?->id;

        Cache::forget($this->cacheKey($serialId, $schoolYearId));
    }

    public function forgetMany(array $serialIds, ?int $schoolYearId = null): void
    {
        foreach ($serialIds as $serialId) {
            $this->forget($serialId, $schoolYearId);
        }
    }

    /**
     * Recharge en cache les détails de toutes les filières actives
     * pour une année scolaire donnée (appelé au switch d'année).
     */
    public function refreshForSchoolYear(int $schoolYearId): void
    {
        $serialIds = Serial::query()
            ->where('is_active', true)
            ->pluck('id')
            ->all();

        if (empty($serialIds)) {
            return;
        }

        $computed = $this->computeMany($serialIds, $schoolYearId);

        Cache::putMany(
            collect($computed)->mapWithKeys(
                fn (array $data, int $serialId) => [$this->cacheKey($serialId, $schoolYearId) => $data]
            )->all(),
            self::CACHE_TTL
        );
    }

    protected function cacheKey(int $serialId, ?int $schoolYearId): string
    {
        return self::CACHE_PREFIX . ":{$serialId}:{$schoolYearId}";
    }

    /**
     * Calcule les détails de plusieurs filières en 4 requêtes max,
     * peu importe le nombre de filières demandées.
     */
    protected function computeMany(array $serialIds, ?int $schoolYearId): array
    {
        $teachersCounts = $this->computeTeachersCounts($serialIds, $schoolYearId);
        $classesCounts  = $this->computeClassesCounts($serialIds, $schoolYearId);
        $studentsCounts = $this->computeStudentsCounts($serialIds, $schoolYearId);

        $result = [];

        foreach ($serialIds as $serialId) {
            $result[$serialId] = [
                'teachers_count' => $teachersCounts[$serialId] ?? 0,
                'classes_count'  => $classesCounts[$serialId] ?? 0,
                'students_count' => $studentsCounts[$serialId] ?? 0,
                'best_student'   => null,
                'weak_student'   => null,
            ];
        }

        return $result;
    }

    /**
     * Mirroir de Serial::getFiliarTeachersOfSchoolYear(), groupé par serial_id.
     */
    protected function computeTeachersCounts(array $serialIds, ?int $schoolYearId): Collection
    {
        return ClasseSubjectOfSchoolYear::query()
            ->join('classes', 'classes.id', '=', 'classe_subject_of_school_years.classe_id')
            ->select('classes.serial_id')
            ->selectRaw('COUNT(DISTINCT classe_subject_of_school_years.teacher_id) as teachers_count')
            ->where('classe_subject_of_school_years.school_year_id', $schoolYearId)
            ->where('classe_subject_of_school_years.is_active', true)
            ->whereNull('classe_subject_of_school_years.ended_at')
            ->whereIn('classes.serial_id', $serialIds)
            ->where('classes.is_active', true)
            ->groupBy('classes.serial_id')
            ->pluck('teachers_count', 'serial_id');
    }

    /**
     * Mirroir de Serial::getFiliarClassesOfSchoolYear(), groupé par serial_id.
     */
    protected function computeClassesCounts(array $serialIds, ?int $schoolYearId): Collection
    {
        return Classe::query()
            ->select('serial_id')
            ->selectRaw('COUNT(*) as classes_count')
            ->whereIn('serial_id', $serialIds)
            ->where('school_year_id', $schoolYearId)
            ->where('is_active', true)
            ->where('is_locked', false)
            ->groupBy('serial_id')
            ->pluck('classes_count', 'serial_id');
    }

    /**
     * Mirroir de Serial::getFiliarStudentsOfSchoolYear(), groupé par serial_id.
     */
    protected function computeStudentsCounts(array $serialIds, ?int $schoolYearId): Collection
    {
        return YearlyClasseStudent::query()
            ->join('classes', 'classes.id', '=', 'yearly_classe_students.classe_id')
            ->select('classes.serial_id')
            ->selectRaw('COUNT(*) as students_count')
            ->where('yearly_classe_students.school_year_id', $schoolYearId)
            ->where('yearly_classe_students.is_active', true)
            ->whereNull('yearly_classe_students.ended_at')
            ->whereIn('classes.serial_id', $serialIds)
            ->where('classes.is_active', true)
            ->groupBy('classes.serial_id')
            ->pluck('students_count', 'serial_id');
    }


    protected function formatChief(YearlyFiliarChief $chief): array
    {
        return [
            'teacher_id' => $chief->teacher_id,
            'full_name'  => trim(($chief->teacher?->getFullName() ?? '')),
        ];
    }
}