<?php

namespace App\Services\SubjectsServices;


use App\Contracts\RefreshableSchoolYearCache;
use App\Models\ClasseSubjectOfSchoolYear;
use App\Models\SchoolYear;
use App\Models\Subject;
use App\Models\TeacherYearlySubject;
use App\Models\YearlySubjectChief;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class SubjectDetailsCacheService implements RefreshableSchoolYearCache
{
    protected const CACHE_TTL = 3600; // 1h

    protected const CACHE_PREFIX = 'subject_details';


	/**
	 * Recharge en cache les détails de TOUTES les matières actives
	 * pour une année scolaire donnée (typiquement appelé au switch d'année).
	 * Écrase le cache existant (pas de lecture préalable) pour garantir des données fraîches.
	 */
	public function refreshForSchoolYear(?int $schoolYearId = null): void
	{
		$schoolYearId ??= SchoolYear::current()?->first()?->id;

		if (!$schoolYearId) {
			return;
		}

		$subjectIds = Subject::query()
			->where('is_active', true)
			->pluck('id')
			->all();

		if (empty($subjectIds)) {
			return;
		}

		$computed = $this->computeMany($subjectIds, $schoolYearId);

		Cache::putMany(
			collect($computed)->mapWithKeys(
				fn (array $data, int $subjectId) => [$this->cacheKey($subjectId, $schoolYearId) => $data]
			)->all(),
			self::CACHE_TTL
		);
	}

    /**
     * Récupère les détails d'une seule matière (cache-first).
     */
    public function get(int $subjectId, ?int $schoolYearId = null): array
    {
        return $this->getMany([$subjectId], $schoolYearId)[$subjectId];
    }

    /**
     * Récupère les détails de plusieurs matières en minimisant les
     * allers-retours cache/DB : 1 lecture groupée du cache, puis
     * calcul groupé uniquement des clés manquantes.
     */
    public function getMany(array $subjectIds, ?int $schoolYearId = null): array
    {
        if (empty($subjectIds)) {
            return [];
        }

        $schoolYearId ??= SchoolYear::current()?->first()?->id;

        $subjectIds = array_values(array_unique($subjectIds));

        $keysBySubject = collect($subjectIds)->mapWithKeys(
            fn (int $id) => [$id => $this->cacheKey($id, $schoolYearId)]
        );

        $cached = Cache::many($keysBySubject->values()->all());

        $missingSubjectIds = $keysBySubject
            ->filter(fn (string $key) => is_null($cached[$key]))
            ->keys()
            ->all();

        $computed = [];

        if (!empty($missingSubjectIds)) {
            $computed = $this->computeMany($missingSubjectIds, $schoolYearId);

            Cache::putMany(
                collect($computed)->mapWithKeys(
                    fn (array $data, int $subjectId) => [$keysBySubject[$subjectId] => $data]
                )->all(),
                self::CACHE_TTL
            );
        }

        $result = [];

        foreach ($subjectIds as $subjectId) {
            $key = $keysBySubject[$subjectId];
            $result[$subjectId] = $cached[$key] ?? $computed[$subjectId];
        }

        return $result;
    }

    public function forget(int $subjectId, ?int $schoolYearId = null): void
    {
        $schoolYearId ??= SchoolYear::current()?->first()?->id;

        Cache::forget($this->cacheKey($subjectId, $schoolYearId));
    }

    public function forgetMany(array $subjectIds, ?int $schoolYearId = null): void
    {
        foreach ($subjectIds as $subjectId) {
            $this->forget($subjectId, $schoolYearId);
        }
    }

    protected function cacheKey(int $subjectId, ?int $schoolYearId): string
    {
        return self::CACHE_PREFIX . ":{$subjectId}:{$schoolYearId}";
    }

    /**
     * Calcule les détails de plusieurs matières en 3 requêtes max
     * (peu importe le nombre de matières demandées).
     */
    protected function computeMany(array $subjectIds, ?int $schoolYearId): array
    {
        $teachersCounts = $this->computeTeachersCounts($subjectIds, $schoolYearId);
        $classesCounts  = $this->computeClassesCounts($subjectIds, $schoolYearId);
        $chiefs         = $this->computeChiefs($subjectIds, $schoolYearId);

        $result = [];

        foreach ($subjectIds as $subjectId) {
            $result[$subjectId] = [
                'teachers_count' => $teachersCounts[$subjectId] ?? 0,
                'classes_count'  => $classesCounts[$subjectId] ?? 0,
                'best_classe'    => null,
                'best_student'   => null,
                'chief'          => $chiefs[$subjectId] ?? ['principal' => null, 'adjoint' => null],
            ];
        }

        return $result;
    }

    protected function computeTeachersCounts(array $subjectIds, ?int $schoolYearId): Collection
    {
        return TeacherYearlySubject::query()
            ->select('subject_id')
            ->selectRaw('COUNT(DISTINCT teacher_id) as teachers_count')
            ->whereIn('subject_id', $subjectIds)
            ->where('school_year_id', $schoolYearId)
            ->where('is_active', true)
            ->groupBy('subject_id')
            ->pluck('teachers_count', 'subject_id');
    }

    protected function computeClassesCounts(array $subjectIds, ?int $schoolYearId): Collection
    {
        return ClasseSubjectOfSchoolYear::query()
            ->select('subject_id')
            ->selectRaw('COUNT(DISTINCT classe_id) as classes_count')
            ->whereIn('subject_id', $subjectIds)
            ->where('school_year_id', $schoolYearId)
            ->current() // scope existant : is_active=true, ended_at=null
            ->groupBy('subject_id')
            ->pluck('classes_count', 'subject_id');
    }

    /**
     * @return array<int, array{principal: ?array, adjoint: ?array}>
     */
    protected function computeChiefs(array $subjectIds, ?int $schoolYearId): array
    {
        $chiefsBySubject = YearlySubjectChief::query()
            ->select(['id', 'subject_id', 'teacher_id', 'is_master'])
            ->whereIn('subject_id', $subjectIds)
            ->where('school_year_id', $schoolYearId)
            ->where('is_active', true)
            ->with(['teacher.user:id,name,prenames']) // adapte les colonnes user à ton schéma
            ->get()
            ->groupBy('subject_id');

        $result = [];

        foreach ($subjectIds as $subjectId) {
            $group = $chiefsBySubject->get($subjectId, collect());

            $principal = $group->firstWhere('is_master', true);
            $adjoint   = $group->firstWhere('is_master', false);

            $result[$subjectId] = [
                'principal' => $principal ? $this->formatChief($principal) : null,
                'adjoint'   => $adjoint ? $this->formatChief($adjoint) : null,
            ];
        }

        return $result;
    }

    protected function formatChief(YearlySubjectChief $chief): array
    {
        return [
            'teacher_id' => $chief->teacher_id,
            'full_name'  => trim(($chief->teacher?->getFullName() ?? '')),
        ];
    }
}