<?php

namespace App\Livewire\Tenants\ActionsTraits;

use App\Events\DataUpdatedEvent;
use App\Models\ClasseSubjectOfSchoolYear;
use App\Models\TimePlan;
use App\Models\TimePlanSlot;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\On;
use WireUi\Traits\WireUiActions;

/**
 * Actions & helpers partagés pour la gestion des emplois du temps.
 *
 * Le composant hôte doit exposer (pour le CRUD créneau) :
 * - bool   $showSlotForm
 * - ?int   $slotId
 * - ?int   $slotFormPlanId
 * - ?int   $assignment_id
 * - int    $day_of_week
 * - string $starts_at, $ends_at, $slot_label, $slot_notes
 *
 * Aucune dépendance à currentPlan / timePlan en lecture métier.
 */
trait TimePlanActions
{
    use WireUiActions;

    public $counter = 0;

    // Formulaire créneau (requis par TimePlanActions)
    public ?int $slotId = null;
    public ?int $assignment_id = null;
    public int $day_of_week = 1;
    public string $starts_at = '08:00';
    public string $ends_at = '10:00';
    public string $slot_label = '';
    public string $slot_notes = '';

    public bool $showPlanForm = false;
    public bool $showSlotForm = false;
    public ?int $slotFormPlanId = null;

    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata(): void
    {
        $this->counter++;
        $this->refreshPlansData();
    }

    /**
     * Invalide les computed du composant hôte (dashboard, page classe, …).
     */
    protected function refreshPlansData(): void
    {
        try {
            unset(
                $this->plans,
                $this->currentPlan,
                $this->assignments,
                $this->timePlan,
                $this->slotsByDay,
                $this->timeRanges,
                $this->teacherSlots,
                $this->teacherAssignments,
            );
        } catch (\Throwable) {
            // silencieux
        }
    }

    // ─────────────────────────────────────────────────────────────
    //  GRILLE HORAIRE (partagé)
    // ─────────────────────────────────────────────────────────────

    public function weekDays(): array
    {
        return [
            1 => 'Lundi',
            2 => 'Mardi',
            3 => 'Mercredi',
            4 => 'Jeudi',
            5 => 'Vendredi',
            6 => 'Samedi',
        ];
    }

    /** Alias pour la page classe (blade utilise $this->days). */
    public function days(): array
    {
        return $this->weekDays();
    }

    public function scheduleRows(): array
    {
        return [
            ['type' => 'hour', 'start' => '07:00', 'end' => '08:00'],
            ['type' => 'hour', 'start' => '08:00', 'end' => '09:00'],
            ['type' => 'hour', 'start' => '09:00', 'end' => '10:00'],
            ['type' => 'break', 'label' => 'Récréation', 'start' => '10:00', 'end' => '10:00', 'variant' => 'recreation'],
            ['type' => 'hour', 'start' => '10:00', 'end' => '11:00'],
            ['type' => 'hour', 'start' => '11:00', 'end' => '12:00'],
            ['type' => 'hour', 'start' => '12:00', 'end' => '13:00'],
            ['type' => 'break', 'label' => 'Pause déjeuner', 'start' => '13:00', 'end' => '14:00', 'variant' => 'lunch'],
            ['type' => 'hour', 'start' => '14:00', 'end' => '15:00'],
            ['type' => 'hour', 'start' => '15:00', 'end' => '16:00'],
            ['type' => 'hour', 'start' => '16:00', 'end' => '17:00'],
            ['type' => 'hour', 'start' => '17:00', 'end' => '18:00'],
            ['type' => 'hour', 'start' => '18:00', 'end' => '19:00'],
        ];
    }

    public function normalizeTime(string $time): string
    {
        return substr($time, 0, 5);
    }

    protected function scheduleHourIndex(string $rowStart): int
    {
        foreach ($this->scheduleRows() as $i => $row) {
            if (($row['type'] ?? '') === 'hour' && $row['start'] === $rowStart) {
                return $i;
            }
        }

        return -1;
    }

