<?php

namespace App\Models;

use App\Models\Classe;
use App\Models\ClasseSubjectOfSchoolYear;
use App\Models\Mark;
use App\Models\Payment;
use App\Models\Presence;
use App\Models\TeacherYearlyAccess;
use App\Models\TutorYearlyAccess;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolYear extends Model
{
    use SoftDeletes;

    protected $table = 'school_years';

    protected $fillable = [
        'uuid',
        'slug',
        'start',
        'end',
        'period_type',
        'is_active',
        'is_closed',
    ];

    protected $casts = [
        'start'     => 'date',
        'end'       => 'date',
        'is_active' => 'boolean',
        'is_closed' => 'boolean',
    ];

    // ─── Relations ────────────────────────────────────────────────────

    /**
     * Get all classes for this school year.
     *
     * @return HasMany
     */
    public function classes(): HasMany
    {
        return $this->hasMany(Classe::class, 'school_year_id');
    }

    /**
     * Get all marks for this school year.
     *
     * @return HasMany
     */
    public function marks(): HasMany
    {
        return $this->hasMany(Mark::class, 'school_year_id');
    }

    /**
     * Get all presences for this school year.
     *
     * @return HasMany
     */
    public function presences(): HasMany
    {
        return $this->hasMany(Presence::class, 'school_year_id');
    }

    /**
     * Get all payments for this school year.
     *
     * @return HasMany
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'school_year_id');
    }

    /**
     * Get all teacher yearly accesses for this school year.
     *
     * @return HasMany
     */
    public function teacherAccesses(): HasMany
    {
        return $this->hasMany(TeacherYearlyAccess::class, 'school_year_id');
    }

    /**
     * Get all tutor yearly accesses for this school year.
     *
     * @return HasMany
     */
    public function tutorAccesses(): HasMany
    {
        return $this->hasMany(TutorYearlyAccess::class, 'school_year_id');
    }

    /**
     * Get all classe-subject assignments for this school year.
     *
     * @return HasMany
     */
    public function classeSubjects(): HasMany
    {
        return $this->hasMany(ClasseSubjectOfSchoolYear::class, 'school_year_id');
    }

    // ─── Scopes ───────────────────────────────────────────────────────

    /**
     * Scope to get only active school years.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get only closed school years.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeClosed(Builder $query): Builder
    {
        return $query->where('is_closed', true);
    }

    /**
     * Scope to get the current school year (active and not closed).
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeCurrent(Builder $query): Builder
    {
        return $query->where('is_active', true)->where('is_closed', false);
    }

    // ─── Helpers ──────────────────────────────────────────────────────

    /**
     * Check if the school year is currently active.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->is_active && !$this->is_closed;
    }

    /**
     * Check if the school year is closed.
     *
     * @return bool
     */
    public function isClosed(): bool
    {
        return $this->is_closed;
    }

    /**
     * Check if the school year uses trimesters.
     *
     * @return bool
     */
    public function usesTrimestres(): bool
    {
        return $this->period_type === 'trimestre';
    }

    /**
     * Get the number of periods for this school year.
     *
     * @return int
     */
    public function periodsCount(): int
    {
        return $this->usesTrimestres() ? 3 : 2;
    }

    /**
     * Get the label for a period (Semestre or Trimestre).
     *
     * @return string
     */
    public function periodLabel(): string
    {
        return $this->usesTrimestres() ? 'Trimestre' : 'Semestre';
    }

    /**
     * Get all periods as an array with their number and label.
     *
     * @return array<int, array{number: int, label: string}>
     */
    public function getPeriods(): array
    {
        $label = $this->periodLabel();
        $count = $this->periodsCount();

        return array_map(
            fn(int $i) => ['number' => $i, 'label' => "$label $i"],
            range(1, $count)
        );
    }
}