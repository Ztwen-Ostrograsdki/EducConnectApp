<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Skills extends Model
{
    protected $table = 'skills';

    protected $connection = 'tenant';

    protected $fillable = [
        'content',
        'filiar_id',
        'serial_id',
        'subject_id',
        'code',
        'is_active',
        'weight',
        'coef',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'coef'      => 'integer',
    ];

    // ─── Relations ────────────────────────────────────────────────────

    public function filiar(): BelongsTo
    {
        return $this->belongsTo(Filiar::class, 'filiar_id')->withTrashed();
    }

    public function serial(): BelongsTo
    {
        return $this->belongsTo(Serial::class, 'serial_id')->withTrashed();
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id')->withTrashed();
    }

    /**
     * Modules (Subjects is_module=true) liés via module_skills.
     */
    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(
            Subject::class,
            'module_skills',
            'skill_id',
            'subject_id'
        )
            ->withPivot(['id', 'code', 'weight', 'coef', 'is_active'])
            ->withTimestamps()
            ->where('subjects.is_module', true);
    }

    public function moduleSkills(): HasMany
    {
        return $this->hasMany(ModuleSkills::class, 'skill_id');
    }

    // ─── Scopes ───────────────────────────────────────────────────────

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (blank($term)) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('content', 'like', "%{$term}%")
                ->orWhere('code', 'like', "%{$term}%");
        });
    }

    // ─── Helpers ──────────────────────────────────────────────────────

    public function isActive(): bool
    {
        return (bool) $this->is_active;
    }
}
