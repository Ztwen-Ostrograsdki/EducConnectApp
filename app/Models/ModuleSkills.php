<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModuleSkills extends Model
{
    protected $table = 'module_skills';

    protected $connection = 'tenant';

    protected $fillable = [
        'skill_id',
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

    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skills::class, 'skill_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id')->withTrashed();
    }

    /**
     * Alias clair : le module = Subject avec is_module=true.
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id')->withTrashed();
    }

    // ─── Scopes ───────────────────────────────────────────────────────

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeForModule(Builder $query, int $subjectId): Builder
    {
        return $query->where('subject_id', $subjectId);
    }

    // ─── Helpers ──────────────────────────────────────────────────────

    public function isActive(): bool
    {
        return (bool) $this->is_active;
    }
}
