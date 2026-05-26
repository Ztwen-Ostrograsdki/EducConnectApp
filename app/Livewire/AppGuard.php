<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Contracts\View\View;
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

        abort_unless(auth()->guard($this->guard)->check(), 403);

        $this->userId = auth()->guard($this->guard)->id();
    }

    public function getListeners(): array
    {
        if (! $this->isTenant) {
            return [
                'echo-private:central-admin,.tenant.created' => 'handleTenantCreated',
                'echo-private:central-admin,.tenant.blocked' => 'handleTenantBlockedAck',
                'echo-private:central-admin,.tenant.request.created' => 'handleNewTenantRequestCreated',
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
        $user = auth()->guard('tenant')->user();

        if ($user->hasRole('directeur')) {
            $listeners["echo-private:tenant.{$this->tenantId}.directeur,.paiement.recu"]       = 'handlePaiement';
            $listeners["echo-private:tenant.{$this->tenantId}.directeur,.enseignant.inscrit"]   = 'handleEnseignantInscrit';
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

        auth()->guard('tenant')->logout();
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
    }

    public function render(): View
    {
        return view('livewire.app-guard');
    }
}