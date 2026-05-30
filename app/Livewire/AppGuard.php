<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class AppGuard extends Component
{
    use WireUiActions;

    public bool    $isTenant;
    public ?string $tenantId;
    public ?int    $userId;
    public string  $guard;

    public function mount(): void
    {
        $this->isTenant = tenant() !== null;
        $this->tenantId = $this->isTenant ? tenant('id') : null;
        $this->guard    = $this->isTenant ? 'tenant' : 'central';

        abort_unless(Auth::guard($this->guard)->check(), 403);

        $this->userId = Auth::guard($this->guard)->id();
    }

    public function getListeners(): array
    {
        if (! $this->isTenant) {
            return [
                'echo-private:central-admin,.tenant.created' => 'handleTenantCreated',
                'echo-private:central-admin,.any.error' => 'handleAnyError',
                'echo-private:central-admin,.tenant.credentials.failed' => 'handleTenantCredentialsFailded',
                'echo-private:central-admin,.tenant.roles.seed.failed' => 'handleTenantRolesSeedsFailded',
                'echo-private:central-admin,.tenant.blocked' => 'handleTenantBlockedAck',
                'echo-private:central-admin,.tenant.request.created' => 'handleNewTenantRequestCreated',
                'echo-private:central-admin,.tenant.init.errors' => 'handleSomeErrorsOccurWhenInitialyzeTenantSpace',
            ];
        }

        // Channels communs à tous les users du tenant
        $listeners = [
            "echo-private:tenant.{$this->tenantId},.tenant.blocked"      => 'handleBlocked',
            "echo-private:tenant.{$this->tenantId},.maintenance"          => 'handleMaintenance',
            // Channel personnel
            "echo-private:tenant.{$this->tenantId}.user.{$this->userId},.user.notification" => 'handlePersonalNotification',
        ];

        // Channels spécifiques au rôle
        /** @var User $user */
        $user = Auth::guard('tenant')->user();

        if ($user->hasRole('directeur')) {
            $listeners["echo-private:tenant.{$this->tenantId}.directeur,.paiement.recu"]       = 'handlePaiement';
            $listeners["echo-private:tenant.{$this->tenantId}.directeur,.enseignant.inscrit"]   = 'handleEnseignantInscrit';
            $listeners["echo-private:tenant.{$this->tenantId}.directeur,.tenant.roles.seed.failed"]   = 'handleRolesSeedsFailed';
        }

        if ($user->hasRole('enseignant')) {
            $listeners["echo-private:tenant.{$this->tenantId}.enseignant,.emploi.updated"]      = 'handleEmploiUpdated';
            $listeners["echo-private:tenant.{$this->tenantId}.enseignant,.absence.signalee"]    = 'handleAbsenceSignalee';
        }

        return $listeners;
    }

    // ── Handlers communs ──────────────────────────────────

    public function handleBlocked(): void
    {
        $this->notification()->send([
            'icon'        => 'error',
            'title'       => 'Compte suspendu',
            'description' => 'Votre accès a été révoqué.',
        ]);

        Auth::guard('tenant')->logout();
        // $this->redirect('/login', navigate: true);
    }

    public function handleMaintenance(array $event): void
    {
        $this->notification()->send([
            'icon'        => 'warning',
            'title'       => 'Maintenance prévue',
            'description' => $event['message'] ?? '',
        ]);
    }

    public function handlePersonalNotification(array $event): void
    {
        $this->notification()->send([
            'icon'        => 'info',
            'title'       => $event['title'] ?? 'Notification',
            'description' => $event['message'] ?? '',
        ]);
    }

    // ── Handlers directeur ────────────────────────────────

    public function handlePaiement(array $event): void
    {
        $this->notification()->send([
            'icon'        => 'success',
            'title'       => 'Paiement reçu',
            'description' => "Montant : {$event['montant']} FCFA",
        ]);
    }

    public function handleEnseignantInscrit(array $event): void
    {
        $this->notification()->send([
            'icon'        => 'success',
            'title'       => 'Nouvel enseignant',
            'description' => "{$event['name']} vient de s'inscrire.",
        ]);
    }
    
    public function handleRolesSeedsFailed(array $event): void
    {
        $this->notification()->send([
            'icon'        => 'error',
            'title'       => 'ECHEC MIGRATION ROLES - PERMISSIONS',
            'description' => "La migration des rôles et permission a échoué. Veuillez relancer la migration ou demande de l'aide au technicien de la plateforme!",
        ]);
    }

    // ── Handlers enseignant ───────────────────────────────

    public function handleEmploiUpdated(array $event): void
    {
        $this->notification()->send([
            'icon'        => 'info',
            'title'       => 'Emploi du temps modifié',
            'description' => $event['message'] ?? '',
        ]);
    }

    public function handleAbsenceSignalee(array $event): void
    {
        $this->notification()->send([
            'icon'        => 'warning',
            'title'       => 'Absence signalée',
            'description' => $event['message'] ?? '',
        ]);
    }

    // ── Handlers central ──────────────────────────────────

    public function handleTenantCreated(array $event): void
    {
        $this->notification()->send([
            'icon'        => 'success',
            'title'       => 'Nouvelle école inscrite',
            'description' => "Une nouvelle école a été créée",
        ]);
        $this->dispatch('LiveReloadDashboardEvent');
    }
    
    
    public function handleAnyError(array $event): void
    {
        $this->notification()->send([
            'icon'        => 'negative',
            'title'       => $event['target'] ? $event['target'] : "Une erreure est survenue!",
            'timeout' => 0,
            'description' => $event['error'],
        ]);
    }
    
    public function handleTenantCredentialsFailded(array $event): void
    {
        $this->notification()->send([
            'icon'        => 'success',
            'title'       => "Echec d'envoie par mail",
            'timeout' => 0,
            'description' => "L'envoi des données par mail au tenant " . $event['tenant'] . " a echoué! Les raisons : " . $event['error'],
        ]);
    }
    
    
    public function handleTenantRolesSeedsFailded(array $event): void
    {
        $this->notification()->send([
            'icon'        => 'success',
            'title'       => "ECHEC MIGRATIONS: ROLES ET PERMISSIONS",
            'timeout' => 0,
            'description' => "La migration des rôles et permissions dans la base de données du tenant : " . $event['tenant'] . ' a échoué. Les raisons : ' . $event['error'],
        ]);
        $this->dispatch('LiveReloadDashboardEvent');
    }
    
    public function handleSomeErrorsOccurWhenInitialyzeTenantSpace(array $event): void
    {
        $this->notification()->send([
            'icon'        => 'negative',
            'title'       => "ECHEC INITIALISATION DU TENANT",
            'timeout' => 0,
            'description' => "L'initialisation du tenant : " . $event['tenant'] . ' a échoué. Les raisons : ' . $event['error'],
        ]);

        $this->dispatch('LiveReloadDashboardEvent');
    }

    public function handleTenantBlockedAck(array $event): void
    {
        $this->notification()->send([
            'icon'        => 'warning',
            'title'       => 'École bloquée',
            'description' => "Tenant #{$event['tenant_id']} déconnecté.",
        ]);
    }
    
    public function handleNewTenantRequestCreated(array $event): void
    {
        $full_name = $event['name'] . ' ' . $event['prenames'];

        $email = $event['email'];

        $school_name = $event['school_name'];

        $this->notification()->send([
            'icon'        => 'success',
            'title'       => 'Nouvelle demande reçue',
            'description' => "Mr/Mme {$full_name} vient de faire une demande pour son école {$school_name} sous l'adresse mail {$email}.",
        ]);

        $this->dispatch('LiveNewTenantRequestCreatedEvent', $email);
    }

    public function render(): View
    {
        return view('livewire.app-guard');
    }
}