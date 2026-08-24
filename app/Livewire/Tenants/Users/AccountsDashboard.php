<?php

namespace App\Livewire\Tenants\Users;

use App\Events\DataUpdatedEvent;
use App\Events\InitProcessToCreateOrUpdateModelWithRoleEvent;
use App\Events\InitProcessToDestroyUserRoleEvent;
use App\Events\UserAccountWasBlockedEvent;
use App\Models\SchoolYear;
use App\Models\User;
use App\Services\DashboardCounterService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use WireUi\Traits\WireUiActions;


#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title('Portails de tous les comptes')]
class AccountsDashboard extends Component
{
    use WithPagination, WireUiActions;

    #[Url(history: false)]
    public string $search = '';

    #[Url(history: false)]
    public string $roleFilter = '';

    #[Url(history: false)]
    public string $statusFilter = '';

    public int $perPage = 15;

    public int $counter = 0;


    protected array $toggleableRoles = ['enseignant', 'tuteur'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingRoleFilter(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }


    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata()
    {
        $this->counter++;
    }


    #[Computed]
    public function activeYear(): ?SchoolYear
    {
        return SchoolYear::current()->first();
    }

    /**
     * ------------------------------------------------------------
     *  TOGGLE ROLE (enseignant / tuteur)
     * ------------------------------------------------------------
     */
    public function toggleRole(int $userId, string $role): void
    {
        if (!in_array($role, $this->toggleableRoles)) {
            return;
        }

        $user = User::find($userId);

        if (!$user) {
            $this->notification()->error(title: 'Utilisateur introuvable');
            return;
        }

        $hasRole = $user->hasRole($role);
        $roleLabel = ucfirst($role);

        $this->dispatch('swal', [
            'title'              => $hasRole
                ? "Retirer le rôle {$roleLabel} ?"
                : "Attribuer le rôle {$roleLabel} ?",
            'text'               => $hasRole
                ? "{$user->name} n'aura plus accès aux fonctionnalités liées à ce rôle."
                : "{$user->name} aura désormais accès aux fonctionnalités liées à ce rôle.",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => $hasRole ? 'Oui, retirer' : 'Oui, attribuer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => $hasRole ? '#ef4444' : '#22c55e',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToToggleRole',
            'onConfirmedParams'  => ['userId' => $userId, 'role' => $role],
        ]);
    }

    #[On('ConfirmToToggleRole')]
    public function OnToggleRole(int $userId, string $role): void
    {
        $user = User::find($userId);

        if (!$user) {
            $this->notification()->error(title: 'Utilisateur introuvable');
            return;
        }

        try {
            $hasRole = $user->hasRole($role);

            if ($hasRole) {

                InitProcessToDestroyUserRoleEvent::dispatch(
                    tenantId: tenant('id'),
                    role: $role,
                    userId: $userId,
                    data: [
                        'email' => $user->email,
                        'contacts' => $user->contacts,
                        'full_name' => $user->getFullName(),
                        'addresse' => $user->addresse,
                        'schoolYearSlug' => $this->activeYear->slug
                    ],
                );


            } else {

                InitProcessToCreateOrUpdateModelWithRoleEvent::dispatch(
                    tenantId: tenant('id'),
                    role: $role,
                    userId: $userId,
                    data: [
                        'email' => $user->email,
                        'contacts' => $user->contacts,
                        'full_name' => $user->getFullName(),
                        'addresse' => $user->addresse,
                    ],
                );

            }

            $this->notification()->success(
                title: $hasRole ? "Rôle retiré" : "Processus d'attribution du rôle {$role} lancé",
                description: "Le rôle " . ucfirst($role) . " a bien été " . ($hasRole ? 'retiré de' : 'attribué à') . " {$user->name}.",
            );

            broadcast(new DataUpdatedEvent(tenant('id')));

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Action échouée',
                description: "Une erreur est survenue : " . cutter($th->getMessage(), 200),
            );
        }
    }

    /**
     * ------------------------------------------------------------
     *  RETIRER TOUS LES RÔLES
     * ------------------------------------------------------------
     */
    public function removeAllRoles(int $userId): void
    {
        $user = User::find($userId);

        if (!$user) {
            $this->notification()->error(title: 'Utilisateur introuvable');
            return;
        }

        $this->dispatch('swal', [
            'title'              => "Retirer tous les rôles ?",
            'text'               => "{$user->name} perdra immédiatement tous ses rôles et les accès associés.",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, tout retirer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#ef4444',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToRemoveAllRoles',
            'onConfirmedParams'  => ['userId' => $userId],
        ]);
    }

