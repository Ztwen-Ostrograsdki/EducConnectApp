<?php

namespace App\Models;

use App\Events\TenantForceDeleted;
use App\Helpers\ClasseHelpers;
use App\Models\Filiar;
use App\Models\Promotion;
use App\Models\RequestToCreateNewTenant;
use App\Models\SchoolYear;
use App\Models\Serial;
use App\Models\Subject;
use App\Models\TenantModuleAccess;
use App\Models\TenantStatistic;
use App\Models\User;
use App\Observers\ObserveTenant;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;


#[ObservedBy(ObserveTenant::class)]
class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains, SoftDeletes;


    protected $connection = 'central';

    /**
     * Colonnes personnalisées stockées dans la colonne JSON "data"
     * de la table tenants (fonctionnement natif de stancl/tenancy)
     */
    public static function getCustomColumns(): array
    {
        return [
            'uuid',
            'id',
            'simple_name',
            'domain_name',
            'name',               // Nom de Tenant
            'prenames',               // Prénoms tenant
            'school_name',               // Nom de l'école
            'school_slug',                    // Subdomain (ex: ecole-lumiere)
            'enseignement_type',       // general | technique | hybride
            'periode_type',            // semestre | trimestre
            'status',                  // pending | active | suspended | cancelled
            'email',                   // Email de contact de l'école
            'contacts',               // Téléphone de l'école
            'adresse',                 // Adresse physique
            'country',                 // Pays physique
            'city',                 // Ville physique
            'logo',                    // Chemin du logo
            'date_expiration_abonnement',
            'school_devise',
            'types_devoirs', //devoir1-devoir2 ou devoir-compo
            'school_type', //Privé ou public
            'domain_blocked',
            'open_only_for_tenant',
            'role',
            'job_name',
            'profil_photo',
            'request_id',
            'department',
            'birth_date',
            'gender',
            'completed',
            'stage',
        ];
    }

    /**
     * Valeurs par défaut
     */
    protected $attributes = [
        'status' => 'pending',
        'enseignement_type' => 'general',
        'school_type' => 'prive',
        'periode_type' => 'semestre',
        'school_devise' => 'Votre dévise',
        'country' => 'Bénin',
        'city' => 'Cotonou',
        'role' => 'directeur',
    ];

    /**
     * Casting des colonnes
     */
    protected $casts = [
        'date_expiration_abonnement' => 'datetime',
        'birth_date' => 'date',
        'completed' => 'boolean',
        'stage' => 'integer',
    ];

    /**
 * Override le nom de la DB pour remplacer les tirets par des underscores.
 * ex: "ecole-lumiere" → DB: "tenant_ecole_lumiere"
 */
    public function generateDatabaseName() : string
    {
        return config('tenancy.database.prefix') . str_replace('-', '_', $this->getTenantKey()) . config('tenancy.database.suffix');
    }

    public function getDomainName(): ?string
    {
        return $this->domains()->value('domain');
    }

    protected static function booted(): void
    {
        parent::booted();

        static::forceDeleted(function (self $tenant) {
            event(new TenantForceDeleted($tenant));
        });
    }

    public function user()
    {
        return $this->hasOne(User::class, 'email');
    }

    public function tenant_request()
    {
        return $this->belongsTo(RequestToCreateNewTenant::class, 'request_id');
    }

    // ─── Scopes ───────────────────────────────────────────────────────

    /** Écoles actives uniquement */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    /** Écoles accessibles aux enseigants, parents, eleves */
    public function scopeNotOnlyOpenForTenant(Builder $query): Builder
    {
        return $query->where('open_only_for_tenant', false);
    }

    /** Écoles accessibles  */
    public function scopeDomainIsOpen(Builder $query): Builder
    {
        return $query->where('domain_blocked', false);
    }

    /** Écoles en attente de validation */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    // ─── Helpers ──────────────────────────────────────────────────────

    /** Vérifie si l'école est active */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /** Vérifie si l'école est en attente */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /** Vérifie si l'école utilise les trimestres */
    public function usesTrimestres(): bool
    {
        return Str::lower($this->periode_type) === 'trimestre';
    }

    /** Vérifie si l'école utilise les semestres */
    public function usesSemestres(): bool
    {
        return Str::lower($this->periode_type) === 'semestre';
    }

    /** Nombre de périodes selon le choix */
    public function nombrePeriodes(): int
    {
        return $this->usesTrimestres() ? 3 : 2;
    }

    /** Label de la période */
    public function labelPeriode(): string
    {
        return $this->usesTrimestres() ? 'Trimestre' : 'Semestre';
    }

    public function moduleAccess(): HasOne
    {
        return $this->hasOne(TenantModuleAccess::class, 'tenant_id');
    }

    public function statistics(): HasOne
    {
        return $this->hasOne(TenantStatistic::class, 'tenant_id');
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


    /**
     * Get current school year .
     */
    public function getCurrentSchoolYear(): ?SchoolYear
    {
        if(session()->has('school_year_selected')){
            $school_year = SchoolYear::where('is_active', true)->whereSlug(session('school_year_selected'))?->first() ?? null;

        }
        $school_year = SchoolYear::where('is_active', true)->where('is_closed', false)?->first() ?? null;

        if($school_year) session()->put('school_year_selected', $school_year?->slug);
            
        return $school_year;
    }

    public function getActiveSchoolYear() : ?SchoolYear
    {
        $school_year = SchoolYear::where('is_active', true)->first();

        if($school_year) return $school_year;

        else return null;
    }
    
    
    public function schoolYearsCount() : ?int
    {
        return SchoolYear::whereNotNull('id')?->count();
    }
    
    public function promotions(?int $limit = null)
    {
        $query = Promotion::where('is_active', true)?->orderByDesc('name');

        if($limit) $query->take($limit);

        return $query->get();
    }

    public function promotionsCount() : ?int
    {
        return count($this->promotions());
    }

    public function filiars(?int $limit = null)
    {
        $query = Filiar::where('is_active', true)?->orderByDesc('name');

        if($limit) $query->take($limit);

        return $query->get();
    }
    
    public function filiarsCount() : ?int
    {
        return count($this->filiars());
    }

    public function serials(?int $limit = null)
    {
        $query = Serial::where('is_active', true)?->orderByDesc('name');

        if($limit) $query->take($limit);

        return $query->get();
    }

    public function serialsCount() : ?int
    {
        return count($this->serials());
    }


    public function subjects(?int $limit = null)
    {
        $query = Subject::where('is_active', true)?->orderByDesc('name');

        if($limit) $query->take($limit);

        return $query->get();
    }

    public function subjectsCount() : ?int
    {
        return count($this->subjects());
    }


    public function getSchoolYearClasses(?int $school_year_id = null, ?int $limit = null)
    {
        if(!$school_year_id) $school_year_id = $this->getActiveSchoolYear()?->id;

        if($school_year_id){

            $query = Classe::where('school_year_id', $school_year_id);

            if($limit) $query->take($limit);

            return $query->orderByDesc('name')->get();

        }

        return [];


    }

    public function getTeachersWithoutYearlyAccesses(?int $school_year_id = null)
    {
        $teachers = [];

        if(!$school_year_id) $school_year_id = $this->getActiveSchoolYear()?->id;

        $all_actives_teachers = Teacher::where('status', 'active')->get();

        foreach($all_actives_teachers as $teacher){

           if($teacher->yearlyAccesses()->where('school_year_id', $school_year_id)->whereStatus('active')->whereNull('suspended_at')->count() < 1){

                $teachers[] = $teacher;

           }
        }

        return $teachers;

    }
    
    public function getClassesOfSchoolYear(?SchoolYear $school_year = null, bool $must_has_promotion = true, bool $must_has_filiar = true)
    {
        $classes = [];


        if(!$school_year) $school_year = $this->getActiveSchoolYear();

        if($school_year && $must_has_filiar && $must_has_promotion){

            return $classes = Classe::where('is_active', true)->where('school_year_id', $school_year->id)->whereNotNull('promotion_id')->whereNotNull('filiar_id')->get();
        }

        elseif($school_year && $must_has_filiar){

            return $classes = Classe::where('is_active', true)->where('school_year_id', $school_year->id)->whereNotNull('filiar_id')->get();
        }
        elseif($school_year && $must_has_promotion){

            return $classes = Classe::where('is_active', true)->where('school_year_id', $school_year->id)->whereNotNull('promotion_id')->get();
        }
        elseif($must_has_filiar && $must_has_promotion){

            return $classes = Classe::where('is_active', true)->whereNotNull('promotion_id')->whereNotNull('filiar_id')->get();
        }

        return $classes;
    }


    public function promotionCanHasFiliarOrSerial(int $promotion_id) : bool
    {
        $promotion_name = Promotion::find($promotion_id)?->name;

        if($promotion_name){

            if(in_array(Str::lower($promotion_name), config('app.promotions_without_filiars_series'))) return false;

            else return true;

        }

        return false;
    }
    
    public function classeNameGenerator(?int $school_year_id, int $promotion_id, string $name) : string
    {
        $promotion_name = Promotion::find($promotion_id)?->name;

        if($promotion_name){

            $last_classe_with_same_name = Classe::where('school_year_id', $school_year_id)->where('name', $name)->count();

            if($last_classe_with_same_name){

                $name .= '-' . $last_classe_with_same_name + 1;
            }

        }

        return $name;
    }

    public function classeCodeGenerator(?int $school_year_id, int $promotion_id, string $first_name, ?string $suffix = '') : string
    {
        $promotion_name = Promotion::find($promotion_id)?->name;

        $occurence = null;

        if($promotion_name){

            $name = $this->classeNameGenerator($school_year_id, $promotion_id, $first_name);

            $last_classe_with_same_name = Classe::where('school_year_id', $school_year_id)->where('name', $first_name)->count();

            if($last_classe_with_same_name){

                $occurence = $last_classe_with_same_name + 1;
            }

            if($suffix) $suffix = '-' . $suffix;

            if($occurence) $occurence = '-' . $occurence;

            return Str::upper(ClasseHelpers::getClasseNameFormatted($name)['code']) . '' . $suffix . '' . $occurence ?? Str::initials($name, true);

        }

        return Str::initials($first_name, true);
    }







}
