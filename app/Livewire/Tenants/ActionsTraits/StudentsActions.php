<?php

namespace App\Livewire\Tenants\ActionsTraits;

use App\Jobs\JobToSendCredentialsToUser;
use App\Models\Student;
use App\Models\User;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

trait StudentsActions{


	use WithPagination, WireUiActions;
    
    public string $search = '';

    public string $city = '';

    public $counter = 3;

    public string $gender = '';

    public string $department = '';

    public ?string $status = null;

    public int $perPage = 12;

    public $showConfirmDeleteStudent = false;

    public $showConfirmStudentRestorationModal = false;

    public $showConfirmForceDeleteStudent = false;

    public $showConfirmStudentLock = false;

    public $showConfirmStudentUnLock = false;


    public $showConfirmDeleteStudents = false;

    public $showConfirmStudentsRestorationModal = false;

    public $showConfirmForceDeleteStudents = false;

    public $showConfirmStudentsLock = false;

    public $showConfirmStudentsUnLock = false;


    public ?string $targetedStudentUuid = null;


	public function closeModal()
    {
        $this->reset('showConfirmDeleteStudent', 'showConfirmStudentRestorationModal', 'showConfirmForceDeleteStudent', 'showConfirmStudentLock', 'showConfirmDeleteStudents', 'showConfirmStudentsRestorationModal', 'showConfirmForceDeleteStudents', 'showConfirmStudentsLock', 'targetedStudentUuid', 'showConfirmStudentsUnLock');
    }




    public function lockStudent(string $studentUuid): void
    {
        $this->showConfirmStudentLock = true;

        $this->targetedStudentUuid = $studentUuid;

    }

