<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Gallery extends Model
{
    use SoftDeletes;

    protected $table = 'galleries';

    protected $connection = 'tenant';

    protected $fillable = [
        'uuid',
        'path',
        'creator',
        'title',
        'description',
        'hidden',
    ];

    protected $casts = [
        'hidden' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Gallery $gallery) {
            if (empty($gallery->uuid)) {
                $gallery->uuid = (string) Str::uuid();
            }
        });
    }

    // ─── Relations ────────────────────────────────────────────────────

    public function creatorUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator');
    }

    // ─── Scopes ───────────────────────────────────────────────────────

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('hidden', false);
    }

    public function scopeHidden(Builder $query): Builder
    {
        return $query->where('hidden', true);
    }

    // ─── Helpers ──────────────────────────────────────────────────────

    public function isHidden(): bool
    {
        return (bool) $this->hidden;
    }

    public function getPathUrlAttribute(): ?string
    {
        if (! $this->path) {
            return null;
        }

        // Adapter selon votre helper TenantStorage si besoin
        return asset('storage/' . $this->path);
    }
}
