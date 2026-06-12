<?php

namespace App\Livewire\Tenants\ActionsTraits;

use App\Events\TeacherWasBlockedEvent;
use App\Jobs\JobToSendCredentialsToUser;
use App\Models\Teacher;
use App\Models\User;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

trait TeachersActions{


	use WithPagination, WireUiActions;
    
    public $counter = 3;
    
    public string $search = '';

    public string $city = '';

    public string $gender = '';

    public string $department = '';

    public ?string $status = null;

    public int $perPage = 12;

    public $showConfirmDeleteTeacher = false;

    public $showConfirmTeacherRestorationModal = false;

    public $showConfirmForceDeleteTeacher = false;

    public $showConfirmTeacherLock = false;

    public $showConfirmTeacherUnLock = false;


    public $showConfirmDeleteTeachers = false;

    public $showConfirmTeachersRestorationModal = false;

    public $showConfirmForceDeleteTeachers = false;

    public $showConfirmTeachersLock = false;

    public $showConfirmTeachersUnLock = false;


    public ?string $targetedTeacherUserUuid = null;


	public function closeModal()
    {
        $this->reset('showConfirmDeleteTeacher', 'showConfirmTeacherRestorationModal', 'showConfirmForceDeleteTeacher', 'showConfirmTeacherLock', 'showConfirmDeleteTeachers', 'showConfirmTeachersRestorationModal', 'showConfirmForceDeleteTeachers', 'showConfirmTeachersLock', 'targetedTeacherUserUuid', 'showConfirmTeachersUnLock');
    }




    public function lockTeacher(string $userUuid): void
    {
        $this->showConfirmTeacherLock = true;

        $this->targetedTeacherUserUuid = $userUuid;

    }