    /**
     * Créneaux d’un plan groupés par day_of_week (1→6).
     */
    public function slotsByDayFor(int $planId): Collection
    {
        $slots = TimePlanSlot::query()
            ->where('time_plan_id', $planId)
            ->orderBy('starts_at')
            ->get();

        return collect($this->weekDays())->mapWithKeys(
            fn ($label, $day) => [$day => $slots->where('day_of_week', $day)->values()]
        );
    }

    /**
     * Rowspan à partir d’une ligne horaire : heures contiguës uniquement,
     * s’arrête avant récréation / pause déjeuner.
     */
    public function slotContiguousSpan(TimePlanSlot $slot, string $fromRowStart): int
    {
        $slotStart = $this->normalizeTime((string) $slot->starts_at);
        $slotEnd = $this->normalizeTime((string) $slot->ends_at);
        $rows = $this->scheduleRows();
        $startIdx = $this->scheduleHourIndex($fromRowStart);

        if ($startIdx < 0) {
            return 1;
        }

        $span = 0;
        for ($i = $startIdx, $n = count($rows); $i < $n; $i++) {
            $row = $rows[$i];

            if (($row['type'] ?? '') === 'break') {
                break;
            }

            if (($row['type'] ?? '') !== 'hour') {
                break;
            }

            if ($row['start'] >= $slotEnd || $row['end'] <= $slotStart) {
                break;
            }

            $span++;
        }

        return max(1, $span);
    }

    /**
     * Créneau à afficher sur une cellule : début exact, ou reprise après pause.
     */
    public function slotFragmentAt(int $planId, int $day, string $rowStart): ?TimePlanSlot
    {
        $daySlots = $this->slotsByDayFor($planId)[$day] ?? collect();

        $starting = $daySlots->first(
            fn (TimePlanSlot $slot) => $this->normalizeTime((string) $slot->starts_at) === $rowStart
        );

        if ($starting) {
            return $starting;
        }

        $idx = $this->scheduleHourIndex($rowStart);
        if ($idx <= 0) {
            return null;
        }

        $rows = $this->scheduleRows();
        $prev = $rows[$idx - 1] ?? null;
        if (!$prev || ($prev['type'] ?? '') !== 'break') {
            return null;
        }

        return $daySlots->first(function (TimePlanSlot $slot) use ($rowStart) {
            $slotStart = $this->normalizeTime((string) $slot->starts_at);
            $slotEnd = $this->normalizeTime((string) $slot->ends_at);

            return $slotStart < $rowStart && $slotEnd > $rowStart;
        });
    }

    /**
     * Cellule déjà couverte par un rowspan du même bloc contigu (sans traverser une pause).
     */
    public function isCellCoveredBySpan(int $planId, int $day, string $rowStart): bool
    {
        $idx = $this->scheduleHourIndex($rowStart);
        if ($idx < 0) {
            return false;
        }

        $rows = $this->scheduleRows();
        $daySlots = $this->slotsByDayFor($planId)[$day] ?? collect();

        foreach ($daySlots as $slot) {
            $slotStart = $this->normalizeTime((string) $slot->starts_at);
            $slotEnd = $this->normalizeTime((string) $slot->ends_at);

            if (!($slotStart < $rowStart && $slotEnd > $rowStart)) {
                continue;
            }

            $fragmentStartIdx = $this->scheduleHourIndex($slotStart);
            if ($fragmentStartIdx < 0) {
                foreach ($rows as $i => $row) {
                    if (($row['type'] ?? '') === 'hour' && $row['start'] < $slotEnd && $row['end'] > $slotStart) {
                        $fragmentStartIdx = $i;
                        break;
                    }
                }
            }

            if ($fragmentStartIdx < 0) {
                continue;
            }

            $blockedByBreak = false;
            for ($i = $fragmentStartIdx; $i < $idx; $i++) {
                if (($rows[$i]['type'] ?? '') === 'break') {
                    $blockedByBreak = true;
                    $fragmentStartIdx = $i + 1;
                }
            }

            if ($blockedByBreak) {
                if (
                    $fragmentStartIdx < $idx
                    && ($rows[$fragmentStartIdx]['type'] ?? '') === 'hour'
                    && $rows[$fragmentStartIdx]['start'] < $rowStart
                ) {
                    return true;
                }
                continue;
            }

            return true;
        }

        return false;
    }

