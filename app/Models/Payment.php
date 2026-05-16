<?php

namespace App\Models;

use App\Models\Classe;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\User;
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
        'amount'          => 'decimal:2',
        'amount_payed'    => 'decimal:2',
        'remained_amount' => 'decimal:2',
        'payment_date'    => 'datetime',
    ];

    /**
     * Get the student this payment belongs to.
     *
     * @return BelongsTo
     */
    public function student(): BelongsTo { return $this->belongsTo(Student::class, 'student_id'); }

    /**
     * Get the class this payment belongs to.
     *
     * @return BelongsTo
     */
    public function classe(): BelongsTo { return $this->belongsTo(Classe::class, 'classe_id'); }

    /**
     * Get the school year this payment belongs to.
     *
     * @return BelongsTo
     */
    public function schoolYear(): BelongsTo { return $this->belongsTo(SchoolYear::class, 'school_year_id'); }

    /**
     * Get the tutor who made this payment.
     *
     * @return BelongsTo
     */
    public function tutor(): BelongsTo { return $this->belongsTo(Tutor::class, 'tutor_id'); }

    /**
     * Get the user who registered this payment.
     *
     * @return BelongsTo
     */
    public function registredBy(): BelongsTo { return $this->belongsTo(User::class, 'registred_by'); }

    /**
     * Scope to filter payments by school year.
     *
     * @param Builder $query
     * @param int $schoolYearId
     * @return Builder
     */
    public function scopeByYear(Builder $query, int $schoolYearId): Builder { return $query->where('school_year_id', $schoolYearId); }

    /**
     * Scope to get only complete payments.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeComplete(Builder $query): Builder { return $query->where('status', 'complet'); }

    /**
     * Scope to get only pending payments.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePending(Builder $query): Builder { return $query->where('status', 'en_attente'); }

    /**
     * Check if this payment is complete.
     *
     * @return bool
     */
    public function isComplete(): bool { return $this->status === 'complet'; }
}
