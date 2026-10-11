<?php

namespace App\Models;

use App\Models\Filiar;
use App\Models\Serial;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class SubjectAnimationTime extends Model
{
    protected $connection = 'tenant';
    protected $table = 'subject_animation_times';

    protected $fillable = [
        'subject_id', 'school_year_id', 'day_of_week',
        'starts_at', 'ends_at', 'label', 'notes','is_active', 'filiar_id', 'serial_id'
    ];

    protected function casts(): array
    {
        return ['day_of_week' => 'integer'];
    }


    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
    
    public function filiar()
    {
        return $this->belongsTo(Filiar::class);
    }

    public function serial()
    {
        return $this->belongsTo(Serial::class);
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
