<?php

namespace App\Models;

use App\Helpers\Support\TenantStorage;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

class Student extends Model
{
    use SoftDeletes, HasRoles;

    protected $connection = 'tenant'; 

    protected $table = 'students';

    protected $fillable = [
        'matricule',
        'uuid',
        'qr_code',
        'educMaster',
        'name',
        'prenames',
        'contacts',
        'gender',
        'birth_date',
        'birth_place',
        'country',
        'adresse',
        'city',
        'department',
        'email',
        'profil_photo',
        'user_id',
        'mother_full_name',
        'father_full_name',
        'is_active',
        'blocked',
        'status',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'is_active' => 'boolean',
        'blocked' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            
            
        });

        static::created(function ($model) {

            if($model->gender){
                if(in_array(Str::lower($model->gender), ['masculin', 'm'])){

                    $model->update(['gender' => 'MASCULIN']);
                }
                elseif(in_array(Str::lower($model->gender), ['feminin', 'f', 'féminin'])){

                    $model->update(['gender' => 'FEMININ']);
                }
            }

            $model->update([
                'department' => normalizeString($model->department) ?? null,
                'city' => normalizeString($model->city) ?? null,
                'country' => normalizeString($model->country) ?? null,
                'birth_place' => normalizeString($model->birth_place) ?? null
            ]);
            
        });
    }




    // ─── Relations ────────────────────────────────────────────────────

    /**
     * Get all classes this student has been enrolled in (all years).
     */
    public function classes(): HasMany
    {
        return $this->hasMany(YearlyClasseStudent::class, 'student_id');
    }

    public function yearlyClasseStudents(): HasMany
    {
        return $this->hasMany(YearlyClasseStudent::class, 'student_id');
    }


    public function currentYearlyAccess(?int $classe_id, ?int $school_year_id = null)
    {

        if(!$school_year_id) $school_year_id = SchoolYear::current()?->first()?->id;

        return $this->yearlyClasseStudents()->where('school_year_id', $school_year_id)->where('status', 'active')->where('classe_id', $classe_id)?->first();
    }


    

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get all tutors linked to this student.
     */
    public function tutors(): BelongsToMany
    {
        return $this->belongsToMany(Tutor::class, 'student_tutor_relations', 'student_id', 'tutor_id')
            ->withPivot(['parent_relation', 'is_primary_contact', 'is_active', 'locked'])
            ->withTimestamps();
    }

    /**
     * Get the primary contact tutor for this student.
     */
    public function primaryTutor(): BelongsToMany
    {
        return $this->tutors()->wherePivot('is_primary_contact', true);
    }

    /**
     * Get all active tutors for this student.
     */
    public function activeTutors(): BelongsToMany
    {
        return $this->tutors()->wherePivot('is_active', true);
    }

    /**
     * Get all marks for this student.
     */
    public function marks(): HasMany
    {
        return $this->hasMany(Mark::class, 'student_id');
    }

    /**
     * Get all marks for a specific school year.
     */
    public function marksByYear(int $schoolYearId): HasMany
    {
        return $this->marks()->where('school_year_id', $schoolYearId);
    }

    /**
     * Get all marks for a specific school year and period.
     */
    public function marksByYearAndPeriod(int $schoolYearId, int $period): HasMany
    {
        return $this->marksByYear($schoolYearId)->where('period', $period);
    }

    /**
     * Get all presences for this student.
     */
    public function presences(): HasMany
    {
        return $this->hasMany(Presence::class, 'student_id');
    }

    /**
     * Get all presences for a specific school year.
     */
    public function presencesByYear(int $schoolYearId): HasMany
    {
        return $this->presences()->where('school_year_id', $schoolYearId);
    }

    /**
     * Get all payments for this student.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'student_id');
    }

    /**
     * Get all tutor yearly accesses related to this student.
     */
    public function tutorAccesses(): HasMany
    {
        return $this->hasMany(TutorYearlyAccess::class, 'student_id');
    }

    /**
     * Get all classes where this student is the first class representative.
     */
    public function classeRespo1(): HasMany
    {
        return $this->hasMany(Classe::class, 'respo_1_id');
    }

    /**
     * Get all classes where this student is the second class representative.
     */
    public function classeRespo2(): HasMany
    {
        return $this->hasMany(Classe::class, 'respo_2_id');
    }

    // ─── Scopes ───────────────────────────────────────────────────────

    /**
     * Scope to get students enrolled in a specific school year.
     */
    public function scopeEnrolledInYear(Builder $query, int $schoolYearId): Builder
    {
        return $query->whereHas('classes', function ($q) use ($schoolYearId) {
            $q->wherePivot('school_year_id', $schoolYearId)
                ->wherePivot('status', 'actif');
        });
    }

    /**
     * Scope to get students without an active class for a specific school year.
     */
    public function scopeWithoutClassForYear(Builder $query, int $schoolYearId): Builder
    {
        return $query->whereDoesntHave('classes', function ($q) use ($schoolYearId) {
            $q->wherePivot('school_year_id', $schoolYearId)
                ->wherePivot('status', 'actif');
        });
    }

    // ─── Helpers ──────────────────────────────────────────────────────

    /**
     * Get the full name of the student.
     */
    public function fullName(): string
    {
        return "{$this->name} {$this->prenames}";
    }

    /**
     * Check if the student is enrolled in a specific school year.
     */
    public function isEnrolledForYear(int $schoolYearId): bool
    {
        return $this->classes()
            ->wherePivot('school_year_id', $schoolYearId)
            ->wherePivot('status', 'actif')
            ->exists();
    }

    /**
     * Get the active class for a specific school year.
     */
    public function getClassForYear(int $schoolYearId): ?Classe
    {
        return $this->classes()
            ->wherePivot('school_year_id', $schoolYearId)
            ->wherePivot('status', 'actif')
            ->first();
    }

    
    /**
     * interrogationAverage
     *
     * @param  mixed $subjectId
     * @param  mixed $schoolYearId
     * @param  mixed $period
     * @return float
     */
    public function interrogationAverage(int $subjectId, int $schoolYearId, string $period): float 
    {
        $average = $this->marks()

            ->subject($subjectId)

            ->schoolYear($schoolYearId)

            ->period($period)

            ->interrogations()

            ->avg('mark');

        return round($average ?? 0, 2);
    }

    
    /**
     * subjectAverage
     *
     * @param  mixed $subjectId
     * @param  mixed $schoolYearId
     * @param  mixed $period
     * @return float
     */
    public function subjectAverage(int $subjectId, int $schoolYearId, string $period
    ): float {

        $interrogationAverage = $this->interrogationAverage(
            $subjectId,
            $schoolYearId,
            $period
        );

        $tenant = tenant();

        /*
        |--------------------------------------------------------------------------
        | Cas 1 : Devoir 1 + Devoir 2
        |--------------------------------------------------------------------------
        */

        if ($tenant->type_devoirs === 'devoir1-devoir2') {

            $devoir1 = $this->marks()

                ->subject($subjectId)

                ->schoolYear($schoolYearId)

                ->period($period)

                ->type('devoir1')

                ->avg('mark') ?? 0;

            $devoir2 = $this->marks()

                ->subject($subjectId)

                ->schoolYear($schoolYearId)

                ->period($period)

                ->type('devoir2')

                ->avg('mark') ?? 0;

            $average = (
                $devoir1 +
                $devoir2 +
                $interrogationAverage
            ) / 3;
        }

        /*
        |--------------------------------------------------------------------------
        | Cas 2 : Devoir + Composition
        |--------------------------------------------------------------------------
        */

        else {

            $devoir = $this->marks()

                ->subject($subjectId)

                ->schoolYear($schoolYearId)

                ->period($period)

                ->type('devoir')

                ->avg('mark') ?? 0;

            $composition = $this->marks()

                ->subject($subjectId)

                ->schoolYear($schoolYearId)

                ->period($period)

                ->type('composition')

                ->avg('mark') ?? 0;

            $average = (
                $devoir +
                $composition +
                $interrogationAverage
            ) / 3;
        }

        return round($average, 2);
    }


    public function getFullName(bool $reverse = false)
    {
        if(!$reverse) return  $this->name . ' ' . $this->prenames;

        else  return $this->prenames . ' ' . $this->name;
    }


    public function getUserNamePrefix(bool $withFullName = false, bool $reverseName = false)
    {
        $prefix = 'Mr/Mme';

        if(in_array($this->gender, ['male', 'Male', 'M', 'm', 'masculin', 'Masculin'])) $prefix = 'Mr';

        if(in_array($this->gender, ['female', 'Female', 'F', 'f', 'feminin', 'Féminin', 'Feminin'])) $prefix = 'Mme';

        if($withFullName) return $prefix . ' ' . $this->getFullName($reverseName);

        return $prefix;
    }

    public function greating(bool $withFullName = true, bool $reverse = false)
    {
        $name = $this->getUserNamePrefix($withFullName, $reverse);

        $hour = date('G');
        
        if($hour >= 0 && $hour <= 12){

            $greating = "Bonjour ";
        }
        else{

            $greating = "Bonsoir ";
        }

        return $name  ? $greating . ' ' . $name : $greating;
    }

    public function getProfilPhotoUrlAttribute(): ?string
    {
       if($this->profil_photo)  return TenantStorage::url( $this->profil_photo);

       else return asset('images/default-avatar.jpg') ;
    }


    public function profil_photo_url() 
    {
        return $this->profil_photo_url;
    }



    public function myRoles()
    {
        $roles = [];

        if($this->user){

            $user = $this->user;

            if($user->roles){

                foreach($user->roles as $role){

                    $roles[] = $role->name;
                }

                return implode(' - ', $roles);
            }

        }
        else{

            if($this->roles){

                foreach($this->roles as $role){

                    $roles[] = $role->name;
                }

                return implode(' - ', $roles);
            }
        }

        return null;
    }
    
}
