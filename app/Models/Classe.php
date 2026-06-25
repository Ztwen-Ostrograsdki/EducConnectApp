<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


class Classe extends Model
{
    use SoftDeletes;

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
        return $this->belongsTo(Promotion::class, 'promotion_id');
    }

    public function filiar(): BelongsTo
    {
        return $this->belongsTo(Filiar::class, 'filiar_id');
    }

    public function serial(): BelongsTo
    {
        return $this->belongsTo(Serial::class, 'serial_id');
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

    // Enseignants intervenant dans cette classe
    public function teachers(): HasMany
    {
        return $this->hasMany(ClasseSubjectOfSchoolYear::class, 'classe_id');
    }


    // Total enseignants intervenant dans cette classe
    public function teachersCount(): int
    {
        return $this->teachers?->count();
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
                        ->latest('created_at')->take(10)->get();
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

    // Nombre d'élèves actifs
    public function effectif(): int
    {
        return $this->activesStudents()->count();
    }

    // Vérifie si la classe est pleine
    public function isFull(): bool
    {
        return $this->effectif() >= $this->effectif_max;
    }

    public function speciality()
    {
        if($this->filiar){

            return $this->filiar->name;

        }
        elseif($this->serial){

            return $this->serial->name;
        }

        return $this->promotion->name;
    }
}
