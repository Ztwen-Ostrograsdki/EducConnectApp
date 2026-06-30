<?php

namespace App\Models;

use App\Models\Classe;
use App\Models\Filiar;
use App\Models\Serial;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


class Promotion extends Model
{
    use SoftDeletes;

    protected $connection = 'tenant';

    protected $table = 'promotions';

    protected $fillable = [
        'uuid',
        'slug',
        'name',
        'code',
        'level',
        'order',
        'filiar_id',
        'serial_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    // ─── Relations ────────────────────────────────────────────────────

    public function classes(): HasMany
    {
        return $this->hasMany(Classe::class, 'promotion_id');
    }

    public function getPromotionClassesOfSchoolYear(?int $school_year_id = null)
    {
        if(!$school_year_id) $school_year_id = SchoolYear::current()?->first()?->id;

        return $this->classes()->where('classes.school_year_id', $school_year_id)->where('classes.is_active', true)->where('classes.is_locked', false)->orderBy('name', 'desc');
    }

    public function getPromotionClassesOfSchoolYearCount(?int $school_year_id = null) : int
    {
        return $this->getPromotionClassesOfSchoolYear($school_year_id)->count();
    }

    public function getPromotionStudentsOfSchoolYear(?int $school_year_id = null)
    {
        if(!$school_year_id) $school_year_id = SchoolYear::current()?->first()?->id;

        return  Student::where('is_active', true)->whereHas('yearlyClasseStudents', fn($q) => 
                                $q->where('school_year_id', $school_year_id)
                                  ->where('is_active', true)
                                  ->whereNull('ended_at')
                                  ->whereHas('classe', fn($qc) => 
                                        $qc->where('promotion_id', $this->id)
                                           ->where('is_active', true)
                                    )
                            )->orderBy('name', 'desc');
    }

    public function getPromotionStudentsOfSchoolYearCount(?int $school_year_id = null) : int
    {
        return $this->getPromotionStudentsOfSchoolYear($school_year_id)->count();
    }



    public function filiar(): BelongsTo
    {
        return $this->belongsTo(Filiar::class);
    }

    public function serial(): BelongsTo
    {
        return $this->belongsTo(Serial::class);
    }

    // Classes d'une promotion pour une année donnée
    public function classesByYear(int $schoolYearId): HasMany
    {
        return $this->classes()->where('school_year_id', $schoolYearId);
    }

    // ─── Scopes ───────────────────────────────────────────────────────

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeByLevel(Builder $query, string $level): Builder
    {
        return $query->where('level', $level);
    }

    public function scopePrimaire(Builder $query): Builder
    {
        return $query->where('level', 'primaire');
    }

    public function scopeSecondaire(Builder $query): Builder
    {
        return $query->where('level', 'secondaire');
    }

    public function scopeSuperieur(Builder $query): Builder
    {
        return $query->where('level', 'superieur');
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order');
    }

    public function specialityModel()
    {
        if($this->filiar){

            return $this->filiar;

        }
        elseif($this->serial){

            return $this->serial;
        }

        return null;
    }

    public function speciality()
    {
        if($this->filiar){

            return $this->filiar->name;

        }
        elseif($this->serial){

            return $this->serial->name;
        }

        return $this->promotion->name;
    }
}
