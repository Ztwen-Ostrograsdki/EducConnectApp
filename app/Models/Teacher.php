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
        return $this->yearlyAccesses()->where('school_year_id', $schoolYearId);
    }

    /**
     * Get all classes where this teacher is the principal.
     */
    public function principalClasses(): HasMany
    {
        return $this->hasMany(Classe::class, 'principal_id');
    }

    /**
     * Get all classe-subject assignments for this teacher.
     */
    public function classeSubjects(): HasMany
    {
        return $this->hasMany(ClasseSubjectOfSchoolYear::class, 'teacher_id');
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
        return $this->user->getFullName($reverse);
    }


    public function profil_photo_url() 
    {
        return $this->user->profil_photo_url;
    }


    public function giveAccessForThisSchoolYear(?string $tenantId = null, ?SchoolYear $school_year = null, ?string $domain = null)
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
    
    
    public function revokAccessForThisSchoolYear(?int $school_year_id)
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
