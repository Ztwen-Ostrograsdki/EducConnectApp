<?php

namespace App\Livewire\Tenants\Classes;

use App\Jobs\JobToMigrateStudentsToClasses;
use App\Models\Classe;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\YearlyClasseStudent;
use App\Services\StudentMigrationSession;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use PhpOffice\PhpSpreadsheet\IOFactory;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title('Migration des apprenants vers une classe')]
class MigrateStudentsToClassesComponent extends Component
{
    use WithFileUploads, WithPagination, WireUiActions;

    // ─── Mode ─────────────────────────────────────────────────────────
    public string $mode = 'manual';

    // ─── Classe cible ─────────────────────────────────────────────────
    public ?string $classe_slug = null;
    public ?int $classeId = null;
    public ?Classe $selectedClasse = null;
    public array $draftStudents = [];

    // ─── Mode manuel ──────────────────────────────────────────────────
    public string $identifiersInput = '';

    // ─── Mode Excel ───────────────────────────────────────────────────
    public $excelFile       = null;
    public array $importErrors = [];
    public string $detectedColumn = ''; // 'matricule' | 'educMaster' | 'both'

    // ─── Mode browse ──────────────────────────────────────────────────
    public string $browseSearch = '';

    // ─── UI ───────────────────────────────────────────────────────────
    public string $errorMessage = '';
    public array  $notFoundIds  = [];
    public bool   $migrating    = false;

    // ─── Lifecycle ────────────────────────────────────────────────────

    public function mount(?string $classe_slug = null): void
    {
        $draft = StudentMigrationSession::get();

        if($classe_slug){

            $schoolYearId = SchoolYear::current()->first()?->id;

            $classe = Classe::withTrashed()->where('slug', $classe_slug)->where('school_year_id', $schoolYearId)->first();

            if($classe){

                $this->classeId = $classe->id;

                StudentMigrationSession::setClasse($classe->id);

                $this->selectedClasse = $classe;

                $this->classe_slug = $classe_slug;
            }
            else{

                abort(404);
            }
        }
        else {
            // Pas de paramètre → on reprend ce qui est en session
            $this->classeId = $draft['classe_id'];
        }

        if(session()->has('students_mode_import')){

            $this->mode = session('students_mode_import');
        }

        $this->refreshDraft();
    }

    public function updatedClasseId(): void
    {
        $this->selectedClasse = $this->classeId
            ? Classe::with(['promotion', 'filiar', 'serial'])->find($this->classeId)
            : null;

        StudentMigrationSession::setClasse((int) $this->classeId);
        $this->errorMessage = '';
    }

    private function refreshDraft(): void
    {
        $this->draftStudents = StudentMigrationSession::get()['students'] ?? [];
    }


    public function updatedMode(): void
    {
        session()->put('students_mode_import', $this->mode);

        $this->resetPage();
        $this->errorMessage     = '';
        $this->notFoundIds      = [];
        $this->importErrors     = [];
        $this->identifiersInput = '';
        $this->excelFile        = null;
        $this->detectedColumn   = '';
    }

    // ─── Computed ─────────────────────────────────────────────────────

    #[Computed]
    public function activeYear(): ?SchoolYear
    {
        return SchoolYear::current()->first();
    }

    #[Computed]
    public function classes()
    {
        return Classe::where('school_year_id', $this->activeYear?->id)
            ->where('is_active', true)
            ->with(['promotion', 'filiar'])
            ->orderBy('name')
            ->get();
    }


    #[Computed]
    public function draft(): array
    {
        return StudentMigrationSession::get();
    }

    #[Computed]
    public function draftStudents(): array
    {
        return $this->draft['students'] ?? [];
    }

    #[Computed]
    public function validStudents(): array
    {
        return array_values(array_filter($this->draftStudents, fn($s) => !$s['conflict']));
    }

    #[Computed]
    public function conflictStudents(): array
    {
        return array_values(array_filter($this->draftStudents, fn($s) => $s['conflict']));
    }


    // ─── Résolution identifiant → Student ────────────────────────────

    /**
     * Détecte quelle colonne utiliser selon la valeur de l'en-tête.
     * Retourne 'matricule', 'educMaster', ou 'both' (ambiguë → cherche les deux).
     */
    private function detectColumnType(string $header): string
    {
        $h = strtolower(trim($header));

        if (str_contains($h, 'matricule')) return 'matricule';
        if (str_contains($h, 'educmaster') || str_contains($h, 'educ_master')) return 'educMaster';

        // En-tête ambiguë (ex: "identifiant", "id", "code") → cherche les deux
        return 'both';
    }

