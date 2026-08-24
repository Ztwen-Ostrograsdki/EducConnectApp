<?php

namespace App\Jobs;

use App\Events\DataUpdatedEvent;
use App\Exceptions\ModelCouldNotBeDeleteBecauseHasActivesAssignmentsException;
use App\Models\Classe;
use App\Models\SchoolYear;
use App\Models\StudentTutorRelation;
use App\Models\TeacherYearlyAccess;
use App\Models\TeacherYearlySubject;
use App\Models\User;
use App\Models\YearlyFiliarChief;
use App\Models\YearlySubjectChief;
use App\Notifications\RealTimeNotification;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Attributes\Timeout;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Throwable;

#[Timeout(300)]
class JobToDestroyUserRole implements ShouldQueue
{
    use Queueable, Batchable, Dispatchable, InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $tenantId,
        public string $role,
        public int $userId,
        public ?array $data = [],
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            tenancy()->initialize($this->tenantId);

            if ($this->batch()?->cancelled()) {
                return;
            }

            DB::transaction(function () {

                $done = false;

                $payload = $this->data;

                $tenant = tenancy()->tenant;

                $director = User::first();

                if (! $director) {

                    $this->fail("COMPTE DIRECTEUR INEXISTANT : IMPOSSIBLE DE FAIRE UNE QUELCONQUE ACTION SUR LA BASE DE DONNEES AVANT LE COMPTE DIRECTEUR");

                    return;
                }

                $user = User::find($this->userId);

                if (!$user) {

                    $full_name = $payload['full_name'];
                    
                    $error_message = "Echec de création de l'espace du {$this->role} " . $full_name . " . Compte introuvable";

                    $director?->notify(new RealTimeNotification(
                        userEmail: $director?->email,
                        tenantId:  $this->tenantId,
                        title:     "Erreur création du compte " . ($payload['email'] ?? ''),
                        message:   $error_message,
                        type:      'error',
                    ));

                    $this->fail($error_message);

                    return;
                }

                $schoolYear = SchoolYear::firstWhere('slug', $this->data['schoolYearSlug']);

                if (!$schoolYear) {

                    $full_name = $payload['full_name'];
                    
                    $error_message = "Echec de création de l'espace du {$this->role} " . $full_name . " . Compte introuvable";

                    $director?->notify(new RealTimeNotification(
                        userEmail: $director?->email,
                        tenantId:  $this->tenantId,
                        title:     "Erreur création du compte " . ($payload['email'] ?? ''),
                        message:   $error_message,
                        type:      'error',
                    ));

                    $this->fail($error_message);

                    return;
                }

                $full_name = $user->getFullName();

                try {

                    if(str()->lower($this->role) === 'tuteur'){

                        if(!$user->hasRole('tuteur')){
                            return;
                        }

                        $parent = $user->parent;

                        if($parent){

                            StudentTutorRelation::where('tutor_id', $parent->id)->delete();

                            $parent->forceDelete();
                        }

                        $user->removeRole(str()->lower($this->role));

                        $done = true;
                    }
                    elseif(str()->lower($this->role) === 'enseignant'){

                        if(!$user->hasRole('enseignant')){
                            return;
                        }

                        if(!$user->teacher->ensureThatTeacherDoesntHaveClasse()){

                            $message = $full_name . " enseigne dans au moins une classe. Pour retirer le rôle enseignant à l'enseignant {$full_name}, vous devez d'abord lui retirer toutes ses classes !";

                            if($director){

                                $director->notify(new RealTimeNotification(
                                    userEmail: $director?->email,
                                    tenantId: $this->tenantId,
                                    title:             "Vous ne pouvez pas retirer le rôle enseignant à cet enseignant!",
                                    message:           $message,
                                    type:              'error',
                                ));
                            }

                            throw new ModelCouldNotBeDeleteBecauseHasActivesAssignmentsException(
                                $message
                            );

                        }

                        try {
                            
                            TeacherYearlyAccess::where('school_year_id', $schoolYear->id)->where('teacher_id', $user->teacher->id)->delete();

                            YearlySubjectChief::where('school_year_id', $schoolYear->id)->where('teacher_id', $user->teacher->id)->delete();

                            YearlyFiliarChief::where('school_year_id', $schoolYear->id)->where('teacher_id', $user->teacher->id)->delete();
                            
                            TeacherYearlySubject::where('school_year_id', $schoolYear->id)->where('teacher_id', $user->teacher->id)->delete();

                            Classe::where('school_year_id', $schoolYear->id)->where('principal_id', $user->teacher->id)->update(['principal_id'=> null]);

                            $user->removeRole(str()->lower($this->role));

                            $done = true;

                        } catch (Throwable $e) {

                            $error_message = "Erreur suppression de privilège {$this->role} du compte " . $payload['email'];

                            $this->fail($error_message);

                            throw $e;

                        }

                    }

                } catch (Throwable $th) {

                    DB::rollBack();

                    $director?->notify(new RealTimeNotification(
                        userEmail: $director?->email,
                        tenantId:  $this->tenantId,
                        title:     "Erreur suppression de privilège {$this->role} du compte " . $payload['email'],
                        message:   cutter($th->getMessage(), 2000),
                        type:      'error',
                    ));

                    $this->fail(cutter($th->getMessage(), 2000));
                    return;
                }

                if ($done) {

                    $director?->notify(new RealTimeNotification(
                        userEmail: $director?->email,
                        tenantId:  $this->tenantId,
                        title:     "PRIVILEGE TUTEUR RETIRE AVEC SUCCES",
                        message:   "Le rôle {$this->role} du compte de " . $user->getUserNamePrefix(true, true) . " a été retiré avec succès!",
                        type:      'success',
                    ));
                }
            });


        } 
        catch (Throwable $th){

            $this->fail(cutter($th->getMessage(), 2000));
        }
        
        finally {
            
            broadcast(new DataUpdatedEvent(($this->tenantId)));

            tenancy()->end();
        }
    }

    public function failed(Throwable $exception): void
    {
        tenancy()->initialize($this->tenantId);

        try {
            $director = User::first();

            $role = str()->upper($this->role);

            $director?->notify(new RealTimeNotification(
                userEmail: $director->email,
                tenantId:  $this->tenantId,
                title:     "ECHEC DE SUPPRESSION DU PRIVILEGES {$role} du compte de " . $this->data['full_name'],
                message:   cutter($exception->getMessage(), 2000),
                type:      'error',
            ));
            
        } finally {

            tenancy()->end();
        }
    }
}
