<?php

namespace App\Models;

use App\Exceptions\ModelCouldNotBeDeleteBecauseHasActivesAssignmentsException;
use App\Models\ClasseSubjectOfSchoolYear;
use App\Models\Mark;
use App\Models\Presence;
use App\Models\SchoolYear;
use App\Models\Teacher;
use App\Models\User;
use App\Models\YearlyPromotionSpecialitySubjectCoef;
use App\Models\YearlySubjectChief;
use App\Notifications\RealTimeNotification;
use App\Services\SubjectsServices\SubjectDetailsCacheService;
use App\Traits\InvalidatesDashboardCounters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Subject extends Model
{
    use SoftDeletes, InvalidatesDashboardCounters;

    protected $table = 'subjects';

    protected $connection = 'tenant';

    protected $fillable = [
        'uuid',
        'slug',
        'level',
        'name',
        'code',
        'type',
        'description',
        'is_active',
        'is_module',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_module' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            //
        });

        static::created(function ($model) {
            //
        });

        static::deleted(function ($model) {
            app(SubjectDetailsCacheService::class)->forget($model->id);
        });

        static::restored(function ($model) {
            app(SubjectDetailsCacheService::class)->forget($model->id);
        });

        static::deleting(function ($model) {
            $director = User::first();

            if (! $model->ensureThatSubjectDoesntJoinedToTeachersInClasses()) {
                $message = "La matière " . $model->name . " est enseignée dans au moins une classe. Pour supprimer cette matière, vous devez d'abord la retirer de toutes classes concernées!";

                if ($director) {
                    $director->notify(new RealTimeNotification(
                        userEmail: $director?->email,
                        tenantId: $director->tenant_id,
                        title:             "Vous ne pouvez pas supprimer cette matière!",
                        message:           $message,
                        type:              'error',
                    ));
                }

                throw new ModelCouldNotBeDeleteBecauseHasActivesAssignmentsException(
                    $message
                );
            }
        });

        static::forceDeleting(function ($model) {
            $director = User::first();

            if (! $model->ensureThatSubjectDoesntJoinedToTeachersInClasses()) {
                $message = "La matière " . $model->name . " est enseignée dans au moins une classe. Pour supprimer cette matière, vous devez d'abord la retirer de toutes classes concernées!";

                if ($director) {
                    $director->notify(new RealTimeNotification(
                        userEmail: $director?->email,
                        tenantId: $director->tenant_id,
                        title:             "Vous ne pouvez pas supprimer cette matière!",
                        message:           $message,
                        type:              'error',
                    ));
                }

                throw new ModelCouldNotBeDeleteBecauseHasActivesAssignmentsException(
                    $message
                );
            }
        });
    }

    // ─── Relations ────────────────────────────────────────────────────

    public function classeSubjects(): HasMany
    {
        return $this->hasMany(ClasseSubjectOfSchoolYear::class, 'subject_id');
    }

    public function coefs(): HasMany
    {
        return $this->hasMany(YearlyPromotionSpecialitySubjectCoef::class, 'subject_id');
    }

    public function coefiscients(): HasMany
    {
        return $this->hasMany(YearlyPromotionSpecialitySubjectCoef::class, 'subject_id');
    }

    public function subjectChiefs(): HasMany
    {
        return $this->hasMany(YearlySubjectChief::class, 'subject_id');
    }

    public function marks(): HasMany
    {
        return $this->hasMany(Mark::class, 'subject_id');
    }

    public function presences(): HasMany
    {
        return $this->hasMany(Presence::class, 'subject_id');
    }

    /**
     * Compétences liées à ce module (via module_skills).
     * Uniquement pertinent si is_module = true.
     */
    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(
            Skills::class,
            'module_skills',
            'subject_id',
            'skill_id'
        )
            ->withPivot(['id', 'code', 'weight', 'coef', 'is_active'])
            ->withTimestamps();
    }

    public function moduleSkills(): HasMany
    {
        return $this->hasMany(ModuleSkills::class, 'subject_id');
    }

    // ─── Scopes ───────────────────────────────────────────────────────

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeModule(Builder $query): Builder
    {
        return $query->where('is_module', true);
    }

    public function scopeConduiteless(Builder $query): Builder
    {
        return $query->where('name', '<>', 'conduite')
                     ->where('name', '<>', 'Conduite')
                     ->where('code', '<>', 'conduite')
                     ->where('code', '<>', 'cond');
    } 
    public function scopeConduite(Builder $query): Builder
    {
        return $query->where('name', 'conduite')
                     ->orWhere('name', 'Conduite')
                     ->orWhere('code', 'conduite')
                     ->orWhere('code', 'cond');
    }

    public function scopeByLevel(Builder $query, string $level): Builder
    {
        return $query->where('level', $level);
    }

    // ─── Helpers ──────────────────────────────────────────────────────

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function isModule(): bool
    {
        return (bool) $this->is_module;
    }

    public function ensureThatSubjectDoesntJoinedToTeachersInClasses(?int $school_year_id = null): bool
    {
        if (! $school_year_id) {
            $school_year_id = SchoolYear::current()?->first()?->id;
        }

        $exists = ClasseSubjectOfSchoolYear::where('subject_id', $this->id)
            ->where('school_year_id', $school_year_id)
            ->where('is_active', true)
            ->whereNull('ended_at')
            ->exists();

        return $exists === false;
    }

    public function getSubjectTeachersOfSchoolYear(?int $school_year_id = null, ?int $classe_id = null, ?int $filiar_id = null, ?int $promotion_id = null, ?string $gender = null): ?Builder
    {
        if (! $school_year_id) {
            $school_year_id = SchoolYear::current()?->first()?->id;
        }

        return Teacher::query()
            ->select('teachers.*')
            ->join('users', 'users.id', '=', 'teachers.user_id')
            ->with(['user'])
            ->whereNotNull('affiliated_at')
            ->whereHas('yearlySubjects', fn ($q) =>
                $q->where('school_year_id', $school_year_id)
                    ->where('subject_id', $this->id)
                    ->where('is_active', true)
            )
            ->when($gender, fn ($q) => $q->whereIn('users.gender', [$gender, Str::lower($gender), Str::upper($gender)]));
    }

    public function currentPrincipalAE(?int $school_year_id = null)
    {
        if (! $school_year_id) {
            $school_year_id = SchoolYear::current()?->first()?->id;
        }

        return Teacher::query()
            ->select('teachers.*')
            ->join('users', 'users.id', '=', 'teachers.user_id')
            ->with(['user'])
            ->whereNotNull('affiliated_at')
            ->whereHas('subjectsChiefs', fn ($q) =>
                $q->where('school_year_id', $school_year_id)
                    ->where('subject_id', $this->id)
                    ->where('is_active', true)
                    ->where('is_master', true)
            )->with('subjectsChiefs')->first();
    }

    public function currentAdjointAE(?int $school_year_id = null)
    {
        if (! $school_year_id) {
            $school_year_id = SchoolYear::current()?->first()?->id;
        }

        return Teacher::query()
            ->select('teachers.*')
            ->join('users', 'users.id', '=', 'teachers.user_id')
            ->with(['user'])
            ->whereNotNull('affiliated_at')
            ->whereHas('subjectsChiefs', fn ($q) =>
                $q->where('school_year_id', $school_year_id)
                    ->where('subject_id', $this->id)
                    ->where('is_active', true)
                    ->where('is_master', false)
            )->with('subjectsChiefs')->first();
    }

    public function principalAE(): HasOneThrough
    {
        $schoolYearId = SchoolYear::current()?->first()?->id;

        return $this->hasOneThrough(
            Teacher::class,
            YearlySubjectChief::class,
            'subject_id',
            'id',
            'id',
            'teacher_id'
        )
            ->join('users', 'users.id', '=', 'teachers.user_id')
            ->addSelect('teachers.*')
            ->with('user')
            ->whereNotNull('teachers.affiliated_at')
            ->where('yearly_subject_chiefs.school_year_id', $schoolYearId)
            ->where('yearly_subject_chiefs.is_active', true)
            ->where('yearly_subject_chiefs.is_master', true);
    }

    public function adjointAE(): HasOneThrough
    {
        $schoolYearId = SchoolYear::current()?->first()?->id;

        return $this->hasOneThrough(
            Teacher::class,
            YearlySubjectChief::class,
            'subject_id',
            'id',
            'id',
            'teacher_id'
        )
            ->join('users', 'users.id', '=', 'teachers.user_id')
            ->addSelect('teachers.*')
            ->with('user')
            ->whereNotNull('teachers.affiliated_at')
            ->where('yearly_subject_chiefs.school_year_id', $schoolYearId)
            ->where('yearly_subject_chiefs.is_active', true)
            ->where('yearly_subject_chiefs.is_master', false);
    }

    public function currentChiefs(): HasManyThrough
    {
        $schoolYearId = SchoolYear::current()?->first()?->id;

        return $this->hasManyThrough(
            Teacher::class,
            YearlySubjectChief::class,
            'subject_id',
            'id',
            'id',
            'teacher_id'
        )
            ->join('users', 'users.id', '=', 'teachers.user_id')
            ->addSelect('teachers.*', 'yearly_subject_chiefs.is_master as chief_is_master')
            ->with('user')
            ->whereNotNull('teachers.affiliated_at')
            ->where('yearly_subject_chiefs.school_year_id', $schoolYearId)
            ->where('yearly_subject_chiefs.is_active', true)
            ->orderByDesc('yearly_subject_chiefs.is_master');
    }

    public function getSubjectTeachersOfSchoolYearCount(?int $school_year_id = null, ?int $classe_id = null, ?int $filiar_id = null, ?int $promotion_id = null, ?string $gender = null): int
    {
        if (! $school_year_id) {
            $school_year_id = SchoolYear::current()?->first()?->id;
        }

        return $this->getSubjectTeachersOfSchoolYear($school_year_id, $classe_id, $filiar_id, $promotion_id, $gender)->count();
    }

    public function getSubjectClassesOfSchoolYear(?int $school_year_id = null, ?int $filiar_id = null, ?int $promotion_id = null): ?Builder
    {
        if (! $school_year_id) {
            $school_year_id = SchoolYear::current()?->first()?->id;
        }

        return Classe::where('is_active', true)->where('school_year_id', $school_year_id)
            ->whereHas('classeSubjects', fn ($q) =>
                $q->where('school_year_id', $school_year_id)
                    ->where('subject_id', $this->id)
                    ->where('is_active', true)
            )
            ->when($filiar_id, fn ($q) => $q->where('filiar_id', $filiar_id))
            ->when($promotion_id, fn ($q) => $q->where('promotion_id', $promotion_id));
    }

    public function getSubjectClassesOfSchoolYearCount(?int $school_year_id = null, ?int $filiar_id = null, ?int $promotion_id = null): int
    {
        if (! $school_year_id) {
            $school_year_id = SchoolYear::current()?->first()?->id;
        }

        return $this->getSubjectClassesOfSchoolYear($school_year_id, $filiar_id, $promotion_id)->count();
    }

    public function isConduite(): bool
    {
        return str()->lower($this->name) === 'conduite' || str()->lower($this->code) === 'cond';
    }

    public function ap_times()
    {
        return $this->hasMany(SubjectAnimationTime::class);
    }

    public function ap_time(): ?HasOneThrough
    {
        $schoolYear = SchoolYear::current()?->first();

        if (! $schoolYear) {
            return null;
        }

        return $this->hasOneThrough(
            Subject::class,
            SubjectAnimationTime::class,
        )
            ->where('subject_animation_times.school_year_id', $schoolYear->id)
            ->where('subject_animation_times.is_active', true);
    }
}
