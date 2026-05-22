<?php

namespace App\Models;

use App\Models\TenantModuleAccess;
use App\Models\TenantStatistic;
use Illuminate\Database\Eloquent\Builder;
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
            'id',
            'name',                    // Nom de l'école
            'slug',                    // Subdomain (ex: ecole-lumiere)
            'type_enseignement',       // general | technique | hybride
            'type_periode',            // semestre | trimestre
            'statut',                  // pending | active | suspended | cancelled
            'email',                   // Email de contact de l'école
            'contacts',               // Téléphone de l'école
            'adresse',                 // Adresse physique
            'logo',                    // Chemin du logo
            'date_expiration_abonnement',
            'devise',
            'types_devoirs', //devoir1-devoir2 ou devoir-compo
            'type_etablissement', //Privé ou public
            'domain_blocked',
            'open_only_for_tenant',
        ];
    }


    /**
     * Valeurs par défaut
     */
    protected $attributes = [
        'statut' => 'pending',
        'type_enseignement' => 'general',
        'type_periode' => 'semestre',
        'devise' => 'Votre dévise',
    ];

    /**
     * Casting des colonnes
     */
    protected $casts = [
        'date_expiration_abonnement' => 'datetime',
    ];

    // ─── Scopes ───────────────────────────────────────────────────────

    /** Écoles actives uniquement */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('statut', 'active');
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
        return $query->where('statut', 'pending');
    }

    // ─── Helpers ──────────────────────────────────────────────────────

    /** Vérifie si l'école est active */
    public function isActive(): bool
    {
        return $this->statut === 'active';
    }

    /** Vérifie si l'école est en attente */
    public function isPending(): bool
    {
        return $this->statut === 'pending';
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


}
