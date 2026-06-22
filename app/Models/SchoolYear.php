<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;


class SchoolYear extends Model
{
    use SoftDeletes;

    protected $table = 'school_years';

    protected $connection = 'tenant';

    protected $fillable = [
        'uuid',
        'slug',
        'min_year',
        'max_year',
        'periode_type',
        'is_active', //Année en cours : elle peut etre fermée is_closed = true
        'is_closed', // Année fermée = plus aucune modification de l'exterieur. Toute action sur les models et la base de données est imposssible et bloquée. Une année activée fermée === année en cours mais déjà cloturée 
        'locked_for_period',
        'is_current_school_year',
        'marks_locked_for_periods',
        'periods',
    ];


    protected $casts = [
        'min_year' => 'int',
        'max_year' => 'int',
        'is_active' => 'boolean',
        'is_closed' => 'boolean',
        'periods' => 'array',
    ];

    // ─── Relations ────────────────────────────────────────────────────

    /**
     * Get all classes for this school year.
     */
    public function classes(): HasMany
    {
        return $this->hasMany(Classe::class, 'school_year_id');
    }

    /**
     * Get all marks for this school year.
     */
    public function marks(): HasMany
    {
        return $this->hasMany(Mark::class, 'school_year_id');
    }

    /**
     * Get all presences for this school year.
     */
    public function presences(): HasMany
    {
        return $this->hasMany(Presence::class, 'school_year_id');
    }

    /**
     * Get all payments for this school year.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'school_year_id');
    }

    /**
     * Get all teacher yearly accesses for this school year.
     */
    public function teacherAccesses(): HasMany
    {
        return $this->hasMany(TeacherYearlyAccess::class, 'school_year_id');
    }

    /**
     * Get all tutor yearly accesses for this school year.
     */
    public function tutorAccesses(): HasMany
    {
        return $this->hasMany(TutorYearlyAccess::class, 'school_year_id');
    }

    /**
     * Get all classe-subject assignments for this school year.
     */
    public function classeSubjects(): HasMany
    {
        return $this->hasMany(ClasseSubjectOfSchoolYear::class, 'school_year_id');
    }

    // ─── Scopes ───────────────────────────────────────────────────────

    /**
     * Scope to get only active school years.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get only closed school years.
     */
    public function scopeClosed(Builder $query): Builder
    {
        return $query->where('is_closed', true);
    }

    /**
     * Scope to get the current school year (active and not closed).
     */
    public function scopeCurrent(Builder $query): Builder
    {
        return $query->where('is_active', true)->where('is_closed', false);
    }

    // ─── Helpers ──────────────────────────────────────────────────────

    /**
     * Check if the school year is currently active.
     */
    public function isActive(): bool
    {
        return $this->is_active && ! $this->is_closed;
    }


    /**
     * Check if the school year is closed.
     */
    public function isClosed(): bool
    {
        return $this->is_closed;
    }

    /**
     * Check if the school year uses trimesters.
     */
    public function usesTrimestres(): bool
    {
        return $this->period_type === 'trimestre';
    }

    /**
     * Get the number of periods for this school year.
     */
    public function periodsCount(): int
    {
        return $this->usesTrimestres() ? 3 : 2;
    }

    /**
     * Get the label for a period (Semestre or Trimestre).
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
            fn (int $i) => ['number' => $i, 'label' => "$label $i"],
            range(1, $count)
        );
    }


    public function getDuration()
    {
        $periods = $this->periods;

        $periods_without_key = [];


        foreach($periods as $p){

            $periods_without_key[] = $p;

        }

        $min = (array)$periods_without_key[0];

        $max = (array)$periods_without_key[count($periods_without_key) - 1];

        $duration = getFulldurationBetween2Dates($min['start'], $max['end']);


        return $duration;
    }


    public function getStartDate()
    {
        $periods = $this->periods ?? null;

        if(!$periods) return "Date initiale non défine!";

        $periods_without_key = [];


        foreach($periods as $p){

            $periods_without_key[] = $p;

        }

        $date = (array)$periods_without_key[0];

        if (! $date['start']) {
            return null;
        }

        $carbon = Carbon::parse($date['start'])->locale('fr');

        // weekday + jour en minuscule, mois avec 1ère lettre en majuscule, année
        return $carbon->translatedFormat('l d') . ' '
            .Str::ucwords($carbon->translatedFormat('F')) . ' '
            . $carbon->format('Y');
    }


    public function getEndDate()
    {
        $periods = $this->periods ?? null;

        if(!$periods) return "Date finale non défine!";

        $periods_without_key = [];


        foreach($periods as $p){

            $periods_without_key[] = $p;

        }

        $date = (array)$periods_without_key[count($periods_without_key) - 1];

        if (! $date['start']) {
            return null;
        }

        $carbon = Carbon::parse($date['start'])->locale('fr');

        // weekday + jour en minuscule, mois avec 1ère lettre en majuscule, année
        return $carbon->translatedFormat('l d') . ' '
            .Str::ucwords($carbon->translatedFormat('F')) . ' '
            . $carbon->format('Y');
    }
}
