<?php

namespace App\Livewire\Tenants\Users\Teacher;

use App\Events\InitProcessToUpdateStudentsMarksEvent;
use App\Models\Classe;
use App\Models\ClasseSubjectOfSchoolYear;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\Subject;
use App\Services\ClassesServices\ClasseEffectifsService;
use App\Services\MarksServices\ClasseSubjectMarksCacheService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class TeacherClasseMarksViewer extends Component
{
    use WireUiActions;

    protected const INTERRO_TYPES = ['interro1', 'interro2', 'interro3', 'interro4'];

    public $counter = 0;

    public string $classe_slug;

    public string $subject_slug;

    public int $classe_subject_id;

    public ?int $period;

    public ?int $editingStudentId = null;

    public array $editInputs = [];

    public array $pendingEdits = [];

    public function mount(string $classe_slug, string $subject_slug)
    {
        if(!$classe_slug && !$subject_slug) return abort(404);

        $this->classe_slug  = $classe_slug;

        $this->subject_slug  = $subject_slug;

        $this->classe_subject_id = $this->classe_subject->id;

        $this->loadActivePeriod();

        $this->loadPendingEditsFromSession();
    }

    public function loadActivePeriod()
    {
        if($this->activeYear && $this->activeYear->is_active && $this->activeYear->active_period){

            $this->period = $this->activeYear->active_period;
        }
    }

    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata()
    {
        unset($this->marksData);

        $this->counter++;
    }

    #[On('TeacherWasBlockedLiveEvent')]
    public function teacherBlocked()
    {
        $this->notification()->send([
            'icon'        => 'warning',
            'title'       => "Votre compte enseignant a été bloqué",
            'timeout' => 0,
        ]);

        $this->counter++;

        return $this->redirect(route('tenant.my.profil'));
    }

    public function updatedPeriod()
    {
        unset($this->marksData);

        $this->editingStudentId = null;
        $this->editInputs = [];

        $this->loadPendingEditsFromSession();
    }

    // ─── Session (modifications en attente) ──────────────────────────

    private function pendingEditsSessionKey(): string
    {
        return "teacher_marks_edit:{$this->classe_subject_id}:pending:{$this->period}";
    }

    private function loadPendingEditsFromSession(): void
    {
        $this->pendingEdits = $this->period
            ? session()->get($this->pendingEditsSessionKey(), [])
            : [];
    }

    private function savePendingEditsToSession(): void
    {
        if (!$this->period) return;

        session()->put($this->pendingEditsSessionKey(), $this->pendingEdits);
    }

    private function formatMarkValue(float $value): string
    {
        $formatted = rtrim(rtrim(number_format($value, 2, '.', ''), '0'), '.');

        return str_replace('.', ',', $formatted);
    }

    // ─── Edition d'un apprenant ────────────────────────────────────────

    /**
     * Ouvre le formulaire d'édition pour un apprenant : un champ par note
     * actuellement non nulle (nombre de champs figé au moment de l'ouverture).
     */
    public function editStudentMark(int $student_id)
    {
        if (!$this->period) return;

        $student = $this->students->firstWhere('id', $student_id);

        if (!$student) return;

        $currentMarks = $this->marksData[$student_id] ?? [];

        $existingTypes = collect($this->markColumns())
            ->keys()
            ->filter(fn ($type) => !is_null($currentMarks[$type]['value'] ?? null))
            ->values()
            ->all();

        if (empty($existingTypes)) {
            $this->notification()->send([
                'icon'        => 'warning',
                'title'       => 'Aucune note à modifier',
                'description' => "{$student->getFullName()} n'a aucune note enregistrée pour cette période.",
            ]);
            return;
        }

        $this->editingStudentId = $student_id;

        // Si une édition est déjà en attente pour cet apprenant, on repart de là où
        // l'enseignant s'était arrêté ; sinon on part des valeurs actuelles en base.
        $pending = $this->pendingEdits[$student_id] ?? null;

        $this->editInputs = [];

        foreach ($existingTypes as $type) {
            $value = $pending
                ? ($pending[$type] ?? null)
                : ($currentMarks[$type]['value'] ?? null);

            $this->editInputs[$type] = !is_null($value) ? $this->formatMarkValue((float) $value) : '';
        }
    }

    public function cancelEditStudentMark(): void
    {
        $this->editingStudentId = null;
        $this->editInputs = [];
    }

    /**
     * Valide et enregistre en session les modifications de l'apprenant en cours d'édition.
     */
    public function finishEditStudentMark(): void
    {
        if (!$this->editingStudentId) return;

        $studentId = $this->editingStudentId;
        $student = $this->students->firstWhere('id', $studentId);

        try {
            $values = [];

            foreach ($this->editInputs as $type => $raw) {

                $raw = trim((string) $raw);

                if ($raw === '') {
                    $values[$type] = null;
                    continue;
                }

                $normalized = str_replace(',', '.', $raw);
                $label = $this->markColumns()[$type] ?? $type;

                if (!is_numeric($normalized)) {
                    throw new \InvalidArgumentException("La valeur \"{$raw}\" n'est pas une note valide pour \"{$label}\".");
                }

                $value = round((float) $normalized, 2);

                if ($value < 0 || $value > 20) {
                    throw new \InvalidArgumentException("La note \"{$raw}\" pour \"{$label}\" doit être comprise entre 0 et 20.");
                }

                $values[$type] = $value;
            }

            $this->pendingEdits[$studentId] = $values;

            $this->savePendingEditsToSession();

            $this->editingStudentId = null;
            $this->editInputs = [];

            $this->notification()->send([
                'icon'        => 'success',
                'title'       => 'Modifications enregistrées',
                'description' => "Les modifications de {$student?->getFullName()} sont en attente de confirmation.",
            ]);
        } catch (\InvalidArgumentException $e) {
            $this->notification()->send([
                'icon'        => 'error',
                'title'       => 'Erreur de saisie',
                'description' => $e->getMessage(),
            ]);
        }
    }

    public function removePendingEdit(int $student_id): void
    {
        unset($this->pendingEdits[$student_id]);

        $this->savePendingEditsToSession();
    }

    public function cancelAllPendingEdits(): void
    {
        $this->pendingEdits = [];

        if ($this->period) {
            session()->forget($this->pendingEditsSessionKey());
        }

        $this->notification()->send([
            'icon'  => 'success',
            'title' => 'Toutes les modifications en attente ont été annulées',
        ]);
    }

    /**
     * Lance la mise à jour globale : dispatch l'event, puis vide la session.
     */
    public function confirmMarksUpdate(): void
    {
        if (empty($this->pendingEdits)) {
            $this->notification()->send([
                'icon'  => 'warning',
                'title' => 'Aucune modification en attente',
            ]);
            return;
        }

        $payload = collect($this->pendingEdits)
            ->map(fn ($marks, $studentId) => [
                'student_id' => (int) $studentId,
                'marks'      => $marks,
            ])
            ->values()
            ->all();

        event(new InitProcessToUpdateStudentsMarksEvent(
            tenantId: tenancy()->tenant->id,
            teacherId: $this->teacher->id,
            classeId: $this->classe->id,
            subjectId: $this->subject->id,
            period: $this->period,
            data: $payload,
            schoolYearId: $this->activeYear->id,
        ));

        $this->pendingEdits = [];

        session()->forget($this->pendingEditsSessionKey());

        $this->notification()->send([
            'icon'        => 'success',
            'title'       => 'Mise à jour lancée',
            'description' => count($payload) . " apprenant(s) en cours de traitement.",
        ]);
    }

    // ─── Computed existants ────────────────────────────────────────────

    #[Computed]
    public function teacher()
    {
        return auth('tenant')->user()->teacher;
    }

    #[Computed]
    public function classe_subject()
    {
        $classe_subject = ClasseSubjectOfSchoolYear::with(['teacher', 'classe', 'subject'])
                   ->whereHas('teacher', fn($qt) =>
                        $qt->where('id', $this->teacher->id)
                   )
                   ->whereHas('classe', fn($qc) =>
                        $qc->where('slug', $this->classe_slug)
                   )
                   ->whereHas('subject', fn($qs) =>
                        $qs->where('slug', $this->subject_slug)
                )->where('school_year_id', $this->activeYear->id)->whereNull('ended_at')->where('is_active', true)->first();

        if(!$classe_subject) return abort(404);

        return $classe_subject;
    }

    #[Computed]
    public function classe()
    {
        if(!$this->classe_slug) return abort(404);

        $classe = Classe::firstWhere('slug', $this->classe_slug);

        if(!$classe) return abort(404);

        return $classe;
    }

    #[Computed]
    public function effectifs()
    {
        $effectifs = app(ClasseEffectifsService::class)->getEffectifs($this->classe->id);

        return $effectifs;
    }

    #[Computed]
    public function students()
    {
        return Student::whereHas('yearlyClasseStudents', fn($q) =>
            $q->where('classe_id', $this->classe->id)
              ->where('school_year_id', $this->classe->school_year_id)
              ->where('is_active', true)
        )
        ->orderBy('name')
        ->orderBy('prenames')
        ->get();
    }

    #[Computed]
    public function subject()
    {
        if(!$this->subject_slug) return abort(404);

        $subject = Subject::firstWhere('slug', $this->subject_slug);

        if(!$subject) return abort(404);

        return $subject;
    }

    #[Computed]
    public function user()
    {
        return auth('tenant')->user();
    }

    #[Computed]
    public function activeYear(): ?SchoolYear
    {
        return SchoolYear::current()->first();
    }

    #[Computed]
    public function periods_types()
    {
        return $this->activeYear->getPeriods();
    }

    #[Computed]
    public function marksData(): array
    {
        if (!$this->period) {
            return [];
        }

        return app(ClasseSubjectMarksCacheService::class)->get(
            $this->classe->id,
            $this->subject->id,
            $this->period,
            $this->activeYear->id
        );
    }

    #[Computed]
    public function studentsRows(): array
    {
        $columns = array_keys($this->markColumns());
        $devoirColumns = array_keys($this->devoirColumns());
        $coefficient = (float) ($this->classe_subject->coefficient ?? 0);

        $rows = $this->students->map(function (Student $student) use ($columns, $devoirColumns, $coefficient) {

            $studentMarks = $this->marksData[$student->id] ?? [];

            $values = [];

            foreach ($columns as $type) {
                $values[$type] = $studentMarks[$type]['value'] ?? null;
            }

            $interroValues = array_filter(
                array_intersect_key($values, array_flip(self::INTERRO_TYPES)),
                fn ($v) => !is_null($v)
            );

            $moyInterro = !empty($interroValues)
                ? round(array_sum($interroValues) / count($interroValues), 2)
                : null;

            $devoirValues = array_filter(
                array_intersect_key($values, array_flip($devoirColumns)),
                fn ($v) => !is_null($v)
            );

            $moyDevoirs = !empty($devoirValues)
                ? round(array_sum($devoirValues) / count($devoirValues), 2)
                : null;

            if (!is_null($moyInterro) && !is_null($moyDevoirs)) {
                $moy = round(($moyInterro + $moyDevoirs) / 2, 2);
            } elseif (!is_null($moyInterro)) {
                $moy = $moyInterro;
            } elseif (!is_null($moyDevoirs)) {
                $moy = $moyDevoirs;
            } else {
                $moy = null;
            }

            $moyCoef = !is_null($moy) ? round($moy * $coefficient, 2) : null;

            return [
                'student'     => $student,
                'marks'       => $values,
                'moy_interro' => $moyInterro,
                'moy'         => $moy,
                'moy_coef'    => $moyCoef,
            ];
        });

        $ranked = $rows->sortByDesc(fn ($row) => $row['moy'] ?? -1)->values();

        $rank = 0;
        $lastMoy = null;
        $position = 0;

        $ranked = $ranked->map(function ($row) use (&$rank, &$lastMoy, &$position) {

            $position++;

            if (is_null($row['moy'])) {
                $row['rank'] = null;
                return $row;
            }

            if ($row['moy'] !== $lastMoy) {
                $rank = $position;
                $lastMoy = $row['moy'];
            }

            $row['rank'] = $rank;

            return $row;
        });

        return $ranked->sortBy(fn ($row) => $row['student']->name . $row['student']->prenames)->values()->all();
    }

    #[Computed]
    public function devoirColumns(): array
    {
        $devoirsType = tenant()->devoirs_type ?? 'devoir1-devoir2';

        return $devoirsType === 'devoir1-compo'
            ? ['devoir1' => 'Devoir 1', 'compo' => 'Compo']
            : ['devoir1' => 'Devoir 1', 'devoir2' => 'Devoir 2'];
    }

    #[Computed]
    public function markColumns(): array
    {
        return [
            'interro1' => 'Interro 1',
            'interro2' => 'Interro 2',
            'interro3' => 'Interro 3',
            'interro4' => 'Interro 4',
        ] + $this->devoirColumns();
    }

    public function render()
    {
        return view('livewire.tenants.users.teacher.teacher-classe-marks-viewer');
    }
}