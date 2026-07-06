<?php

namespace App\Livewire\Tenants\ActionsTraits;

use App\Events\DataUpdatedEvent;
use App\Events\InitProcessToGrantYearlyAccessToTeachersEvent;
use App\Jobs\JobBulkerActionsOnModels;
use App\Jobs\JobToSendCredentialsToUser;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\User;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

trait TutorsActions{


	use WithPagination, WireUiActions;
    
    public $counterl = 3;

    public ?SchoolYear $current_active_year;

    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata()
    {
        $this->counterl++;
    }

    
    // ─── Actions individuelles ─────────────────────────────────────────

    public function desactivateTutor(int $tutorId): void
    {
        $this->dispatch('swal', [
            'title'              => 'Désactiver|Bloquer ce parent ?',
            'text'               => 'Ce parent n\'aura plus accès à son espace.',
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, désactiver',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmTutorDesactivation',
            'onConfirmedParams'  => ['tutorId' => $tutorId],
        ]);
    }

    #[On('ConfirmTutorDesactivation')]
    public function onConfirmTutorDesactivation(int $tutorId): void
    {
        $tutor = Tutor::find($tutorId);

        if (!$tutor) {
            $this->notification()->error(title: 'Parent introuvable');
            return;
        }

        $tutor->update(['is_active' => false]);

        // broadcast(new TeacherWasBlockedEvent(tenant('id'), $tutor->id));

        $this->notification()->success(
            title: 'Compte parent désactivé',
            description: "Le compte parent {$tutor->getFullName()} a été désactivé.",
        );

        broadcast(new DataUpdatedEvent(tenant('id')));
    }

   
    public function activateTutor(int $tutorId): void
    {
        $this->dispatch('swal', [
            'title'              => 'Réactiver le compte parent ?',
            'text'               => 'Le parent retrouvera l\'accès à son espace.',
            'icon'               => 'question',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, Réactiver le compte parent',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#84cc16',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmTutorUnLocking',
            'onConfirmedParams'  => ['tutorId' => $tutorId],
        ]);
    }

    #[On('ConfirmTutorUnLocking')]
    public function OnConfirmTutorUnLocking(int $tutorId): void
    {
        $tutor = Tutor::find($tutorId);


        if (!$tutor) {
            $this->notification()->error(title: 'Parent introuvable');
            return;
        }

        $tutor->update(['blocked' => false]);

        $this->notification()->success(
            title: 'Parent débloqué',
            description: "Le compte Parent {$tutor->getFullName()} a été réactivé.",
        );

        broadcast(new DataUpdatedEvent(tenant('id')));
    }

    public function giveYearlyAccessToTutorForStudent(int $tutorId, int $studentId): void
    {
        $this->dispatch('swal', [
            'title'              => "Accorder l'accès au parent pour cet apprenant?",
            'text'               => "Dans ce cas ce parent aura désormais accès aux informations de cet apprenant depuis son espace parent",
            'icon'               => 'info',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, accorder',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#84cc16',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmGivingAccessToTutor',
            'onConfirmedParams'  => ['teacherId' => $tutorId, 'studentId' => $studentId],
        ]);
    }

    #[On('ConfirmGivingAccessToTutor')]
    public function OnConfirmGivingAccessToTutor(int $tutorId, int $studentId): void
    {
        $tutor = Tutor::whereId($tutorId)->first();

        $student = Student::whereId($studentId)->first();

        if (!$tutor || !$student) {
            $this->notification()->error(title: 'Parent ou Apprenant introuvable');
            return;
        }
        $tutor->giveYearlyAccessToTutorForStudent($studentId, tenant('id'), request()->getSchemeAndHttpHost());
        
		$this->notification()->success(
            title: 'Processus lancé...',
            description: "Le processus d'accès pour {$tutor->getFullName()} a été lancé.",
        );


        broadcast(new DataUpdatedEvent(tenant('id')));
    }


    public function removeTutorAccessForStudent(int $tutorId, int $studentId): void
    {
        $this->dispatch('swal', [
            'title'              => 'Révoquer l\'accès ?',
            'text'               => 'Le parent ne sera plus en mesure d\'avoir accès aux données de cet apprenant pour l\'année ' . ($this->activeYear?->slug ?? ''),
            'icon'               => 'info',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, retirer l\'accès ',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#84cc16',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToRemoveTutorAccess',
            'onConfirmedParams'  => ['teacherId' => $tutorId, 'studentId' => $studentId],
        ]);
    }

