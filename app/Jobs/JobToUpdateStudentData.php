<?php

namespace App\Jobs;

use App\Events\AStudentCreationFailedEvent;
use App\Events\StudentDataUpdatedEvent;
use App\Events\StudentsCreationStatusUpdatedEvent;
use App\Helpers\Robot;
use App\Models\ImportTask;
use App\Models\Student;
use App\Models\User;
use App\Notifications\RealTimeNotification;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Throwable;

class JobToUpdateStudentData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    public int $tries = 2;

    /**
     * @param string $tenantId
     * @param int    $studentId
     * @param array    $data
     * @param string    $domain
     */
    public function __construct(
        public string $tenantId,
        public int    $studentId,
        public array $data,
        public ?string $domain,
        public ?int $editorId = null
    ) {
    }

    /**
     * @return void
     */
    public function handle(): void
    {

        try {

            tenancy()->initialize($this->tenantId);

            $student = Student::findOrFail($this->studentId);

            $tenant = tenancy()->tenant;

            $director = User::first();

            if ($this->batch()?->cancelled()) {
                return;
            }

            if(!$student){

                $msg = "Aucun apprenant correspondant n'existe dans la base de données";

                $full_name = $this->data['name'] . ' ' .  $this->data['prenames'];

                $this->notifyer($director, $full_name, $msg);
                
                $this->fail($msg);

                return;
            }


            // Anti-doublon
            $existed_full_name = Student::withTrashed()->where('name', $this->data['name'])->where('prenames', $this->data['prenames'])->where('id', '<>', $this->studentId)->exists();

            $existed_educ = Student::withTrashed()->where('educMaster', $this->data['educMaster'])->where('id', '<>', $this->studentId)->exists();


            if($existed_educ){

                $msg = "Un apprenant est déjà enregistré sous ce numero!";

                $full_name = $this->data['name'] . ' ' .  $this->data['prenames'];

                $director?->notify(new RealTimeNotification(
                    userEmail: $director?->email,
                    tenantId: $this->tenantId,
                    title:             "ECHEC DE LA MISE A JOUR DES INFOS DE L'APPRENANT " . $full_name,
                    message:           $msg,
                    type:              'error',
                ));

                $this->fail($msg);

                return;
            }

            $existed_email1 = Student::withTrashed()->where('email', $this->data['email'])->where('id', '<>', $this->studentId)->exists();

            if($existed_email1){

                
            }


            $existed_email2 = User::withTrashed()->where('email', $this->data['email'])->exists();


            if($existed_email2){

                if($student->user){

                    if($student->user->id !== $existed_email2->id){

                        $msg = "Cet adresse mail est déjà utilisée!";

                        $full_name = $this->data['name'] . ' ' .  $this->data['prenames'];

                        $this->notifyer($director, $full_name, $msg);
                        
                        $this->fail($msg);

                        return;

                    }
                    
                }
                else{

                    $msg = "Cet adresse mail est déjà utilisée!";

                    $full_name = $this->data['name'] . ' ' .  $this->data['prenames'];

                    $this->notifyer($director, $full_name, $msg);
                    
                    $this->fail($msg);

                    return;


                }
            }


            if($existed_full_name){

                $msg = "Un apprenant est déjà enregistré sous ce numero!";

                $full_name = $this->data['name'] . ' ' .  $this->data['prenames'];

                $this->notifyer($director, $full_name, $msg);
                
                $this->fail($msg);

                return;
            }



            $adresse = ($this->data['city'] ?? null) && ($this->data['department'] ?? null)
                ? $this->data['city'] . ' (' . $this->data['department'] . ')'
                : null;

            $qr_code_payload = [
                'nom'                    => $this->data['name'],
                'prenoms'                => $this->data['prenames'],
                'pays'                   => $this->data['country'] ?? null,
                'contacts'               => $this->data['contacts'] ?? null,
                'addresse'               => $adresse,
                'ecole'                  => $tenant->school_name,
                'domaine'                => $tenant->domain_name,
            ];

            $done = $student->update([
                'name'                        => $this->data['name'],
                'prenames'                    => $this->data['prenames'],
                'educMaster'                  => $this->data['educMaster'],
                'country'                     => $this->data['country'] ?? null,
                'city'                        => $this->data['city'] ?? null,
                'department'                  => $this->data['department'] ?? null,
                'email'                       => $this->data['email'] ?? null,
                'mother_full_name'            => $this->data['mother_full_name'],
                'father_full_name'            => $this->data['father_full_name'],
                'birth_place'                 => $this->data['birth_place'],
                'contacts'                    => $this->data['contacts'] ?? null,
                'gender'                      => $this->data['gender'] ?? null,
                'birth_date'                  => $this->data['birth_date'] ?? null,
                'adresse'                     => $adresse,
                'qr_code'                     => Robot::makeQrCode($qr_code_payload),
            ]);

            if($done){

                StudentDataUpdatedEvent::dispatch(
                    tenantId: $this->tenantId,
                    studentId: $this->studentId,
                    domain: $this->domain
                );

                $director?->notify(new RealTimeNotification(
                    userEmail: $director?->email,
                    tenantId: $this->tenantId,
                    title:             "INFOS APPRENANT MISES A JOUR",
                    message:           "Les infos de l'apprenant " . $student->getUserNamePrefix(true, true) . " ont été mises à jour avec succès!",
                    type:              'success',
                ));
            }


        } 
        catch (\Throwable $th){

            $full_name = $this->data['name'] . ' ' .  $this->data['prenames'];

            $director = User::firstWhere('tenant_id', $this->tenantId);

            $this->notifyer($director, $full_name, $th->getMessage());

            return;
        }
        finally {

            tenancy()->end();

        }
    }

    /**
     * @param Throwable $exception
     * @return void
     */
    public function failed(Throwable $exception): void
    {
        tenancy()->initialize($this->tenantId);

        try {

            $full_name = $this->data['name'] . ' ' .  $this->data['prenames'];

            $director = User::firstWhere('tenant_id', $this->tenantId);

            $this->notifyer($director, $full_name, $exception->getMessage());


        } finally {
            tenancy()->end();
        }
    }


    public function notifyer($director, string $full_name, string $message)
    {
        $director?->notify(new RealTimeNotification(
            userEmail: $director?->email,
            tenantId: $this->tenantId,
            title:             "ECHEC DE LA MISE A JOUR DES INFOS DE L'APPRENANT " . $full_name,
            message:           cutter($message, 200),
            type:              'error',
        ));

        $this->fail($message);

        return;
    }
}
