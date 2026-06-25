<?php

namespace App\Jobs;

use App\Events\DataUpdatedEvent;
use App\Models\Classe;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\User;
use App\Models\YearlyClasseStudent;
use App\Notifications\RealTimeNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class JobToMigrateStudentsToClasses implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public readonly string   $tenantId,
        public readonly int   $classeId,
        public readonly array $studentIds,
        public readonly int   $schoolYearId,
        public readonly int   $authorId,
    ) {}

    public function handle(): void
    {
        $now = now();

        try {

            tenancy()->initialize($this->tenantId);

            $director = User::first();

            if(!$director){

                $this->fail("COMPTE DIRECTEUR INEXISTANT : IMPOSSIBLE DE CREER UN UTILISATEUR AVANT LE COMPTE DIRECTEUR");

                return;

            }

            $classe = Classe::find($this->classeId);

            if($classe){

                $schoolYear = SchoolYear::find($this->schoolYearId);

                if($schoolYear && $schoolYear->is_active){

                    foreach ($this->studentIds as $studentId) {

                        $student = Student::find($studentId);

                        if($student){

                            $exists = YearlyClasseStudent::where('student_id', $studentId)
                                ->where('school_year_id', $this->schoolYearId)
                                ->exists();

                            if ($exists) {
                                continue; 
                            }

                            YearlyClasseStudent::create([
                                'student_id'     => $studentId,
                                'classe_id'      => $this->classeId,
                                'school_year_id' => $this->schoolYearId,
                                'author_id'      => $this->authorId,
                                'is_active'      => true,
                                'status'         => 'Approuvé',
                                'started_at'     => $now,
                            ]);
                        }
                    }
                }
                else{

                    $error_message = "L'année scolaire est introuvable ou peut-être n'est pas active!";

                    $director?->notify(new RealTimeNotification(
                        userEmail: $director?->email,
                        tenantId: $this->tenantId,
                        title:             "Erreur de migration des apprenants vers la classe ",
                        message:           $error_message,
                        type:              'error',
                    ));

                    $this->fail($error_message);
                }
            }
            else{

                $error_message = "La classe de destination est introuvable!";

                $director?->notify(new RealTimeNotification(
                    userEmail: $director?->email,
                    tenantId: $this->tenantId,
                    title:             "Erreur de migration des apprenants vers la classe ",
                    message:           $error_message,
                    type:              'error',
                ));

                $this->fail($error_message);
            }

        } catch (\Throwable $th) {

            $director?->notify(new RealTimeNotification(
                userEmail: $director->email,
                tenantId:  $this->tenantId,
                title:     "ECHEC DE MIGRATION DES APPRENANTS VERS LA CLASSE " . $classe?->name,
                message:   cutter($th->getMessage(), 150),
                type:      'error',
            ));
        }
        finally{

            broadcast(new DataUpdatedEvent($this->tenantId));

            tenancy()->end();
        }
    }
}
