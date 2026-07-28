<?php

namespace App\Jobs;

use App\Events\DataUpdatedEvent;
use App\Models\Classe;
use App\Models\ClasseSubjectOfSchoolYear;
use App\Models\SchoolYear;
use App\Models\Subject;
use App\Models\User;
use App\Notifications\RealTimeNotification;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Attributes\Timeout;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

#[Timeout(300)]
class JobToRemoveSubjectConduiteFromOldTeacher implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $tenantId,
        public readonly int $classeId,
        public readonly ?int $schoolYearId = null,
    ) {}

    public function handle(): void
    {
        try {

            tenancy()->initialize($this->tenantId);

            $director = User::first();

            if(!$director){

                $this->fail("COMPTE DIRECTEUR INEXISTANT : IMPOSSIBLE DE FAIRE UNE QUELCONQUE ACTION AVANT LE COMPTE DIRECTEUR");

                return;

            }

            if($this->schoolYearId){

                $schoolYear = SchoolYear::find($this->schoolYearId);
            }

            else{

                $schoolYear = SchoolYear::current()->first();
            }

            if(!($schoolYear && $schoolYear->is_active)){

                $error_message = "L'année scolaire introuvable  ou non active!";

                $director?->notify(new RealTimeNotification(
                    userEmail: $director?->email,
                    tenantId: $this->tenantId,
                    title:             "POSSIBLE ERREUR D'ASSIGNATION DE LA MATIERE CONDUITE AU PP",
                    message:           $error_message,
                    type:              'error',
                ));

                $this->fail($error_message);
                

            }

            self::removeSubjectConduiteFromOldPPTeacher($schoolYear->id, $schoolYear->slug);


        } catch (\Throwable $th) {

            $director?->notify(new RealTimeNotification(
                userEmail: $director->email,
                tenantId:  $this->tenantId,
                title:     "POSSIBLE ERREUR D'ASSIGNATION DE LA MATIERE CONDUITE AU PP",
                message:   cutter($th->getMessage(), 2000),
                type:      'error',
            ));
        }
        finally{

            broadcast(new DataUpdatedEvent($this->tenantId));

            tenancy()->end();
        }
    }


    public function removeSubjectConduiteFromOldPPTeacher(int $schoolYearId, string $schoolYearSlug): void
    {

        DB::transaction(function () use ($schoolYearId) {

            $director = User::first();

            $classe = Classe::find($this->classeId);

            if(!$classe){


                $this->fail("Classe introuvable");

                return;
            }

            $subject_name = 'Conduite';

            $subject = Subject::whereName('conduite')->orWhere('name', 'Conduite')->first();

            if(!$subject){

                Subject::create([
                    'uuid'        => (string) Str::uuid(),
                    'slug'        => Str::slug($subject_name),
                    'name'        => $subject_name,
                    'code'        => "Cond",
                    'description' => null,
                    'type'        => "education - morale",
                    'level'       => "secondaire",
                    'is_active'   => true,
                ]);

                return;

            }

            $principal = $classe->principal;

            if($principal){

                JobToJoinSubjectConduiteToPP::dispatch($this->tenantId, $this->classeId, $this->schoolYearId);

                return;

            }


            if(!$principal){

                $exists = ClasseSubjectOfSchoolYear::where('classe_id', $this->classeId)
                ->where('subject_id', $subject->id)
                ->where('school_year_id', $schoolYearId)
                ->first();

                if($exists->started_at->gt(now()->subWeeks(2))){
                
                    $exists->update([
                        'ended_at'           => now(),
                        'replacement_reason' => 'Remaniement d\'emploi du temps',
                        'replaced_by'        => $director->id,
                    ]);

                }
                else{

                    $exists->delete();
                }

                $message = "Apparemment, la classe {$classe->name} n'a pas de PP. Veuillez désigner le PP de cette classe.";

                $director?->notify(new RealTimeNotification(
                    userEmail: $director?->email,
                    tenantId: $this->tenantId,
                    title:             "CLASSE SANS PP",
                    message:           $message,
                    type:              'success',
                ));
            }
            
        });

    }


    public function failer(string $error_message)
    {
        $director = User::first();

        $director?->notify(new RealTimeNotification(
            userEmail: $director?->email,
            tenantId: $this->tenantId,
            title:             "POSSIBLE ERREUR D'ASSIGNATION DE LA MATIERE CONDUITE AU PP",
            message:           $error_message,
            type:              'error',
        ));

        $this->fail($error_message);

        return;
    }
}
