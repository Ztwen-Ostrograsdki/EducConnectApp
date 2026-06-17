<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;


class Payment extends Model
{
    use SoftDeletes;

    protected $table = 'payments';

    protected $fillable = [
        'uuid', 'qr_code', 'student_id', 'classe_id', 'school_year_id',
        'tutor_id', 'reference', 'type', 'amount', 'amount_payed',
        'remained_amount', 'status', 'payment_mode', 'payment_date',
        'observations', 'registred_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'amount_payed' => 'decimal:2',
        'remained_amount' => 'decimal:2',
        'payment_date' => 'datetime',
    ];

    /**
     * Get the student this payment belongs to.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /**
     * Get the class this payment belongs to.
     */
    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class, 'classe_id');
    }

    /**
     * Get the school year this payment belongs to.
     */
    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class, 'school_year_id');
    }

    /**
     * Get the tutor who made this payment.
     */
    public function tutor(): BelongsTo
    {
        return $this->belongsTo(Tutor::class, 'tutor_id');
    }

    /**
     * Get the user who registered this payment.
     */
    public function registredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registred_by');
    }

    /**
     * Scope to filter payments by school year.
     */
    public function scopeByYear(Builder $query, int $schoolYearId): Builder
    {
        return $query->where('school_year_id', $schoolYearId);
    }

    /**
     * Scope to get only complete payments.
     */
    public function scopeComplete(Builder $query): Builder
    {
        return $query->where('status', 'complet');
    }

    /**
     * Scope to get only pending payments.
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'en_attente');
    }

    /**
     * Check if this payment is complete.
     */
    public function isComplete(): bool
    {
        return $this->status === 'complet';
    }
}
