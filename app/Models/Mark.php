<?php

namespace App\Models;

use App\Models\Classe;
use App\Models\MarkUpdatedsHistory;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mark extends Model
{
    use SoftDeletes;

    protected $table = 'marks';

    protected $fillable = [
        'uuid', 'student_id', 'classe_id', 'subject_id', 'school_year_id',
        'teacher_id', 'period', 'mark_type', 'value', 'comments',
        'editable', 'validated', 'locked_at', 'locked_by',
    ];

    protected $casts = [
        'value'     => 'decimal:2',
        'editable'  => 'boolean',
        'validated' => 'boolean',
        'locked_at' => 'datetime',
        'period'    => 'integer',
    ];

    /**
     * Get the student this mark belongs to.
     *
     * @return BelongsTo
     */
    public function student(): BelongsTo { return $this->belongsTo(Student::class, 'student_id'); }

    /**
     * Get the class this mark belongs to.
     *
     * @return BelongsTo
     */
    public function classe(): BelongsTo { return $this->belongsTo(Classe::class, 'classe_id'); }

    /**
     * Get the subject this mark belongs to.
     *
     * @return BelongsTo
     */
    public function subject(): BelongsTo { return $this->belongsTo(Subject::class, 'subject_id'); }

    /**
     * Get the school year this mark belongs to.
     *
     * @return BelongsTo
     */
    public function schoolYear(): BelongsTo { return $this->belongsTo(SchoolYear::class, 'school_year_id'); }

    /**
     * Get the teacher who entered this mark.
     *
     * @return BelongsTo
     */
    public function teacher(): BelongsTo { return $this->belongsTo(Teacher::class, 'teacher_id'); }

    /**
     * Get the user who locked this mark.
     *
     * @return BelongsTo
     */
    public function lockedBy(): BelongsTo { return $this->belongsTo(User::class, 'locked_by'); }

    /**
     * Get all edit histories for this mark.
     *
     * @return HasMany
     */
    public function histories(): HasMany { return $this->hasMany(MarkUpdatedsHistory::class, 'mark_id'); }

    /**
     * Scope to get only editable marks.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeEditable(Builder $query): Builder { return $query->where('editable', true); }

    /**
     * Scope to filter marks by period.
     *
     * @param Builder $query
     * @param int $period
     * @return Builder
     */
    public function scopeByPeriod(Builder $query, int $period): Builder { return $query->where('period', $period); }

    /**
     * Scope to filter marks by school year.
     *
     * @param Builder $query
     * @param int $schoolYearId
     * @return Builder
     */
    public function scopeByYear(Builder $query, int $schoolYearId): Builder { return $query->where('school_year_id', $schoolYearId); }

    /**
     * Check if this mark is currently editable.
     *
     * @return bool
     */
    public function isEditable(): bool { return $this->editable && !$this->locked_at; }

    /**
     * Get the maximum value for this mark type.
     *
     * @return float
     */
    public function maxValue(): float
    {
        return match($this->mark_type) {
            'interro1', 'interro2', 'interro3',
            'interro4', 'interro5', 'interro6' => 10,
            default                             => 20,
        };
    }

    /**
     * Get the mark value converted to a scale of 20.
     *
     * @return float
     */
    public function valueOnTwenty(): float
    {
        $max = $this->maxValue();
        return $max === 20 ? $this->value : ($this->value * 20 / $max);
    }
}