<?php

namespace App\Jobs;

use App\Events\DataUpdatedEvent;
use App\Models\Classe;
use App\Models\Mark;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\User;
use App\Notifications\RealTimeNotification;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class JobToMigrateStudentMarksToHisNewClasseAfterMigrationToNewClasseDuringTheSameSchoolYear implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $tenantId,
        public int $student_id,
        public int $oldClasseId,
        public ?int $school_year_id = null,
        public ?int $author_id = null,
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

            $director = User::first();

            if(!$director){

                $this->fail("COMPTE DIRECTEUR INEXISTANT : IMPOSSIBLE DE FAIRE UNE QUELCONQUE ACTION AVANT LE COMPTE DIRECTEUR");

                return;

            }

            $student = Student::find($this->student_id);

            if($student){

                if(!$this->school_year_id) $schoolYear = SchoolYear::current()->first();

                $schoolYear = SchoolYear::find($this->school_year_id);

                if($schoolYear && $schoolYear->is_active){

                    $oldClasse = Classe::find($this->oldClasseId);

                    if($oldClasse){


                        $newClasse = $student->currentClasse()->classe;

                        if($newClasse){

                            DB::transaction(function () use ($oldClasse, $newClasse, $schoolYear, $student) {
                                
                                $subjectIdsInNewClasse = $newClasse->classeSubjects()->pluck('subject_id')->all();

                                $oldClasse->marks()
                                            ->where('school_year_id', $schoolYear->id)
                                            ->where('student_id', $student->id)
                                            ->each(function ($mark) use ($newClasse, $subjectIdsInNewClasse) {
                                                if (in_array($mark->subject_id, $subjectIdsInNewClasse)) {
                                                    $mark->update(['classe_id' => $newClasse->id]);
                                                }
                                            });

                                $oldClasse->marks()->where('school_year_id', $schoolYear->id)->where('student_id', $student->id)->each(function($mark) use($newClasse) {

                                    $subject_id = $mark->subject_id;

                                    $subject_exists_in_new_classe = $newClasse->classeSubjects()->where('subject_id', $subject_id)?->exists();

                                    if($subject_exists_in_new_classe){

                                        $exists = Mark::where('classe_id', $newClasse->id)
                                            ->where('student_id', $mark->student_id)
                                            ->where('subject_id', $mark->subject_id)
                                            ->where('period_id', $mark->period_id)
                                            ->exists();

                                        if (! $exists) {
                                            $mark->update(['classe_id' => $newClasse->id]);
                                        }

                                    }
                                });
                            });

                        }
                        else{

                            $error_message = "La nouvelle classe de l'apprenant " . $student->getFullName() . " est introuvable !";

                            $title = "ERREUR DE MIGRATION DE NOTES : NOUVELLE CLASSE INTROUVABLE";

                            $this->failer($director, $title, $error_message);

                            return;
                        }


                    }
                    else{

                        $error_message = "La classe de provenance de l'apprenant " . $student->getFullName() . " est introuvable !";

                        $title = "ERREUR DE MIGRATION DE NOTES : ANCIENNE CLASSE INTROUVABLE";

                        $this->failer($director, $title, $error_message);

                        return;
                    }

                }
                else{

                    $error_message = "L'année scolaire est introuvable ou peut-être n'est pas active!";

                    $title = "Erreur de migration des notes apprenant vers sa nouvelle classe ";

                    $this->failer($director, $title, $error_message);

                    return;
                }
            }
            else{

                $error_message = "L'apprenant est introuvable!";

                $title = "Erreur de migration des notes apprenant vers sa nouvelle classe ";

                $this->failer($director, $title, $error_message);

                return;
            }

        } catch (\Throwable $th) {

            $title = "ECHEC DE MIGRATION DES NOTES APPRENANT SA NOUVELLE CLASSE ";

            $error_message = cutter($th->getMessage(), 2000);

            $this->failer($director, $title, $error_message);
        }
        finally{

            broadcast(new DataUpdatedEvent($this->tenantId));

            tenancy()->end();
        }
    }


    public function failer(User $director, string $title, string $error_message) : void
    {
        $director?->notify(new RealTimeNotification(
            userEmail: $director->email,
            tenantId:  $this->tenantId,
            title:     $title,
            message:   $error_message,
            type:      'error',
        ));

        $this->fail($error_message);

        return;
    }
}
