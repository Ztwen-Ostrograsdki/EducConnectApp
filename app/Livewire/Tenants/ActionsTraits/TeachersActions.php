<?php

namespace App\Livewire\Tenants\ActionsTraits;

use App\Events\DataUpdatedEvent;
use App\Events\InitProcessToGrantYearlyAccessToTeachersEvent;
use App\Events\TeacherWasBlockedEvent;
use App\Jobs\JobBulkerActionsOnModels;
use App\Jobs\JobToSendCredentialsToUser;
use App\Models\Classe;
use App\Models\SchoolYear;
use App\Models\Teacher;
use App\Models\User;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

trait TeachersActions{


	use WithPagination, WireUiActions;
    
    public $counter = 3;

    public ?SchoolYear $current_active_year;

    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata()
    {
        $this->counter++;
    }

    
    // ─── Actions individuelles ─────────────────────────────────────────

    public function lockTeacher(int $teacherId): void
    {
        $this->dispatch('swal', [
            'title'              => 'Bloquer cet enseignant ?',
            'text'               => 'L\'enseignant n\'aura plus accès à son espace.',
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, bloquer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmTeacherLocking',
            'onConfirmedParams'  => ['teacherId' => $teacherId],
        ]);
    }

    #[On('ConfirmTeacherLocking')]
    public function ConfirmTeacherLocking(int $teacherId): void
    {
        $teacher = Teacher::find($teacherId);

        if (!$teacher) {
            $this->notification()->error(title: 'Enseignant introuvable');
            return;
        }

        $teacher->update(['blocked' => true]);

        broadcast(new TeacherWasBlockedEvent(tenant('id'), $teacher->id));

        $this->notification()->success(
            title: 'Enseignant bloqué',
            description: "L'enseignant {$teacher->getFullName()} a été bloqué.",
        );

        broadcast(new DataUpdatedEvent(tenant('id')));
    }

    public function lockAccessToClasse(int $teacherId, int $classeId): void
    {
        $this->dispatch('swal', [
            'title'              => "Bloquer l'accès de cet enseignant à la classe?",
            'text'               =>"L'enseignant n'aura plus accès à cette classe.",
            'icon'               => 'question',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, Bloquer son accès',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#84cc16',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToLockTeacherAccessToClasse',
            'onConfirmedParams'  => ['teacherId' => $teacherId, 'classeId' => $classeId],
        ]);
    }