    public function ConfirmStudentLocking(): void
    {
       $user = User::whereUuid($this->targetedStudentUuid)->firstOrFail();
        if($user){

            $locked = $user->Student->update(['blocked' => true]);

            if($locked){

				// (broadcast(new StudentWasBlockedEvent(tenant('id'), $user->id)));
				
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

	public function sendCredentialsToStudent(string $studentUuid)
	{
		// $user = User::firstWhere('uuid', $studentUuid);

        // if($user && $user->logged_count < 1 ){

		// 	$domain = request()->getSchemeAndHttpHost();

        //     $space_url = get_tenant_url($domain, 'login');

        //     // JobToSendCredentialsToUser::dispatch(tenant('id'), $user->email, null, $space_url);

        //     $this->notification()->send([
        //         'icon'        => 'success',
        //         'title'       => "Envoi des données espace enseignant ",
        //         'description' => "Processus lancé ...",
        //     ]);
        // }
        // else{

        //     $this->notification()->send([
        //         'icon'        => 'error',
        //         'title'       => 'Erreur processus',
        //         'description' => "La reqûete n'existe pas ou n'a pas encore été validée!",
        //     ]);
        // }
	}


    public function unlockStudent(string $studentUuid): void
    {
        $this->showConfirmStudentUnLock = true;

        $this->targetedStudentUuid = $studentUuid;

    }

    public function ConfirmStudentUnLocking(): void
    {
        $user = User::whereUuid($this->targetedStudentUuid)->firstOrFail();

        if($user){

            $locked = $user->Student->update(['blocked' => false]);

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



    public function deleteStudent(string $studentUuid): void
    {
        $this->showConfirmDeleteStudent = true;

        $this->targetedStudentUuid = $studentUuid;

    }

    public function ConfirmStudentDeletion(): void
    {
       $student = Student::whereUuid($this->targetedStudentUuid)->firstOrFail();

        if($student){

            $deleted = $student->delete();

            if($deleted){
                $this->notification()->send([
                    'icon'        => 'success',
                    'title'       => 'apprenant envoyé dans la corbeille',
                    'description' => "L'apprenant a été envoyé dans la corbeille!",
                ]);
            }
            else{
                $this->notification()->send([
                    'icon'        => 'warning',
                    'title'       => 'Echec de la suppresion',
                    'description' => "L'apprenant n'a pas été supprimé!",
                ]);
            }
        }
        else{

           $this->notification()->send([
                'icon'        => 'error',
                'title'       => "Aucune donnée correspondante trouvées dans la base de données",
                'description' => "Vérifiez la requête",
            ]);
            
        }
        $this->closeModal();
        
    }


    public function forceDeleteStudent(string $studentUuid): void
    {
        $this->showConfirmForceDeleteStudent = true;

        $this->targetedStudentUuid = $studentUuid;

    }

    public function ConfirmStudentForceDelete(): void
    {
       $student = Student::whereUuid($this->targetedStudentUuid)->firstOrFail();
        
       if($student){

            $deleted = $student->forceDelete();

            if($deleted){
                $this->notification()->send([
                    'icon'        => 'success',
                    'title'       => 'apprenant supprimé définitivement',
                    'description' => "Toutefois cette action a été planifiée et ne sera effective qu'après trente (30) jours",
                ]);
            }
            else{
                $this->notification()->send([
                    'icon'        => 'warning',
                    'title'       => 'Echec de la suppresion',
                    'description' => "L'apprenant n'a pas été supprimé!",
                ]);
            }
        }
        else{

            $this->notification()->send([
                'icon'        => 'error',
                'title'       => "Aucune donnée correspondante trouvées dans la base de données",
                'description' => "Vérifiez la requête",
            ]);
            
        }
        $this->closeModal();
        
    }


    public function restoreStudent(string $studentUuid): void
    {
        $this->showConfirmStudentRestorationModal = true;

        $this->targetedStudentUuid = $studentUuid;

    }

    public function ConfirmStudentRestoration(): void
    {
       $student = Student::whereUuid($this->targetedStudentUuid)->firstOrFail();

        if($student){

            $restored = $student->restore();

            if($restored){

                $this->notification()->send([
                    'icon'        => 'success',
                    'title'       => 'enseignant restauré',
                    'description' => "L'enseignant " . $student->getFullName() . " a été restauré de la corbeille",
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
                'title'       => "Aucune donnée correspondante trouvées dans la base de données",
                'description' => "Vérifiez la requête",
            ]);
            
        }
        $this->closeModal();
        
    }


	// GROUPS ACTIONS ON STUDENTS

	public function lockStudents(): void
    {
        $this->showConfirmStudentsLock = true;

    }

    public function ConfirmStudentsLocking(): void
    {
       $query = Student::where('blocked', false);

        if($query->count()){

            $locked = true;

            if($locked){
                $this->notification()->send([
                    'icon'        => 'success',
                    'title'       => 'apprenant bloqués',
                    'description' => "Les apprenant ont été bloqués!",
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


    public function unlockStudents(): void
    {
        $this->showConfirmStudentsUnLock = true;

    }

    public function ConfirmStudentsUnLocking(): void
    {
        $query = Student::where('blocked', true);

        if($query){

            $locked = true;

            if($locked){
                $this->notification()->send([
                    'icon'        => 'success',
                    'title'       => 'apprenant débloqués',
                    'description' => "Les apprenant ont été débloqués!",
                ]);
            }
            else{
                $this->notification()->send([
                    'icon'        => 'warning',
                    'title'       => 'Echec du déblocage',
                    'description' => "es apprenant n'a pas été débloqués!",
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



    public function deleteStudents(): void
    {
        $this->showConfirmDeleteStudents = true;
    }

    public function ConfirmStudentsDeletion(): void
    {
       $query = Student::withoutTrashed()->whereNotNull('user_id');

        if($query->count()){

			$done = true;

            if($done){

				$this->notification()->send([
					'icon'        => 'success',
					'title'       => 'apprenants envoyés dans la corbeille',
					'description' => "Les apprenants ont été envoyés dans la corbeille!",
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
                'title'       => "Pas d'apprenants trouvés",
                'description' => "La liste des apprenants semble vide",
            ]);
            
        }
        $this->closeModal();
        
    }


    public function forceDeleteStudents(): void
    {
        $this->showConfirmForceDeleteStudents = true;
    }

    public function ConfirmStudentsForceDelete(): void
    {
       $query = Student::onlyTrashed()->whereNotNull('user_id');

		if($query->count()){

			$deleted = true;

			if($deleted){
				$this->notification()->send([
					'icon'        => 'success',
					'title'       => 'apprenants de la corbeille supprimé définitivement',
					'description' => "Toutefois cette action a été planifiée et ne sera effective qu'après trente (30) jours",
				]);
			}
			else{
				$this->notification()->send([
					'icon'        => 'warning',
					'title'       => 'Echec de la suppresion',
					'description' => "Les apprenants n'ont pas été supprimés!",
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


    public function restoreStudents(): void
    {
        $this->showConfirmStudentsRestorationModal = true;
    }

    public function ConfirmStudentsRestoration(): void
    {
       $query = Student::onlyTrashed()->whereNotNull('user_id');

		if($query->count()){

			$deleted = true;

			if($deleted){
				$this->notification()->send([
					'icon'        => 'success',
					'title'       => 'Apprenants de la corbeille supprimé définitivement',
					'description' => "Toutefois cette action a été planifiée et ne sera effective qu'après trente (30) jours",
				]);
			}
			else{
				$this->notification()->send([
					'icon'        => 'warning',
					'title'       => 'Echec de la suppresion',
					'description' => "Les Apprenants n'ont pas été supprimés!",
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

    public function createStudentsFromExcelFile()
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