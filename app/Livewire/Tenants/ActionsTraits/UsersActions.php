<?php

namespace App\Livewire\Tenants\ActionsTraits;

use App\Events\DataUpdatedEvent;
use App\Events\TeacherWasBlockedEvent;
use App\Events\UserAccountWasBlockedEvent;
use App\Models\User;
use Livewire\Attributes\On;
use WireUi\Traits\WireUiActions;

trait UsersActions{


	use WireUiActions;
    
    
    // ─── Actions individuelles ─────────────────────────────────────────

    public function lockUser(int $userId): void
    {
        $this->dispatch('swal', [
            'title'              => "Bloquer cet utilisateur ?",
            'text'               => "Cet utilisateur ne pourra plus se connecter à son espace.",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, bloquer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToBlockUser',
            'onConfirmedParams'  => ['userId' => $userId],
        ]);
    }

    #[On('ConfirmToBlockUser')]
    public function onConfirmToBlockUser(int $userId): void
    {
        $user = User::find($userId);

        if (!$user) {
            $this->notification()->error(title: 'Utilisateur introuvable');
            return;
        }

        $user->update(['blocked' => true]);

        broadcast(new UserAccountWasBlockedEvent(tenant('id'), $user->id));

        $this->notification()->success(
            title: 'Enseignant bloqué',
            description: "L'enseignant {$user->getFullName()} a été bloqué.",
        );

        broadcast(new DataUpdatedEvent(tenant('id')));
    }

    

    public function unlockUser(int $userId): void
    {
        $this->dispatch('swal', [
            'title'              => 'Débloquer cet utilisateur ?',
            'text'               => "L'utilisateur retrouvera l'accès à son espace.",
            'icon'               => 'question',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, débloquer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#84cc16',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToUnBlockUser',
            'onConfirmedParams'  => ['userId' => $userId],
        ]);
    }

    #[On('ConfirmToUnBlockUser')]
    public function OnConfirmToUnBlockUser(int $userId): void
    {
        $user = User::find($userId);


        if (!$user) {
            $this->notification()->error(title: 'Utilisateur introuvable');
            return;
        }

        $user->update(['blocked' => false]);

        $this->notification()->success(
            title: 'Enseignant débloqué',
            description: "L'enseignant {$user->getFullName()} a été débloqué.",
        );

        broadcast(new DataUpdatedEvent(tenant('id')));
    }


    public function deleteUser(int $userId): void
    {
        $this->dispatch('swal', [
            'title'              => 'Envoyer cet utilisateur à la corbeille ?',
            'text'               => "L'enseignant n'aura plus accès à son espace.",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, corbeille',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#ef4444',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToDeleteUser',
            'onConfirmedParams'  => ['userId' => $userId],
        ]);
    }

    #[On('ConfirmToDeleteUser')]
    public function OnConfirmToDeleteUser(int $userId): void
    {
        $user = User::find($userId);
        
        if (!$user) {

            $this->notification()->error(title: 'Utilisateur introuvable');
            return;
        }

        if($user->teacher && !$user->ensureThatTeacherDoesntHaveClasse()){

            $this->notification()->send([
                'icon'        => 'warning',
                'timeout' => 0,
                'title'       => "Vous ne pouvez pas supprimer cet enseignant!",
                'description' => $user->getFullName() . " enseigne dans au moins une classe. Pour supprimer cet enseigant, vous devez d'abord lui retirer toutes ses classes !",
            ]);


            return;

        }


        $user->delete();

        $this->notification()->success(
            title: 'Utilisateur mis en corbeille',
            description: "L'utilisateur {$user->getFullName()} a été envoyé à la corbeille.",
        );

        broadcast(new DataUpdatedEvent(tenant('id')));

        broadcast(new UserAccountWasBlockedEvent(tenant('id'), $userId));
    }

    public function restoreUser(int $userId): void
    {
        $this->dispatch('swal', [
            'title'              => 'Restorer cet enseignant ?',
            'text'               => 'L\'enseignant retrouvera accès à son espace.',
            'icon'               => 'question',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, restorer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#a855f7',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToRestoreUser',
            'onConfirmedParams'  => ['userId' => $userId],
        ]);
    }

    #[On('ConfirmToRestoreUser')]
    public function OnConfirmToRestoreUser(int $userId): void
    {
        $user = User::withTrashed()->whereId($userId)->first();

        if (!$user) {
            $this->notification()->error(title: 'Utilisateur introuvable');
            return;
        }
        $user->restore();
        $this->notification()->success(
            title: 'Compte utilisateur restoré',
            description: "Le compte de l'utilisateur {$user->getFullName()} a été restoré.",
        );

        broadcast(new DataUpdatedEvent(tenant('id')));
    }

    public function forceDeleteUser(int $userId): void
    {
        $this->dispatch('swal', [
            'title'              => 'Suppression définitive ?',
            'text'               => 'Cette action est irréversible. Elle sera effective dans 30 jours.',
            'icon'               => 'error',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, supprimer déf.',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#ef4444',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToForceDeleteUser',
            'onConfirmedParams'  => ['userId' => $userId],
        ]);
    }

    #[On('ConfirmToForceDeleteUser')]
    public function OnConfirmUserrForceDelete(int $userId): void
    {
        $user = User::withTrashed()->whereId($userId)->first();

        $userName = $user->getFullName() . ' (' . $user->email .')';

        if (!$user) {
            $this->notification()->error(title: 'Utilisateur introuvable');
            return;
        }

        if($user->teacher && !$user->ensureThatTeacherDoesntHaveClasse()){

            $this->notification()->send([
                'icon'        => 'warning',
                'timeout' => 0,
                'title'       => "Vous ne pouvez pas supprimer cet enseignant!",
                'description' => $user->getFullName() . " enseigne dans au moins une classe. Pour supprimer cet enseigant, vous devez d'abord lui retirer toutes ses classes !",
            ]);


            return;

        }

        if($user->created_at->gt(now()->subMonths(3))){

            $this->notification()->success(
                title: 'Suppression planifiée',
                description: "Effective dans 30 jours.",
            );
        }
        else{
            $user->forceDelete();

            $this->notification()->success(
            title: "Enseignant supprimé définitivement",
                description: "L'enseignant " . $userName . " a été supprimé définitivement de la plateforme!",
            );

        }
        broadcast(new DataUpdatedEvent(tenant('id')));
    }

}