    #[On('ConfirmToRemoveTutorAccess')]
    public function OnConfirmToRemoveTutorAccess(int $tutorId, int $studentId): void
    {
        $tutor = Tutor::whereId($tutorId)->first();

        $student = Student::whereId($studentId)->first();

        if (!$tutor || !$student) {
            $this->notification()->error(title: 'Parent ou Apprenant introuvable');
            return;
        }
        $tutor->removeTutorYearlyAccessForStudent($studentId, tenant('id'), request()->getSchemeAndHttpHost());

        $this->notification()->success(
            title: 'Accès révoqué',
            description: "Le processus de suppression de l'accès du parent {$tutor->getFullName()} a été lancé.",
        );

        broadcast(new DataUpdatedEvent(tenant('id')));
    }

    public function deleteTutor(int $tutorId): void
    {
        $this->dispatch('swal', [
            'title'              => 'Envoyer à la corbeille ?',
            'text'               => 'Le parent n\'aura plus accès à son espace.',
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, corbeille',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#ef4444',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmTutorDeletion',
            'onConfirmedParams'  => ['teacherId' => $tutorId],
        ]);
    }

    #[On('ConfirmTutorDeletion')]
    public function OnConfirmTutorDeletion(int $tutorId): void
    {
        $tutor = Tutor::find($tutorId);
        
        if (!$tutor) {

            $this->notification()->error(title: 'parent introuvable');
            return;
        }

        $tutor->delete();

        $this->notification()->success(
            title: 'Parent mis en corbeille',
            description: "Le parent {$tutor->getFullName()} a été envoyé à la corbeille.",
        );

        broadcast(new DataUpdatedEvent(tenant('id')));
    }

    public function restoreTutor(int $tutorId): void
    {
        $this->dispatch('swal', [
            'title'              => 'Restorer ce parent ?',
            'text'               => 'Le parent retrouvera l\'accès à son espace.',
            'icon'               => 'question',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, restorer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#a855f7',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmTutorRestoration',
            'onConfirmedParams'  => ['teacherId' => $tutorId],
        ]);
    }

    #[On('ConfirmTutorRestoration')]
    public function OnConfirmTutorRestoration(int $tutorId): void
    {
        $tutor = Tutor::withTrashed()->whereId($tutorId)->first();

        if (!$tutor) {
            $this->notification()->error(title: 'Parent introuvable');
            return;
        }

        $tutor->restore();

        $this->notification()->success(
            title: 'Parent restoré',
            description: "Le compte du Parent {$tutor->getFullName()} a été restoré.",
        );

        broadcast(new DataUpdatedEvent(tenant('id')));
    }