    public function isSlotLive(TimePlanSlot $slot): bool
    {
        $now = Carbon::now();

        if ((int) $slot->day_of_week !== (int) $now->dayOfWeekIso) {
            return false;
        }

        $start = Carbon::today()->setTimeFromTimeString(
            strlen((string) $slot->starts_at) === 5
                ? $slot->starts_at . ':00'
                : (string) $slot->starts_at
        );

        $end = Carbon::today()->setTimeFromTimeString(
            strlen((string) $slot->ends_at) === 5
                ? $slot->ends_at . ':00'
                : (string) $slot->ends_at
        );

        return $now->betweenIncluded($start, $end);
    }

    /**
     * Affectations actives (matière + enseignant) pour un plan donné.
     */
    public function assignmentsFor(int $planId): Collection
    {
        $plan = TimePlan::query()->find($planId);

        if (!$plan) {
            return collect();
        }

        return ClasseSubjectOfSchoolYear::query()
            ->where('classe_id', $plan->classe_id)
            ->where('school_year_id', $plan->school_year_id)
            ->whereDoesntHave('subject', fn ($q) => $q->whereIn('name', ['conduite', 'Conduite']))
            ->current()
            ->with(['subject', 'teacher', 'classe'])
            ->get()
            ->filter(fn ($assignment) => $assignment->subject && $assignment->teacher)
            ->sortBy(fn ($assignment) => $assignment->subject->name)
            ->values();
    }

    /**
     * Affectations actives d’un enseignant pour une année (classes où il intervient).
     */
    public function assignmentsForTeacher(int $teacherId, int $schoolYearId): Collection
    {
        return ClasseSubjectOfSchoolYear::query()
            ->where('teacher_id', $teacherId)
            ->where('school_year_id', $schoolYearId)
            ->whereDoesntHave('subject', fn ($q) => $q->whereIn('name', ['conduite', 'Conduite']))
            ->current()
            ->with(['subject', 'teacher', 'classe'])
            ->get()
            ->filter(fn ($a) => $a->subject && $a->classe)
            ->sortBy(fn ($a) => $a->classe->name . ' ' . $a->subject->name)
            ->values();
    }

    /**
     * Tous les créneaux d’un enseignant sur une année scolaire.
     */
    public function teacherSlotsFor(int $teacherId, int $schoolYearId): Collection
    {
        return TimePlanSlot::query()
            ->whereHas('timePlan', fn (Builder $q) => $q->where('school_year_id', $schoolYearId)->where('status', 'published'))
            ->whereHas(
                'classeSubjectOfSchoolYear',
                fn (Builder $q) => $q
                    ->where('teacher_id', $teacherId)
                    ->whereNull('ended_at')
                    ->where('is_active', true)
            )
            ->with([
                'timePlan.classe',
                'classeSubjectOfSchoolYear.subject',
                'classeSubjectOfSchoolYear.teacher',
                'classeSubjectOfSchoolYear.classe',
            ])
            ->orderBy('day_of_week')
            ->orderBy('starts_at')
            ->get();
    }

    /**
     * Groupe une collection de créneaux par day_of_week.
     */
    public function slotsByDayFromSlots(Collection $slots): Collection
    {
        return collect($this->weekDays())->mapWithKeys(
            fn ($label, $day) => [$day => $slots->where('day_of_week', $day)->values()]
        );
    }