    /**
     * Résout un identifiant brut selon le type de colonne détecté.
     * Retourne le Student ou null.
     */
    private function resolveOneIdentifier(string $id, string $columnType): ?Student
    {
        $id = trim($id);
        if ($id === '') return null;

        return match ($columnType) {
            'matricule'  => Student::where('matricule', $id)->first(),
            'educMaster' => Student::whereNotNull('educMaster')->where('educMaster', $id)->first(),
            'both'       => Student::where('matricule', $id)
                                ->orWhere(fn($q) => $q->whereNotNull('educMaster')->where('educMaster', $id))
                                ->first(),
        };
    }

    /**
     * Prend un tableau d'identifiants bruts, retourne [$found[], $notFound[]].
     * $columnType = 'matricule' | 'educMaster' | 'both'
     */
    private function resolveIdentifiers(array $ids, string $columnType = 'both'): array
    {
        $found    = [];
        $notFound = [];
        $yearId   = $this->activeYear?->id;

        // Dédoublonnage des IDs bruts
        $ids = array_unique(array_filter(array_map('trim', $ids)));

        // IDs déjà en session → on skip pour éviter les doublons
        $alreadyInDraft = array_column($this->draftStudents, 'id');

        foreach ($ids as $raw) {
            $student = $this->resolveOneIdentifier($raw, $columnType);

            if (!$student) {
                $notFound[] = $raw;
                continue;
            }

            // Déjà dans le draft → on skip silencieusement
            if (in_array($student->id, $alreadyInDraft)) {
                continue;
            }

            // Vérifier conflit en DB (déjà affecté à une classe cette année)
            $existing = YearlyClasseStudent::where('student_id', $student->id)
                ->where('school_year_id', $yearId)
                ->with('classe')
                ->first();

            $found[] = [
                'id'              => $student->id,
                'matricule'       => $student->matricule,
                'educMaster'      => $student->educMaster,
                'name'            => $student->name,
                'prenames'        => $student->prenames,
                'conflict'        => (bool) $existing,
                'conflict_classe' => $existing?->classe?->name,
            ];
        }

        return [$found, $notFound];
    }

    // ─── Mode manuel ──────────────────────────────────────────────────

    public function loadManual(): void
    {
        if (!$this->classeId) {
            $this->errorMessage = 'Veuillez sélectionner une classe.';
            return;
        }

        $raw = preg_split('/[,\-\n\s]+/', $this->identifiersInput);
        $raw = array_filter(array_map('trim', $raw));

        if (empty($raw)) {
            $this->errorMessage = 'Aucun identifiant saisi.';
            return;
        }

        [$found, $notFound] = $this->resolveIdentifiers(array_values($raw), 'both');

        StudentMigrationSession::addStudents($found);

        $this->notFoundIds      = $notFound;
        $this->identifiersInput = '';
        $this->errorMessage     = '';
    }

    // ─── Mode Excel ───────────────────────────────────────────────────

    public function updatedExcelFile(): void
    {
        $this->importErrors   = [];
        $this->notFoundIds    = [];
        $this->detectedColumn = '';

        $this->validate([
            'excelFile' => 'required|file|mimes:xlsx,xls|max:5120',
        ]);

        if (!$this->classeId) {
            $this->errorMessage = 'Veuillez sélectionner une classe avant d\'importer.';
            $this->excelFile    = null;
            return;
        }

        try {
            $path        = $this->excelFile->getRealPath();
            $spreadsheet = IOFactory::load($path);
            $sheet       = $spreadsheet->getActiveSheet();
            $rows        = $sheet->toArray(null, true, true, true);

            if (empty($rows)) {
                $this->errorMessage = 'Le fichier est vide.';
                $this->excelFile    = null;
                return;
            }

            // ── Détection de l'en-tête ────────────────────────────────
            $headerRow = array_shift($rows); // retire la ligne d'en-tête
            $idColKey  = null;
            $columnType = 'both';

            foreach ($headerRow as $colKey => $cellValue) {
                if (!$cellValue) continue;
                $type = $this->detectColumnType((string) $cellValue);
                if ($type !== 'both') {
                    // En-tête explicite trouvée
                    $idColKey   = $colKey;
                    $columnType = $type;
                    break;
                } elseif (str_contains(strtolower(trim((string) $cellValue)), 'identifiant')
                    || str_contains(strtolower(trim((string) $cellValue)), 'id')
                    || str_contains(strtolower(trim((string) $cellValue)), 'code')
                ) {
                    // En-tête ambiguë → cherche les deux colonnes DB
                    $idColKey   = $colKey;
                    $columnType = 'both';
                    break;
                }
            }

            if ($idColKey === null) {
                $this->errorMessage = 'Colonne identifiant introuvable. Attendu : "matricule", "educMaster", ou "identifiant".';
                $this->excelFile    = null;
                return;
            }

            $this->detectedColumn = $columnType;

            // ── Extraction des identifiants ───────────────────────────
            $errors = [];
            $ids    = [];

            foreach ($rows as $index => $row) {
                $line = $index + 2; // ligne Excel réelle
                $val  = trim((string) ($row[$idColKey] ?? ''));

                if ($val === '') {
                    $errors[] = "Ligne {$line} : identifiant vide, ligne ignorée.";
                    continue;
                }

                $ids[] = $val;
            }

            // ── Résolution ────────────────────────────────────────────
            [$found, $notFound] = $this->resolveIdentifiers($ids, $columnType);

            foreach ($notFound as $nf) {
                $errors[] = "Identifiant « {$nf} » : aucun apprenant trouvé en base, ignoré.";
            }

            StudentMigrationSession::addStudents($found);

            $this->importErrors = $errors;
            $this->excelFile    = null;

            // ── Notification ──────────────────────────────────────────
            $added    = count($found);
            $ignored  = count($notFound);
            $conflicts = count(array_filter($found, fn($s) => $s['conflict']));

            if ($added > 0 && empty($errors)) {
                $this->dispatch('notify', type: 'success',
                    message: "{$added} apprenant(s) chargé(s) depuis le fichier.");
            } elseif ($added > 0) {
                $this->dispatch('notify', type: 'warning',
                    message: "{$added} chargé(s), {$ignored} ignoré(s). Voir les détails.");
            } else {
                $this->dispatch('notify', type: 'error',
                    message: "Aucun apprenant chargé. {$ignored} ligne(s) ignorée(s).");
            }

        } catch (\Throwable $e) {
            $this->dispatch('notify', type: 'error',
                message: 'Impossible de lire le fichier : ' . $e->getMessage());
            $this->excelFile = null;
        }
    }