    #[On('ConfirmToRemoveAllRoles')]
    public function OnRemoveAllRoles(int $userId): void
    {
        $user = User::find($userId);

        if (!$user) {
            $this->notification()->error(title: 'Utilisateur introuvable');
            return;
        }

        try {
            $user->syncRoles([]);

            $this->notification()->success(
                title: 'Rôles retirés',
                description: "Tous les rôles de {$user->name} ont été retirés.",
            );

            broadcast(new DataUpdatedEvent(tenant('id')));

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Action échouée',
                description: "Une erreur est survenue : " . cutter($th->getMessage(), 200),
            );
        }
    }

    /**
     * ------------------------------------------------------------
     *  BLOQUER / DÉBLOQUER
     * ------------------------------------------------------------
     */
    public function toggleBlocked(int $userId): void
    {
        $user = User::find($userId);

        if (!$user) {
            $this->notification()->error(title: 'Utilisateur introuvable');
            return;
        }

        $isBlocked = (bool) $user->blocked;

        $this->dispatch('swal', [
            'title'              => $isBlocked ? "Débloquer ce compte ?" : "Bloquer ce compte ?",
            'text'               => $isBlocked
                ? "{$user->name} pourra de nouveau se connecter à la plateforme."
                : "{$user->name} ne pourra plus se connecter à la plateforme.",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => $isBlocked ? 'Oui, débloquer' : 'Oui, bloquer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => $isBlocked ? '#22c55e' : '#ef4444',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToToggleBlocked',
            'onConfirmedParams'  => ['userId' => $userId],
        ]);
    }

    #[On('ConfirmToToggleBlocked')]
    public function OnToggleBlocked(int $userId): void
    {
        $user = User::find($userId);

        if (!$user) {
            $this->notification()->error(title: 'Utilisateur introuvable');
            return;
        }

        try {
            $done = $user->update(['blocked' => !$user->blocked]);

            if ($done) {

                if($user->blocked){

                    broadcast(new UserAccountWasBlockedEvent(tenant('id'), $user->id));
                }
                $this->notification()->success(
                    title: $user->blocked ? 'Compte bloqué' : 'Compte débloqué',
                    description: "Le compte de {$user->name} a été " . ($user->blocked ? 'bloqué' : 'débloqué') . ".",
                );

                broadcast(new DataUpdatedEvent(tenant('id')));
            } else {
                $this->notification()->error(
                    title: 'Action échouée',
                    description: "Une erreur est survenue, veuillez réessayer !",
                );
            }

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Action échouée',
                description: "Une erreur est survenue : " . cutter($th->getMessage(), 200),
            );
        }
    }

    /**
     * ------------------------------------------------------------
     *  STATISTIQUES
     * ------------------------------------------------------------
     */
    #[Computed]
    public function stats(): array
    {
        return app(DashboardCounterService::class)->getMany([
            'users',
            'users_tutors',
            'users_teachers',
            'teachers_in_classes',
            'users_blockeds',
            'users_without_roles',
        ]);
    }


    #[Computed]
    public function roles()
    {
        return Role::whereNotNull('name')->orderBy('name')->pluck('name')->toArray();
    }


    #[Computed]
    public function users()
    {
        return User::query()
            ->with('roles')
            ->whereDoesntHave('roles', fn ($q) => $q->where('name', 'directeur'))
            ->when($this->search, function ($q) {
                $q->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('prenames', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%")
                    ->orWhere('contacts', 'like', "%{$this->search}%");
                });
            })
            ->when($this->roleFilter, function ($q) {
                $q->whereHas('roles', fn ($r) => $r->where('name', $this->roleFilter));
            })
            ->when($this->statusFilter === 'blocked', fn ($q) => $q->where('blocked', true))
            ->when($this->statusFilter === 'active', fn ($q) => $q->where('blocked', false))
            ->orderBy('name')
            ->orderBy('prenames')
            ->paginate($this->perPage);
    }

    public function render()
    {
        

        return view('livewire.tenants.users.accounts-dashboard');
    }
}