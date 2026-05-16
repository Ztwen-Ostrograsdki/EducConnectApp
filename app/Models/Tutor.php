<?php

namespace App\Models;

use App\Models\Payment;
use App\Models\Student;
use App\Models\TutorYearlyAccess;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tutor extends Model
{
    use SoftDeletes;

    protected $table = 'tutors';

    protected $fillable = [
        'uuid',
        'qr_code',
        'user_id',
        'name',
        'prenames',
        'email',
        'contacts',
        'whatsapp_number',
        'gender',
        'profession',
        'adresse',
        'status',
        'blocked',
        'blocked_reasons',
        'account_created_at',
    ];

    protected $casts = [
        'blocked'            => 'boolean',
        'account_created_at' => 'datetime',
    ];

    // ─── Relations ────────────────────────────────────────────────────

    /**
     * Get the user account associated with this tutor.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all students linked to this tutor.
     *
     * @return BelongsToMany
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_tutor_relations', 'tutor_id', 'student_id')
                    ->withPivot(['parent_relation', 'is_primary_contact', 'is_active', 'locked'])
                    ->withTimestamps();
    }

    /**
     * Get only active students linked to this tutor.
     *
     * @return BelongsToMany
     */
    public function activeStudents(): BelongsToMany
    {
        return $this->students()->wherePivot('is_active', true);
    }

    /**
     * Get all yearly accesses for this tutor.
     *
     * @return HasMany
     */
    public function yearlyAccesses(): HasMany
    {
        return $this->hasMany(TutorYearlyAccess::class, 'tutor_id');
    }

    /**
     * Get yearly accesses for a specific student and school year.
     *
     * @param int $studentId
     * @param int $schoolYearId
     * @return HasMany
     */
    public function accessForStudentAndYear(int $studentId, int $schoolYearId): HasMany
    {
        return $this->yearlyAccesses()
                    ->where('student_id', $studentId)
                    ->where('school_year_id', $schoolYearId);
    }

    /**
     * Get all payments recorded by this tutor.
     *
     * @return HasMany
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'tutor_id');
    }

    // ─── Scopes ───────────────────────────────────────────────────────

    /**
     * Scope to get only active and non-blocked tutors.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active')->where('blocked', false);
    }

    /**
     * Scope to get only blocked tutors.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeBlocked(Builder $query): Builder
    {
        return $query->where('blocked', true);
    }

    // ─── Helpers ──────────────────────────────────────────────────────

    /**
     * Get the full name of the tutor.
     *
     * @return string
     */
    public function fullName(): string
    {
        return "{$this->name} {$this->prenames}";
    }

    /**
     * Check if the tutor is active and not blocked.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && !$this->blocked;
    }

    /**
     * Check if the tutor is blocked.
     *
     * @return bool
     */
    public function isBlocked(): bool
    {
        return $this->blocked;
    }

    /**
     * Check if the tutor has a valid access for a specific student and school year.
     *
     * @param int $studentId
     * @param int $schoolYearId
     * @return bool
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
     *
     * @param int $studentId
     * @return bool
     */
    public function hasStudent(int $studentId): bool
    {
        return $this->students()
                    ->where('students.id', $studentId)
                    ->exists();
    }

    /**
     * Check if the tutor can renew their token for a specific student and school year.
     *
     * @param int $studentId
     * @param int $schoolYearId
     * @return bool
     */
    public function canRenewTokenForYear(int $studentId, int $schoolYearId): bool
    {
        if (!$this->isActive()) return false;
        if (!$this->hasStudent($studentId)) return false;

        $access = $this->yearlyAccesses()
                       ->where('student_id', $studentId)
                       ->where('school_year_id', $schoolYearId)
                       ->first();

        if (!$access) return false;

        return !$access->isSuspended() && !$access->isBlocked();
    }
}