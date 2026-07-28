<?php

namespace App\Jobs;

use App\Events\DataUpdatedEvent;
use App\Models\Classe;
use App\Models\ClasseSubjectOfSchoolYear;
use App\Models\SchoolYear;
use App\Models\Subject;
use App\Models\TeacherYearlySubject;
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
class JobToJoinSubjectConduiteToPP implements ShouldQueue
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
                    title:             "MATIERE CONDUITE NON ASSIGNEE AU PP DE CLASSE",
                    message:           $error_message,
                    type:              'error',
                ));

                $this->fail($error_message);
                

            }

            self::assignTeacher($schoolYear->id, $schoolYear->slug);


        } catch (\Throwable $th) {

            $director?->notify(new RealTimeNotification(
                userEmail: $director->email,
                tenantId:  $this->tenantId,
                title:     "MATIERE CONDUITE NON ASSIGNEE AU PP DE CLASSE",
                message:   cutter($th->getMessage(), 2000),
                type:      'error',
            ));
        }
        finally{

            broadcast(new DataUpdatedEvent($this->tenantId));

            tenancy()->end();
        }
    }


    public function assignTeacher(int $schoolYearId, string $schoolYearSlug): void
    {

        DB::transaction(function () use ($schoolYearId) {

            $director = User::first();

            $classe = Classe::find($this->classeId);

            if(!$classe){

                $error_message = "La matière conduite n'a pas pu être asssignée au PP de la classe. Veuillez donc, relancer ou le faire manuellement";

                $this->failer($error_message);

                return;
            }

            $subject_name = 'Conduite';

            $subject = Subject::whereName('conduite')->orWhere('name', 'Conduite')->first();

            if(!$subject){

                $subject_created = Subject::create([
                    'uuid'        => (string) Str::uuid(),
                    'slug'        => Str::slug($subject_name),
                    'name'        => $subject_name,
                    'code'        => "Cond",
                    'description' => null,
                    'type'        => "education - morale",
                    'level'       => "secondaire",
                    'is_active'   => true,
                ]);

                if(!$subject_created){

                    $error_message = "La matière <<Conduite>> n'existe pas ou a été désactivée, veuillez la crééer ou la réactiver d'abord avant de pouvoir l'assigner aux PP";

                    $this->failer($error_message);

                    return;
                }

                $subject = $subject_created;
            }

            $principal = $classe->principal;

            if(!$principal){

                $error_message = "La classe {$classe->name} n'a pas le PP actuellement";

                $this->failer($error_message);
            }

            $teacherHasSubjectConduite = TeacherYearlySubject::where('teacher_id', $principal->id)
                ->where('subject_id', $subject->id)
                ->where('school_year_id', $schoolYearId)
                ->first();

            if($teacherHasSubjectConduite){

                $teacherHasSubjectConduite->update(['is_active' => true]);
            }
            else{
                TeacherYearlySubject::create([
                    'teacher_id'    => $principal->id,
                    'subject_id'    => $subject->id,
                    'school_year_id'=> $schoolYearId,
                    'is_active'     => true,
                ]);
            }

            $conditions =  [
                'subject_id'      => $subject->id, 
                'classe_id'       => $classe->id, 
                'teacher_id'      => $principal->id, 
                'school_year_id'  => $schoolYearId
            ];

            $data = [
                'ended_at'       => null,
                'is_active'      => true,
                'started_at'     => now(),

            ];

            ClasseSubjectOfSchoolYear::updateOrCreate(
            $conditions,
                $data
            );

            $message = "La matière CONDUITE a été assignée avec succès au prof {$principal->getFullName()}, PP de la classe de {$classe->name}";

            $director?->notify(new RealTimeNotification(
                userEmail: $director?->email,
                tenantId: $this->tenantId,
                title:             "MATIERE CONDUITE ASSIGNEE AU PP DE CLASSE AVEC SUCCES",
                message:           $message,
                type:              'success',
            ));
        });

    }


    public function failer(string $error_message)
    {
        $director = User::first();

        $director?->notify(new RealTimeNotification(
            userEmail: $director?->email,
            tenantId: $this->tenantId,
            title:             "MATIERE CONDUITE NON ASSIGNEE AU PP DE CLASSE",
            message:           $error_message,
            type:              'error',
        ));

        $this->fail($error_message);

        return;
    }
}
