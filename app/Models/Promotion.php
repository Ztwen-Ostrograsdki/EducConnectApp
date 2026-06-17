<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


class Promotion extends Model
{
    use SoftDeletes;

    protected $table = 'promotions';

    protected $fillable = [
        'uuid',
        'slug',
        'name',
        'code',
        'level',
        'order',
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
}
