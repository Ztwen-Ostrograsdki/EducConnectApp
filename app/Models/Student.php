<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;

    protected $table = 'students';

    protected $fillable = [
        'matricule',
        'uuid',
        'qr_code',
        'EducMaster',
        'name',
        'prenames',
        'contacts',
        'gender',
        'birth_date',
        'birth_place',
        'nationality',
        'address',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'contacts' => 'array',
    ];

    // ─── Relations ────────────────────────────────────────────────────

    /**
     * Get all classes this student has been enrolled in (all years).
     */
    public function classes(): HasMany
    {
        return $this->hasMany(YearlyClasseStudent::class, 'student_id');
    }

    /**
     * Get all tutors linked to this student.
     */
    public function tutors(): BelongsToMany
    {
        return $this->belongsToMany(Tutor::class, 'student_tutor_relations', 'student_id', 'tutor_id')
            ->withPivot(['parent_relation', 'is_primary_contact', 'is_active', 'locked'])
            ->withTimestamps();
    }

    /**
     * Get the primary contact tutor for this student.
     */
    public function primaryTutor(): BelongsToMany
    {
        return $this->tutors()->wherePivot('is_primary_contact', true);
    }

    /**
     * Get all active tutors for this student.
     */
    public function activeTutors(): BelongsToMany
    {
        return $this->tutors()->wherePivot('is_active', true);
    }

    /**
     * Get all marks for this student.
     */
    public function marks(): HasMany
    {
        return $this->hasMany(Mark::class, 'student_id');
    }

    /**
     * Get all marks for a specific school year.
     */
    public function marksByYear(int $schoolYearId): HasMany
    {
        return $this->marks()->where('school_year_id', $schoolYearId);
    }

    /**
     * Get all marks for a specific school year and period.
     */
    public function marksByYearAndPeriod(int $schoolYearId, int $period): HasMany
    {
        return $this->marksByYear($schoolYearId)->where('period', $period);
    }

    /**
     * Get all presences for this student.
     */
    public function presences(): HasMany
    {
        return $this->hasMany(Presence::class, 'student_id');
    }

    /**
     * Get all presences for a specific school year.
     */
    public function presencesByYear(int $schoolYearId): HasMany
    {
        return $this->presences()->where('school_year_id', $schoolYearId);
    }

    /**
     * Get all payments for this student.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'student_id');
    }

    /**
     * Get all tutor yearly accesses related to this student.
     */
    public function tutorAccesses(): HasMany
    {
        return $this->hasMany(TutorYearlyAccess::class, 'student_id');
    }

    /**
     * Get all classes where this student is the first class representative.
     */
    public function classeRespo1(): HasMany
    {
        return $this->hasMany(Classe::class, 'respo_1_id');
    }

    /**
     * Get all classes where this student is the second class representative.
     */
    public function classeRespo2(): HasMany
    {
        return $this->hasMany(Classe::class, 'respo_2_id');
    }

    // ─── Scopes ───────────────────────────────────────────────────────

    /**
     * Scope to get students enrolled in a specific school year.
     */
    public function scopeEnrolledInYear(Builder $query, int $schoolYearId): Builder
    {
        return $query->whereHas('classes', function ($q) use ($schoolYearId) {
            $q->wherePivot('school_year_id', $schoolYearId)
                ->wherePivot('status', 'actif');
        });
    }

    /**
     * Scope to get students without an active class for a specific school year.
     */
    public function scopeWithoutClassForYear(Builder $query, int $schoolYearId): Builder
    {
        return $query->whereDoesntHave('classes', function ($q) use ($schoolYearId) {
            $q->wherePivot('school_year_id', $schoolYearId)
                ->wherePivot('status', 'actif');
        });
    }

    // ─── Helpers ──────────────────────────────────────────────────────

    /**
     * Get the full name of the student.
     */
    public function fullName(): string
    {
        return "{$this->name} {$this->prenames}";
    }

    /**
     * Check if the student is enrolled in a specific school year.
     */
    public function isEnrolledForYear(int $schoolYearId): bool
    {
        return $this->classes()
            ->wherePivot('school_year_id', $schoolYearId)
            ->wherePivot('status', 'actif')
            ->exists();
    }

    /**
     * Get the active class for a specific school year.
     */
    public function getClassForYear(int $schoolYearId): ?Classe
    {
        return $this->classes()
            ->wherePivot('school_year_id', $schoolYearId)
            ->wherePivot('status', 'actif')
            ->first();
    }
}
