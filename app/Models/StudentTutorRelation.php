<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentTutorRelation extends Model
{
    protected $table = 'student_tutor_relations';

    protected $fillable = [
        'student_id', 'tutor_id', 'parent_relation',
        'is_primary_contact', 'is_active', 'locked',
    ];

    protected $casts = [
        'is_primary_contact' => 'boolean',
        'is_active' => 'boolean',
        'locked' => 'boolean',
    ];

    /**
     * Get the student in this relation.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /**
     * Get the tutor in this relation.
     */
    public function tutor(): BelongsTo
    {
        return $this->belongsTo(Tutor::class, 'tutor_id');
    }

    /**
     * Check if this relation is active.
     */
    public function isActive(): bool
    {
        return $this->is_active && ! $this->locked;
    }
}
