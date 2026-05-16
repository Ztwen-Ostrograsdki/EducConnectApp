<?php

namespace App\Models;

use App\Models\Classe;
use App\Models\ClasseSubjectOfSchoolYear;
use App\Models\Mark;
use App\Models\Presence;
use App\Models\TeacherYearlyAccess;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use SoftDeletes;

    protected $table = 'teachers';

    protected $fillable = [
        'uuid',
        'qr_code',
        'user_id',
        'identifiant',
        'identity_card_details',
        'name',
        'prenames',
        'email',
        'contacts',
        'gender',
        'birth_date',
        'birth_place',
        'nationality',
        'address',
        'photo',
        'specialties',
        'diploma',
        'blocked',
        'blocked_reasons',
        'status',
        'affiliated_at',
    ];

    protected $casts = [
        'birth_date'            => 'date',
        'affiliated_at'         => 'datetime',
        'blocked'               => 'boolean',
        'identity_card_details' => 'array',
        'contacts'              => 'array',
        'specialties'           => 'array',
        'diploma'               => 'array',
    ];

    protected $hidden = [
        'identity_card_details',
    ];

    // ─── Relations ────────────────────────────────────────────────────

    /**
     * Get the user account associated with this teacher.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all yearly accesses for this teacher.
     *
     * @return HasMany
     */
    public function yearlyAccesses(): HasMany
    {
        return $this->hasMany(TeacherYearlyAccess::class, 'teacher_id');
    }

    /**
     * Get the yearly access for a specific school year.
     *
     * @param int $schoolYearId
     * @return HasMany
     */
    public function accessForYear(int $schoolYearId): HasMany
    {
        return $this->yearlyAccesses()->where('school_year_id', $schoolYearId);
    }

    /**
     * Get all classes where this teacher is the principal.
     *
     * @return HasMany
     */
    public function principalClasses(): HasMany
    {
        return $this->hasMany(Classe::class, 'principal_id');
    }

    /**
     * Get all classe-subject assignments for this teacher.
     *
     * @return HasMany
     */
    public function classeSubjects(): HasMany
    {
        return $this->hasMany(ClasseSubjectOfSchoolYear::class, 'teacher_id');
    }

    /**
     * Get active classe-subject assignments for a specific school year.
     *
     * @param int $schoolYearId
     * @return HasMany
     */
    public function activeClasseSubjectsByYear(int $schoolYearId): HasMany
    {
        return $this->classeSubjects()
                    ->where('school_year_id', $schoolYearId)
                    ->whereNull('ended_at');
    }

    /**
     * Get all marks entered by this teacher.
     *
     * @return HasMany
     */
    public function marks(): HasMany
    {
        return $this->hasMany(Mark::class, 'teacher_id');
    }

    /**
     * Get all presences recorded by this teacher.
     *
     * @return HasMany
     */
    public function presences(): HasMany
    {
        return $this->hasMany(Presence::class, 'teacher_id');
    }

    // ─── Scopes ───────────────────────────────────────────────────────

    /**
     * Scope to get only active teachers.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get only blocked teachers.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeBlocked(Builder $query): Builder
    {
        return $query->where('blocked', true);
    }

    /**
     * Scope to get only non-blocked teachers.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeNotBlocked(Builder $query): Builder
    {
        return $query->where('blocked', false);
    }

    // ─── Helpers ──────────────────────────────────────────────────────

    /**
     * Check if the teacher is active and not blocked.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && !$this->blocked;
    }

    /**
     * Check if the teacher is blocked.
     *
     * @return bool
     */
    public function isBlocked(): bool
    {
        return $this->blocked;
    }

    /**
     * Get the full name of the teacher.
     *
     * @return string
     */
    public function fullName(): string
    {
        return "{$this->name} {$this->prenames}";
    }

    /**
     * Check if the teacher has a valid access for a given school year.
     *
     * @param int $schoolYearId
     * @return bool
     */
    public function hasValidAccessForYear(int $schoolYearId): bool
    {
        return $this->yearlyAccesses()
                    ->where('school_year_id', $schoolYearId)
                    ->where('status', 'active')
                    ->exists();
    }

    /**
     * Check if the teacher can renew their token for a given school year.
     *
     * @param int $schoolYearId
     * @return bool
     */
    public function canRenewTokenForYear(int $schoolYearId): bool
    {
        $access = $this->yearlyAccesses()
                       ->where('school_year_id', $schoolYearId)
                       ->first();

        if (!$access) return false;

        return !$access->isSuspended() && $this->isActive();
    }
}