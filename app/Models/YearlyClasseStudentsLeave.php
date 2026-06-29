<?php

namespace App\Models;

use App\Models\Classe;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class YearlyClasseStudentsLeave extends Model
{
    protected $table = 'yearly_classe_students_leaves';

    protected $fillable = [
        'school_year_id',
        'student_id',
        'classe_id',
        'author_id',
        'leave_at',
        'reasons',
        'ended_at',
        'status',
    ];

    protected $casts = [
        'leave_at' => 'date',
        'ended_at' => 'date',
    ];

    // ─── Relations ────────────────────────────────────────────────────

    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class, 'school_year_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');

    }

    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class, 'classe_id');
    }
}