    #[On('ConfirmToLockTeacherAccessToClasse')]
    public function OnConfirmToLockTeacherAccessToClasse(int $teacherId, int $classeId): void
    {
        $teacher = Teacher::find($teacherId);

        $classe = Classe::find($classeId);

        if (!$teacher) {
            $this->notification()->error(title: 'Enseignant introuvable');
            return;
        }

        if (!$classe) {
            $this->notification()->error(title: 'Classe introuvable');
            return;
        }

        try {
            if($teacher->canAccessIntoClasse($classe->id)){

                $locked_for_teachers = $classe->locked_for_teachers;

                $locked_for_teachers[$teacherId] = $teacherId;

                $classe->update(['locked_for_teachers' => $locked_for_teachers]);

            }

            $this->notification()->success(
                title: "L'accès de l'enseignant à été bloqué",
                description: "{$teacher->getFullName()} n'a plus accès à la classe " . $classe->name,
            );

            broadcast(new DataUpdatedEvent(tenant('id')));

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: "L'accès de l'enseignant {$teacher?->getFullName()} à la classe {$classe->name} n'a pas pu être résolu ",
                description: "Raisons : " . cutter($th->getMessage(), 150),
            );
        }
    }

    public function unLockAccessToClasse(int $teacherId, int $classeId): void
    {
        $this->dispatch('swal', [
            'title'              => "Autoriser l'accès de cet enseignant à la classe?",
            'text'               =>"L'enseignant aura de nouveau accès à cette classe.",
            'icon'               => 'question',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, Autoriser son accès',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#84cc16',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'UnlockAccessToClasse',
            'onConfirmedParams'  => ['teacherId' => $teacherId, 'classeId' => $classeId],
        ]);
    }

    #[On('UnlockAccessToClasse')]
    public function OnUnlockAccessToClasse(int $teacherId, int $classeId): void
    {
        $teacher = Teacher::find($teacherId);

        $classe = Classe::find($classeId);

        if (!$teacher) {
            $this->notification()->error(title: 'Enseignant introuvable');
            return;
        }

        if (!$classe) {
            $this->notification()->error(title: 'Classe introuvable');
            return;
        }

        try {
            if($teacher->cannotAccessIntoClasse($classe->id)){

                $locked_for_teachers = $classe->locked_for_teachers;

                unset($locked_for_teachers[$teacherId]);

                array_values($locked_for_teachers);

                $classe->update(['locked_for_teachers' => $locked_for_teachers]);

                broadcast(new DataUpdatedEvent(tenant('id')));

                $this->notification()->success(
                    title: "L'accès de l'enseignant à été autorisé",
                    description: "L'enseignant {$teacher->getFullName()} a à présent accès à la classe " . $classe->name,
                );

            }

            

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: "L'accès de l'enseignant {$teacher?->getFullName()} à la classe {$classe->name} n'a pas pu être résolu ",
                description: "Raisons : " . cutter($th->getMessage(), 150),
            );
        }
    }

    public function unlockTeacher(int $teacherId): void
    {
        $this->dispatch('swal', [
            'title'              => 'Débloquer cet enseignant ?',
            'text'               => 'L\'enseignant retrouvera accès à son espace.',
            'icon'               => 'question',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, débloquer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#84cc16',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmTeacherUnLocking',
            'onConfirmedParams'  => ['teacherId' => $teacherId],
        ]);
    }

    #[On('ConfirmTeacherUnLocking')]
    public function OnConfirmTeacherUnLocking(int $teacherId): void
    {
        $teacher = Teacher::find($teacherId);


        if (!$teacher) {
            $this->notification()->error(title: 'Enseignant introuvable');
            return;
        }

        $teacher->update(['blocked' => false]);

        $this->notification()->success(
            title: 'Enseignant débloqué',
            description: "L'enseignant {$teacher->getFullName()} a été débloqué.",
        );

        broadcast(new DataUpdatedEvent(tenant('id')));
    }

    public function giveAccessForThisSchoolYear(int $teacherId): void
    {
        $this->dispatch('swal', [
            'title'              => 'Accorder l\'accès ?',
            'text'               => 'L\'enseignant sera enrôlé pour l\'année ' . ($this->activeYear?->slug ?? ''),
            'icon'               => 'info',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, enrôler',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#84cc16',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmGivingAccessToTeacher',
            'onConfirmedParams'  => ['teacherId' => $teacherId],
        ]);
    }

    #[On('ConfirmGivingAccessToTeacher')]
    public function OnConfirmGivingAccessToTeacher(int $teacherId): void
    {
        $teacher = Teacher::whereId($teacherId)->first();

        if (!$teacher) {
            $this->notification()->error(title: 'Enseignant introuvable');
            return;
        }
        $teacher->giveAccessToTeacherForThisSchoolYear(tenant('id'), null, request()->getSchemeAndHttpHost());
        $this->notification()->success(
            title: 'Accès accordé',
            description: "Le processus d'accès pour {$teacher->getFullName()} a été lancé.",
        );


        broadcast(new DataUpdatedEvent(tenant('id')));
    }


    public function removeAccessForThisSchoolYear(int $teacherId): void
    {
        $this->dispatch('swal', [
            'title'              => 'Révoquer l\'accès ?',
            'text'               => 'L\'enseignant ne sera plus enrôlé pour l\'année ' . ($this->activeYear?->slug ?? ''),
            'icon'               => 'info',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, retirer l\'accès ',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#84cc16',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToRemoveAccessToTeacher',
            'onConfirmedParams'  => ['teacherId' => $teacherId],
        ]);
    }

    #[On('ConfirmToRemoveAccessToTeacher')]
    public function OnConfirmToRemoveAccessToTeacher(int $teacherId): void
    {
        $teacher = Teacher::whereId($teacherId)->first();

        if (!$teacher) {
            $this->notification()->error(title: 'Enseignant introuvable');
            return;
        }

        //Assert if teacher don't have classes before

        if(!$teacher->ensureThatTeacherDoesntHaveClasse()){

            $this->notification()->send([
                'icon'        => 'warning',
                'timeout' => 0,
                'title'       => "Vous ne pouvez pas supprimer l'accès de cet enseignant!",
                'description' => $teacher->getFullName() . " enseigne dans au moins une classe. Pour supprimer l'accès de cet enseigant, vous devez d'abord lui retirer toutes ses classes !",
            ]);


            return;

        }

        $teacher->removeTeacherAccessForThisSchoolYear(tenant('id'), null, request()->getSchemeAndHttpHost());
        $this->notification()->success(
            title: 'Accès révoqué',
            description: "Le processus de suppression d'accès pour l'enseignant {$teacher->getFullName()} a été lancé.",
        );

        broadcast(new DataUpdatedEvent(tenant('id')));
    }

    public function deleteTeacher(int $teacherId): void
    {
        $this->dispatch('swal', [
            'title'              => 'Envoyer à la corbeille ?',
            'text'               => 'L\'enseignant n\'aura plus accès à son espace.',
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, corbeille',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#ef4444',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmTeacherDeletion',
            'onConfirmedParams'  => ['teacherId' => $teacherId],
        ]);
    }

    #[On('ConfirmTeacherDeletion')]
    public function OnConfirmTeacherDeletion(int $teacherId): void
    {
        $teacher = Teacher::find($teacherId);
        
        if (!$teacher) {

            $this->notification()->error(title: 'Enseignant introuvable');
            return;
        }

        //Assert if teacher don't have classes before

        if(!$teacher->ensureThatTeacherDoesntHaveClasse()){

            $this->notification()->send([
                'icon'        => 'warning',
                'timeout' => 0,
                'title'       => "Vous ne pouvez pas supprimer cet enseignant!",
                'description' => $teacher->getFullName() . " enseigne dans au moins une classe. Pour supprimer cet enseigant, vous devez d'abord lui retirer toutes ses classes !",
            ]);


            return;

        }


        $teacher->delete();

        $this->notification()->success(
            title: 'Enseignant mis en corbeille',
            description: "L'enseignant {$teacher->getFullName()} a été envoyé à la corbeille.",
        );

        broadcast(new DataUpdatedEvent(tenant('id')));
    }

    public function restoreTeacher(int $teacherId): void
    {
        $this->dispatch('swal', [
            'title'              => 'Restaurer cet enseignant ?',
            'text'               => 'L\'enseignant retrouvera accès à son espace.',
            'icon'               => 'question',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, restaurer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#a855f7',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmTeacherRestoration',
            'onConfirmedParams'  => ['teacherId' => $teacherId],
        ]);
    }

    #[On('ConfirmTeacherRestoration')]
    public function OnConfirmTeacherRestoration(int $teacherId): void
    {
        $teacher = Teacher::withTrashed()->whereId($teacherId)->first();

        if (!$teacher) {
            $this->notification()->error(title: 'Enseignant introuvable');
            return;
        }
        $teacher->restore();
        $this->notification()->success(
            title: 'Enseignant restauré',
            description: "L'enseignant {$teacher->getFullName()} a été restauré.",
        );

        broadcast(new DataUpdatedEvent(tenant('id')));
    }

    public function forceDeleteTeacher(int $teacherId): void
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
            'onConfirmed'        => 'ConfirmTeacherForceDelete',
            'onConfirmedParams'  => ['teacherId' => $teacherId],
        ]);
    }

    #[On('ConfirmTeacherForceDelete')]
    public function OnConfirmTeacherForceDelete(int $teacherId): void
    {
        $teacher = Teacher::withTrashed()->whereId($teacherId)->first();

        $teacherName = $teacher->getFullName() . ' (' . $teacher->user?->email .')';

        if (!$teacher) {
            $this->notification()->error(title: 'Enseignant introuvable');
            return;
        }

        if(!$teacher->ensureThatTeacherDoesntHaveClasse()){

            $this->notification()->send([
                'icon'        => 'warning',
                'timeout' => 0,
                'title'       => "Vous ne pouvez pas supprimer cet enseignant!",
                'description' => $teacher->getFullName() . " enseigne dans au moins une classe. Pour supprimer cet enseigant, vous devez d'abord lui retirer toutes ses classes !",
            ]);


            return;

        }

        if($teacher->created_at->gt(now()->subMonths(3))){

            $this->notification()->success(
                title: 'Suppression planifiée',
                description: "Effective dans 30 jours.",
            );
        }
        else{
            $teacher->forceDelete();

            $this->notification()->success(
            title: "Enseignant supprimé définitivement",
                description: "L'enseignant " . $teacherName . " a été supprimé définitivement de la plateforme!",
            );

        }
        broadcast(new DataUpdatedEvent(tenant('id')));
    }

    // ─── Actions groupées ──────────────────────────────────────────────

    public function lockTeachers(): void
    {
        $this->dispatch('swal', [
            'title'              => 'Bloquer tous les enseignants ?',
            'text'               => 'Tous les enseignants actifs n\'auront plus accès.',
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, bloquer tous',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmTeachersLocking',
        ]);
    }

    #[On('ConfirmTeachersLocking')]
    public function OnConfirmTeachersLocking(): void
    {
        $ids = Teacher::where('blocked', false)->pluck('id')->toArray();

        JobBulkerActionsOnModels::dispatch(
            tenantId: tenant('id'),
            model: Teacher::class,
            ids: $ids,
            method: 'update',
            options: ['blocked' => true],
            withTrashedDeleted: true,
            taskTitle: "BLOCAGE EN MASSE DES ENSEIGNANTS"
        );
        
        
        $this->notification()->success(
            title: 'Enseignants bloqués',
            description: 'Tous les enseignants ont été bloqués.',
        );


        broadcast(new DataUpdatedEvent(tenant('id')));
    }

    public function unlockTeachers(): void
    {
        $this->dispatch('swal', [
            'title'              => 'Débloquer tous les enseignants ?',
            'icon'               => 'question',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, débloquer tous',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#84cc16',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmTeachersUnLocking',
        ]);
    }

    #[On('ConfirmTeachersUnLocking')]
    public function OnConfirmTeachersUnLocking(): void
    {
        $ids = Teacher::where('blocked', true)->pluck('id')->toArray();

        JobBulkerActionsOnModels::dispatch(
            tenantId: tenant('id'),
            model: Teacher::class,
            ids: $ids,
            method: 'update',
            options: ['blocked' => false],
            withTrashedDeleted: false,
            taskTitle: "DEBLOCAGE EN MASSE DES ENSEIGNANTS"
        );

        $this->notification()->success(
            title: 'Enseignants débloqués',
            description: 'Tous les enseignants ont été débloqués.',
        );

        broadcast(new DataUpdatedEvent(tenant('id')));
    }

    public function restoreTeachers(): void
    {
        $this->dispatch('swal', [
            'title'              => 'Restaurer tous les enseignants ?',
            'icon'               => 'question',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, restaurer tous',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#a855f7',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmTeacherRestoration',
        ]);
    }

    #[On('ConfirmTeachersRestoration')]
    public function OnConfirmTeachersRestoration(): void
    {
        $ids = Teacher::onlyTrashed()->pluck('id')->toArray();

        JobBulkerActionsOnModels::dispatch(
            tenantId: tenant('id'),
            model: Teacher::class,
            ids: $ids,
            method: 'restore',
            options: [],
            withTrashedDeleted: true,
            taskTitle: "RESTORATION EN MASSE DES ENSEIGNANTS EN CORBEILLE"
        );

        $this->notification()->success(
            title: 'Enseignants restaurés',
            description: 'Tous les enseignants ont été restaurés.',
        );

        broadcast(new DataUpdatedEvent(tenant('id')));
    }

    public function forceDeleteTeachers(): void
    {
        $this->dispatch('swal', [
            'title'              => 'Suppression définitive de tous ?',
            'text'               => 'Irréversible. Effective dans 30 jours.',
            'icon'               => 'error',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, supprimer déf. tous',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#ef4444',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmTeachersForceDelete',
        ]);
    }

    #[On('ConfirmTeachersForceDelete')]
    public function OnConfirmTeachersForceDelete()
    {
        $school_year = SchoolYear::current()->first();

        if(!$school_year){

            $this->notification()->send([
                'icon'        => 'error',
                'title'       => 'Erreur processus',
                'description' => "La reqûete ne peut aboutir car aucune année scolaire n'est active",
            ]);
             return;

        } 

        $ids = Teacher::onlyTrashed()
                        ->whereDoesntHave('classeSubjects', fn($q) => 
                            $q->where('school_year_id', $school_year->id)
                        ) 
                        ->pluck('id')
                        ->toArray();

        JobBulkerActionsOnModels::dispatch(tenantId: tenant('id'),
            model: Teacher::class,
            ids: $ids,
            method: 'forceDelete',
            options: [],
            withTrashedDeleted: true,
            taskTitle: "SUPPRESSION DEFINITIVE EN MASSE DES ENSEIGNANTS"
        );

        $this->notification()->success(
            title: 'Suppression planifiée',
            description: 'Effective dans 30 jours.',
        );

        broadcast(new DataUpdatedEvent(tenant('id')));
    }

    public function sendCredentialsToTeacher(string $userUuid)
	{
		$user = User::firstWhere('uuid', $userUuid);

        if($user && $user->logged_count < 1 ){

			$domain = request()->getSchemeAndHttpHost();

            $space_url = get_tenant_url($domain, 'login');

            JobToSendCredentialsToUser::dispatch(tenant('id'), $user->email, null, $space_url);

            $this->notification()->send([
                'icon'        => 'success',
                'title'       => "Envoi des données espace enseignant ",
                'description' => "Processus lancé ...",
            ]);
        }
        else{

            $this->notification()->send([
                'icon'        => 'error',
                'title'       => 'Erreur processus',
                'description' => "La reqûete n'existe pas ou n'a pas encore été validée!",
            ]);
        }
	}


    public function giveAccessesToTeachersForThisSchoolYear(): void
    {
        $this->dispatch('swal', [
            'title'              => "Accorder l'accès aux enseignants ?",
            'text'               => "Les enseignants indexés auront accès à leurs classes respectives pour l'année scolaire ". ($this->activeYear?->slug ?? ''),
            'icon'               => 'info',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, enrôler',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#84cc16',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmGivingAccessesToTeachers',
        ]);
    }

    #[On('ConfirmGivingAccessesToTeachers')]
    public function OnConfirmGivingAccessesToTeachers(): void
    {
        $domain = request()->getSchemeAndHttpHost();

        InitProcessToGrantYearlyAccessToTeachersEvent::dispatch(
            tenantId: tenant('id'),
            onlyForSubjects: null,
            excepts: null,
            school_year_id: null,
            domain: $domain
        );
       
        $this->notification()->success(
            title: "Création d'accès lancée",
            description: "Le processus de création d'accès lancé.",
        );

        broadcast(new DataUpdatedEvent(tenant('id')));
    }


    public function createTeachersFromExcelFile()
    {
        session()->put('showImportMode', true);
    }


    public function exportToExcelFile()
    {

    }
    
    
}