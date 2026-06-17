<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class YearlyClasseStudent extends Model
{
    protected $table = 'yearly_classe_students';

    protected $fillable = [
        'school_year_id',
        'student_id',
        'classe_id',
        'author_id',
        'started_at',
        'ended_at',
        'status',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'started_at' => 'date',
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
