<?php

namespace App\Livewire\Tenants\Users\Teacher;

use App\Events\InitProcessToCreateStudentsMarksEvent;
use App\Models\Classe;
use App\Models\ClasseSubjectOfSchoolYear;
use App\Models\Mark;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\Subject;
use App\Services\ClassesServices\ClasseEffectifsService;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use WireUi\Traits\WireUiActions;

class TeacherClasseMarksManagerComponent extends Component
{
    use WireUiActions, WithFileUploads;

    private const INTERRO_TYPES = ['interro1', 'interro2', 'interro3', 'interro4'];

    public $counter = 0;

    public string $classe_slug;

    public string $subject_slug;

    public int $classe_subject_id;

    /** Période sélectionnée pour la saisie en cours */
    public ?int $period = null;

    /** 'manual' ou 'excel' */
    public string $mode = 'manual';

    /** Fichier Excel temporairement uploadé */
    public $excelFile;

    /** Messages d'erreurs / lignes ignorées lors du dernier import Excel */
    public array $excelPreviewErrors = [];

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

        $this->loadActivePeriod();

        // $this->period = session()->get($this->lastPeriodSessionKey());

        if ($this->period) {
            $this->loadPendingMarksFromSession();
            $this->syncInputsFromPending();
        }
    }


    public function loadActivePeriod()
    {
        $schoolYear = SchoolYear::current()->first();

        if($schoolYear && $schoolYear->is_active && $schoolYear->active_period){

            $this->period = $schoolYear->active_period;

        }
    }


    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata()
    {
        $this->counter++;

        unset($this->periods_types);

        // unset($this->classe);

        // unset($this->classe_subject);

        unset($this->students);

        // unset($this->subject);

        unset($this->activeYear);

        // unset($this->teacher);

        $this->loadActivePeriod();
    }

     // ─── Computed existants ────────────────────────────────────────────

    #[Computed]
    public function periods_types()
    {
        return $this->activeYear->getPeriods();
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
        $this->excelPreviewErrors = [];

        if ($value) {
            session()->put($this->lastPeriodSessionKey(), $value);
            $this->loadPendingMarksFromSession();
            $this->syncInputsFromPending();
        } else {
            $this->pendingMarks = [];
        }
    }

    public function switchMode(string $mode): void
    {
        $this->mode = in_array($mode, ['manual', 'excel'], true) ? $mode : 'manual';
        $this->excelPreviewErrors = [];

        if ($this->mode === 'manual') {
            $this->reset('excelFile');
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

    // ─── Règles métier notes ──────────────────────────────────────────

    private function devoirTypesForTenant(): array
    {
        $tenant = tenancy()->tenant;

        return $tenant->devoirs_type === 'devoir1-devoir2'
            ? ['devoir1', 'devoir2']
            : ['devoir1', 'compo'];
    }

    /**
     * Libellés Excel attendus pour les colonnes de devoir, selon le tenant.
     */
    private function devoirColumnLabels(): array
    {
        $types = $this->devoirTypesForTenant();

        return [
            'devoir1' => 'Devoir 1',
            'devoir2' => $types[1] === 'compo' ? 'Composition' : 'Devoir 2',
        ];
    }

    /**
     * Types de notes (interro/devoir/compo) déjà enregistrés en base pour cet apprenant,
     * pour la période et l'année scolaire en cours.
     */
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

    private function normalizeText(?string $value): string
    {
        $value = Str::ascii(trim((string) $value));
        $value = Str::lower($value);

        return trim(preg_replace('/\s+/', ' ', $value));
    }

    /**
     * Construit les index de recherche des apprenants par matricule et par nom+prénoms
     * (dans les deux ordres possibles), normalisés sans accents/casse.
     *
     * @return array{0: array<string, int[]>, 1: array<string, int[]>}
     */
    private function buildStudentIdentificationIndexes(): array
    {
        $matriculeIndex = [];
        $nameIndex = [];

        foreach ($this->students as $student) {

            $matricule = trim((string) ($student->matricule ?? $student->code ?? ''));

            if ($matricule !== '') {
                $matriculeIndex[Str::lower($matricule)][] = $student->id;
            }

            $nameIndex[$this->normalizeText($student->name . ' ' . $student->prenames)][] = $student->id;
            $nameIndex[$this->normalizeText($student->prenames . ' ' . $student->name)][] = $student->id;
        }

        return [$matriculeIndex, $nameIndex];
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

            $this->inputs[$studentId] = [
                'interro' => $this->formatMarksForInput($interroMarks, self::INTERRO_TYPES),
                'devoir'  => $this->formatMarksForInput($devoirMarks, $this->devoirTypesForTenant()),
            ];

            $this->notification()->send([
                'icon'        => 'success',
                'title'       => 'Notes ajoutées',
                'description' => "Les notes de {$student->getFullName()} ont été ajoutées à la liste en attente.",
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

    // ─── Import Excel ───────────────────────────────────────────────────

   /**
 * Assigne dans l'ordre les valeurs lues à la liste des types de note encore disponibles.
 * Ex: [12, 15] + ['interro2', 'interro3'] => ['interro2' => 12, 'interro3' => 15]
 */
    private function assignToAvailableSlots(array $values, array $availableTypes): array
    {
        $assigned = [];

        foreach ($values as $index => $value) {
            if (!isset($availableTypes[$index])) break;

            $assigned[$availableTypes[$index]] = $value;
        }

        return $assigned;
    }

    public function loadExcelFile(): void
    {
        $this->excelPreviewErrors = [];

        try {
            $this->validate([
                'excelFile' => 'required|file|mimes:xlsx,xls|max:5120',
            ], [], [
                'excelFile' => 'fichier Excel',
            ]);

            if (!$this->period) {
                throw new \InvalidArgumentException('Veuillez sélectionner une période avant de charger un fichier.');
            }

            $spreadsheet = IOFactory::load($this->excelFile->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();

            $highestRow = $sheet->getHighestDataRow();
            $highestColumnIndex = Coordinate::columnIndexFromString($sheet->getHighestDataColumn());

            $devoirLabels = $this->devoirColumnLabels();

            $knownHeaders = [
                'matricule'          => 'matricule',
                'nom'                => 'nom',
                'noms'               => 'nom',
                'prenoms'            => 'prenoms',
                'prenom'             => 'prenoms',
                'interro 1'          => 'interro_1',
                'interro1'           => 'interro_1',
                'interrogation 1'    => 'interro_1',
                'interro 2'          => 'interro_2',
                'interro2'           => 'interro_2',
                'interrogation 2'    => 'interro_2',
                'interro 3'          => 'interro_3',
                'interro3'           => 'interro_3',
                'interrogation 3'    => 'interro_3',
                'interro 4'          => 'interro_4',
                'interro4'           => 'interro_4',
                'interrogation 4'    => 'interro_4',
                $this->normalizeText($devoirLabels['devoir1']) => 'devoir_1',
                $this->normalizeText($devoirLabels['devoir2']) => 'devoir_2',
            ];

            // ─── Lecture des en-têtes (ligne 1) ───
            $headerMap = [];

            for ($col = 1; $col <= $highestColumnIndex; $col++) {
                $coordinate = Coordinate::stringFromColumnIndex($col) . '1';
                $headerValue = $this->normalizeText((string) $sheet->getCell($coordinate)->getValue());

                if ($headerValue !== '' && isset($knownHeaders[$headerValue])) {
                    $headerMap[$col] = $knownHeaders[$headerValue];
                }
            }

            $hasMatricule = in_array('matricule', $headerMap, true);
            $hasNomPrenoms = in_array('nom', $headerMap, true) && in_array('prenoms', $headerMap, true);

            if (!$hasMatricule && !$hasNomPrenoms) {
                throw new \InvalidArgumentException(
                    "Le fichier doit contenir une colonne \"Matricule\", ou à défaut les colonnes \"Nom\" et \"Prénoms\", pour identifier les apprenants."
                );
            }

            [$matriculeIndex, $nameIndex] = $this->buildStudentIdentificationIndexes();

            // Nettoyage des saisies manuelles non encore insérées avant de charger le fichier
            foreach (array_keys($this->inputs) as $studentId) {
                if (!isset($this->pendingMarks[$studentId])) {
                    unset($this->inputs[$studentId]);
                }
            }

            $errors = [];
            $loadedCount = 0;
            $processedRowByStudent = [];

            for ($row = 2; $row <= $highestRow; $row++) {

                $rowData = [];

                foreach ($headerMap as $col => $key) {
                    $coordinate = Coordinate::stringFromColumnIndex($col) . $row;
                    $rowData[$key] = $sheet->getCell($coordinate)->getValue();
                }

                if (empty($rowData) || collect($rowData)->every(fn($v) => trim((string) $v) === '')) {
                    continue;
                }

                // ─── Identification de l'apprenant ───
                $studentId = null;
                $matriculeCell = trim((string) ($rowData['matricule'] ?? ''));

                if ($matriculeCell !== '') {
                    $key = Str::lower($matriculeCell);

                    if (!isset($matriculeIndex[$key])) {
                        $errors[] = "Ligne {$row} : aucun apprenant trouvé avec le matricule \"{$matriculeCell}\".";
                        continue;
                    }

                    if (count($matriculeIndex[$key]) > 1) {
                        $errors[] = "Ligne {$row} : plusieurs apprenants partagent le matricule \"{$matriculeCell}\", ligne ignorée.";
                        continue;
                    }

                    $studentId = $matriculeIndex[$key][0];
                } else {
                    $nom = trim((string) ($rowData['nom'] ?? ''));
                    $prenoms = trim((string) ($rowData['prenoms'] ?? ''));

                    if ($nom === '' || $prenoms === '') {
                        $errors[] = "Ligne {$row} : nom, prénoms ou matricule manquant, ligne ignorée.";
                        continue;
                    }

                    $key = $this->normalizeText($nom . ' ' . $prenoms);
                    $altKey = $this->normalizeText($prenoms . ' ' . $nom);

                    $candidateIds = array_values(array_unique(array_merge(
                        $nameIndex[$key] ?? [],
                        $nameIndex[$altKey] ?? []
                    )));

                    if (empty($candidateIds)) {
                        $errors[] = "Ligne {$row} : aucun apprenant trouvé pour \"{$nom} {$prenoms}\".";
                        continue;
                    }

                    if (count($candidateIds) > 1) {
                        $errors[] = "Ligne {$row} : plusieurs apprenants correspondent à \"{$nom} {$prenoms}\", utilisez le matricule pour lever l'ambiguïté.";
                        continue;
                    }

                    $studentId = $candidateIds[0];
                }

                $student = $this->students->firstWhere('id', $studentId);

                if (!$student) {
                    $errors[] = "Ligne {$row} : apprenant introuvable dans cette classe.";
                    continue;
                }

                if (isset($processedRowByStudent[$studentId])) {
                    $errors[] = "Ligne {$row} : doublon pour {$student->getFullName()} (déjà traité à la ligne {$processedRowByStudent[$studentId]}), ligne ignorée.";
                    continue;
                }

                if (isset($this->pendingMarks[$studentId])) {
                    $errors[] = "Ligne {$row} : des notes sont déjà en attente pour {$student->getFullName()}, ses notes existantes n'ont pas été modifiées. Retirez-les d'abord si vous voulez les remplacer depuis Excel.";
                    continue;
                }

                // ─── Lecture et validation des notes ───
                $existingTypes = $this->getExistingTypesForStudent($studentId);

                $availableInterroTypes = array_values(array_diff(self::INTERRO_TYPES, $existingTypes));
                $availableDevoirTypes  = array_values(array_diff($this->devoirTypesForTenant(), $existingTypes));

                $interroValues = [];

                foreach (['interro_1', 'interro_2', 'interro_3', 'interro_4'] as $colKey) {

                    $raw = $rowData[$colKey] ?? null;

                    if ($raw === null || trim((string) $raw) === '') continue;

                    $normalized = str_replace(',', '.', trim((string) $raw));

                    if (!is_numeric($normalized) || (float) $normalized < 0 || (float) $normalized > 20) {
                        $errors[] = "Ligne {$row} ({$student->getFullName()}) : valeur d'interrogation \"{$raw}\" invalide, cellule ignorée.";
                        continue;
                    }

                    $interroValues[] = round((float) $normalized, 2);
                }

                if (count($interroValues) > count($availableInterroTypes)) {
                    $errors[] = "Ligne {$row} ({$student->getFullName()}) : trop de notes d'interrogation, seules les " . count($availableInterroTypes) . " première(s) ont été prises en compte.";
                    $interroValues = array_slice($interroValues, 0, count($availableInterroTypes));
                }

                $devoirValues = [];

                foreach (['devoir_1', 'devoir_2'] as $colKey) {

                    $raw = $rowData[$colKey] ?? null;

                    if ($raw === null || trim((string) $raw) === '') continue;

                    $normalized = str_replace(',', '.', trim((string) $raw));

                    if (!is_numeric($normalized) || (float) $normalized < 0 || (float) $normalized > 20) {
                        $errors[] = "Ligne {$row} ({$student->getFullName()}) : valeur de devoir \"{$raw}\" invalide, cellule ignorée.";
                        continue;
                    }

                    $devoirValues[] = round((float) $normalized, 2);
                }

                if (count($devoirValues) > count($availableDevoirTypes)) {
                    $errors[] = "Ligne {$row} ({$student->getFullName()}) : trop de notes de devoir, seules les " . count($availableDevoirTypes) . " première(s) ont été prises en compte.";
                    $devoirValues = array_slice($devoirValues, 0, count($availableDevoirTypes));
                }

                if (empty($interroValues) && empty($devoirValues)) {
                    continue;
                }

                // ─── Assignation aux créneaux disponibles (même logique que la saisie manuelle) ───
                $interroMarks = $this->assignToAvailableSlots($interroValues, $availableInterroTypes);
                $devoirMarks  = $this->assignToAvailableSlots($devoirValues, $availableDevoirTypes);

                // Insertion directe en pending : pas besoin que l'enseignant clique "Insérer"
                $this->pendingMarks[$studentId] = [
                    'interro' => $interroMarks,
                    'devoir'  => $devoirMarks,
                ];

                $this->inputs[$studentId] = [
                    'interro' => $this->formatMarksForInput($interroMarks, self::INTERRO_TYPES),
                    'devoir'  => $this->formatMarksForInput($devoirMarks, $this->devoirTypesForTenant()),
                ];

                $processedRowByStudent[$studentId] = $row;
                $loadedCount++;
            }

            $this->savePendingMarksToSession();

            $this->excelPreviewErrors = $errors;
            $this->reset('excelFile');
            $this->mode = 'manual';

            if ($loadedCount > 0) {
                $this->notification()->send([
                    'icon'        => 'success',
                    'title'       => 'Notes importées et ajoutées',
                    'description' => "{$loadedCount} apprenant(s) ajouté(s) automatiquement à la liste en attente" . (count($errors) ? ', ' . count($errors) . ' ligne(s)/cellule(s) ignorée(s) (voir détails ci-dessous).' : '.'),
                ]);
            } else {
                $this->notification()->send([
                    'icon'        => 'error',
                    'title'       => 'Aucune note importée',
                    'description' => 'Vérifiez le format du fichier et les messages ci-dessous.',
                ]);
            }
        } catch (\InvalidArgumentException $e) {
            $this->notification()->send([
                'icon'        => 'error',
                'title'       => 'Erreur de chargement',
                'description' => $e->getMessage(),
            ]);
        } catch (\Throwable $e) {
            report($e);

            $this->notification()->send([
                'icon'        => 'error',
                'title'       => 'Erreur inattendue',
                'description' => "Le fichier n'a pas pu être lu. Vérifiez qu'il s'agit bien d'un fichier Excel valide (.xlsx ou .xls).",
            ]);
        }
    }

    // ─── Actions globales ───────────────────────────────────────────────

    public function resetAllInputs(): void
    {
        $this->inputs = [];

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
        if(!($this->activeYear && $this->activeYear->active_period)){

            $this->notification()->send([
                'icon'        => 'warning',
                'title'       => "Aucun {$this->activeYear?->periodLabel()} n'est actif",
                'description' => "Veuillez demander au directeur ou aux administrateurs d'activer le {$this->activeYear?->periodLabel()}",
            ]);

            return;
        }
        if (!$this->period) {
            $this->notification()->send([
                'icon'        => 'warning',
                'title'       => "Aucun {$this->activeYear?->periodLabel()} sélectionné",
                'description' => "Veuillez sélectionner un {$this->activeYear?->periodLabel()} avant de valider.",
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

        InitProcessToCreateStudentsMarksEvent::dispatch(
            tenantId:       tenant('id'),
            teacherId:      $this->teacher->id,
            classeId:       $this->classe->id,
            subjectId:      $this->subject->id,
            period:         $this->period,
            data:           $this->finalMarksPayload,
            schoolYearId:   $this->activeYear->id,
        );
    }

   

    public function render()
    {
        return view('livewire.tenants.users.teacher.teacher-classe-marks-manager-component');
    }
}