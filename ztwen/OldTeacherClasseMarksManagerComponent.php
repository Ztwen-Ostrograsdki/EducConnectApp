<?php

namespace App\Livewire\Tenants\Users\Teacher;

use App\Models\Classe;
use App\Models\ClasseSubjectOfSchoolYear;
use App\Models\Mark;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\Subject;
use App\Services\ClassesServices\ClasseEffectifsService;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class TeacherClasseMarksManagerComponent extends Component
{
    use WireUiActions;

    private const INTERRO_TYPES = ['interro1', 'interro2', 'interro3', 'interro4'];

    public $counter = 0;

    public string $classe_slug;

    public string $subject_slug;

    public int $classe_subject_id;

    /** Période sélectionnée pour la saisie en cours */
    public ?int $period = null;

    /** Saisies brutes non encore insérées, par student_id => ['interro' => '12-15', 'devoir' => '14']  */
    public array $inputs = [];

    /** Notes en attente d'enregistrement (persistées en session), par student_id => ['interro' => [...], 'devoir' => [...]] */
    public array $pendingMarks = [];

    /** Payload final prêt à être exporté vers le job (rempli par validateAllMarks) */
    public array $finalMarksPayload = [];

    public function mount(string $classe_slug, string $subject_slug)
    {
       if (!$classe_slug && !$subject_slug) return abort(404);

        $this->classe_slug  = $classe_slug;
        $this->subject_slug  = $subject_slug;
        $this->classe_subject_id = $this->classe_subject->id;

        $this->period = session()->get($this->lastPeriodSessionKey());

        if ($this->period) {
            $this->loadPendingMarksFromSession();
            $this->syncInputsFromPending();
        }
    }


    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata()
    {
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

    public function updatedPeriod($value): void
    {
       $this->inputs = [];

        if ($value) {
            session()->put($this->lastPeriodSessionKey(), $value);
            $this->loadPendingMarksFromSession();
            $this->syncInputsFromPending();
        } else {
            $this->pendingMarks = [];
        }
    }

    /**
     * Remplit $inputs à partir de pendingMarks pour l'affichage (valeurs formatées),
     * notamment après un rechargement de page.
     */
    private function syncInputsFromPending(): void
    {
        foreach ($this->pendingMarks as $studentId => $marks) {
            $this->inputs[$studentId] = [
                'interro' => $this->formatMarksForInput($marks['interro'] ?? [], self::INTERRO_TYPES),
                'devoir'  => $this->formatMarksForInput($marks['devoir'] ?? [], $this->devoirTypesForTenant()),
            ];
        }
    }

    // ─── Session helpers ──────────────────────────────────────────────

    private function lastPeriodSessionKey(): string
    {
        return "teacher_marks:{$this->classe_subject_id}:last_period";
    }

    private function pendingSessionKey(): string
    {
        return "teacher_marks:{$this->classe_subject_id}:pending:{$this->period}";
    }

    private function loadPendingMarksFromSession(): void
    {
        $this->pendingMarks = $this->period
            ? session()->get($this->pendingSessionKey(), [])
            : [];
    }

    private function savePendingMarksToSession(): void
    {
        if (!$this->period) return;

        session()->put($this->pendingSessionKey(), $this->pendingMarks);
    }

    // ─── Règles métier notes ──────────────────────────────────────────

    private function devoirTypesForTenant(): array
    {
        $tenant = tenancy()->tenant;

        return $tenant->devoirs_type === 'devoir1-devoir2'
            ? ['devoir1', 'devoir2']
            : ['devoir1', 'compo'];
    }

   
    private function getExistingTypesForStudent(int $studentId): array
    {
        return $this->existingMarksByStudent
            ->get($studentId, collect())
            ->pluck('type')
            ->toArray();
    }

    #[Computed]
    public function existingMarksByStudent(): Collection
    {
        if (!$this->period) return collect();

        return Mark::query()
            ->where('subject_id', $this->subject->id)
            ->where('school_year_id', $this->activeYear->id)
            ->where('period', $this->period)
            ->whereIn('student_id', $this->students->pluck('id'))
            ->get()
            ->groupBy('student_id');
    }

    /**
     * Transforme "12-09-13,5" en [12.0, 9.0, 13.5]. Lève une exception si une valeur est invalide.
     */
    private function parseMarksInput(?string $raw): array
    {
        $raw = trim((string) $raw);

        if ($raw === '') return [];

        $values = [];

        foreach (explode('-', $raw) as $part) {
            $part = trim($part);

            if ($part === '') continue;

            $normalized = str_replace(',', '.', $part);

            if (!is_numeric($normalized)) {
                throw new \InvalidArgumentException("La valeur \"{$part}\" n'est pas une note valide.");
            }

            $value = round((float) $normalized, 2);

            if ($value < 0 || $value > 20) {
                throw new \InvalidArgumentException("La note \"{$part}\" doit être comprise entre 0 et 20.");
            }

            $values[] = $value;
        }

        return $values;
    }

    private function formatMarkValue(float $value): string
    {
        $formatted = rtrim(rtrim(number_format($value, 2, '.', ''), '0'), '.');

        return str_replace('.', ',', $formatted);
    }

    private function formatMarksForInput(array $typedValues, array $typeOrder): string
    {
        $ordered = [];

        foreach ($typeOrder as $type) {
            if (array_key_exists($type, $typedValues)) {
                $ordered[] = $this->formatMarkValue($typedValues[$type]);
            }
        }

        return implode('-', $ordered);
    }

    // ─── Actions ligne par apprenant ───────────────────────────────────

    public function addStudentMarks(int $studentId)
    {
        try {
            if (!$this->period) {
                throw new \InvalidArgumentException('Veuillez sélectionner une période avant de saisir les notes.');
            }

            $student = $this->students->firstWhere('id', $studentId);

            if (!$student) {
                throw new \InvalidArgumentException("Cet apprenant n'appartient pas à cette classe.");
            }

            $rawInterro = $this->inputs[$studentId]['interro'] ?? '';
            $rawDevoir  = $this->inputs[$studentId]['devoir'] ?? '';

            $interroValues = $this->parseMarksInput($rawInterro);
            $devoirValues  = $this->parseMarksInput($rawDevoir);

            if (empty($interroValues) && empty($devoirValues)) {
                throw new \InvalidArgumentException('Veuillez saisir au moins une note (interrogation ou devoir).');
            }

            $existingTypes = $this->getExistingTypesForStudent($studentId);

            $interroMarks = [];

            if (!empty($interroValues)) {
                $availableInterroTypes = array_values(array_diff(self::INTERRO_TYPES, $existingTypes));

                if (count($interroValues) > count($availableInterroTypes)) {
                    $already = count(self::INTERRO_TYPES) - count($availableInterroTypes);

                    throw new \InvalidArgumentException(
                        "Cet apprenant a déjà {$already} note(s) d'interrogation enregistrée(s). "
                        . 'Vous ne pouvez en ajouter que ' . count($availableInterroTypes) . ' de plus.'
                    );
                }

                foreach ($interroValues as $index => $value) {
                    $interroMarks[$availableInterroTypes[$index]] = $value;
                }
            }

            $devoirMarks = [];

            if (!empty($devoirValues)) {
                $devoirTypesOrder = $this->devoirTypesForTenant();

                $availableDevoirTypes = array_values(array_diff($devoirTypesOrder, $existingTypes));

                if (count($devoirValues) > count($availableDevoirTypes)) {
                    $already = count($devoirTypesOrder) - count($availableDevoirTypes);

                    throw new \InvalidArgumentException(
                        "Cet apprenant a déjà {$already} note(s) de devoir enregistrée(s). "
                        . 'Vous ne pouvez en ajouter que ' . count($availableDevoirTypes) . ' de plus.'
                    );
                }

                foreach ($devoirValues as $index => $value) {
                    $devoirMarks[$availableDevoirTypes[$index]] = $value;
                }
            }

            $this->pendingMarks[$studentId] = [
                'interro' => $interroMarks,
                'devoir'  => $devoirMarks,
            ];

            $this->savePendingMarksToSession();

            // On NE vide plus les inputs : on les remplace par la version formatée
            // pour qu'ils restent visibles (désactivés) après insertion.
            $this->inputs[$studentId] = [
                'interro' => $this->formatMarksForInput($interroMarks, self::INTERRO_TYPES),
                'devoir'  => $this->formatMarksForInput($devoirMarks, $this->devoirTypesForTenant()),
            ];

            $this->notification()->send([
                'icon'        => 'success',
                'title'       => 'Notes ajoutées',
                'description' => "Les notes de {$student->name} {$student->prenames} ont été ajoutées à la liste en attente.",
            ]);

            
        } catch (\InvalidArgumentException $e) {
            $this->notification()->send([
                'icon'        => 'error',
                'title'       => 'Erreur de saisie',
                'description' => $e->getMessage(),
            ]);
        }
    }

    
    public function editStudentMarks(int $studentId): void
    {
        if (!isset($this->pendingMarks[$studentId])) return;

        // Les inputs contiennent déjà la valeur formatée (posée par addStudentMarks
        // ou par syncInputsFromPending) : on se contente de sortir du pending
        // pour que les champs redeviennent éditables.
        unset($this->pendingMarks[$studentId]);

        $this->savePendingMarksToSession();

    }

    public function removeStudentMarks(int $studentId): void
    {
        if (!isset($this->pendingMarks[$studentId])) return;

        unset($this->pendingMarks[$studentId]);
        unset($this->inputs[$studentId]);

        $this->savePendingMarksToSession();

        $this->notification()->send([
            'icon'  => 'success',
            'title' => 'Notes retirées de la liste en attente',
        ]);
    }

    public function resetStudentInputs(int $studentId): void
    {
        unset($this->inputs[$studentId]);
    }

    // ─── Actions globales ───────────────────────────────────────────────

    public function resetAllInputs(): void
    {
        $this->inputs = [];

        $this->pendingMarks = [];

        if ($this->period) {
            session()->forget($this->pendingSessionKey());
        }

        $this->notification()->send([
            'icon'  => 'info',
            'title' => 'Saisies non enregistrées effacées',
        ]);
    }

    public function resetAllPendingMarks(): void
    {
        $this->pendingMarks = [];

        if ($this->period) {
            session()->forget($this->pendingSessionKey());
        }

        $this->notification()->send([
            'icon'  => 'success',
            'title' => 'Toutes les notes en attente ont été réinitialisées',
        ]);
    }

    public function validateAllMarks(): void
    {
        if (!$this->period) {
            $this->notification()->send([
                'icon'        => 'warning',
                'title'       => 'Aucune période sélectionnée',
                'description' => 'Veuillez sélectionner une période avant de valider.',
            ]);
            return;
        }

        if (empty($this->pendingMarks)) {
            $this->notification()->send([
                'icon'        => 'warning',
                'title'       => 'Aucune note à valider',
                'description' => "Vous n'avez ajouté aucune note pour le moment.",
            ]);
            return;
        }

        $this->finalMarksPayload = collect($this->pendingMarks)
            ->map(function (array $marks, $studentId) {
                return [
                    'student_id'     => (int) $studentId,
                    'classe_id'      => $this->classe->id,
                    'subject_id'     => $this->subject->id,
                    'school_year_id' => $this->activeYear->id,
                    'teacher_id'     => $this->teacher->id,
                    'period'         => $this->period,
                    'marks'          => array_merge($marks['interro'] ?? [], $marks['devoir'] ?? []),
                ];
            })
            ->values()
            ->toArray();

        $this->notification()->send([
            'icon'        => 'success',
            'title'       => 'Notes prêtes à être enregistrées',
            'description' => count($this->finalMarksPayload) . " apprenant(s) prêt(s) pour l'enregistrement.",
        ]);

        dd($this->finalMarksPayload);

        // TODO (prochaine étape) : dispatch(new GenerateMarksJob($this->finalMarksPayload));
    }

    // ─── Computed existants ────────────────────────────────────────────

    #[Computed]
    public function periods_types()
    {
        return tenancy()->tenant->getPeriodsTypes();
    }

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

        if (!$classe_subject) return abort(404);

        return $classe_subject;
    }

    #[Computed]
    public function classe()
    {
        if (!$this->classe_slug) return abort(404);

        $classe = Classe::firstWhere('slug', $this->classe_slug);

        if (!$classe) return abort(404);

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
        if (!$this->subject_slug) return abort(404);

        $subject = Subject::firstWhere('slug', $this->subject_slug);

        if (!$subject) return abort(404);

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

    public function render()
    {
        return view('livewire.tenants.users.teacher.teacher-classe-marks-manager-component');
    }
}