<?php

namespace App\Models;

use App\Events\DataUpdatedEvent;
use App\Exceptions\CouldNotMigrateStudentFromClasseToNewWhenHasMarksInSubjectsThatDoesntExistsInTheNewClasseDuringTheSameSchoolYearException;
use App\Helpers\Support\TenantStorage;
use App\Models\SchoolYear;
use App\Models\User;
use App\Notifications\RealTimeNotification;
use App\Traits\InvalidatesDashboardCounters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

class Student extends Model
{
    use SoftDeletes, HasRoles, InvalidatesDashboardCounters;

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

            $director = User::first();

            if($director){

                broadcast(new DataUpdatedEvent($director->tenant_id));

            }

            if($model->gender){

                $gend = Str::lower($model->gender);

                if(in_array($gend, ['masculin', 'm']) || Str::initials($gend) === 'm'){

                    $model->update(['gender' => 'M']);
                }
                elseif(in_array($gend, ['feminin', 'f', 'féminin']) || Str::initials($gend) === 'f'){

                    $model->update(['gender' => 'F']);
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

    protected static function booted()
    {
        static::updated(function (Student $student) {
            if ($student->wasChanged('gender')) {
                static::flushEffectifsForActiveClasses($student);
            }
        });
    }

    /**
     * Invalide le cache "apprenants_par_sexe" de toutes les classes
     * où cet élève est actuellement actif (peu importe l'année scolaire active,
     * au cas où plusieurs années seraient consultables en simultané).
     */
    protected static function flushEffectifsForActiveClasses(Student $student): void
    {
        $classeIds = YearlyClasseStudent::query()
            ->where('student_id', $student->id)
            ->where('is_active', true)
            ->whereNull('ended_at')
            ->pluck('classe_id')
            ->unique();

        foreach ($classeIds as $classeId) {
            Cache::tags(["classe:{$classeId}", 'effectifs'])->flush();
        }
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
    
    
    public function yearlyStudentsLeaves(): HasMany
    {
        return $this->hasMany(YearlyClasseStudentsLeave::class, 'student_id');
    }


    public function currentYearlyAccess(?int $classe_id, ?int $school_year_id = null)
    {

        if(!$school_year_id) $school_year_id = SchoolYear::current()?->first()?->id;

        return $this->yearlyClasseStudents()->where('school_year_id', $school_year_id)->where('is_active', true)->where('classe_id', $classe_id)?->first();
    }


    public function currentClasse(?int $school_year_id = null)
    {
        if(!$school_year_id) $school_year_id = SchoolYear::current()?->first()?->id;

        return YearlyClasseStudent::with('classe')
                                  ->where('student_id', $this->id)
                                  ->where('school_year_id', $school_year_id)
                                  ->where('is_active', true)
                                  ->first();
    }

    public function hasResponsibleInThisYear(?int $school_year_id = null) : ?string
    {
        if(!$school_year_id) $school_year_id = SchoolYear::current()?->first()?->id;

        $current_classe = $this->currentClasse($school_year_id);

        if($current_classe){

            if($current_classe->classe){

                if($current_classe->classe->respo_1_id && $current_classe->classe->respo_1_id === $this->id) return "Responsable N°1";

                if($current_classe->classe->respo_2_id && $current_classe->classe->respo_2_id === $this->id) return "Responsable N°2";

                return null;
            }

            return null;

        }

        return null;

    }


    

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get all tutors linked to this student.
     */
    public function tutors(): HasMany
    {
        return $this->hasMany(StudentTutorRelation::class, 'student_id');
    } 
    
    /* Get all tutors linked to this student.
     */
    public function parents(): HasMany
    {
        return $this->hasMany(StudentTutorRelation::class, 'student_id');
    }

    /**
     * Get the primary contact tutor for this student.
     */
    public function primaryTutor(): BelongsToMany
    {
        return $this->tutors()->where('is_primary_contact', true);
    }

    /**
     * Get all active tutors for this student.
     */
    public function activeTutors(): BelongsToMany
    {
        return $this->tutors()->where('is_active', true);
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
            ->where('school_year_id', $schoolYearId)
            ->where('status', 'actif')
            ->exists();
    }

    /**
     * Get the active class for a specific school year.
     */
    public function getStudentCurrentClasse(?int $schoolYearId = null)
    {
        if(!$schoolYearId) $schoolYearId = SchoolYear::current()?->first()?->id;

        return $this->classes()
            ->where('school_year_id', $schoolYearId)
            ->where('is_active', true)
            ->with('classe')
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

    public function checkIfStudentNotLeavedYet(?int $classe_id = null, ?int $school_year_id = null) : bool
    {
        if(!$school_year_id) $school_year_id = SchoolYear::current()?->first()?->id;

        $currentClasse = $this->getStudentCurrentClasse($school_year_id);

        if(!$currentClasse) return true;

        if(!$classe_id) $classe_id = $currentClasse?->id;

        return !$this->yearlyStudentsLeaves()
                       ->where('school_year_id', $school_year_id)
                       ->where('classe_id', $classe_id)
                       ->whereNull('ended_at')
                       ->exists();


    }



    public function markStudentAsLeaved(?int $classe_id = null, ?int $school_year_id = null, ?string $reasons = "Abondonné sans motif précisé!")
    {
        if(!$school_year_id) $school_year_id = SchoolYear::current()?->first()?->id;

        $currentClasse = $this->getStudentCurrentClasse($school_year_id);

        if(!$currentClasse) return false;

        if(!$classe_id) $classe_id = $currentClasse?->classe_id;

        $exists = $this->yearlyStudentsLeaves()
                       ->where('school_year_id', $school_year_id)
                       ->where('classe_id', $classe_id)
                       ->whereNull('ended_at')
                       ->exists();

        if(!$exists){

            return YearlyClasseStudentsLeave::create([
                'school_year_id' => $school_year_id,
                'classe_id' => $classe_id,
                'student_id' => $this->id,
                'leave_at' => now(),
                'reasons' => $reasons,
            ]);
        }

        return false;

    }
    
    
    public function reinsertStudentIntoClasse(?int $classe_id = null, ?int $school_year_id = null, ?string $reasons = "Abondonné sans motif précisé!")
    {
        if(!$school_year_id) $school_year_id = SchoolYear::current()?->first()?->id;

        $currentClasse = $this->getStudentCurrentClasse($school_year_id);

        if(!$currentClasse) return false;

        if(!$classe_id) $classe_id = $currentClasse?->classe_id;

        $exists = $this->yearlyStudentsLeaves()
                       ->where('school_year_id', $school_year_id)
                       ->where('classe_id', $classe_id)
                       ->whereNotNull('leave_at')
                       ->first();

        if($exists){

            return $exists->delete();
        }

        return false;

    }

    public function toProfilRoute()
    {
        return route('tenant.student.profil', ['student_uuid' => $this->uuid]);
    }

    public function ensureThatStudentCanMigratedToThisClasse(int $newClasseId, ?int $school_year_id = null) : bool
    {
        if(!$school_year_id){

            if(!$school_year_id) $schoolYear = SchoolYear::current()?->first();
        }
        else{

            $schoolYear = SchoolYear::find($this->school_year_id);
        }

        $oldClasse = $this->currentClasse()?->classe;

        if($oldClasse){

            $newClasse = Classe::find($newClasseId);

            if($newClasse){

                $subjectIdsInNewClasse = $newClasse->classeSubjects()->pluck('subject_id')->all();

                $subjectIdsInWhereStudentsHasMarksInOldClasse = $this->marks()->where('school_year_id', $schoolYear->id)->where('classe_id', $oldClasse->id)->pluck('subject_id')->all();

                if(count($subjectIdsInWhereStudentsHasMarksInOldClasse) <= 0) return true;

                if(count($subjectIdsInWhereStudentsHasMarksInOldClasse) > count($subjectIdsInNewClasse)) return false;

                foreach($subjectIdsInWhereStudentsHasMarksInOldClasse as $sub_id){

                    if(!in_array($sub_id, $subjectIdsInNewClasse)) return false;
                }

            }
        }
        else{

            return true;
        }

        return false;
    }


    public function migrateStudentToClasse(int $classeId, ?int $school_year_id = null, bool $redirect_to_profil = false)
    {
        DB::beginTransaction();

        try {

            $director = User::first();

            $classe = Classe::find($classeId);

            if($classe){

                if(!$school_year_id){

                   $schoolYear = SchoolYear::current()?->first();
                }
                else{
                    $schoolYear = SchoolYear::find($this->school_year_id);
                }

                if($this->ensureThatStudentCanMigratedToThisClasse($classeId) === false){

                    $error_message = "Cet apprenant comporte des notes dans certaines matières de son ancienne classe et, certaines de ces matières n'existent pas dans la nouvelle classe.";

                    $director?->notify(new RealTimeNotification(
                        userEmail: $director?->email,
                        tenantId: $director->tenant_id,
                        title:             "MIGRATION IMPOSSIBLE : INCOHERENCE DE MATIERES",
                        message:           $error_message,
                        type:              'error',
                    ));

                    throw new CouldNotMigrateStudentFromClasseToNewWhenHasMarksInSubjectsThatDoesntExistsInTheNewClasseDuringTheSameSchoolYearException($error_message);

                }

                if($schoolYear && $schoolYear->is_active){

                    $studentId = $this->id;

                    YearlyClasseStudent::where('student_id', $this->id)->where('school_year_id', $schoolYear->id)?->delete();

                    YearlyClasseStudent::create([
                        'student_id'     => $studentId,
                        'classe_id'      => $classeId,
                        'school_year_id' => $schoolYear->id,
                        'author_id'      => $director->id,
                        'is_active'      => true,
                        'status'         => 'Approuvé',
                        'started_at'     => now(),
                    ]);

                    $director?->notify(new RealTimeNotification(
                        userEmail: $director->email,
                        tenantId:  $director->tenant_id,
                        title:     "MIGRATION TERMINEE AVEC SUCCES!",
                        message:   $this->getFullName() . " est à présent un apprenant actif de la classe de " . $classe?->name,
                        type:      'success',
                    ));

                    DB::commit();

                    if($redirect_to_profil) return redirect($this->toProfilRoute());
                }
                else{

                    $error_message = "L'année scolaire est introuvable ou peut-être n'est pas active!";

                    $director?->notify(new RealTimeNotification(
                        userEmail: $director?->email,
                        tenantId: $director->tenant_id,
                        title:             "Erreur de migration ",
                        message:           $error_message,
                        type:              'error',
                    ));

                }
            }
            else{

                $error_message = "La classe de destination est introuvable!";

                $director?->notify(new RealTimeNotification(
                    userEmail: $director?->email,
                    tenantId: $director->tenant_id,
                    title:             "Erreur de migration: la classe n'existe pas ",
                    message:           $error_message,
                    type:              'error',
                ));

            }

        } catch (\Throwable $th) {

            DB::rollback();

            $director?->notify(new RealTimeNotification(
                userEmail: $director->email,
                tenantId:  $director->tenant_id,
                title:     "ECHEC DE LA MIGRATION DE L'APPRENANT " . $this->getFullName() . " VERS LA CLASSE " . $classe?->name,
                message:   cutter($th->getMessage(), 200),
                type:      'error',
            ));
        }
        finally{

            broadcast(new DataUpdatedEvent($director->tenant_id));

        }
    }


    public function removeStudentFromHisCurrentClasse(bool $redirect_to_profil = false)
    {
        DB::beginTransaction();

        try {

            $director = User::first();

            $classe = $this->currentClasse()?->classe;

            if($classe){

                $schoolYear = SchoolYear::find($classe->school_year_id);

                if($schoolYear && $schoolYear->is_active){

                    $student = $this;

                    if($student){

                        YearlyClasseStudent::where('student_id', $this->id)->where('school_year_id', $classe->school_year_id)->delete();

                        $director?->notify(new RealTimeNotification(
                            userEmail: $director->email,
                            tenantId:  $director->tenant_id,
                            title:     "RETRAIT TERMINE AVEC SUCCES!",
                            message:   $this->getFullName() . " est à présent un apprenant sans classe. Il a été retiré de la classe " . $classe?->name,
                            type:      'success',
                        ));

                        DB::commit();
                    }
                }
                else{

                    $error_message = "L'année scolaire est introuvable ou peut-être n'est pas active!";

                    $director?->notify(new RealTimeNotification(
                        userEmail: $director?->email,
                        tenantId: $director->tenant_id,
                        title:             "Erreur de retrait ",
                        message:           $error_message,
                        type:              'error',
                    ));

                }
            }
            else{

                $director?->notify(new RealTimeNotification(
                    userEmail: $director?->email,
                    tenantId: $director->tenant_id,
                    title:             "Erreur de retrait: la classe n'existe pas ",
                    message:           "Il semble que l'apprenant n'a pas de classe actuellement",
                    type:              'error',
                ));

            }

        } catch (\Throwable $th) {

            DB::rollback();

            $director?->notify(new RealTimeNotification(
                userEmail: $director->email,
                tenantId:  $director->tenant_id,
                title:     "ECHEC DU RETRAIT DE L'APPRENANT " . $this->getFullName() . " DE SA CLASSE ACTUELLE" . $classe?->name,
                message:   cutter($th->getMessage(), 200),
                type:      'error',
            ));
        }
        finally{

            broadcast(new DataUpdatedEvent($director->tenant_id));

        }
    }
    
}
