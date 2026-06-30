<?php

namespace App\Models;

use App\Jobs\JobToCreateYearlyAccessForTeacher;
use App\Notifications\RealTimeNotification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use SoftDeletes;

    protected $connection = 'tenant';

    protected $table = 'teachers';

    protected $fillable = [
        'uuid',
        'qr_code',
        'user_id',
        'identifiant',
        'identity_card_details',
        'email',
        'specialties',
        'diploma',
        'blocked',
        'blocked_reasons',
        'status',
        'affiliated_at',
    ];

    protected $casts = [
        'affiliated_at' => 'datetime',
        'blocked' => 'boolean',
        'identity_card_details' => 'array',
        'specialties' => 'array',
        'diploma' => 'array',
    ];

    protected $hidden = [
        'identity_card_details',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            
            
        });

        static::created(function ($model) {
            
        });
    }


    // ─── Relations ────────────────────────────────────────────────────

    /**
     * Get the user account associated with this teacher.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all yearly subjects for this teacher.
     */
    public function yearlySubjects(): HasMany
    {
        return $this->hasMany(TeacherYearlySubject::class, 'teacher_id');
    }

    /**
     * Get all yearly accesses for this teacher.
     */
    public function yearlyAccesses(): HasMany
    {
        return $this->hasMany(TeacherYearlyAccess::class, 'teacher_id');
    }

    /**
     * Get the yearly access for a specific school year.
     */
    public function accessForYear(int $schoolYearId): HasMany
    {
        return $this->yearlyAccesses()->where('school_year_id', $schoolYearId)->where('is_active', true);
    }

    /**
     * Get all classes where this teacher is the principal.
     */
    public function principalClasses(): HasMany
    {
        return $this->hasMany(Classe::class, 'principal_id');
    }


    public function getClassesWhereIsPrincipal(?int $school_year_id = null)
    {
        if(!$school_year_id) $school_year_id = SchoolYear::current()?->first()?->id;

        return Classe::where('principal_id', $this->id)->where('school_year_id', $school_year_id)->where('is_active', true)->get();
    }

    /**
     * Get all classe-subject assignments for this teacher.
     */
    public function classeSubjects(): HasMany
    {
        return $this->hasMany(ClasseSubjectOfSchoolYear::class, 'teacher_id');
    }

    /**
     * Get all classe-subject assignments in the classe for this teacher.
     */
    public function getSubjectsForThisClasse(int $classe_id, ?int $school_year_id = null)
    {
        if(!$school_year_id) $school_year_id = SchoolYear::current()?->first()?->id;

        return ClasseSubjectOfSchoolYear::where('teacher_id', $this->id)->where('school_year_id', $school_year_id)->where('classe_id', $classe_id)->where('is_active', true)->whereNull('ended_at')->get();
    }


    /**
     * Get all classe assignments for this year.
     */
    public function getTeacherClassesForThisSchoolYear(?array $excepts = [], ?int $school_year_id = null)
    {
        if(!$school_year_id) $school_year_id = SchoolYear::current()?->first()?->id;

        $relations = ClasseSubjectOfSchoolYear::where('teacher_id', $this->id)->where('school_year_id', $school_year_id)->where('is_active', true)->whereNull('ended_at')->pluck('classe_id')->toArray();

        if(count($relations)){

            return Classe::whereIn('id', $relations)->whereNotIn('id', $excepts)->get();

        } 

        return [];
    }

    public function getTeacherClassesCountForThisSchoolYear(?array $excepts = [], ?int $school_year_id = null)
    {
        if(!$school_year_id) $school_year_id = SchoolYear::current()?->first()?->id;

        return ClasseSubjectOfSchoolYear::where('teacher_id', $this->id)->where('school_year_id', $school_year_id)->where('is_active', true)->whereNull('ended_at')->distinct('classe_id')->count();
       
    }

    /**
     * Get all classe assignments for this year.
     */
    public function getTeacherClassesWithSubjectsForThisSchoolYear(?array $excepts = [], ?int $school_year_id = null)
    {
        if(!$school_year_id) $school_year_id = SchoolYear::current()?->first()?->id;

        return ClasseSubjectOfSchoolYear::with(['classe', 'subject'])->where('teacher_id', $this->id)->where('school_year_id', $school_year_id)->where('is_active', true)->whereNull('ended_at')->get();

    }


    public function ensureThatTeacherDoesntHaveClasseWithThisSubject(int $subjectId, ?int $school_year_id = null) : bool
    {
        if(!$school_year_id) $school_year_id = SchoolYear::current()?->first()?->id;

        $exists = ClasseSubjectOfSchoolYear::where('teacher_id', $this->id)->where('school_year_id', $school_year_id)->where('subject_id', $subjectId)->where('is_active', true)->whereNull('ended_at')->exists();

        return !$exists;

    }


    public function getYearlySubjects(?int $school_year_id = null)
    {
        if(!$school_year_id) $school_year_id = SchoolYear::current()?->first()?->id;

        return $this->yearlySubjects()->where('school_year_id', $school_year_id)->get();
    }

    /**
     * Get active classe-subject assignments for a specific school year.
     */
    public function activeClasseSubjectsByYear(int $schoolYearId): HasMany
    {
        return $this->classeSubjects()
            ->where('school_year_id', $schoolYearId)
            ->whereNull('ended_at');
    }
    
    public function cannotAccessIntoClasse(int $classe_id, ?int $school_year_id = null)
    {
        if(!$school_year_id) $school_year_id = SchoolYear::current()?->first()?->id;
        
        $classe = Classe::findOrFail($classe_id);

        $locked_for_teachers = $classe->locked_for_teachers;

        if(!$locked_for_teachers) return false;

        if(in_array($this->id, $locked_for_teachers)) return true;

        else return false;
    }


    public function canAccessIntoClasse(int $classe_id, ?int $school_year_id = null)
    {
        return !$this->cannotAccessIntoClasse($classe_id, $school_year_id);
    }

    /**
     * Get all marks entered by this teacher.
     */
    public function marks(): HasMany
    {
        return $this->hasMany(Mark::class, 'teacher_id');
    }

    /**
     * Get all presences recorded by this teacher.
     */
    public function presences(): HasMany
    {
        return $this->hasMany(Presence::class, 'teacher_id');
    }

    // ─── Scopes ───────────────────────────────────────────────────────

    /**
     * Scope to get only active teachers.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get only blocked teachers.
     */
    public function scopeBlocked(Builder $query): Builder
    {
        return $query->where('blocked', true);
    }

    /**
     * Scope to get only non-blocked teachers.
     */
    public function scopeNotBlocked(Builder $query): Builder
    {
        return $query->where('blocked', false);
    }

    // ─── Helpers ──────────────────────────────────────────────────────

    /**
     * Check if the teacher is active and not blocked.
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && ! $this->blocked;
    }

    /**
     * Check if the teacher is blocked.
     */
    public function isBlocked(): bool
    {
        return $this->blocked;
    }

    /**
     * Get the full name of the teacher.
     */
    public function fullName(): string
    {
        return "{$this->name} {$this->prenames}";
    }

    /**
     * Check if the teacher has a valid access for a given school year.
     */
    public function hasValidAccessForYear(?int $schoolYearId = null): bool
    {
        if(!$schoolYearId) $schoolYearId = SchoolYear::where('is_active', true)->where('is_closed', false)->first()?->id;
        return $this->yearlyAccesses()
            ->where('school_year_id', $schoolYearId)
            ->where('status', 'active')
            ->exists();
    }

    /**
     * Check if the teacher can renew their token for a given school year.
     */
    public function canRenewTokenForYear(int $schoolYearId): bool
    {
        $access = $this->yearlyAccesses()
            ->where('school_year_id', $schoolYearId)
            ->first();

        if (! $access) {
            return false;
        }

        return ! $access->isSuspended() && $this->isActive();
    }


    public function getFullName(bool $reverse = false)
    {
        return $this->user?->getFullName($reverse);
    }

    public function getUserNamePrefix(bool $withFullName = false, bool $reverseName = false)
    {
        return $this->user?->getUserNamePrefix($withFullName, $reverseName);
    }

    public function greating(bool $withFullName = true, bool $reverse = false)
    {
        return $this->user?->greating($withFullName, $reverse);
    }


    public function profil_photo_url() 
    {
        return $this->user?->profil_photo_url;
    }


    public function giveAccessToTeacherForThisSchoolYear(?string $tenantId = null, ?SchoolYear $school_year = null, ?string $domain = null)
    {
        if(!$school_year) $school_year = SchoolYear::firstWhere('is_active', true);

        if($school_year) JobToCreateYearlyAccessForTeacher::dispatch($tenantId, $this->id, $school_year->id, $domain);

        else return User::first()?->notify(new RealTimeNotification(
                    userEmail: User::first()?->email,
                    tenantId:  $this->tenantId,
                    title:     "ECHEC DE LA CREATION D'ACCES ENSEIGNANT ",
                    message:   "Aucune année scolaire active!",
                    type:      'error',
                ));

    }
    
    
    public function removeTeacherAccessForThisSchoolYear(?int $school_year_id)
    {
        if($this->hasValidAccessForYear($school_year_id)){

            if(!$school_year_id) $school_year_id = SchoolYear::where('is_active', true)->where('is_closed', false)->first()?->id;

                return $this->yearlyAccesses()
                    ->where('school_year_id', $school_year_id)
                    ->where('status', 'active')
                    ?->delete();
        }

        else{

            return User::first()?->notify(new RealTimeNotification(
                    userEmail: User::first()?->email,
                    tenantId:  $this->tenantId,
                    title:     "ECHEC DE LA REVOCATION D'ACCES ENSEIGNANT ",
                    message:   "Aucune année scolaire active!",
                    type:      'error',
                ));

        } 

    }
}
