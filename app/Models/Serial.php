<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


class Serial extends Model
{
    use SoftDeletes;

    protected $table = 'serials';

    protected $connection = 'tenant';

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
     * Get all classes belonging to this serial.
     */
    public function classes(): HasMany
    {
        return $this->hasMany(Classe::class, 'serial_id');
    }


    public function promotions(): HasMany
    {
        return $this->hasMany(Promotion::class);
    }

    // ─── Scopes ───────────────────────────────────────────────────────

    /**
     * Scope to get only active serials.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    // ─── Helpers ──────────────────────────────────────────────────────

    /**
     * Check if the serial is active.
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }
}
