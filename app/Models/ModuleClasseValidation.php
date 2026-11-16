<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModuleClasseValidation extends Model
{
    protected $table = 'module_classe_validations';

    protected $connection = 'tenant';

    protected $fillable = [
        'school_year_id',
        'module_id',
        'classe_id',
        'coef',
        'seuil',
    ];

    protected $casts = [
        'coef'  => 'integer',
        'seuil' => 'float',
    ];

    protected $attributes = [
        'seuil' => 10,
    ];

    // ─── Relations ────────────────────────────────────────────────────

    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class, 'school_year_id');
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'module_id')->withTrashed();
    }

    /**
     * Alias clair (module = Subject is_module=true).
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'module_id')->withTrashed();
    }

    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class, 'classe_id');
    }

    // ─── Scopes ───────────────────────────────────────────────────────

    public function scopeForSchoolYear(Builder $query, int $schoolYearId): Builder
    {
        return $query->where('school_year_id', $schoolYearId);
    }

    public function scopeForClasse(Builder $query, int $classeId): Builder
    {
        return $query->where('classe_id', $classeId);
    }

    public function scopeForModule(Builder $query, int $moduleId): Builder
    {
        return $query->where('module_id', $moduleId);
    }

    public function scopeCurrentYear(Builder $query): Builder
    {
        $yearId = SchoolYear::current()?->first()?->id;

        if ($yearId) {
            return $query->where('school_year_id', $yearId);
        }

        return $query->whereRaw('1 = 0');
    }
}
