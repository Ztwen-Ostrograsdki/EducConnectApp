<?php

namespace App\Models;

use App\Models\Classe;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Filiar extends Model
{
    use SoftDeletes;

    protected $table = 'filiars';

    protected $fillable = [
        'uuid',
        'slug',
        'name',
        'code',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ─── Relations ────────────────────────────────────────────────────

    /**
     * Get all classes belonging to this filiar.
     *
     * @return HasMany
     */
    public function classes(): HasMany
    {
        return $this->hasMany(Classe::class, 'filiar_id');
    }

    // ─── Scopes ───────────────────────────────────────────────────────

    /**
     * Scope to get only active filiars.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    // ─── Helpers ──────────────────────────────────────────────────────

    /**
     * Check if the filiar is active.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }
}