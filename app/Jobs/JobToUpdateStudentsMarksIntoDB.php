<?php

namespace App\Jobs;

use App\Events\DataUpdatedEvent;
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
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class JobToUpdateStudentsMarksIntoDB implements ShouldQueue
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
        public array $data,
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

        tenancy()->initialize($tenant);

        try {
            $this->processUpdates();
        } finally {

            broadcast(new DataUpdatedEvent($this->tenantId));
            
            tenancy()->end();
        }
    }

    private function processUpdates(): void
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
                title:             "NOTES DE CLASSE NON MISES A JOUR",
                message:           "Votre compte est bloqué, vous ne pouvez pas mettre à jour de notes!",
                type:              'error',
            ));

            $this->fail("Compte enseignant bloqué");

            return;
        }


        if(!$teacher->hasValidAccessForYear()){

            $teacher->user?->notify(new RealTimeNotification(
                userEmail: $teacher->user?->email,
                tenantId: $this->tenantId,
                title:             "NOTES DE CLASSE NON MISES A JOUR",
                message:           "Vous n'avez pas d'accès aux classes cette année ou il a été désactivé!",
                type:              'error',
            ));

            $this->fail("Vous n'avez pas d'accès aux classes cette année ou il a été désactivé!");

            return;
        }

        if(!$classe || !$subject){

            $error_message = "La mise à jour des notes des apprenants de la classe que vous avez lancé a échoué car la matière ou la classe n'a pas été trouvée ou a été supprimée";

            if($classe){

                $error_message = "La mise à jour des notes des apprenants de la classe {$classe->name} que vous avez lancé a échoué car la matière n'a pas été trouvée ou a été supprimée";
            }
            elseif($subject){

                $error_message = "La mise à jour des notes de {$subject->name} des apprenants que vous avez lancé a échoué car la classe n'a pas été trouvée ou a été supprimée";
            }

            $teacher->user?->notify(new RealTimeNotification(
                userEmail: $teacher->user?->email,
                tenantId: $this->tenantId,
                title:             "NOTES DE CLASSE NON MISES A JOUR",
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
                title:             "NOTES DE CLASSE NON MISES A JOUR",
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
                title:             "NOTES DE CLASSE NON MISES A JOUR",
                message:           "Votre accès à la classe {$this->classe_name} a été désactivé!",
                type:              'error',
            ));

            $this->fail("Votre accès à la classe {$this->classe_name} a été désactivé!");

            return;
        }
        
        $devoirTypes = $this->devoirTypesForTenant();

        $schoolYearId = $this->schoolYearId;

        foreach ($this->data as $studentPayload) {

            $studentId = (int) ($studentPayload['student_id'] ?? 0);
            $editedMarks = $studentPayload['marks'] ?? [];

            if (!$studentId || empty($editedMarks)) continue;

            $student = Student::find($studentId);

            if(!$student){
                
            }

            try {
                DB::transaction(function () use ($studentId, $editedMarks, $schoolYearId, $devoirTypes) {

                    $editedTypes = array_keys($editedMarks);

                    // Verrouille les lignes concernées (actives) le temps de la transaction.
                    Mark::query()
                        ->where('student_id', $studentId)
                        ->where('subject_id', $this->subjectId)
                        ->where('school_year_id', $schoolYearId)
                        ->where('period', $this->period)
                        ->whereIn('type', $editedTypes)
                        ->lockForUpdate()
                        ->get();

                    // ─── INTERROS : reclassement, jamais de trou avant la dernière valeur ───
                    // array_intersect préserve l'ordre de INTERRO_TYPES : le résultat est
                    // toujours un préfixe contigu (interro1, interro2, ...).
                    $editedInterroTypes = array_values(array_intersect(self::INTERRO_TYPES, $editedTypes));

                    if (!empty($editedInterroTypes)) {

                        $orderedValues = [];

                        foreach ($editedInterroTypes as $type) {
                            $value = $editedMarks[$type];

                            if (is_null($value)) continue;

                            $orderedValues[] = $this->validatedValue($value, $type);
                        }

                        foreach ($editedInterroTypes as $index => $targetType) {

                            $value = $orderedValues[$index] ?? null;

                            if (is_null($value)) {
                                $this->removeMark($studentId, $schoolYearId, $targetType);
                            } else {
                                $this->upsertMark($studentId, $schoolYearId, $targetType, $value);
                            }
                        }
                    }

                    // ─── DEVOIRS : indépendants, jamais de reclassement entre devoir1 et devoir2/compo ───
                    $editedDevoirTypes = array_values(array_intersect($devoirTypes, $editedTypes));

                    foreach ($editedDevoirTypes as $type) {

                        $value = $editedMarks[$type];

                        if (is_null($value)) {
                            $this->removeMark($studentId, $schoolYearId, $type);
                        } else {
                            $this->upsertMark($studentId, $schoolYearId, $type, $this->validatedValue($value, $type));
                        }
                    }
                });
            } catch (\Throwable $e) {
                report($e);
                
                $message = "La mise à jour des notes de {$this->subject_name} de l'apprenant {$student->getFullName()} (classe : {$this->classe_name}) a échoué. Les raisons : " . cutter($e->getMessage(), 1200);

                $teacher->user?->notify(new RealTimeNotification(
                    userEmail: $teacher->user?->email,
                    tenantId: $this->tenantId,
                    title:             "NOTES NON MISES A JOUR",
                    message:           $message,
                    type:              'error',
                ));
            }
            
        }

        $message = "La mise à jour des notes de {$this->subject_name} de la classe {$this->classe_name} que vous lancé est terminé.";

        $teacher->user?->notify(new RealTimeNotification(
            userEmail: $teacher->user?->email,
            tenantId: $this->tenantId,
            title:             "MISE A JOUR DE NOTES DE CLASSE TERMINEE",
            message:           $message,
            type:              'success',
        ));
    }


    /**
     * Crée la note, ou restaure + met à jour une ligne soft-supprimée existante.
     * Nécessaire car la contrainte unique 'uniq_mark' ne tient pas compte de
     * deleted_at : un simple create() planterait si la ligne a déjà été
     * soft-supprimée puis qu'on la recrée avec le même type.
     */
    private function upsertMark(int $studentId, int $schoolYearId, string $type, float $value): void
    {
        $existing = Mark::withTrashed()
            ->where('student_id', $studentId)
            ->where('subject_id', $this->subjectId)
            ->where('school_year_id', $schoolYearId)
            ->where('period', $this->period)
            ->where('type', $type)
            ->first();

        if ($existing) {
            if ($existing->trashed()) {
                $existing->restore();
            }

            $existing->update([
                'classe_id'  => $this->classeId,
                'teacher_id' => $this->teacherId,
                'value'      => $value,
            ]);

            return;
        }

        Mark::create([
            'student_id'     => $studentId,
            'classe_id'      => $this->classeId,
            'subject_id'     => $this->subjectId,
            'school_year_id' => $schoolYearId,
            'teacher_id'     => $this->teacherId,
            'period'         => $this->period,
            'type'           => $type,
            'value'          => $value,
        ]);
    }

    /**
     * Retire une note (soft delete). Le futur upsertMark() saura la restaurer
     * si une valeur revient un jour sur ce même créneau.
     */
    private function removeMark(int $studentId, int $schoolYearId, string $type): void
    {
        Mark::query()
            ->where('student_id', $studentId)
            ->where('subject_id', $this->subjectId)
            ->where('school_year_id', $schoolYearId)
            ->where('period', $this->period)
            ->where('type', $type)
            ->delete();
    }

    private function validatedValue(mixed $value, string $type): float
    {
        $value = round((float) $value, 2);

        if ($value < 0 || $value > 20) {
            throw new \RuntimeException("Valeur invalide pour \"{$type}\" : doit être comprise entre 0 et 20.");
        }

        return $value;
    }

    private function devoirTypesForTenant(): array
    {
        $tenant = tenancy()->tenant;

        return $tenant->devoirs_type === 'devoir1-devoir2'
            ? ['devoir1', 'devoir2']
            : ['devoir1', 'compo'];
    }
}