    public function ConfirmTeacherLocking(): void
    {
       $user = User::whereUuid($this->targetedTeacherUserUuid)->firstOrFail();
        if($user){

            $locked = $user->teacher->update(['blocked' => true]);

            if($locked){

				(broadcast(new TeacherWasBlockedEvent(tenant('id'), $user->id)));
				
                $this->notification()->send([
                    'icon'        => 'success',
                    'title'       => 'Enseignant bloquée',
                    'description' => "L'Enseignant a été bloqué!",
                ]);
            }
            else{
                $this->notification()->send([
                    'icon'        => 'warning',
                    'title'       => 'Echec de la blocage',
                    'description' => "L'Enseignant n'a pas été bloqué!",
                ]);
            }

        }
        else{

            $this->notification()->send([
                'icon'        => 'error',
                'title'       => 'utilisateur introuvable',
                'description' => "L'utilisateur n'existe pas dans la base de données",
            ]);
            
        }
        $this->closeModal();
        
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


    public function unlockTeacher(string $userUuid): void
    {
        $this->showConfirmTeacherUnLock = true;

        $this->targetedTeacherUserUuid = $userUuid;

    }

    public function ConfirmTeacherUnLocking(): void
    {
        $user = User::whereUuid($this->targetedTeacherUserUuid)->firstOrFail();

        if($user){

            $locked = $user->teacher->update(['blocked' => false]);

            if($locked){
                $this->notification()->send([
                    'icon'        => 'success',
                    'title'       => 'Enseignant débloqué',
                    'description' => "L'enseignant a été débloqué!",
                ]);
            }
            else{
                $this->notification()->send([
                    'icon'        => 'warning',
                    'title'       => 'Echec de la déblocage',
                    'description' => "L'enseignant n'a pas été débloqué!",
                ]);
            }

        }
        else{

            $this->notification()->send([
                'icon'        => 'error',
                'title'       => 'utilisateur introuvable',
                'description' => "L'utilisateur n'existe pas dans la base de données",
            ]);
            
        }
        $this->closeModal();
        
    }



    public function deleteTeacher(string $userUuid): void
    {
        $this->showConfirmDeleteTeacher = true;

        $this->targetedTeacherUserUuid = $userUuid;

    }

    public function ConfirmTeacherDeletion(): void
    {
       $user = User::whereUuid($this->targetedTeacherUserUuid)->firstOrFail();
        if($user){

            $teacher = $user->teacher;

            if($teacher){

                $deleted = $teacher->delete();

                if($deleted){
                    $this->notification()->send([
                        'icon'        => 'success',
                        'title'       => 'enseignant envoyé dans la corbeille',
                        'description' => "L'enseignant a été envoyé dans la corbeille!",
                    ]);
                }
                else{
                    $this->notification()->send([
                        'icon'        => 'warning',
                        'title'       => 'Echec de la suppresion',
                        'description' => "L'Enseignant n'a pas été supprimé!",
                    ]);
                }
            }
            else{

                $this->notification()->send([
                    'icon'        => 'error',
                    'title'       => "Utilisateur non lié à un enseignant",
                    'description' => "Vérifiez la requête",
                ]);
                
            }

        }
        else{

            $this->notification()->send([
                'icon'        => 'error',
                'title'       => 'utilisateur introuvable',
                'description' => "L'utilisateur n'existe pas dans la base de données",
            ]);
            
        }
        $this->closeModal();
        
    }


    public function forceDeleteTeacher(string $userUuid): void
    {
        $this->showConfirmForceDeleteTeacher = true;

        $this->targetedTeacherUserUuid = $userUuid;

    }

    public function ConfirmTeacherForceDelete(): void
    {
       $user = User::whereUuid($this->targetedTeacherUserUuid)->firstOrFail();
        if($user){

            $teacher = $user->teacher;

            if($teacher){

                $deleted = $teacher->forceDelete();

                if($deleted){
                    $this->notification()->send([
                        'icon'        => 'success',
                        'title'       => 'enseignant supprimé définitivement',
                        'description' => "Toutefois cette action a été planifiée et ne sera effective qu'après trente (30) jours",
                    ]);
                }
                else{
                    $this->notification()->send([
                        'icon'        => 'warning',
                        'title'       => 'Echec de la suppresion',
                        'description' => "L'enseignant n'a pas été supprimé!",
                    ]);
                }
            }
            else{

                $this->notification()->send([
                    'icon'        => 'error',
                    'title'       => "Utilisateur non lié à un enseignant",
                    'description' => "Vérifiez la requête",
                ]);
                
            }

        }
        else{

            $this->notification()->send([
                'icon'        => 'error',
                'title'       => 'utilisateur introuvable',
                'description' => "L'utilisateur n'existe pas dans la base de données",
            ]);
            
        }
        $this->closeModal();
        
    }


    public function restoreTeacher(string $userUuid): void
    {
        $this->showConfirmTeacherRestorationModal = true;

        $this->targetedTeacherUserUuid = $userUuid;

    }

    public function ConfirmTeacherRestoration(): void
    {
       $user = User::whereUuid($this->targetedTeacherUserUuid)->firstOrFail();
        if($user){

            $teacher = $user->teacher;

            if($teacher){

                $restored = $teacher->restore();

                if($restored){
                    $this->notification()->send([
                        'icon'        => 'success',
                        'title'       => 'enseignant restauré',
                        'description' => "L'enseignant " . $teacher->getFullName() . " a été restauré de la corbeille",
                    ]);
                }
                else{
                    $this->notification()->send([
                        'icon'        => 'warning',
                        'title'       => 'Echec de la restauration',
                        'description' => "L'enseignant n'a pas été restauré!",
                    ]);
                }
            }
            else{

                $this->notification()->send([
                    'icon'        => 'error',
                    'title'       => "Utilisateur non lié à un enseignant",
                    'description' => "Vérifiez la requête",
                ]);
                
            }

        }
        else{

            $this->notification()->send([
                'icon'        => 'error',
                'title'       => 'utilisateur introuvable',
                'description' => "L'utilisateur n'existe pas dans la base de données",
            ]);
            
        }
        $this->closeModal();
        
    }


	// GROUPS ACTIONS ON TEACHERS

	public function lockTeachers(): void
    {
        $this->showConfirmTeachersLock = true;

    }

    public function ConfirmTeachersLocking(): void
    {
       $query = Teacher::where('blocked', false);

        if($query->count()){

            $locked = true;

            if($locked){
                $this->notification()->send([
                    'icon'        => 'success',
                    'title'       => 'Enseignants bloqués',
                    'description' => "Les enseignants ont été bloqués!",
                ]);
            }
            else{
                $this->notification()->send([
                    'icon'        => 'warning',
                    'title'       => 'Echec du blocage',
                    'description' => "L'enseignant n'a pas été bloqué!",
                ]);
            }

        }
        else{

            $this->notification()->send([
                'icon'        => 'error',
                'title'       => 'Enseignant introuvable',
                'description' => "L'enseignant n'existe pas dans la base de données",
            ]);
            
        }
        $this->closeModal();
        
    }


    public function unlockTeachers(): void
    {
        $this->showConfirmTeachersUnLock = true;

    }

    public function ConfirmTeachersUnLocking(): void
    {
        $query = Teacher::where('blocked', true);

        if($query){

            $locked = true;

            if($locked){
                $this->notification()->send([
                    'icon'        => 'success',
                    'title'       => 'Enseignants débloqués',
                    'description' => "Les enseignants ont été débloqués!",
                ]);
            }
            else{
                $this->notification()->send([
                    'icon'        => 'warning',
                    'title'       => 'Echec du déblocage',
                    'description' => "es enseignants n'a pas été débloqués!",
                ]);
            }

        }
        else{

            $this->notification()->send([
                'icon'        => 'error',
                'title'       => 'utilisateur introuvable',
                'description' => "L'utilisateur n'existe pas dans la base de données",
            ]);
            
        }
        $this->closeModal();
        
    }



    public function deleteTeachers(): void
    {
        $this->showConfirmDeleteTeachers = true;
    }

    public function ConfirmTeachersDeletion(): void
    {
       $query = Teacher::withoutTrashed()->whereNotNull('user_id');

        if($query->count()){

			$done = true;

            if($done){

				$this->notification()->send([
					'icon'        => 'success',
					'title'       => 'Enseignants envoyés dans la corbeille',
					'description' => "Les enseignants ont été envoyés dans la corbeille!",
				]);
			}
			else{
				$this->notification()->send([
					'icon'        => 'warning',
					'title'       => 'Echec de la suppresion',
					'description' => "La suppresion a échoué!",
				]);
			}
                   

        }
        else{

            $this->notification()->send([
                'icon'        => 'error',
                'title'       => "Pas d'enseignants trouvés",
                'description' => "La liste des enseignants semble vide",
            ]);
            
        }
        $this->closeModal();
        
    }


    public function forceDeleteTeachers(): void
    {
        $this->showConfirmForceDeleteTeachers = true;
    }

    public function ConfirmTeachersForceDelete(): void
    {
       $query = Teacher::onlyTrashed()->whereNotNull('user_id');

		if($query->count()){

			$deleted = true;

			if($deleted){
				$this->notification()->send([
					'icon'        => 'success',
					'title'       => 'Enseignants de la corbeille supprimé définitivement',
					'description' => "Toutefois cette action a été planifiée et ne sera effective qu'après trente (30) jours",
				]);
			}
			else{
				$this->notification()->send([
					'icon'        => 'warning',
					'title'       => 'Echec de la suppresion',
					'description' => "Les enseignants n'ont pas été supprimés!",
				]);
			}
		}
		else{

			$this->notification()->send([
				'icon'        => 'error',
				'title'       => "Aucun enseignant trouvé dans la corbeille",
				'description' => "Vérifiez la requête",
			]);
			
		}
        $this->closeModal();
        
    }


    public function restoreTeachers(): void
    {
        $this->showConfirmTeachersRestorationModal = true;
    }

    public function ConfirmTeachersRestoration(): void
    {
       $query = Teacher::onlyTrashed()->whereNotNull('user_id');

		if($query->count()){

			$deleted = true;

			if($deleted){
				$this->notification()->send([
					'icon'        => 'success',
					'title'       => 'Enseignants de la corbeille supprimé définitivement',
					'description' => "Toutefois cette action a été planifiée et ne sera effective qu'après trente (30) jours",
				]);
			}
			else{
				$this->notification()->send([
					'icon'        => 'warning',
					'title'       => 'Echec de la suppresion',
					'description' => "Les enseignants n'ont pas été supprimés!",
				]);
			}
		}
		else{

			$this->notification()->send([
				'icon'        => 'error',
				'title'       => "Aucun enseignant trouvé dans la corbeille",
				'description' => "Vérifiez la requête",
			]);
			
		}
        $this->closeModal();
        
    }

    public function createTeachersFromExcelFile()
    {
        session()->put('showImportMode', true);
    }


    public function exportToExcelFile()
    {

    }
    
    public function clearFilters()
    {
        $this->reset('search', 'gender', 'city', 'gender', 'department');
    }
}