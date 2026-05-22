<?php

namespace App\Models;

use App\Models\MarkHistory;
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
        'teacher_id', 'period', 'type', 'value', 'comments',
        'editable', 'validated', 'locked_at', 'locked_by',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'editable' => 'boolean',
        'validated' => 'boolean',
        'locked_at' => 'datetime',
        'period' => 'integer',
    ];

    /**
     * Get the student this mark belongs to.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /**
     * Get the class this mark belongs to.
     */
    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class, 'classe_id');
    }

    /**
     * Get the subject this mark belongs to.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    /**
     * Get the school year this mark belongs to.
     */
    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class, 'school_year_id');
    }

    /**
     * Get the teacher who entered this mark.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    /**
     * Get the user who locked this mark.
     */
    public function lockedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'locked_by');
    }

    /**
     * Get all edit histories for this mark.
     */
    public function histories(): HasMany
    {
        return $this->hasMany(MarkHistory::class, 'mark_id');
    }

    /**
     * Scope to get only editable marks.
     */
    public function scopeEditable(Builder $query): Builder
    {
        return $query->where('editable', true);
    }
    
        
    /**
     * scopeSubject
     *
     * @param  mixed $query
     * @param  mixed $subjectId
     * @return Builder
     */
    public function scopeSubject(Builder $query, int $subjectId): Builder
    {
        return $query->where('subject_id', $subjectId);
    }
    
    /**
     * scopeStudent
     *
     * @param  mixed $query
     * @param  mixed $studentId
     * @return Builder
     */
    public function scopeStudent(Builder $query, int $studentId) : Builder
    {
        return $query->where('student_id', $studentId);
    }
    
    /**
     * scopeClasse
     *
     * @param  mixed $query
     * @param  mixed $classeId
     * @return Builder
     */
    public function scopeClasse(Builder $query, int $classeId): Builder
    {
        return $query->where('classe_id', $classeId);
    }
    
    /**
     * scopeType
     *
     * @param  mixed $query
     * @param  mixed $type
     * @return Builder
     */
    public function scopeType(Builder $query, string $type) : Builder
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to filter marks by period.
     */
    public function scopeByPeriod(Builder $query, int $period): Builder
    {
        return $query->where('period', $period);
    }

    /**
     * Scope to filter marks by school year.
     */
    public function scopeByYear(Builder $query, int $schoolYearId): Builder
    {
        return $query->where('school_year_id', $schoolYearId);
    }

    /**
     * Check if this mark is currently editable.
     */
    public function isEditable(): bool
    {
        return $this->editable && ! $this->locked_at;
    }

    /**
     * Get the maximum value for this mark type.
     */
    public function maxValue(): float
    {
        return match ($this->mark_type) {
            'interro1', 'interro2', 'interro3',
            'interro4' => 10,
            default => 20,
        };
    }

    /**
     * Get the mark value converted to a scale of 20.
     */
    public function valueOnTwenty(): float
    {
        $max = $this->maxValue();

        return $max === 20 ? $this->value : ($this->value * 20 / $max);
    }
    
    /**
     * scopeInterrogations
     *
     * @param  mixed $query
     * @return Builder
     */
    public function scopeInterrogations(Builder $query): Builder
    {
        return $query->whereIn('type', [
            'interro1',
            'interro2',
            'interro3',
            'interro4',
        ]);
    }

    /**
     * scopeDevoirs
     *
     * @param  mixed $query
     * @return Builder
     */
    public function scopeDevoirs(Builder $query): Builder
    {
        $tenant = tenant();


        if($tenant->type_devoirs === 'devoir1-devoir2'){
            $query->whereIn('type', [
                'devoir1',
                'devoir2',
            ]);
        }
        elseif($tenant->type_devoirs === 'devoir-compo'){
            $query->whereIn('type', [
                'devoir1',
                'compo',
            ]);
        }

        return $query;
    }
}
