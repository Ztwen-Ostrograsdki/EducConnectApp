<?php

namespace App\Models;

use App\Exceptions\ModelCouldNotBeDeleteBecauseHasActivesAssignmentsException;
use App\Models\Student;
use App\Notifications\RealTimeNotification;
use App\Services\ClassesServices\ClasseEffectifsService;
use App\Traits\InvalidatesDashboardCounters;
use Countable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;


class Classe extends Model
{
    use InvalidatesDashboardCounters, SoftDeletes;

    protected $table = 'classes';

    protected $connection = 'tenant';

    protected $fillable = [
        'uuid',
        'school_year_id',
        'promotion_id',
        'filiar_id',
        'serial_id',
        'name',
        'code',
        'slug',
        'localization',
        'level',
        'effectif_max',
        'principal_id',
        'respo_1_id',
        'respo_2_id',
        'is_active',
        'is_locked',
        'locked_for_teachers',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_locked' => 'boolean',
        'locked_for_teachers' => 'array',
        'effectif_max' => 'integer',
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

            if(!$model->ensureThatClasseDoesntHaveActivesTeachersOrStudentsThisSchoolYear()){

                $message = "La classe de " . $model->name . " comporte cette année au moins un enseignant ou un élève. Pour supprimer cette classe, vous devez d'abord lui retirer toutes ses élèves et enseignants !";

                if($director){

                    $director->notify(new RealTimeNotification(
                        userEmail: $director?->email,
                        tenantId: $director->tenant_id,
                        title:             "Vous ne pouvez pas supprimer cette classe!",
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

            if(!$model->ensureThatClasseDoesntHaveActivesTeachersOrStudentsThisSchoolYear()){

                $message = "La classe de " . $model->name . " comporte cette année au moins un enseignant ou un élève. Pour supprimer cette classe, vous devez d'abord lui retirer toutes ses élèves et enseignants !";

                if($director){

                    $director->notify(new RealTimeNotification(
                        userEmail: $director?->email,
                        tenantId: $director->tenant_id,
                        title:             "Vous ne pouvez pas supprimer cette classe!",
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

    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class, 'school_year_id');
    }
    
    public function school_year(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class, 'school_year_id');
    }

    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class, 'promotion_id')->withTrashed();
    }

    public function filiar(): BelongsTo
    {
        return $this->belongsTo(Filiar::class, 'filiar_id')->withTrashed();
    }

    public function serial(): BelongsTo
    {
        return $this->belongsTo(Serial::class, 'serial_id')->withTrashed();
    }

    // Professeur principal
    public function principal(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'principal_id');
    }

    // Responsables (élèves)
    public function respo1(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'respo_1_id');
    }

    public function respo2(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'respo_2_id');
    }


    public function responsables() : array
    {
        return [
            'respo 1' => $this->respo1,
            'respo 2' => $this->respo2,
        ];
    }

    public function classeSubjects(): HasMany
    {
        return $this->hasMany(ClasseSubjectOfSchoolYear::class, 'classe_id');
    }


    // Enseignants intervenant dans cette classe
    public function teachers(): HasMany
    {
        return $this->hasMany(ClasseSubjectOfSchoolYear::class, 'classe_id');
    }

    public function activesTeachers()
    {
        return $this->teachers()->where('classe_subject_of_school_years.is_active', true)->whereNull('classe_subject_of_school_years.ended_at')->get();
    }



    // Élèves inscrits dans cette classe
    public function students(): HasMany
    {
        return $this->hasMany(YearlyClasseStudent::class, 'classe_id');
    }

    // Élèves actifs uniquement
    public function activesStudents(): HasMany
    {
        return $this->students()->where('is_active', true);
    }

    // Matières de la classe (via pivot)
    public function subjects(): HasMany
    {
        return $this->hasMany(ClasseSubjectOfSchoolYear::class, 'classe_id');
    }

    // Matières actives (enseignant actuel, pas de remplacement en cours)
    public function activesSubjects(): HasMany
    {
        return $this->subjects()
            ->whereNull('ended_at')
            ->where('is_active', true);
    }

    // Notes de la classe
    public function marks(): HasMany
    {
        return $this->hasMany(Mark::class, 'classe_id');
    }

    // Présences de la classe
    public function presences(): HasMany
    {
        return $this->hasMany(Presence::class, 'classe_id');
    }

    // Paiements de la classe
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'classe_id');
    }

    // Enseignant actuel d'une matière dans une classe
    public function getCurrentTeacherOfSubject(int $subjectId, int $yearId)
    {
        return ClasseSubjectOfSchoolYear::where('classe_id', $this->id)
            ->where('subject_id', $subjectId)
            ->where('school_year_id', $yearId)
            ->whereNull('ended_at')
            ->first();
    }


    public function recentStudentsMigratedsIntoClasse(int $weeks = 1)
    {
        $school_year_id = $this->school_year_id;

        return Student::whereHas('yearlyClasseStudents', fn($q) =>
                            $q->where('school_year_id', $school_year_id)
                            ->where('classe_id', $this->id)
                            ->where('created_at', '>=', now()->subMonths(2))
                        )
                        ->latest('created_at')->take(5)->get();
    }



    public function ensureThatClasseDoesntHaveActivesTeachersOrStudentsThisSchoolYear(?int $school_year_id = null) : bool
    {
        if(!$school_year_id) $school_year_id = SchoolYear::current()?->first()?->id;

        $hasTeachers = ClasseSubjectOfSchoolYear::where('classe_id', $this->id)
                                           ->where('school_year_id', $school_year_id)
                                           ->where('is_active', true)
                                           ->whereNull('ended_at')->exists();

        $hasStudents = Student::whereHas('yearlyClasseStudents', fn($q) =>
                                $q->where('school_year_id', $school_year_id)
                                ->where('classe_id', $this->id)
                                ->whereNull('ended_at')
                           )->exists();
        

        return $hasTeachers === false && $hasStudents === false;

    }




    public function getClasseStudentsLeaves(?string $gender = null) : Countable
    {
        $school_year_id = $this->school_year_id;

        return Student::whereHas('yearlyClasseStudents', fn($q) =>
            $q->where('classe_id', $this->id)
              ->where('school_year_id', $school_year_id)
              ->where('is_active', true)
        )
        ->when($gender, fn($q) => $q->whereIn('gender', [$gender, Str::lower($gender), Str::upper($gender)]))
        ->whereHas('yearlyStudentsLeaves', fn($req) => 
            $req->where('school_year_id', $school_year_id)
                ->orWhere('classe_id', $this->id)
                ->whereNull('ended_at')
        )
        ->with(['yearlyStudentsLeaves'])
        ->orderBy('name')
        ->orderBy('prenames')
        ->get();
    }


    public function getStudentsByGender(string $gender = 'M')
    {
        $schoolYearId = $this->school_year_id;

        $genderCode = strtoupper(substr(trim($gender), 0, 1));

        return Student::query()
                ->whereHas('yearlyClasseStudents', fn ($q) =>
                    $q->where('classe_id', $this->id)
                    ->where('school_year_id', $schoolYearId)
                    ->where('is_active', true)
                    ->whereNull('ended_at')
                )
                ->whereRaw('UPPER(LEFT(gender, 1)) = ?', [$genderCode])
                ->orderBy('name')
                ->orderBy('prenames');
        

    }


    public function teachersCount(): int
    {
        return app(ClasseEffectifsService::class)
            ->countActiveTeachers($this->id, $this->school_year_id);
    }

    public function effectif(): int
    {
        return app(ClasseEffectifsService::class)
            ->countActiveStudents($this->id, $this->school_year_id);
    }

    public function getStudentsCountByGender(): array
    {
        return app(ClasseEffectifsService::class)
            ->countStudentsByGender($this->id, $this->school_year_id);
    }

    public function getClasseStudentsLeavesCount(): int
    {
        return app(ClasseEffectifsService::class)
            ->countAbandons($this->id, $this->school_year_id);
    }



    /**
     * Historique complet des enseignants d'une matière
     */
    public function getSubjectTeachersHistories(int $subjectId, int $yearId)
    {
        return ClasseSubjectOfSchoolYear::where('classe_id', $this->id)
            ->where('subject_id', $subjectId)
            ->where('school_year_id', $yearId)
            ->orderBy('started_at')
            ->get();

    }

    // Tous les remplacements de l'année
    public function getSubjectReplacements(int $subjectId, int $yearId)
    {
        return ClasseSubjectOfSchoolYear::where('classe_id', $this->id)
            ->where('subject_id', $subjectId)
            ->where('school_year_id', $yearId)
            ->whereNotNull('ended_at')
            ->orderBy('ended_at', 'desc')
            ->get();
    }

    // ─── Scopes ───────────────────────────────────────────────────────

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeByYear(Builder $query, int $schoolYearId): Builder
    {
        return $query->where('school_year_id', $schoolYearId);
    }

    public function scopeByPromotion(Builder $query, int $promotionId): Builder
    {
        return $query->where('promotion_id', $promotionId);
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

    public function isLocked(): bool
    {
        return $this->is_locked;
    }

    // Vérifie si un enseignant est bloqué pour cette classe
    public function isTeacherLocked(int $teacherId): bool
    {
        $locked = $this->locked_for_teachers ?? [];

        return in_array($teacherId, $locked);
    }

    public function isFull(): bool
    {
        return $this->effectif() >= $this->effectif_max;
    }

    public function specialityModel()
    {
        if($this->filiar_id){

            return Filiar::withTrashed()->whereId($this->filiar_id)->first();

        }
        elseif($this->serial_id){

            return Serial::withTrashed()->whereId($this->serial_id)->first();
        }

        return null;
    }

    public function speciality()
    {
        if($this->filiar_id){

            return Filiar::withTrashed()->whereId($this->filiar_id)->first()?->name;

        }
        elseif($this->serial_id){

            return Serial::withTrashed()->whereId($this->serial_id)->first()?->name;
        }

        return $this->promotion->name;
    }
}
