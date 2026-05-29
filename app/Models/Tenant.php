<?php

namespace App\Models;

use App\Models\TenantModuleAccess;
use App\Models\TenantStatistic;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;

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
            'gender',
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
    ];

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
        return $this->type_periode === 'trimestre';
    }

    /** Vérifie si l'école utilise les semestres */
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

    public function moduleAccess(): HasOne
    {
        return $this->hasOne(TenantModuleAccess::class, 'tenant_id');
    }

    public function statistics(): HasOne
    {
        return $this->hasOne(TenantStatistic::class, 'tenant_id');
    }


    public function getFullName()
    {
        return $this->name . ' ' . $this->prenames;
    }


}
