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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class JobToUpdateStudentsMarksIntoDB implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private const INTERRO_TYPES = ['interro1', 'interro2', 'interro3', 'interro4'];

    public string $classe_name = '';

    public string $subject_name = '';

    public string $schoolYearSlug = '';

    /**
     * Délai maximum (en heures, depuis la saisie) pendant lequel une note reste
     * modifiable ou supprimable. null = pas de limite.
     * Alimenté depuis le tenant dans handle().
     */
    public ?int $mark_updatable_time_delay = 72;

    public function __construct(
        public string $tenantId,
        public int $teacherId,
        public int $classeId,
        public int $subjectId,
        public int $period,
        public array $data,
        public ?int $schoolYearId = null,
    ) {}

    // ─────────────────────────────────────────────────────────────────────
    //  Point d'entrée
    // ─────────────────────────────────────────────────────────────────────

    public function handle(): void
    {
        $tenant = Tenant::find($this->tenantId);

        if (!$tenant) {
            report(new \RuntimeException("JobToUpdateStudentsMarksIntoDB : tenant #{$this->tenantId} introuvable, notes non traitées."));
            return;
        }

        // Un seul initialize, avant toute requête sur la base du tenant.
        tenancy()->initialize($tenant);

        try {
            $delay = $tenant->mark_updatable_time_delay;

            $this->mark_updatable_time_delay = is_null($delay) ? null : (int) $delay;

            if (!User::first()) {
                $this->fail("COMPTE DIRECTEUR INEXISTANT : IMPOSSIBLE D'ENREGISTRER DES NOTES AVANT LE COMPTE DIRECTEUR");
                return;
            }

            if (!$this->resolveSchoolYear()) {
                return;
            }

            try {
                $this->processUpdates();
            } finally {
                broadcast(new DataUpdatedEvent($this->tenantId));
            }
        } finally {
            tenancy()->end();
        }
    }

    /**
     * Résout l'année scolaire à utiliser. Retourne false si le job doit s'arrêter.
     */
    private function resolveSchoolYear(): bool
    {
        if (!$this->schoolYearId) {

            $schoolYear = SchoolYear::current()->first();

            if (!$schoolYear) {
                report(new \RuntimeException("JobToUpdateStudentsMarksIntoDB : aucune année scolaire active pour le tenant #{$this->tenantId}, notes non traitées."));
                return false;
            }

            $this->schoolYearId = $schoolYear->id;
        } else {

            $schoolYear = SchoolYear::find($this->schoolYearId);

            if (!$schoolYear || !$schoolYear->is_active || $schoolYear->is_closed) {
                report(new \RuntimeException("JobToUpdateStudentsMarksIntoDB : année scolaire non active pour le tenant #{$this->tenantId}, notes non traitées."));
                return false;
            }
        }

        $this->schoolYearSlug = $schoolYear->slug;

        return true;
    }

    // ─────────────────────────────────────────────────────────────────────
    //  Traitement principal
    // ─────────────────────────────────────────────────────────────────────

    private function processUpdates(): void
    {
        $teacher = Teacher::find($this->teacherId);

        if (!$teacher) {
            $this->fail("Compte enseignant introuvable ou supprimé");
            return;
        }

        if ($teacher->blocked || $teacher->user?->blocked) {
            $this->abortWithNotification($teacher, "Votre compte est bloqué, vous ne pouvez pas mettre à jour de notes!", "Compte enseignant bloqué");
            return;
        }

        if (!$teacher->hasValidAccessForYear()) {
            $this->abortWithNotification($teacher, "Vous n'avez pas d'accès aux classes cette année ou il a été désactivé!");
            return;
        }

        $classe = Classe::find($this->classeId);

        $subject = Subject::find($this->subjectId);

        if (!$classe || !$subject) {

            $error_message = "La mise à jour des notes des apprenants de la classe que vous avez lancée a échoué car la matière ou la classe n'a pas été trouvée ou a été supprimée";

            if ($classe) {
                $error_message = "La mise à jour des notes des apprenants de la classe {$classe->name} que vous avez lancée a échoué car la matière n'a pas été trouvée ou a été supprimée";
            } elseif ($subject) {
                $error_message = "La mise à jour des notes de {$subject->name} des apprenants que vous avez lancée a échoué car la classe n'a pas été trouvée ou a été supprimée";
            }

            $this->abortWithNotification($teacher, $error_message);
            return;
        }

        $this->classe_name = $classe->name;

        $this->subject_name = $subject->name;

        if (!$classe->is_active || $classe->is_locked) {
            $this->abortWithNotification($teacher, "La classe {$this->classe_name} n'est pas active ou a été fermée!");
            return;
        }

        if ($classe->locked_for_teachers && in_array($this->teacherId, $classe->locked_for_teachers)) {
            $this->abortWithNotification($teacher, "Votre accès à la classe {$this->classe_name} a été désactivé!");
            return;
        }

        $devoirTypes = $this->devoirTypesForTenant();

        $schoolYearId = $this->schoolYearId;

        $failures = 0;

        foreach ($this->data as $studentPayload) {

            $studentId = (int) ($studentPayload['student_id'] ?? 0);

            $editedMarks = $studentPayload['marks'] ?? [];

            if (!$studentId || empty($editedMarks)) continue;

            $student = Student::find($studentId);

            if (!$student) {
                $failures++;
                continue;
            }

            try {
                DB::transaction(function () use ($studentId, $editedMarks, $schoolYearId, $devoirTypes) {
                    $this->updateStudentMarks($studentId, $editedMarks, $schoolYearId, $devoirTypes);
                });
            } catch (\Throwable $e) {
                $failures++;

                report($e);

                $this->notifyTeacher(
                    $teacher,
                    "NOTES NON MISES A JOUR",
                    "La mise à jour des notes de {$this->subject_name} de l'apprenant {$student->getFullName()} (classe : {$this->classe_name}) a échoué. Les raisons : " . cutter($e->getMessage(), 1200),
                    'error'
                );
            }
        }

        if ($failures > 0) {
            $this->notifyTeacher(
                $teacher,
                "MISE A JOUR DE NOTES DE CLASSE TERMINEE AVEC ERREURS",
                "La mise à jour des notes de {$this->subject_name} de la classe {$this->classe_name} est terminée, mais {$failures} apprenant(s) n'ont pas pu être mis à jour.",
                'warning'
            );

            return;
        }

        $this->notifyTeacher(
            $teacher,
            "MISE A JOUR DE NOTES DE CLASSE TERMINEE",
            "La mise à jour des notes de {$this->subject_name} de la classe {$this->classe_name} que vous avez lancée est terminée.",
            'success'
        );
    }

    /**
     * Applique les modifications d'UN apprenant. À appeler dans une transaction :
     * toute exception (valeur invalide, délai dépassé...) annule l'ensemble.
     */
    private function updateStudentMarks(int $studentId, array $editedMarks, int $schoolYearId, array $devoirTypes): void
    {
        $editedTypes = array_keys($editedMarks);

        // Verrouille les lignes concernées (actives) le temps de la transaction.
        $this->marksQuery($studentId, $schoolYearId)
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
    }

    // ─────────────────────────────────────────────────────────────────────
    //  Notes : création, modification, suppression
    // ─────────────────────────────────────────────────────────────────────

    /**
     * Crée la note, ou met à jour la ligne existante (en restaurant une ligne
     * soft-supprimée si besoin). Nécessaire car la contrainte unique 'uniq_mark'
     * ne tient pas compte de deleted_at : un simple create() planterait.
     *
     * Le délai de modification n'est contrôlé que si la valeur change réellement :
     * une ancienne note laissée telle quelle ne bloque donc rien.
     *
     * @throws \RuntimeException si la note existe, change de valeur et a dépassé le délai
     */
    private function upsertMark(int $studentId, int $schoolYearId, string $type, float $value): void
    {
        $existing = $this->marksQuery($studentId, $schoolYearId)
            ->withTrashed()
            ->where('type', $type)
            ->first();

        if ($existing) {

            if ($existing->trashed()) {

                $existing->restore();

            } elseif ((float) $existing->value === $value) {

                return; // valeur identique : rien à écrire

            } elseif ($this->isMarkLocked($existing)) {

                throw new \RuntimeException(
                    "La note \"{$type}\" a été saisie il y a plus de "
                    . formatHoursDuration($this->mark_updatable_time_delay)
                    . " : elle ne peut plus être modifiée."
                );
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
     * Retire une note (suppression définitive), si elle est encore dans le délai.
     *
     * On charge les modèles puis on les supprime un par un : un forceDelete() en
     * masse sur le query builder ne déclenche aucun événement Eloquent, donc le
     * trait InvalidatesClasseSubjectMarksCache n'invaliderait pas les caches.
     *
     * @throws \RuntimeException si le délai est dépassé
     */
    private function removeMark(int $studentId, int $schoolYearId, string $type): void
    {
        $marks = $this->marksQuery($studentId, $schoolYearId)
            ->where('type', $type)
            ->get();

        foreach ($marks as $mark) {

            if ($this->isMarkLocked($mark)) {
                throw new \RuntimeException(
                    "La note \"{$type}\" a été saisie il y a plus de "
                    . formatHoursDuration($this->mark_updatable_time_delay)
                    . " : elle ne peut plus être supprimée."
                );
            }

            $mark->forceDelete();
        }
    }

    /**
     * Indique si une note a dépassé le délai de modification/suppression.
     * null = pas de limite.
     */
    private function isMarkLocked(Mark $mark): bool
    {
        if ($this->mark_updatable_time_delay === null) {
            return false;
        }

        return $mark->created_at->lt(now()->subHours($this->mark_updatable_time_delay));
    }

    /**
     * Requête de base : notes d'un apprenant pour la matière et la période du job.
     */
    private function marksQuery(int $studentId, int $schoolYearId): Builder
    {
        return Mark::query()
            ->where('student_id', $studentId)
            ->where('subject_id', $this->subjectId)
            ->where('school_year_id', $schoolYearId)
            ->where('period', $this->period);
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

    // ─────────────────────────────────────────────────────────────────────
    //  Notifications
    // ─────────────────────────────────────────────────────────────────────

    private function notifyTeacher(Teacher $teacher, string $title, string $message, string $type): void
    {
        $teacher->user?->notify(new RealTimeNotification(
            userEmail: $teacher->user?->email,
            tenantId: $this->tenantId,
            title: $title,
            message: $message,
            type: $type,
        ));
    }

    /**
     * Notifie l'enseignant de l'échec global puis marque le job comme échoué.
     */
    private function abortWithNotification(Teacher $teacher, string $message, ?string $failReason = null): void
    {
        $this->notifyTeacher($teacher, "NOTES DE CLASSE NON MISES A JOUR", $message, 'error');

        $this->fail($failReason ?? $message);
    }
}