<?php

namespace App\Models;

use App\Models\Classe;
use App\Models\Promotion;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\YearlyFiliarChief;
use App\Traits\InvalidatesDashboardCounters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;


class Filiar extends Model
{
    use SoftDeletes, InvalidatesDashboardCounters;

    protected $table = 'filiars';

    protected $connection = 'tenant';

    protected $fillable = [
        'uuid',
        'slug',
        'name',
        'code',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ─── Relations ────────────────────────────────────────────────────

    /**
     * Get all classes belonging to this filiar.
     */
    public function classes(): HasMany
    {
        return $this->hasMany(Classe::class, 'filiar_id');
    }

    public function promotions(): HasMany
    {
        return $this->hasMany(Promotion::class);
    }

    /**
     * Get all yearly filiars where this subjects has chiefs (CA)
     */
    public function filiarChiefs(): HasMany
    {
        return $this->hasMany(YearlyFiliarChief::class, 'filiar_id');
    }


    public function currentPrincipalCA(?int $school_year_id = null)
    {
        if(!$school_year_id) $school_year_id = SchoolYear::current()?->first()?->id;

        return Teacher::query()
                        ->select('teachers.*')
                        ->join('users', 'users.id', '=', 'teachers.user_id')
                        ->with(['user'])
                        ->whereNotNull('affiliated_at')
                        ->whereHas('filiarsChiefs', fn($q) => 
                            $q->where('school_year_id', $school_year_id)
                                ->where('filiar_id', $this->id)
                                ->where('is_active', true)
                                ->where('is_master', true)
                        )->with('filiarsChiefs')->first();


    }


    public function currentAjointCA(?int $school_year_id = null)
    {
        if(!$school_year_id) $school_year_id = SchoolYear::current()?->first()?->id;

        return Teacher::query()
                        ->select('teachers.*')
                        ->join('users', 'users.id', '=', 'teachers.user_id')
                        ->with(['user'])
                        ->whereNotNull('affiliated_at')
                        ->whereHas('filiarsChiefs', fn($q) => 
                            $q->where('school_year_id', $school_year_id)
                                ->where('filiar_id', $this->id)
                                ->where('is_active', true)
                                ->where('is_master', false)
                        )->with('filiarsChiefs')->first();


    }



/**
 * CA principal (is_master = true) de l'année active.
 */
    public function principalCA(): HasOneThrough
    {
        $schoolYearId = SchoolYear::current()?->first()?->id;

        return $this->hasOneThrough(
            Teacher::class,
            YearlyFiliarChief::class,
            'filiar_id',   // FK sur yearly_filiar_chiefs qui pointe vers filiars.id
            'id',          // FK sur teachers qui pointe vers yearly_filiar_chiefs.teacher_id
            'id',          // clé locale sur filiars
            'teacher_id'   // clé locale sur yearly_filiar_chiefs
        )
        ->join('users', 'users.id', '=', 'teachers.user_id')
        ->addSelect('teachers.*')
        ->with('user')
        ->whereNotNull('teachers.affiliated_at')
        ->where('yearly_filiar_chiefs.school_year_id', $schoolYearId)
        ->where('yearly_filiar_chiefs.is_active', true)
        ->where('yearly_filiar_chiefs.is_master', true);
    }

    /**
     * CA adjoint (is_master = false) de l'année active.
     */
    public function adjointCA(): HasOneThrough
    {
        $schoolYearId = SchoolYear::current()?->first()?->id;

        return $this->hasOneThrough(
            Teacher::class,
            YearlyFiliarChief::class,
            'filiar_id',
            'id',
            'id',
            'teacher_id'
        )
        ->join('users', 'users.id', '=', 'teachers.user_id')
        ->addSelect('teachers.*')
        ->with('user')
        ->whereNotNull('teachers.affiliated_at')
        ->where('yearly_filiar_chiefs.school_year_id', $schoolYearId)
        ->where('yearly_filiar_chiefs.is_active', true)
        ->where('yearly_filiar_chiefs.is_master', false);
    }

    /**
     * Les deux CA (principal + adjoint) de l'année active, en une seule requête.
     * Chacun peut être absent (0, 1, ou 2 résultats selon ce qui est réellement assigné) ;
     * is_master distingue lequel est principal (true) ou adjoint (false).
     */
    public function currentChiefs(): HasManyThrough
    {
        $schoolYearId = SchoolYear::current()?->first()?->id;

        return $this->hasManyThrough(
            Teacher::class,
            YearlyFiliarChief::class,
            'filiar_id',
            'id',
            'id',
            'teacher_id'
        )
        ->join('users', 'users.id', '=', 'teachers.user_id')
        ->addSelect('teachers.*', 'yearly_filiar_chiefs.is_master as chief_is_master')
        ->with('user')
        ->whereNotNull('teachers.affiliated_at')
        ->where('yearly_filiar_chiefs.school_year_id', $schoolYearId)
        ->where('yearly_filiar_chiefs.is_active', true)
        ->orderByDesc('yearly_filiar_chiefs.is_master'); // principal (true) avant adjoint (false)
    }

    public function getFiliarClassesOfSchoolYear(?int $school_year_id = null)
    {
        if(!$school_year_id) $school_year_id = SchoolYear::current()?->first()?->id;

        return $this->classes()->where('classes.school_year_id', $school_year_id)->where('classes.is_active', true)->where('classes.is_locked', false)->orderBy('name', 'desc');
    }

    public function getFiliarClassesOfSchoolYearCount(?int $school_year_id = null) : int
    {
        return $this->getFiliarClassesOfSchoolYear($school_year_id)->count();
    }

    public function getFiliarSubjectsOfSchoolYear(?int $school_year_id = null)
    {
        if(!$school_year_id) $school_year_id = SchoolYear::current()?->first()?->id;

        return  Subject::where('is_active', true)->whereHas('classeSubjects', fn($q) => 
                                $q->whereHas('classe', fn($qs) => 
                                    $qs->where('is_active', true)
                                        ->where('filiar_id', $this->id)
                                        ->where('is_active', true)
                                )
                                ->where('is_active', true)
                                ->whereNull('ended_at')
                            )->orderBy('name', 'desc');
    }

    public function getFiliarStudentsOfSchoolYear(?int $school_year_id = null, ?int $classe_id = null, ?int $promotion_id = null, ?string $gender = null)
    {
        if(!$school_year_id) $school_year_id = SchoolYear::current()?->first()?->id;

        return  Student::where('is_active', true)->whereHas('yearlyClasseStudents', fn($q) => 
                                $q->where('school_year_id', $school_year_id)
                                  ->where('is_active', true)
                                  ->whereNull('ended_at')
                                  ->whereHas('classe', fn($qc) => 
                                        $qc->where('filiar_id', $this->id)
                                           ->where('is_active', true)
                                           ->when($classe_id, fn($qc) => 
                                            $qc->where('classe_id', $classe_id)
                                        )
                                        ->when($promotion_id, fn($qs) => 
                                            $qs->where('promotion_id', $promotion_id)
                                        )
                                    )

                            )
                            ->when($gender, fn($q) => $q->whereIn('gender', [$gender, Str::lower($gender), Str::upper($gender)]));
    }

    public function getFiliarStudentsOfSchoolYearCount(?int $school_year_id = null) : int
    {
        return $this->getFiliarStudentsOfSchoolYear($school_year_id)->count();
    }


    public function getFiliarTeachersOfSchoolYear(?int $school_year_id = null, ?int $classe_id = null, ?int $promotion_id = null, ?int $subject_id = null, ?string $gender = null)
    {
        if(!$school_year_id) $school_year_id = SchoolYear::current()?->first()?->id;

        return  Teacher::query()
                        ->select('teachers.*')
                        ->join('users', 'users.id', '=', 'teachers.user_id')
                        ->with(['user'])
                        ->whereNotNull('affiliated_at')
                        ->whereHas('classeSubjects', fn($q) => 
                            $q->where('school_year_id', $school_year_id)
                                ->when($classe_id, fn($qc) => 
                                    $qc->where('classe_id', $classe_id)
                                )
                                ->when($subject_id, fn($qs) => 
                                    $qs->where('subject_id', $subject_id)
                                )
                                ->whereHas('classe', fn($qcc) => 
                                    $qcc->where('filiar_id', $this->id)
                                        ->where('is_active', true)
                                        ->when($promotion_id, fn($qp) => 
                                            $qp->where('promotion_id', $promotion_id)
                                        )
                                )
                                ->where('is_active', true)
                                ->whereNull('ended_at')
                        )
                        ->when($gender, fn($q) => $q->whereIn('users.gender', [$gender, Str::lower($gender), Str::upper($gender)]));
    }

    public function getFiliarTeachersOfSchoolYearCount(?int $school_year_id = null) : int
    {
        return $this->getFiliarTeachersOfSchoolYear($school_year_id)->count();
    }

    // ─── Scopes ───────────────────────────────────────────────────────

    /**
     * Scope to get only active filiars.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }



    // ─── Helpers ──────────────────────────────────────────────────────

    /**
     * Check if the filiar is active.
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }
}
