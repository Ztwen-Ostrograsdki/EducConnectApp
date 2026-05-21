<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherYearlyAccess extends Model
{
    protected $table = 'teacher_yearly_accesses';

    protected $fillable = [
        'teacher_id', 'school_year_id', 'token', 'token_expires_at',
        'token_requested_at', 'token_attempts', 'validated_at',
        'status', 'suspension_reason', 'suspended_at', 'suspended_by',
    ];

    protected $casts = [
        'token_expires_at' => 'datetime',
        'token_requested_at' => 'datetime',
        'validated_at' => 'datetime',
        'suspended_at' => 'datetime',
        'token_attempts' => 'integer',
    ];

    /**
     * Get the teacher this access belongs to.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    /**
     * Get the school year this access belongs to.
     */
    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class, 'school_year_id');
    }

    /**
     * Get the director who suspended this access.
     */
    public function suspendedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'suspended_by');
    }

    /**
     * Scope to get only active accesses.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get only pending accesses.
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get only suspended accesses.
     */
    public function scopeSuspended(Builder $query): Builder
    {
        return $query->where('status', 'suspended');
    }

    /**
     * Check if this access is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if this access is pending validation.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if this access is suspended.
     */
    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    /**
     * Check if the token has expired.
     */
    public function isExpired(): bool
    {
        return $this->status === 'expired'
            || ($this->token_expires_at && $this->token_expires_at->isPast());
    }

    /**
     * Check if the teacher can request a new token.
     */
    public function canRenewToken(): bool
    {
        return ! $this->isSuspended() && $this->teacher->isActive();
    }
}