    public function forceDeleteTutor(int $tutorId): void
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
            'onConfirmed'        => 'ConfirmToForceDeleteTutor',
            'onConfirmedParams'  => ['teacherId' => $tutorId],
        ]);
    }

    #[On('ConfirmToForceDeleteTutor')]
    public function OnConfirmToForceDeleteTutor(int $tutorId): void
    {
        $tutor = Tutor::withTrashed()->whereId($tutorId)->first();

        $tutorName = $tutor->getFullName() . ' (' . $tutor->user?->email .')';

        if (!$tutor) {
            $this->notification()->error(title: 'Parent introuvable');
            return;
        }

        if($tutor->created_at->gt(now()->subMonths(3))){

            $this->notification()->success(
                title: 'Suppression planifiée',
                description: "Effective dans 30 jours.",
            );
        }
        else{

            $tutor->forceDelete();

            $this->notification()->success(
            title: "Parent supprimé définitivement",
                description: "Le compte Parent " . $tutorName . " a été supprimé définitivement de la plateforme!",
            );

        }
        broadcast(new DataUpdatedEvent(tenant('id')));
    }

    // ─── Actions groupées ──────────────────────────────────────────────

    public function desactivateTutors(): void
    {
        $this->dispatch('swal', [
            'title'              => 'Désactiver tous les parents/tuteurs ?',
            'text'               => 'Tous les parents/tuteurs actifs n\'auront plus accès.',
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, Désactiver tous',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToDesactivateTutors',
        ]);
    }

    #[On('ConfirmToDesactivateTutors')]
    public function OnConfirmToDesactivateTutors(): void
    {
        $ids = Tutor::where('is_active', true)->pluck('id')->toArray();

        JobBulkerActionsOnModels::dispatch(
            tenantId: tenant('id'),
            model: Tutor::class,
            ids: $ids,
            method: 'update',
            options: ['is_active' => false],
            withTrashedDeleted: true,
            taskTitle: "DESACTIVATION DE COMPTES PARENTS/TUTEURS EN MASSE "
        );
        
        
        $this->notification()->success(
            title: 'Processus de désactivation en masse des parents/tuteurs lancé...',
            description: 'Processus en cours.',
        );
        broadcast(new DataUpdatedEvent(tenant('id')));
    }

    public function activateTutors(): void
    {
        $this->dispatch('swal', [
            'title'              => 'Réactiver tous les parents/tuteurs ?',
            'icon'               => 'question',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, Réactiver tous',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#84cc16',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToReactivateTutors',
        ]);
    }

    #[On('ConfirmToReactivateTutors')]
    public function OnConfirmToReactivateTutors(): void
    {
        $ids = Tutor::where('is_active', true)->pluck('id')->toArray();

        JobBulkerActionsOnModels::dispatch(
            tenantId: tenant('id'),
            model: Tutor::class,
            ids: $ids,
            method: 'update',
            options: ['is_active' => true],
            withTrashedDeleted: false,
            taskTitle: "REACTIVATION DE COMPTES PARENTS/TUTEURS EN MASSE"
        );

        $this->notification()->success(
            title: 'Processus de réactivation en masse de comptes parents/tuteurs lancé...',
            description: 'Processus en cours.',
        );

        broadcast(new DataUpdatedEvent(tenant('id')));
    }

    public function restoreTutors(): void
    {
        $this->dispatch('swal', [
            'title'              => 'Restorer tous les comptes parents/tuteurs ?',
            'icon'               => 'question',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, restorer tous',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#a855f7',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmTutorsRestoration',
        ]);
    }

    #[On('ConfirmTutorsRestoration')]
    public function OnConfirmTutorsRestoration(): void
    {
        $ids = Tutor::onlyTrashed()->pluck('id')->toArray();

        JobBulkerActionsOnModels::dispatch(
            tenantId: tenant('id'),
            model: Tutor::class,
            ids: $ids,
            method: 'restore',
            options: [],
            withTrashedDeleted: true,
            taskTitle: "RESTORATION EN MASSE DES COMPTES PARENTS/TUTORS EN CORBEILLE"
        );

        $this->notification()->success(
            title: 'Processus de restoration en masse des parents/tuteurs lancé...',
            description: 'Processus en cours.',
        );

        broadcast(new DataUpdatedEvent(tenant('id')));
    }

    public function forceDeleteTutors(): void
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
            'onConfirmed'        => 'ConfirmTutorsForceDelete',
        ]);
    }

    #[On('ConfirmTutorsForceDelete')]
    public function OnConfirmTutorsForceDelete()
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

        $ids = Tutor::onlyTrashed()->pluck('id')->toArray();

        JobBulkerActionsOnModels::dispatch(tenantId: tenant('id'),
            model: Tutor::class,
            ids: $ids,
            method: 'forceDelete',
            options: [],
            withTrashedDeleted: true,
            taskTitle: "SUPPRESSION DEFINITIVE EN MASSE DES COMPTEs PARENTS/TUTEURS"
        );

        $this->notification()->success(
            title: 'Suppression planifiée',
            description: 'Effective dans 30 jours.',
        );

        broadcast(new DataUpdatedEvent(tenant('id')));
    }

    public function sendCredentialsToTutor(string $userUuid)
	{
		$user = User::firstWhere('uuid', $userUuid);

        if($user && $user->logged_count < 1 ){

			$domain = request()->getSchemeAndHttpHost();

            $space_url = get_tenant_url($domain, 'login');

            JobToSendCredentialsToUser::dispatch(tenant('id'), $user->email, null, $space_url, "Parents/Tuteurs");

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


    
    
}