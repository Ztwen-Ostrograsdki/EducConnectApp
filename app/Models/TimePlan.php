<?php

namespace App\Models;

use App\Models\Classe;
use App\Models\SchoolYear;
use App\Models\TimePlanSlot;
use App\Observers\ObserveTimePlan;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ObservedBy(ObserveTimePlan::class)]
class TimePlan extends Model
{
    protected $connection = 'tenant';
    protected $table = 'time_plans';

    protected $fillable = [
        'classe_id', 'school_year_id', 'title', 'status', 'published_at', 'notes', 'is_active'
    ];

    protected function casts(): array
    {
        return ['published_at' => 'datetime'];
    }

    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class, 'classe_id');
    }

    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class, 'school_year_id');
    }

    public function slots(): HasMany
    {
        return $this->hasMany(TimePlanSlot::class, 'time_plan_id')->orderBy('day_of_week')->orderBy('starts_at');
    }

    public function scopeForYear(Builder $query, int $schoolYearId): Builder
    {
        return $query->where('school_year_id', $schoolYearId);
    }

    public function isEditable(): bool
    {
        return $this->status !== 'archived';
    }

    protected function archived() : Attribute
    {
        return Attribute::get(
            fn() => $this->status === 'archived'
        );
    }

    protected function draft() : Attribute
    {
        return Attribute::get(
            fn() => $this->status === 'draft'
        );
    }

    protected function published() : Attribute
    {
        return Attribute::get(
            fn() => $this->status === 'published'
        );
    }
    
}
