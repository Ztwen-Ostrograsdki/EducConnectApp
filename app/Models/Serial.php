<?php

namespace App\Models;

use App\Models\Classe;
use App\Models\Promotion;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Traits\InvalidatesDashboardCounters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;


class Serial extends Model
{
    use SoftDeletes, InvalidatesDashboardCounters;

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

    
    public function getSerialClassesOfSchoolYear(?int $school_year_id = null)
    {
        if(!$school_year_id) $school_year_id = SchoolYear::current()?->first()?->id;

        return $this->classes()->where('classes.school_year_id', $school_year_id)->where('classes.is_active', true)->where('classes.is_locked', false)->orderBy('name', 'desc');
    }

    public function getSerialClassesOfSchoolYearCount(?int $school_year_id = null) : int
    {
        return $this->getSerialClassesOfSchoolYear($school_year_id)->count();
    }

    public function getSerialSubjectsOfSchoolYear(?int $school_year_id = null)
    {
        if(!$school_year_id) $school_year_id = SchoolYear::current()?->first()?->id;

        return  Subject::where('is_active', true)->whereHas('classeSubjects', fn($q) => 
                                $q->whereHas('classe', fn($qs) => 
                                    $qs->where('is_active', true)
                                        ->where('serial_id', $this->id)
                                        ->where('is_active', true)
                                )
                                ->where('is_active', true)
                                ->whereNull('ended_at')
                            )->orderBy('name', 'desc');
    }

    public function getSerialStudentsOfSchoolYear(?int $school_year_id = null, ?int $classe_id = null, ?int $promotion_id = null, ?string $gender = null)
    {
        if(!$school_year_id) $school_year_id = SchoolYear::current()?->first()?->id;

        return  Student::where('is_active', true)->whereHas('yearlyClasseStudents', fn($q) => 
                                $q->where('school_year_id', $school_year_id)
                                  ->where('is_active', true)
                                  ->whereNull('ended_at')
                                  ->whereHas('classe', fn($qc) => 
                                        $qc->where('serial_id', $this->id)
                                           ->where('is_active', true)
                                           ->when($classe_id, fn($qc) => 
                                            $qc->where('classe_id', $classe_id)
                                        )
                                        ->when($promotion_id, fn($qs) => 
                                            $qs->where('promotion_id', $promotion_id)
                                        )
                                    )

                            )
                            ->when($gender, fn($q) => $q->whereIn('gender', [$gender, Str::lower($gender), Str::upper($gender)]));
    }

    public function getSerialStudentsOfSchoolYearCount(?int $school_year_id = null) : int
    {
        return $this->getSerialStudentsOfSchoolYear($school_year_id)->count();
    }


    public function getSerialTeachersOfSchoolYear(?int $school_year_id = null, ?int $classe_id = null, ?int $promotion_id = null, ?int $subject_id = null, ?string $gender = null)
    {
        if(!$school_year_id) $school_year_id = SchoolYear::current()?->first()?->id;

        return  Teacher::query()
                        ->select('teachers.*')
                        ->join('users', 'users.id', '=', 'teachers.user_id')
                        ->with(['user'])
                        ->whereNotNull('affiliated_at')
                        ->whereHas('classeSubjects', fn($q) => 
                            $q->where('school_year_id', $school_year_id)
                                ->when($classe_id, fn($qc) => 
                                    $qc->where('classe_id', $classe_id)
                                )
                                ->when($subject_id, fn($qs) => 
                                    $qs->where('subject_id', $subject_id)
                                )
                                ->whereHas('classe', fn($qcc) => 
                                    $qcc->where('serial_id', $this->id)
                                        ->where('is_active', true)
                                        ->when($promotion_id, fn($qp) => 
                                            $qp->where('promotion_id', $promotion_id)
                                        )
                                )
                                ->where('is_active', true)
                                ->whereNull('ended_at')
                        )
                        ->when($gender, fn($q) => $q->whereIn('users.gender', [$gender, Str::lower($gender), Str::upper($gender)]));
    }

    public function getSerialTeachersOfSchoolYearCount(?int $school_year_id = null) : int
    {
        return $this->getSerialTeachersOfSchoolYear($school_year_id)->count();
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
