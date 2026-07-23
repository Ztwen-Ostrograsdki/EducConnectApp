<?php

namespace App\Jobs;

use App\Models\Classe;
use App\Models\Mark;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Tenant;
use App\Models\User;
use App\Notifications\RealTimeNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\Attributes\Timeout;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;


#[Timeout(300)]
class JobToCreateStudentsMarksIntoDB implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private const INTERRO_TYPES = ['interro1', 'interro2', 'interro3', 'interro4'];

    public $classe_name = '';

    public $subject_name = '';

    public $schoolYearSlug = '';

    public function __construct(
        public string $tenantId,
        public int $teacherId,
        public int $classeId,
        public int $subjectId,
        public int $period,
        public array $data, // [ ['student_id' => .., 'marks' => ['interro1' => 12, 'devoir1' => 14, ...]], ... ]
        public ?int $schoolYearId = null,
    ) {}

    public function handle(): void
    {
        $tenant = Tenant::find($this->tenantId);

        if (!$tenant) {
            report(new \RuntimeException("JobToCreateStudentsMarksIntoDB : tenant #{$this->tenantId} introuvable, notes non traitées."));
            return;
        }

        tenancy()->initialize($this->tenantId);

        $director = User::first();

        if(!$director){

            $this->fail("COMPTE DIRECTEUR INEXISTANT : IMPOSSIBLE D'ENREGISTRER DES NOTES AVANT LE COMPTE DIRECTEUR");

            return;
        }

        $schoolYearId = $this->schoolYearId ?? tenancy()->tenant->getActiveSchoolYear()?->id;

        if(!$this->schoolYearId){

            $schoolYear  = SchoolYear::current()->first();

            if(!$schoolYear){

                report(new \RuntimeException("JobToCreateStudentsMarksIntoDB : aucune année scolaire active pour le tenant #{$this->tenantId}, notes non traitées."));
                return;
            }

            $this->schoolYearId = $schoolYear->id;

            $this->schoolYearSlug = $schoolYear->slug;
        }
        else{

            $schoolYear  = SchoolYear::find($this->schoolYearId);

            if(!$schoolYear || !$schoolYear->is_active || $schoolYear->is_closed){

                report(new \RuntimeException("JobToCreateStudentsMarksIntoDB : année scolaire non active pour le tenant #{$this->tenantId}, notes non traitées."));
                return;
            }

            $this->schoolYearSlug = $schoolYear->slug;

        }

        try {
            $this->processMarks();
        } finally {
            tenancy()->end();
        }
    }

    private function processMarks(): void
    {
        $teacher = Teacher::find($this->teacherId);

        $classe = Classe::find($this->classeId);

        $subject = Subject::find($this->subjectId);

        if(!$teacher){

            $this->fail("Compte enseignant introuvable ou supprimé");

            return;

        }

        if($teacher->blocked || $teacher?->user->blocked){

            $teacher->user?->notify(new RealTimeNotification(
                userEmail: $teacher->user?->email,
                tenantId: $this->tenantId,
                title:             "NOTES DE CLASSE NON ENREGISTRES",
                message:           "Votre compte est bloqué, vous ne pouvez pas enregistrer de notes!",
                type:              'error',
            ));

            $this->fail("Compte enseignant bloqué");

            return;
        }


        if(!$teacher->hasValidAccessForYear()){

            $teacher->user?->notify(new RealTimeNotification(
                userEmail: $teacher->user?->email,
                tenantId: $this->tenantId,
                title:             "NOTES DE CLASSE NON ENREGISTRES",
                message:           "Vous n'avez pas d'accès aux classes cette année ou il a été désactivé!",
                type:              'error',
            ));

            $this->fail("Vous n'avez pas d'accès aux classes cette année ou il a été désactivé!");

            return;
        }

        if(!$classe || !$subject){

            $error_message = "L'enregistrement des notes des apprenants de la classe que vous avez lancé a échoué car la matière ou la classe n'a pas été trouvée ou a été supprimée";

            if($classe){

                $error_message = "L'enregistrement des notes des apprenants de la classe {$classe->name} que vous avez lancé a échoué car la matière n'a pas été trouvée ou a été supprimée";
            }
            elseif($subject){

                $error_message = "L'enregistrement des notes de {$subject->name} des apprenants que vous avez lancé a échoué car la classe n'a pas été trouvée ou a été supprimée";
            }

            $teacher->user?->notify(new RealTimeNotification(
                userEmail: $teacher->user?->email,
                tenantId: $this->tenantId,
                title:             "NOTES DE CLASSE NON ENREGISTRES",
                message:           $error_message,
                type:              'error',
            ));

            $this->fail($error_message);

            return;

        }

        $this->classe_name = $classe->name;

        $this->subject_name = $subject->name;

        if(!$classe->is_active || $classe->is_locked){

            $teacher->user?->notify(new RealTimeNotification(
                userEmail: $teacher->user?->email,
                tenantId: $this->tenantId,
                title:             "NOTES DE CLASSE NON ENREGISTRES",
                message:           "La classe {$this->classe_name} n'est pas active ou a été fermée!",
                type:              'error',
            ));

            $this->fail("La classe {$this->classe_name} n'est pas active ou a été fermée!");

            return;

        }

        if(in_array($this->teacherId, $classe->locked_for_teachers)){

            $teacher->user?->notify(new RealTimeNotification(
                userEmail: $teacher->user?->email,
                tenantId: $this->tenantId,
                title:             "NOTES DE CLASSE NON ENREGISTRES",
                message:           "Votre accès à la classe {$this->classe_name} a été désactivé!",
                type:              'error',
            ));

            $this->fail("Votre accès à la classe {$this->classe_name} a été désactivé!");

            return;
        }

        $devoirTypes = $this->devoirTypesForTenant();

        $successCount = 0;

        $totalCount = count($this->data);

        $errors = [];

        foreach ($this->data as $studentPayload) {

            $studentId = (int) ($studentPayload['student_id'] ?? 0);

            $marks = $studentPayload['marks'] ?? [];

            if (!$studentId || empty($marks)) continue;

            $student = Student::find($studentId);

            if(!$student){

                $errors[] = [
                    'student_id' => $studentId,
                    'student_name' => "Introuvable",
                    'message'    => "Apprenant introuvable ou supprimé",
                ];
            }

            try {


                DB::transaction(function () use ($studentId, $marks, $devoirTypes) {

                    // Verrouille les lignes existantes pour ce couple (student, subject, period, year)
                    // afin d'éviter une race condition avec une autre soumission concurrente.
                    $existingTypes = Mark::query()
                        ->where('student_id', $studentId)
                        ->where('subject_id', $this->subjectId)
                        ->where('school_year_id', $this->schoolYearId)
                        ->where('period', $this->period)
                        ->lockForUpdate()
                        ->pluck('type')
                        ->toArray();

                    foreach ($marks as $type => $value) {

                        // Revalidation défensive des quotas, indépendamment de ce
                        // que le Livewire avait calculé côté client/session.
                        if (in_array($type, $existingTypes, true)) {
                            throw new \RuntimeException("Type de note \"{$type}\" déjà enregistré pour cet apprenant (course concurrente détectée).");
                        }

                        if (in_array($type, self::INTERRO_TYPES, true)) {
                            $countInterro = count(array_intersect($existingTypes, self::INTERRO_TYPES));
                            if ($countInterro >= 4) {
                                throw new \RuntimeException("Quota de 4 interrogations déjà atteint pour cet apprenant.");
                            }
                        }

                        if (in_array($type, $devoirTypes, true)) {
                            $countDevoir = count(array_intersect($existingTypes, $devoirTypes));
                            if ($countDevoir >= 2) {
                                throw new \RuntimeException("Quota de 2 devoirs déjà atteint pour cet apprenant.");
                            }
                        }

                        Mark::create([
                            'student_id'     => $studentId,
                            'classe_id'      => $this->classeId,
                            'subject_id'     => $this->subjectId,
                            'school_year_id' => $this->schoolYearId,
                            'teacher_id'     => $this->teacherId,
                            'period'         => $this->period,
                            'type'           => $type,
                            'value'          => $value,
                        ]);

                        $existingTypes[] = $type;
                    }
                });

                $successCount++;

            } catch (\Throwable $e) {
                $errors[] = [
                    'student_id' => $studentId,
                    'student_name' => $student->getFullName(),
                    'message'    => cutter($e->getMessage(), 1200),
                ];

                $message = "L'enregistrement des notes de {$this->subject_name} de l'apprenant {$student->getFullName()} (classe : {$this->classe_name}) a échoué. Les raisons : " . cutter($e->getMessage(), 1200);

                $teacher->user?->notify(new RealTimeNotification(
                    userEmail: $teacher->user?->email,
                    tenantId: $this->tenantId,
                    title:             "NOTES NON ENREGISTRES",
                    message:           $message,
                    type:              'error',
                ));
            }
        }

        $message = "L'enregistrement de notes de {$this->subject_name} de la classe {$this->classe_name} que vous lancé est terminé. Nous avons enregistrés {$successCount} enregistrements réussis sur les {$totalCount} enregistrements que vous aviez lancés!";

        $teacher->user?->notify(new RealTimeNotification(
            userEmail: $teacher->user?->email,
            tenantId: $this->tenantId,
            title:             "ENREGISTREMENT DE NOTES DE CLASSE TERMINEE",
            message:           $message,
            type:              'success',
        ));

        if($errors){

            $errorsCount = count($errors);

            $students_faileds = '';

            foreach ($errors as $key => $er) {
                
                $students_faileds .= " - " . $er['student_name'];
            }

            $message = "L'enregistrement de notes de {$this->subject_name} de la classe {$this->classe_name} que vous lancé est terminé. Nous avons rencontrés {$errorsCount} erreurs sur les apprenants suivants : {$students_faileds}";

            $teacher->user?->notify(new RealTimeNotification(
                userEmail: $teacher->user?->email,
                tenantId: $this->tenantId,
                title:             "ENREGISTREMENTS DE NOTES ECHOUES",
                message:           $message,
                type:              'error',
            ));

        }

        // $successCount / $errors disponibles ici pour ta notification.
    }

    private function devoirTypesForTenant(): array
    {
        $tenant = tenancy()->tenant;

        return $tenant->devoirs_type === 'devoir1-devoir2'
            ? ['devoir1', 'devoir2']
            : ['devoir1', 'compo'];
    }
}