    // ─── Mode browse ──────────────────────────────────────────────────

    public function toggleStudent(int $studentId): void
    {
        if (!$this->classeId) {
            $this->errorMessage = 'Veuillez sélectionner une classe.';
            return;
        }

        $draft = StudentMigrationSession::get();
        $alreadyInDraft = array_column($draft['students'] ?? [], 'id');

        if (in_array($studentId, $alreadyInDraft)) {
            StudentMigrationSession::removeStudent($studentId);
            $this->refreshDraft();
            return;
        }

        $student = Student::find($studentId);
        if (!$student) return;

        [$found] = $this->resolveIdentifiers([$student->matricule], 'matricule');

        if (!empty($found)) {
            StudentMigrationSession::addStudents($found);
        }

        $this->refreshDraft();
    }


    public function isInDraft(int $studentId): bool
    {
        $draft = StudentMigrationSession::get();
        return in_array($studentId, array_column($draft['students'] ?? [], 'id'));
    }

    // ─── Actions sur le draft ─────────────────────────────────────────

    public function removeFromDraft(int $studentId): void
    {
        StudentMigrationSession::removeStudent($studentId);

        $this->refreshDraft();
    }

    public function clearDraft(): void
    {
        StudentMigrationSession::clear();
        $this->notFoundIds  = [];
        $this->importErrors = [];
        $this->errorMessage = '';
        $this->migrating    = false;

        $this->refreshDraft();
    }

    // ─── Migration ────────────────────────────────────────────────────

    public function migrate(): void
    {
        if (!$this->classeId) {
            $this->errorMessage = 'Aucune classe sélectionnée.';
            return;
        }

        $validIds = array_column($this->validStudents, 'id');

        if (empty($validIds)) {
            $this->errorMessage = 'Aucun apprenant valide à migrer.';
            return;
        }

        JobToMigrateStudentsToClasses::dispatch(
            tenantId:     tenant('id'),
            classeId:     $this->classeId,
            studentIds:   $validIds,
            schoolYearId: $this->activeYear->id,
            authorId:     auth('tenant')->id(),
        );

        StudentMigrationSession::clear();

        $this->migrating    = true;
        $this->errorMessage = '';
        $this->notFoundIds  = [];
        $this->importErrors = [];

        $count = count($validIds);

        $this->notification()->success(
                title: 'MIGRATION DES APPRENANTS LANCEE',
                description: "La migration de {$count} apprenants vers la classe {$this->selectedClasse->name} a été lancée.",
            );

    }

    // ─── Render ───────────────────────────────────────────────────────

    public function render()
    {
        $browseStudents = null;

        if ($this->mode === 'browse') {
            $browseStudents = Student::query()
                ->where('is_active', true)
                ->when($this->browseSearch, fn($q) =>
                    $q->where(fn($q) =>
                        $q->where('name', 'like', '%'.$this->browseSearch.'%')
                          ->orWhere('prenames', 'like', '%'.$this->browseSearch.'%')
                          ->orWhere('matricule', 'like', '%'.$this->browseSearch.'%')
                    )
                )
                ->orderBy('name')
                ->paginate(50);
        }

        return view('livewire.tenants.classes.migrate-students-to-classes-component', compact('browseStudents'));
    }
}