<?php
namespace App\Models;

use App\Models\Classe;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Presence extends Model
{
    protected $table = 'presences';

    protected $fillable = [
        'student_id', 'classe_id', 'school_year_id', 'teacher_id',
        'subject_id', 'date', 'status', 'reason', 'tutor_advised', 'tutor_advised_at',
    ];

    protected $casts = [
        'date'             => 'date',
        'tutor_advised'    => 'boolean',
        'tutor_advised_at' => 'datetime',
    ];

    /**
     * Get the student this presence record belongs to.
     *
     * @return BelongsTo
     */
    public function student(): BelongsTo { return $this->belongsTo(Student::class, 'student_id'); }

    /**
     * Get the class this presence record belongs to.
     *
     * @return BelongsTo
     */
    public function classe(): BelongsTo { return $this->belongsTo(Classe::class, 'classe_id'); }

    /**
     * Get the school year this presence record belongs to.
     *
     * @return BelongsTo
     */
    public function schoolYear(): BelongsTo { return $this->belongsTo(SchoolYear::class, 'school_year_id'); }

    /**
     * Get the teacher who recorded this presence.
     *
     * @return BelongsTo
     */
    public function teacher(): BelongsTo { return $this->belongsTo(Teacher::class, 'teacher_id'); }

    /**
     * Get the subject this presence record is for.
     *
     * @return BelongsTo
     */
    public function subject(): BelongsTo { return $this->belongsTo(Subject::class, 'subject_id'); }

    /**
     * Scope to get only absent records.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeAbsent(Builder $query): Builder { return $query->where('status', 'absent'); }

    /**
     * Scope to get only present records.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePresent(Builder $query): Builder { return $query->where('status', 'present'); }

    /**
     * Scope to filter by date.
     *
     * @param Builder $query
     * @param string $date
     * @return Builder
     */
    public function scopeByDate(Builder $query, string $date): Builder { return $query->where('date', $date); }

    /**
     * Scope to filter by school year.
     *
     * @param Builder $query
     * @param int $schoolYearId
     * @return Builder
     */
    public function scopeByYear(Builder $query, int $schoolYearId): Builder { return $query->where('school_year_id', $schoolYearId); }
}