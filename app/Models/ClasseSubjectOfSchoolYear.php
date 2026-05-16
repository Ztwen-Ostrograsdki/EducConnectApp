<?php

namespace App\Models;

use App\Models\Classe;
use App\Models\SchoolYear;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClasseSubjectOfSchoolYear extends Model
{
    protected $table = 'classe_subject_of_school_years';

    protected $fillable = [
        'classe_id', 'subject_id', 'teacher_id', 'school_year_id',
        'coefficient', 'is_active', 'started_at', 'ended_at',
        'replacement_reason', 'replaced_by',
    ];

    protected $casts = [
        'coefficient' => 'decimal:2',
        'is_active'   => 'boolean',
        'started_at'  => 'datetime',
        'ended_at'    => 'datetime',
    ];

    /**
     * Get the class this assignment belongs to.
     *
     * @return BelongsTo
     */
    public function classe(): BelongsTo { return $this->belongsTo(Classe::class, 'classe_id'); }

    /**
     * Get the subject of this assignment.
     *
     * @return BelongsTo
     */
    public function subject(): BelongsTo { return $this->belongsTo(Subject::class, 'subject_id'); }

    /**
     * Get the teacher assigned to this subject in this class.
     *
     * @return BelongsTo
     */
    public function teacher(): BelongsTo { return $this->belongsTo(Teacher::class, 'teacher_id'); }

    /**
     * Get the school year of this assignment.
     *
     * @return BelongsTo
     */
    public function schoolYear(): BelongsTo { return $this->belongsTo(SchoolYear::class, 'school_year_id'); }

    /**
     * Get the user who registered the teacher replacement.
     *
     * @return BelongsTo
     */
    public function replacedBy(): BelongsTo { return $this->belongsTo(User::class, 'replaced_by'); }

    /**
     * Scope to get only current assignments (not replaced).
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeCurrent(Builder $query): Builder { return $query->whereNull('ended_at')->where('is_active', true); }

    /**
     * Scope to get only historical assignments (replaced teachers).
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeHistory(Builder $query): Builder { return $query->whereNotNull('ended_at'); }

    /**
     * Check if this assignment is currently active (no replacement).
     *
     * @return bool
     */
    public function isCurrent(): bool { return is_null($this->ended_at); }

    /**
     * Replace the current teacher with a new one.
     * Closes this assignment and creates a new one for the replacement teacher.
     *
     * @param int $newTeacherId - ID of the new teacher
     * @param string $reason - Reason for the replacement
     * @param int $replacedBy - ID of the user registering the replacement
     * @return static - The newly created assignment
     */
    public function replaceTeacher(int $newTeacherId, string $reason, int $replacedBy): static
    {
        // Close the current assignment
        $this->update([
            'ended_at'    => now(),
            'replaced_by' => $replacedBy,
        ]);

        // Create a new assignment for the replacement teacher
        return static::create([
            'classe_id'          => $this->classe_id,
            'subject_id'         => $this->subject_id,
            'school_year_id'     => $this->school_year_id,
            'teacher_id'         => $newTeacherId,
            'coefficient'        => $this->coefficient,
            'replacement_reason' => $reason,
            'started_at'         => now(),
            'ended_at'           => null,
        ]);
    }
}