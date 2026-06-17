<?php

namespace App\Jobs;

use App\Events\AStudentCreationFailedEvent;
use App\Events\StudentCreatedEvent;
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

class JobToCreateStudent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    public int $tries = 2;

    /**
     * @param string $tenantId
     * @param int    $taskId
     */
    public function __construct(
        public string $tenantId,
        public int    $taskId,
        public ?string $domain,
    ) {
    }

    /**
     * @return void
     */
    public function handle(): void
    {

        try {

            tenancy()->initialize($this->tenantId);

            $task = ImportTask::findOrFail($this->taskId);

            if ($this->batch()?->cancelled()) {
                return;
            }

            $task->update([
                'status'   => 'pending',
                'attempts' => $task->attempts + 1,
            ]);

            $payload = $task->payload;

            $tenant = tenancy()->tenant;

            $director = User::first();

            $full_name = $payload['name'] . ' ' .  $payload['prenames'];

            // Anti-doublon
            if(!empty($payload['email'])){

                    if (Student::where('email', $payload['email'])->first() || User::where('email', $payload['email'])->first()) {

                    $task->update(['status' => 'failed']);

                    $this->fail("Données de l'apprenant est déjà existant dans la base de données!");

                    User::first()->notify(new RealTimeNotification(
                        userEmail: $tenant->email,
                        tenantId: $this->tenantId,
                        title:             "Duplication de compte apprenant",
                        message:           "Le Compte de l'apprenant" . $full_name . " déjà existant dans la base de données!",
                        type:              'error',
                    ));

                    return;
                }
            }

            $birth_date = $payload['birth_date'];

            try {

                $birth_date = Carbon::createFromFormat('d/m/Y', $birth_date)->format('Y-m-d');

            } catch (\Exception $e) {

                $birth_date = Carbon::parse($birth_date)->format('Y-m-d');
            }

            $adresse = ($payload['city'] ?? null) && ($payload['department'] ?? null)
                ? $payload['city'] . ' (' . $payload['department'] . ')'
                : null;

            $qr_code_payload = [
                'nom'                    => $payload['name'],
                'prenoms'                => $payload['prenames'],
                'pays'                   => $payload['country'] ?? null,
                'contacts'               => $payload['contacts'] ?? null,
                'addresse'               => $adresse,
                'ecole'                  => $tenant->school_name,
                'domaine'                => $tenant->domain_name,
            ];

            $student = Student::create([
                'name'                        => $payload['name'],
                'prenames'                    => $payload['prenames'],
                'educMaster'                  => $payload['educMaster'],
                'country'                     => $payload['country'] ?? null,
                'city'                        => $payload['city'] ?? null,
                'department'                  => $payload['department'] ?? null,
                'email'                       => $payload['email'] ?? null,
                'mother_full_name'            => $payload['mother_full_name'],
                'father_full_name'            => $payload['father_full_name'],
                'birth_place'                 => $payload['birth_place'],
                'contacts'                    => $payload['contacts'] ?? null,
                'gender'                      => $payload['gender'] ?? null,
                'birth_date'                  => $birth_date ?? null,
                'adresse'                     => $adresse,
                'matricule'                   => Robot::makeMatricule($tenant->school_name),
                'qr_code'                     => Robot::makeQrCode($qr_code_payload),
            ]);

            $task->update(['status' => 'success']);

            $student->update(['is_active' => true, 'status' => 'active']);

            // StudentCreatedEvent::dispatch($this->tenantId, $task->id, null);

            $can_sent = randomNumber(1, 10);

            if(in_array($can_sent, [1, 3, 7])){

                $director?->notify(new RealTimeNotification(
                    userEmail: $director?->email,
                    tenantId: $this->tenantId,
                    title:             "COMPTE APPRENANT CREE AVEC SUCCES",
                    message:           "Le compte de l'apprenant " . $student->getUserNamePrefix(true, true) . " a été créé avec succès!",
                    type:              'success',
                ));
            }


        } 
        catch (\Throwable $th){

            $full_name = $payload['name'] . ' ' .  $payload['prenames'];

            AStudentCreationFailedEvent::dispatch(
                tenantId: $this->tenantId, 
                taskId: $this->taskId,
                studentName: $full_name,
                error: $th->getMessage(),
            );

            $director?->notify(new RealTimeNotification(
                userEmail: $director?->email,
                tenantId: $this->tenantId,
                title:             "Erreur création du compte apprenant " . $full_name,
                message:           cutter($th->getMessage(), 200),
                type:              'error',
            ));

            $this->fail($th->getMessage());

            return;
        }
        finally {

            $batch = $this->batch();

            if (! $batch) {
                return;
            }

            StudentsCreationStatusUpdatedEvent::dispatch(
                tenantId:   $this->tenantId,
                batchId:    $batch->id,
                totalJobs:  $batch->totalJobs,
                processed:  $batch->processedJobs(),
                percentage: $batch->progress(),
                failed:     $batch->failedJobs,
            );

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

            $task = ImportTask::find($this->taskId);

            $director = User::firstWhere('tenant_id', $this->tenantId);

            $payload = $task->payload;

            $full_name = $payload['name'] . ' ' .  $payload['prenames'];

            $student = Student::firstWhere('email', $task->payload['email']);

            if ($student && !$student->is_active) {

                if($task && $task->status !== 'success'){

                    $student->forceDelete();

                    $task->update([
                        'status' => 'failed',
                        'error'  => $exception->getMessage(),
                    ]);
                }

            }

            broadcast(new AStudentCreationFailedEvent(
                    tenantId: $this->tenantId, 
                    taskId: $this->taskId,
                    studentName: $full_name,
                    error: $exception->getMessage(),
                )
            );


            $director?->notify(new RealTimeNotification(
                userEmail: $director->email,
                tenantId:  $this->tenantId,
                title:     "ECHEC CRÉATION DU COMPTE APPRENANT " . $full_name,
                message:   cutter($exception->getMessage(), 200),
                type:      'error',
            ));

        } finally {
            tenancy()->end();
        }
    }

    
}
