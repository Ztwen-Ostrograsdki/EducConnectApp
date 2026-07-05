<?php

namespace App\Models;

use App\Traits\InvalidatesDashboardCounters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tutor extends Model
{
    use SoftDeletes, InvalidatesDashboardCounters;

    protected $table = 'tutors';

    protected $connection = 'tenant';

    protected $fillable = [
        'uuid',
        'qr_code',
        'user_id',
        'email',
        'is_active',
        'blocked_reasons',
        'affiliated_at',
        'birth_date',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'affiliated_at' => 'datetime',
    ];

    // ─── Relations ────────────────────────────────────────────────────

    /**
     * Get the user account associated with this tutor.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all students linked to this tutor.
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_tutor_relations', 'tutor_id', 'student_id')
            ->withPivot(['parent_relation', 'is_primary_contact', 'is_active', 'locked'])
            ->withTimestamps();
    }

    /**
     * Get only active students linked to this tutor.
     */
    public function activeStudents(): BelongsToMany
    {
        return $this->students()->where('is_active', true);
    }

    /**
     * Get all yearly accesses for this tutor.
     */
    public function yearlyAccesses(): HasMany
    {
        return $this->hasMany(TutorYearlyAccess::class, 'tutor_id');
    }

    /**
     * Get yearly accesses for a specific student and school year.
     */
    public function accessForStudentAndYear(int $studentId, int $schoolYearId): HasMany
    {
        return $this->yearlyAccesses()
            ->where('student_id', $studentId)
            ->where('school_year_id', $schoolYearId);
    }

    /**
     * Get all payments recorded by this tutor.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'tutor_id');
    }

    // ─── Scopes ───────────────────────────────────────────────────────

    /**
     * Scope to get only active and non-blocked tutors.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }


    // ─── Helpers ──────────────────────────────────────────────────────

    /**
     * Get the full name of the tutor.
     */
    public function fullName(): string
    {
        return "{$this->name} {$this->prenames}";
    }

    /**
     * Check if the tutor is active and not blocked.
     */
    public function isActive(): bool
    {
        return $this->is_active === true;
    }


    /**
     * Check if the tutor has a valid access for a specific student and school year.
     */
    public function hasValidAccessForYear(int $studentId, int $schoolYearId): bool
    {
        return $this->yearlyAccesses()
            ->where('student_id', $studentId)
            ->where('school_year_id', $schoolYearId)
            ->where('status', 'active')
            ->exists();
    }

    /**
     * Check if the tutor is linked to a specific student.
     */
    public function hasStudent(int $studentId): bool
    {
        return $this->students()
            ->where('students.id', $studentId)
            ->exists();
    }

    /**
     * Check if the tutor can renew their token for a specific student and school year.
     */
    public function canRenewTokenForYear(int $studentId, int $schoolYearId): bool
    {
        if (! $this->isActive()) {
            return false;
        }
        if (! $this->hasStudent($studentId)) {
            return false;
        }

        $access = $this->yearlyAccesses()
            ->where('student_id', $studentId)
            ->where('school_year_id', $schoolYearId)
            ->first();

        if (! $access) {
            return false;
        }

        return ! $access->isSuspended() && ! $access->isBlocked();
    }
}
