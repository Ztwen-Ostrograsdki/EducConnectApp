<?php

namespace App\Models;

use App\Exceptions\ModelCouldNotBeDeleteBecauseHasActivesAssignmentsException;
use App\Models\ClasseSubjectOfSchoolYear;
use App\Models\Mark;
use App\Models\Presence;
use App\Models\SchoolYear;
use App\Models\Teacher;
use App\Models\User;
use App\Models\YearlySubjectChief;
use App\Notifications\RealTimeNotification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;


class Subject extends Model
{
    use SoftDeletes;

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
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            
            
        });

        static::created(function ($model) {
            
        });


        static::deleting(function ($model) {

            $director = User::first();

            if(!$model->ensureThatSubjectDoesntJoinedToTeachersInClasses()){

                $message = "La matière " . $model->name . " est enseignée dans au moins une classe. Pour supprimer cette matière, vous devez d'abord la retirer de toutes classes concernées!";

                if($director){

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

            if(!$model->ensureThatSubjectDoesntJoinedToTeachersInClasses()){

                $message = "La matière " . $model->name . " est enseignée dans au moins une classe. Pour supprimer cette matière, vous devez d'abord la retirer de toutes classes concernées!";

                if($director){

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

    /**
     * Get all classe-subject assignments for this subject.
     */
    public function classeSubjects(): HasMany
    {
        return $this->hasMany(ClasseSubjectOfSchoolYear::class, 'subject_id');
    }

     /**
     * Get all yearly subjects where for this subject has chief (AE).
     */
    public function subjectChiefs(): HasMany
    {
        return $this->hasMany(YearlySubjectChief::class, 'subject_id');
    }


    /**
     * Get all marks for this subject.
     */
    public function marks(): HasMany
    {
        return $this->hasMany(Mark::class, 'subject_id');
    }

    /**
     * Get all presences for this subject.
     */
    public function presences(): HasMany
    {
        return $this->hasMany(Presence::class, 'subject_id');
    }

    // ─── Scopes ───────────────────────────────────────────────────────

    /**
     * Scope to get only active subjects.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter subjects by level.
     */
    public function scopeByLevel(Builder $query, string $level): Builder
    {
        return $query->where('level', $level);
    }

    // ─── Helpers ──────────────────────────────────────────────────────

    /**
     * Check if the subject is active.
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function ensureThatSubjectDoesntJoinedToTeachersInClasses(?int $school_year_id = null) : bool
    {
        if(!$school_year_id) $school_year_id = SchoolYear::current()?->first()?->id;

        $exists = ClasseSubjectOfSchoolYear::where('subject_id', $this->id)->where('school_year_id', $school_year_id)->where('is_active', true)->whereNull('ended_at')->exists();

        return $exists === false;

    }

    public function getSubjectTeachersOfSchoolYear(?int $school_year_id = null, ?int $classe_id = null, ?int $filiar_id = null, ?int $promotion_id = null, ?string $gender = null) : ?Builder
    {
        if(!$school_year_id) $school_year_id = SchoolYear::current()?->first()?->id;

        return  Teacher::query()
                        ->select('teachers.*')
                        ->join('users', 'users.id', '=', 'teachers.user_id')
                        ->with(['user'])
                        ->whereNotNull('affiliated_at')
                        ->whereHas('yearlySubjects', fn($q) => 
                            $q->where('school_year_id', $school_year_id)
                                ->where('subject_id', $this->id)
                                ->where('is_active', true)
                        )
                        ->when($gender, fn($q) => $q->whereIn('users.gender', [$gender, Str::lower($gender), Str::upper($gender)]));
    }


    public function getSubjectTeachersOfSchoolYearCount(?int $school_year_id = null, ?int $classe_id = null, ?int $filiar_id = null, ?int $promotion_id = null, ?string $gender = null) : int
    {
        if(!$school_year_id) $school_year_id = SchoolYear::current()?->first()?->id;

        return  $this->getSubjectTeachersOfSchoolYear($school_year_id, $classe_id, $filiar_id, $promotion_id, $gender)->count();
    }


    public function getSubjectClassesOfSchoolYear(?int $school_year_id = null, ?int $filiar_id = null, ?int $promotion_id = null, ?string $gender = null) : ?Builder
    {
        if(!$school_year_id) $school_year_id = SchoolYear::current()?->first()?->id;

        return  Teacher::query()
                        ->select('teachers.*')
                        ->join('users', 'users.id', '=', 'teachers.user_id')
                        ->with(['user'])
                        ->whereNotNull('affiliated_at')
                        ->whereHas('yearlySubjects', fn($q) => 
                            $q->where('school_year_id', $school_year_id)
                                ->where('subject_id', $this->id)
                                ->where('is_active', true)
                        )
                        ->when($gender, fn($q) => $q->whereIn('users.gender', [$gender, Str::lower($gender), Str::upper($gender)]));
    }


    public function getSubjectClassesOfSchoolYearCount(?int $school_year_id = null, ?int $filiar_id = null, ?int $promotion_id = null, ?string $gender = null) : int
    {
        if(!$school_year_id) $school_year_id = SchoolYear::current()?->first()?->id;

        return  $this->getSubjectTeachersOfSchoolYear($school_year_id, $filiar_id, $promotion_id, $gender)->count();
    }



}
