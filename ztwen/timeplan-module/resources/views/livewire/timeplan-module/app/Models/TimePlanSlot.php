<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimePlanSlot extends Model
{
    protected $connection = 'tenant';
    protected $table = 'time_plan_slots';

    protected $fillable = [
        'time_plan_id', 'classe_subject_of_school_year_id', 'day_of_week',
        'starts_at', 'ends_at', 'label', 'notes',
    ];

    protected function casts(): array
    {
        return ['day_of_week' => 'integer'];
    }

    public function timePlan(): BelongsTo
    {
        return $this->belongsTo(TimePlan::class, 'time_plan_id');
    }

    public function classeSubjectOfSchoolYear(): BelongsTo
    {
        return $this->belongsTo(ClasseSubjectOfSchoolYear::class, 'classe_subject_of_school_year_id');
    }

    public function getTeacherAttribute(): ?Teacher
    {
        return $this->classeSubjectOfSchoolYear?->teacher;
    }

    public function getSubjectAttribute(): ?Subject
    {
        return $this->classeSubjectOfSchoolYear?->subject;
    }

    public function getDayNameAttribute(): string
    {
        return [1 => 'Lundi', 2 => 'Mardi', 3 => 'Mercredi', 4 => 'Jeudi', 5 => 'Vendredi', 6 => 'Samedi', 7 => 'Dimanche'][$this->day_of_week] ?? 'Jour inconnu';
    }
}
