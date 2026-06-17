<?php

namespace App\Models;

use App\Models\User;
use App\Observers\ObserveNewTenantRequest;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;


#[ObservedBy(ObserveNewTenantRequest::class)]
class RequestToCreateNewTenant extends Model
{

    protected $connection = 'central';
    
    protected $fillable = [
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
        'school_devise',
        'types_devoirs', //devoir1-devoir2 ou devoir-compo
        'school_type', //Privé ou public
        'role',
        'job_name',
        'profil_photo',
        'validated',
        'department',
        'gender',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'email');
    }

    public function tenant_request()
    {
        return $this->hasOne(Tenant::class, 'request_id');
    }

    // ─── Scopes ───────────────────────────────────────────────────────

    /** requetes actives uniquement */
    public function scopeValidated(Builder $query): Builder
    {
        return $query->where('validated', true);
    }


    /** requetes en attente de validation */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    // ─── Helpers ──────────────────────────────────────────────────────

    /** Vérifie si la requête de l'école est active */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /** Vérifie si la requête de l'école est en attente */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /** Vérifie si la requête de l'école utilise les trimestres */
    public function usesTrimestres(): bool
    {
        return $this->type_periode === 'trimestre';
    }

    /** Vérifie si la requête de l'école utilise les semestres */
    public function usesSemestres(): bool
    {
        return $this->type_periode === 'semestre';
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

}
