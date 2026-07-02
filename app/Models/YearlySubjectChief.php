<?php

namespace App\Models;

use App\Models\SchoolYear;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class YearlySubjectChief extends Model
{

    protected $table = 'yearly_subject_chiefs';

    protected $connection = 'tenant';

    protected $fillable = [
        'subject_id', 'teacher_id', 'school_year_id', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];


    /**
     * Get the teacher this access belongs to.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    /**
     * Get the student this access is linked to.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    /**
     * Get the school year this access belongs to.
     */
    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class, 'school_year_id');
    }
}