    /**
     * Résout (ou crée en brouillon) l’emploi du temps d’une classe pour une année.
     */
    public function resolvePlanForClasse(int $classeId, int $schoolYearId): TimePlan
    {
        $plan = TimePlan::query()
            ->where('classe_id', $classeId)
            ->where('school_year_id', $schoolYearId)
            ->first();

        if ($plan) {
            return $plan;
        }

        return TimePlan::query()->create([
            'classe_id'      => $classeId,
            'school_year_id' => $schoolYearId,
            'status'         => 'draft',
            'title'          => null,
            'notes'          => null,
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    //  CRUD CRÉNEAU (par planId)
    // ─────────────────────────────────────────────────────────────

    public function openCreateSlot(?int $planId = null): void
    {
        if ($planId) {
            $plan = TimePlan::query()->find($planId);

            if (!$plan || !$plan->isEditable()) {
                $this->notification()->error(
                    title: 'Action impossible',
                    description: 'Aucun emploi du temps modifiable.',
                );
                return;
            }

            $this->resetSlotForm();
            $this->slotFormPlanId = $planId;
            $this->showSlotForm = true;
            return;
        }

        // Ouverture sans plan (ex. profil enseignant) : le plan sera résolu à la sauvegarde
        $this->resetSlotForm();
        $this->showSlotForm = true;
    }

    public function editSlot(int $slotId, int $planId): void
    {
        $plan = TimePlan::query()->find($planId);

        if (!$plan || !$plan->isEditable()) {
            $this->notification()->error(
                title: 'Action impossible',
                description: 'Cet emploi du temps n’est pas modifiable.',
            );
            return;
        }

        $slot = TimePlanSlot::query()
            ->where('time_plan_id', $planId)
            ->find($slotId);

        if (!$slot) {
            $this->notification()->error(title: 'Créneau introuvable');
            return;
        }

        $this->slotFormPlanId = $planId;
        $this->slotId = $slot->id;
        $this->assignment_id = $slot->classe_subject_of_school_year_id;
        $this->day_of_week = $slot->day_of_week;
        $this->starts_at = substr((string) $slot->starts_at, 0, 5);
        $this->ends_at = substr((string) $slot->ends_at, 0, 5);
        $this->slot_label = $slot->label ?? '';
        $this->slot_notes = $slot->notes ?? '';
        $this->showSlotForm = true;
    }

    public function saveSlot(): void
    {
        $planId = $this->slotFormPlanId ?? null;
        $plan = $planId ? TimePlan::query()->find($planId) : null;

        // Contexte enseignant : résoudre le plan via l’affectation choisie
        if (!$plan && $this->assignment_id) {
            $assignment = ClasseSubjectOfSchoolYear::query()
                ->whereKey($this->assignment_id)
                ->current()
                ->first();

            if ($assignment) {
                $plan = $this->resolvePlanForClasse(
                    (int) $assignment->classe_id,
                    (int) $assignment->school_year_id
                );
                $this->slotFormPlanId = $plan->id;
                $planId = $plan->id;
            }
        }

        if (!$plan) {
            $this->notification()->error(title: 'Emploi du temps introuvable');
            return;
        }

        if (!$plan->isEditable()) {
            $this->notification()->error(
                title: 'Action impossible',
                description: 'Cet emploi du temps est archivé.',
            );
            throw ValidationException::withMessages(['starts_at' => 'Cet emploi du temps est archivé.']);
        }

        $data = $this->validate([
            'assignment_id' => ['required', 'integer'],
            'day_of_week'   => ['required', 'integer', 'between:1,7'],
            'starts_at'     => ['required', 'date_format:H:i'],
            'ends_at'       => ['required', 'date_format:H:i', 'after:starts_at'],
            'slot_label'    => ['nullable', 'string', 'max:150'],
            'slot_notes'    => ['nullable', 'string', 'max:2000'],
        ]);

        $assignment = ClasseSubjectOfSchoolYear::query()
            ->whereKey($data['assignment_id'])
            ->where('classe_id', $plan->classe_id)
            ->where('school_year_id', $plan->school_year_id)
            ->current()
            ->first();

        if (!$assignment) {
            $this->notification()->error(
                title: 'Matière indisponible',
                description: 'Cette matière n’est plus valable pour cette classe et cette année scolaire.',
            );
            throw ValidationException::withMessages([
                'assignment_id' => 'Cette affectation n’est plus active pour cette classe et cette année scolaire.',
            ]);
        }

        DB::connection('tenant')->transaction(function () use ($plan, $assignment, $data) {
            $conflictInClass = TimePlanSlot::query()
                ->where('time_plan_id', $plan->id)
                ->where('day_of_week', $data['day_of_week'])
                ->where('id', '!=', $this->slotId ?? 0)
                ->where('starts_at', '<', $data['ends_at'])
                ->where('ends_at', '>', $data['starts_at'])
                ->exists();

            if ($conflictInClass) {
                $this->notification()->error(
                    title: 'Chevauchement horaire',
                    description: 'Ce créneau chevauche déjà un autre cours de cette classe.',
                );
                throw ValidationException::withMessages([
                    'starts_at' => 'Ce créneau chevauche déjà un autre cours de cette classe.',
                ]);
            }

            $teacherId = $assignment->teacher_id;

            $conflictForTeacher = TimePlanSlot::query()
                ->where('day_of_week', $data['day_of_week'])
                ->where('id', '!=', $this->slotId ?? 0)
                ->where('starts_at', '<', $data['ends_at'])
                ->where('ends_at', '>', $data['starts_at'])
                ->whereHas('timePlan', fn (Builder $q) => $q->where('school_year_id', $plan->school_year_id))
                ->whereHas(
                    'classeSubjectOfSchoolYear',
                    fn (Builder $q) => $q
                        ->where('teacher_id', $teacherId)
                        ->whereNull('ended_at')
                        ->where('is_active', true)
                )
                ->exists();

            if ($conflictForTeacher) {
                $this->notification()->error(
                    title: 'Conflit enseignant',
                    description: 'Cet enseignant a déjà un cours sur ce créneau dans une autre classe.',
                );
                throw ValidationException::withMessages([
                    'assignment_id' => 'Cet enseignant a déjà un cours sur ce créneau dans une autre classe.',
                ]);
            }

            $slot = $this->slotId
                ? TimePlanSlot::query()->where('time_plan_id', $plan->id)->findOrFail($this->slotId)
                : new TimePlanSlot(['time_plan_id' => $plan->id]);

            $slot->fill([
                'classe_subject_of_school_year_id' => $assignment->id,
                'day_of_week'                      => $data['day_of_week'],
                'starts_at'                        => $data['starts_at'],
                'ends_at'                          => $data['ends_at'],
                'label'                            => $data['slot_label'] ?: null,
                'notes'                            => $data['slot_notes'] ?: null,
            ]);
            $slot->save();
        });

        $this->showSlotForm = false;
        $this->resetSlotForm();
        $this->refreshPlansData();

        $this->notification()->success(
            title: 'Créneau enregistré',
            description: 'Le créneau a été enregistré avec succès.',
        );
    }

    protected function resetSlotForm(): void
    {
        $this->slotId = null;
        $this->slotFormPlanId = null;
        $this->assignment_id = null;
        $this->slot_label = '';
        $this->slot_notes = '';
        $this->day_of_week = 1;
        $this->starts_at = '08:00';
        $this->ends_at = '10:00';
    }

    // ─────────────────────────────────────────────────────────────
    //  SUPPRESSION D'UN EMPLOI DU TEMPS
    // ─────────────────────────────────────────────────────────────

    public function deletePlan(int $planId): void
    {
        $this->dispatch('swal', [
            'title'              => 'Supprimer cet emploi du temps ?',
            'text'               => 'Cette action supprimera définitivement l’emploi du temps et tous ses créneaux. Cette opération est irréversible.',
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, supprimer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#ef4444',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToDeletePlan',
            'onConfirmedParams'  => ['planId' => $planId],
        ]);
    }

    #[On('ConfirmToDeletePlan')]
    public function onConfirmToDeletePlan(int $planId): void
    {
        $plan = TimePlan::query()->find($planId);

        if (!$plan) {
            $this->notification()->error(title: 'Emploi du temps introuvable');
            return;
        }

        try {
            $plan->slots()->delete();
            $done = $plan->delete();

            if ($done) {
                if (property_exists($this, 'timePlanId') && $this->timePlanId === $planId) {
                    $this->timePlanId = null;
                    session()->forget('current_time_plan');
                }

                $this->notification()->success(
                    title: 'Emploi du temps supprimé',
                    description: "L'emploi du temps a été supprimé avec succès.",
                );

                broadcast(new DataUpdatedEvent(tenant('id')));
                $this->refreshPlansData();
            } else {
                $this->notification()->error(
                    title: 'Suppression échouée',
                    description: 'Une erreur est survenue, veuillez réessayer.',
                );
            }
        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Suppression échouée',
                description: 'Une erreur est survenue : ' . cutter($th->getMessage(), 200),
            );
        }
    }

    // ─────────────────────────────────────────────────────────────
    //  SUPPRESSION D'UN CRÉNEAU
    // ─────────────────────────────────────────────────────────────

    public function deleteSlot(int $slotId, int $planId): void
    {
        $this->dispatch('swal', [
            'title'              => 'Supprimer ce créneau ?',
            'text'               => 'Cette action retirera définitivement ce créneau de l’emploi du temps.',
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, supprimer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#ef4444',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToDeleteSlot',
            'onConfirmedParams'  => ['slotId' => $slotId, 'planId' => $planId],
        ]);
    }

    #[On('ConfirmToDeleteSlot')]
    public function onConfirmToDeleteSlot(int $slotId, int $planId): void
    {
        $plan = TimePlan::query()->find($planId);

        if (!$plan) {
            $this->notification()->error(title: 'Emploi du temps introuvable');
            return;
        }

        if (!$plan->isEditable()) {
            $this->notification()->error(
                title: 'Action impossible',
                description: 'Cet emploi du temps n’est pas modifiable.',
            );
            return;
        }

        $slot = TimePlanSlot::query()
            ->where('time_plan_id', $plan->id)
            ->find($slotId);

        if (!$slot) {
            $this->notification()->error(title: 'Créneau introuvable');
            return;
        }

        try {
            $done = $slot->delete();

            if ($done) {
                $this->notification()->success(
                    title: 'Créneau supprimé',
                    description: 'Le créneau a été supprimé avec succès.',
                );

                broadcast(new DataUpdatedEvent(tenant('id')));
                $this->refreshPlansData();
            } else {
                $this->notification()->error(
                    title: 'Suppression échouée',
                    description: 'Une erreur est survenue, veuillez réessayer.',
                );
            }
        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Suppression échouée',
                description: 'Une erreur est survenue : ' . cutter($th->getMessage(), 200),
            );
        }
    }

    // ─────────────────────────────────────────────────────────────
    //  PUBLICATION / ARCHIVAGE
    // ─────────────────────────────────────────────────────────────

    public function publishPlan(int $planId): void
    {
        $plan = TimePlan::query()->find($planId);

        if (!$plan) {
            $this->notification()->error(title: 'Emploi du temps introuvable');
            return;
        }

        if ($plan->status === 'archived') {
            $this->notification()->error(
                title: 'Action impossible',
                description: 'Un emploi du temps archivé ne peut pas être publié.',
            );
            return;
        }

        if (!$plan->slots()->exists()) {
            $this->notification()->error(
                title: 'Emploi du temps vide',
                description: 'Ajoutez au moins un créneau avant de publier.',
            );
            return;
        }

        $this->dispatch('swal', [
            'title'              => 'Publier cet emploi du temps ?',
            'text'               => 'Une fois publié, l’emploi du temps sera visible par les enseignants et les élèves concernés.',
            'icon'               => 'question',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, publier',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#06b6d4',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToPublishPlan',
            'onConfirmedParams'  => ['planId' => $planId],
        ]);
    }

    #[On('ConfirmToPublishPlan')]
    public function onConfirmToPublishPlan(int $planId): void
    {
        $plan = TimePlan::query()->find($planId);

        if (!$plan) {
            $this->notification()->error(title: 'Emploi du temps introuvable');
            return;
        }

        try {
            $done = $plan->update([
                'status'       => 'published',
                'published_at' => now(),
            ]);

            if ($done) {
                $this->notification()->success(
                    title: 'Emploi du temps publié',
                    description: "L'emploi du temps a été publié avec succès.",
                );

                broadcast(new DataUpdatedEvent(tenant('id')));
                $this->refreshPlansData();
            } else {
                $this->notification()->error(
                    title: 'Publication échouée',
                    description: 'Une erreur est survenue, veuillez réessayer.',
                );
            }
        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Publication échouée',
                description: 'Une erreur est survenue : ' . cutter($th->getMessage(), 200),
            );
        }
    }

