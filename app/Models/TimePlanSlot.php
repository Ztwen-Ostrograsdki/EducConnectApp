<?php

namespace App\Models;

use App\Observers\ObserveTimePlanSlot;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

#[ObservedBy(ObserveTimePlanSlot::class)]
class TimePlanSlot extends Model
{
    protected $connection = 'tenant';
    protected $table = 'time_plan_slots';

    protected $fillable = [
        'time_plan_id', 'classe_subject_of_school_year_id', 'day_of_week',
        'starts_at', 'ends_at', 'label', 'notes','is_active'
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
        return config('timeplan.days')[$this->day_of_week] ?? 'Jour inconnu';
    }

    public function getDurationAttribute() : int
    {
        $start = Carbon::parse($this->starts_at);
        
        $end = Carbon::parse($this->ends_at);

        return (int)$start->diffInHours($end, true);

    }


    protected function start() : Attribute
    {
        return Attribute::get(
            fn() => Carbon::parse($this->starts_at)->format('H\hi')
        );
    }
    
    protected function end() : Attribute
    {
        return Attribute::get(
            fn() => Carbon::parse($this->ends_at)->format('H\hi')
        );
    }
}
