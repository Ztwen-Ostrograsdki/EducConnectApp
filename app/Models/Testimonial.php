<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Testimonial extends Model
{
    use SoftDeletes;

    protected $table = 'testimonials';

    protected $connection = 'tenant';

    protected $fillable = [
        'uuid',
        'user_id',
        'content',
        'hidden',
    ];

    protected $casts = [
        'hidden' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Testimonial $testimonial) {
            if (empty($testimonial->uuid)) {
                $testimonial->uuid = (string) Str::uuid();
            }
        });
    }

    // ─── Relations ────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
}
