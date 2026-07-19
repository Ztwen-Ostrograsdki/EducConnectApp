<?php

namespace App\Services\ClassesServices;


use App\Contracts\RefreshableSchoolYearCache;
use App\Models\Classe;
use App\Models\ClasseSubjectOfSchoolYear;
use App\Models\SchoolYear;
use App\Models\YearlyClasseStudent;
use App\Models\YearlyClasseStudentsLeave;
use Illuminate\Support\Facades\Cache;

class ClasseEffectifsService implements RefreshableSchoolYearCache
{
    protected int $cacheTtl = 3600;

    public function getEffectifs(int $classeId, ?int $schoolYearId = null): array
    {
        $schoolYearId ??= SchoolYear::current()?->first()?->id;

        return Cache::tags(["classe:{$classeId}", 'effectifs'])
            ->remember(
                $this->cacheKey($classeId, $schoolYearId),
                $this->cacheTtl,
                fn () => $this->computeEffectifs($classeId, $schoolYearId)
            );
    }


    protected function computeEffectifs(int $classeId, int $schoolYearId): array
    {
        return [
            'profs'               => $this->countActiveTeachers($classeId, $schoolYearId),
            'apprenants'          => $this->countActiveStudents($classeId, $schoolYearId),
            'apprenants_par_sexe' => $this->countStudentsByGender($classeId, $schoolYearId),
            'abandons'            => $this->countAbandons($classeId, $schoolYearId),
            'abandons_par_statut' => $this->countAbandonsByStatus($classeId, $schoolYearId),
        ];
    }

    protected function cacheKey(int $classeId, int $schoolYearId): string
    {
        return "classe:{$classeId}:sy:{$schoolYearId}:effectifs";
    }

    // ─── Profs ────────────────────────────────────────────────

    public function countActiveTeachers(int $classeId, int $schoolYearId): int
    {
        return ClasseSubjectOfSchoolYear::query()
            ->where('classe_id', $classeId)
            ->where('school_year_id', $schoolYearId)
            ->where('is_active', true)
            ->whereNull('ended_at')
            ->distinct('teacher_id')
            ->count('teacher_id');
    }

    // ─── Apprenants ───────────────────────────────────────────

    public function countActiveStudents(int $classeId, int $schoolYearId): int
    {
        return YearlyClasseStudent::query()
            ->where('classe_id', $classeId)
            ->where('school_year_id', $schoolYearId)
            ->where('is_active', true)
            ->whereNull('ended_at')
            ->count();
    }

    // Une seule requête groupée, via join sur students pour le genre.
    // Aucune hydratation de Student : la BDD renvoie juste 2-3 lignes agrégées.
    public function countStudentsByGender(int $classeId, int $schoolYearId): array
    {
        $query = YearlyClasseStudent::query()
			->join('students', 'students.id', '=', 'yearly_classe_students.student_id')
			->where('yearly_classe_students.classe_id', $classeId)
			->where('yearly_classe_students.school_year_id', $schoolYearId)
			->where('yearly_classe_students.is_active', true)
			->whereNull('yearly_classe_students.ended_at')
			->selectRaw("UPPER(LEFT(students.gender, 1)) as gender_code, count(*) as total")
			->groupBy('gender_code')
			->pluck('total', 'gender_code')
			->toArray();

        if($query == []){
            return [
                'F' => 0,    
                'M' => 0,    
            ];
        }
        
        return $query;
    }

    // ─── Abandons ─────────────────────────────────────────────

    public function countAbandons(int $classeId, int $schoolYearId): int
    {
        return YearlyClasseStudentsLeave::query()
            ->where('classe_id', $classeId)
            ->where('school_year_id', $schoolYearId)
            ->whereNull('ended_at')
            ->count();
    }

    public function countAbandonsByStatus(int $classeId, int $schoolYearId): array
    {
        return YearlyClasseStudentsLeave::query()
            ->where('classe_id', $classeId)
            ->where('school_year_id', $schoolYearId)
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
    }


    /**
     * Recharge le cache des effectifs pour toutes les classes actives de l'année donnée.
     */
    public function refreshForSchoolYear(int $schoolYearId): void
    {
        $classeIds = Classe::query()
            ->where('school_year_id', $schoolYearId)
            ->where('is_active', true)
            ->pluck('id');

        foreach ($classeIds as $classeId) {

            Cache::tags(["classe:{$classeId}", 'effectifs'])
                ->put(
                    $this->cacheKey($classeId, $schoolYearId),
                    $this->computeEffectifs($classeId, $schoolYearId),
                    $this->cacheTtl
                );
        }
    }
}