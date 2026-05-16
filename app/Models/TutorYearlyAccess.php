<?php

namespace App\Models;

use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TutorYearlyAccess extends Model
{
    protected $table = 'tutor_yearly_accesses';

    protected $fillable = [
        'tutor_id', 'student_id', 'school_year_id', 'blocked', 'blocked_reasons',
        'token', 'token_expires_at', 'token_requested_at', 'token_attempts',
        'validated_at', 'status', 'suspension_reason', 'suspended_at', 'suspended_by',
    ];

    protected $casts = [
        'blocked'            => 'boolean',
        'token_expires_at'   => 'datetime',
        'token_requested_at' => 'datetime',
        'validated_at'       => 'datetime',
        'suspended_at'       => 'datetime',
        'token_attempts'     => 'integer',
    ];

    /**
     * Get the tutor this access belongs to.
     *
     * @return BelongsTo
     */
    public function tutor(): BelongsTo { return $this->belongsTo(Tutor::class, 'tutor_id'); }

    /**
     * Get the student this access is linked to.
     *
     * @return BelongsTo
     */
    public function student(): BelongsTo { return $this->belongsTo(Student::class, 'student_id'); }

    /**
     * Get the school year this access belongs to.
     *
     * @return BelongsTo
     */
    public function schoolYear(): BelongsTo { return $this->belongsTo(SchoolYear::class, 'school_year_id'); }

    /**
     * Get the director who suspended this access.
     *
     * @return BelongsTo
     */
    public function suspendedBy(): BelongsTo { return $this->belongsTo(User::class, 'suspended_by'); }

    /**
     * Scope to get only active accesses.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder { return $query->where('status', 'active')->where('blocked', false); }

    /**
     * Scope to get only suspended accesses.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeSuspended(Builder $query): Builder { return $query->where('status', 'suspended'); }

    /**
     * Check if this access is active and not blocked.
     *
     * @return bool
     */
    public function isActive(): bool { return $this->status === 'active' && !$this->blocked; }

    /**
     * Check if this access is suspended.
     *
     * @return bool
     */
    public function isSuspended(): bool { return $this->status === 'suspended'; }

    /**
     * Check if this access is blocked.
     *
     * @return bool
     */
    public function isBlocked(): bool { return $this->blocked; }

    /**
     * Check if the token has expired.
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->status === 'expired'
            || ($this->token_expires_at && $this->token_expires_at->isPast());
    }

    /**
     * Check if the tutor can request a new token for this student and school year.
     *
     * @return bool
     */
    public function canRenewToken(): bool
    {
        if ($this->isSuspended() || $this->isBlocked()) return false;

        return $this->student->isEnrolledForYear($this->school_year_id);
    }
}