    public function archivePlan(int $planId): void
    {
        $plan = TimePlan::query()->find($planId);

        if (!$plan) {
            $this->notification()->error(title: 'Emploi du temps introuvable');
            return;
        }

        $this->dispatch('swal', [
            'title'              => 'Archiver cet emploi du temps ?',
            'text'               => 'L’emploi du temps ne sera plus modifiable. Vous pourrez le désarchiver plus tard.',
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, archiver',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f59e0b',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToArchivePlan',
            'onConfirmedParams'  => ['planId' => $planId],
        ]);
    }

    #[On('ConfirmToArchivePlan')]
    public function onConfirmToArchivePlan(int $planId): void
    {
        $plan = TimePlan::query()->find($planId);

        if (!$plan) {
            $this->notification()->error(title: 'Emploi du temps introuvable');
            return;
        }

        try {
            $done = $plan->update(['status' => 'archived']);

            if ($done) {
                $this->notification()->success(
                    title: 'Emploi du temps archivé',
                    description: "L'emploi du temps a été archivé.",
                );

                broadcast(new DataUpdatedEvent(tenant('id')));
                $this->refreshPlansData();
            } else {
                $this->notification()->error(
                    title: 'Archivage échoué',
                    description: 'Une erreur est survenue, veuillez réessayer.',
                );
            }
        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Archivage échoué',
                description: 'Une erreur est survenue : ' . cutter($th->getMessage(), 200),
            );
        }
    }

    public function unArchivePlan(int $planId): void
    {
        $plan = TimePlan::query()->find($planId);

        if (!$plan) {
            $this->notification()->error(title: 'Emploi du temps introuvable');
            return;
        }

        $this->dispatch('swal', [
            'title'              => 'Désarchiver cet emploi du temps ?',
            'text'               => 'L’emploi du temps redeviendra modifiable (statut brouillon).',
            'icon'               => 'question',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, désarchiver',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#06b6d4',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToUnArchivePlan',
            'onConfirmedParams'  => ['planId' => $planId],
        ]);
    }

    #[On('ConfirmToUnArchivePlan')]
    public function onConfirmToUnArchivePlan(int $planId): void
    {
        $plan = TimePlan::query()->find($planId);

        if (!$plan) {
            $this->notification()->error(title: 'Emploi du temps introuvable');
            return;
        }

        try {
            $done = $plan->update([
                'status'       => 'draft',
                'published_at' => null,
            ]);

            if ($done) {
                $this->notification()->success(
                    title: 'Emploi du temps désarchivé',
                    description: "L'emploi du temps est de nouveau modifiable.",
                );

                broadcast(new DataUpdatedEvent(tenant('id')));
                $this->refreshPlansData();
            } else {
                $this->notification()->error(
                    title: 'Désarchivage échoué',
                    description: 'Une erreur est survenue, veuillez réessayer.',
                );
            }
        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Désarchivage échoué',
                description: 'Une erreur est survenue : ' . cutter($th->getMessage(), 200),
            );
        }
    }
}
