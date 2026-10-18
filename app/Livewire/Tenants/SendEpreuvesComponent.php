<?php

namespace App\Livewire\Tenants;

use App\Jobs\JobToCreateEpreuves;
use App\Models\Classe;
use App\Models\Filiar;
use App\Models\Promotion;
use App\Models\SchoolYear;
use App\Models\Subject;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use WireUi\Traits\WireUiActions;

#[Title('Envoi des épreuves')]
/**
 * @property-read LengthAwarePaginator $epreuves
 */
class SendEpreuvesComponent extends Component
{
    use WithFileUploads, WireUiActions;

    /** @var array<\Livewire\Features\SupportFileUploads\TemporaryUploadedFile> */
    public array $files = [];

    public ?int $classe_id = null;
    public ?int $filiar_id = null;
    public ?int $promotion_id = null;
    public ?int $subject_id = null;
    public ?int $period = null;
    public string $type = 'devoir';

    public function rules(): array
    {
        return [
            'files'         => ['required', 'array', 'min:1', 'max:15'],
            'files.*'       => ['file', 'max:10240'], // 10 Mo max par fichier
            'classe_id'     => ['nullable', 'integer', 'exists:classes,id'],
            'filiar_id'     => ['nullable', 'integer', 'exists:filiars,id'],
            'promotion_id'  => ['nullable', 'integer', 'exists:promotions,id'],
            'subject_id'    => ['nullable', 'integer', 'exists:subjects,id'],
            'period'        => ['required', 'integer', 'min:1'],
            'type'          => ['required', 'string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'files.required' => 'Veuillez sélectionner au moins un fichier.',
            'files.min'      => 'Veuillez sélectionner au moins un fichier.',
            'files.max'      => 'Vous ne pouvez pas envoyer plus de 15 fichiers à la fois.',
            'files.*.max'    => 'Chaque fichier ne doit pas dépasser 10 Mo.',
            'period.required'=> 'Veuillez sélectionner une période.',
            'type.required'  => 'Veuillez indiquer le type d\'épreuve.',
        ];
    }

    /**
     * Liste détaillée des fichiers sélectionnés (avant envoi).
     *
     * @return array<int, array{name: string, size: string, type: string, index: int}>
     */
    #[Computed]
    public function filesList(): array
    {
        $list = [];

        foreach ($this->files as $index => $file) {
            if (! $file) {
                continue;
            }

            $list[] = [
                'index' => $index,
                'name'  => $file->getClientOriginalName(),
                'size'  => $this->formatBytes($file->getSize()),
                'type'  => $file->extension(),
            ];
        }

        return $list;
    }

    #[Computed]
    public function filesCount(): int
    {
        return count(array_filter($this->files));
    }

    #[Computed]
    public function currentSchoolYear(): ?SchoolYear
    {
        return SchoolYear::query()->current()->first();
    }

    #[Computed]
    public function periods(): array
    {
        $year = $this->currentSchoolYear;

        if (! $year) {
            return [];
        }

        return $year->getPeriods();
    }

    #[Computed]
    public function classes()
    {
        $yearId = $this->currentSchoolYear?->id;

        if (! $yearId) {
            return collect();
        }

        return Classe::query()
            ->where('school_year_id', $yearId)
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    #[Computed]
    public function filiars()
    {
        return Filiar::query()->orderBy('name')->get(['id', 'name']);
    }

    #[Computed]
    public function promotions()
    {
        return Promotion::query()->orderBy('name')->get(['id', 'name']);
    }

    #[Computed]
    public function subjects()
    {
        return Subject::query()->orderBy('name')->get(['id', 'name']);
    }

    /**
     * Retire un fichier de la liste avant envoi.
     */
    public function removeFile(int $index): void
    {
        if (! isset($this->files[$index])) {
            return;
        }

        unset($this->files[$index]);
        $this->files = array_values($this->files);

        unset($this->filesList, $this->filesCount);
    }

    /**
     * Réinitialise complètement la sélection de fichiers.
     */
    public function clearFiles(): void
    {
        $this->reset('files');
        unset($this->filesList, $this->filesCount);
    }

    /**
     * Déclenche l'envoi : validation + dispatch du job.
     */
    public function send(): void
    {
        $this->validate();

        /** @var \App\Models\User $user */
        $user = Auth::guard('tenant')->user();
        $year = $this->currentSchoolYear;

        if (! $year) {
            $this->notification()->error(
                title: 'Année scolaire introuvable',
                description: 'Aucune année scolaire active n\'a été trouvée.',
            );

            return;
        }

        if (empty($this->files)) {
            $this->notification()->error(
                title: 'Aucun fichier',
                description: 'Veuillez sélectionner au moins un fichier à envoyer.',
            );

            return;
        }

        // Récupération du nom de la classe si fournie
        $classeName = null;
        if ($this->classe_id) {
            $classeName = Classe::query()->where('id', $this->classe_id)->value('name');
        }

        // Label de la période
        $periodLabel = null;
        foreach ($this->periods as $p) {
            if (($p['index'] ?? null) === $this->period) {
                $periodLabel = $p['label'] ?? null;
                break;
            }
        }

        // Lot unique pour ce batch
        $lot = (int) (random_int(100, 999));

        // Préparation des fichiers temporaires pour le job
        $filesData = [];
        $tmpDir    = storage_path('app/livewire-tmp/epreuves-' . Str::uuid());

        if (! is_dir($tmpDir)) {
            mkdir($tmpDir, 0755, true);
        }

        foreach ($this->files as $file) {
            if (! $file) {
                continue;
            }

            $originalName = $file->getClientOriginalName();
            $safeName     = Str::slug(pathinfo($originalName, PATHINFO_FILENAME))
                . '.' . strtolower($file->getClientOriginalExtension() ?: 'bin');
            $tmpPath      = $tmpDir . '/' . uniqid('ep_', true) . '_' . $safeName;

            // Copie vers un emplacement temporaire stable pour le job
            copy($file->getRealPath(), $tmpPath);

            $filesData[] = [
                'tmp_path'      => $tmpPath,
                'original_name' => $originalName,
                'mime'          => $file->getMimeType(),
                'size'          => $file->getSize(),
            ];
        }

        if (empty($filesData)) {
            $this->notification()->error(
                title: 'Aucun fichier valide',
                description: 'Impossible de préparer les fichiers pour l\'envoi.',
            );

            return;
        }

        $teacherName = $user->getFullName() ?: ($user->name . ' ' . $user->prenames) ?: 'Enseignant';

        $context = [
            'classe_id'        => $this->classe_id,
            'classe_name'      => $classeName,
            'filiar_id'        => $this->filiar_id,
            'promotion_id'     => $this->promotion_id,
            'subject_id'       => $this->subject_id,
            'school_year_id'   => $year->id,
            'school_year_slug' => $year->slug,
            'period'           => $this->period,
            'period_as_label'  => $periodLabel,
            'type'             => $this->type,
        ];

        JobToCreateEpreuves::dispatch(
            tenant('id'),
            $filesData,
            $context,
            $user->id,
            $teacherName,
            $lot,
        );

        $count = count($filesData);

        $this->notification()->success(
            title: 'Envoi en cours',
            description: $count . ' épreuve(s) en cours de traitement. Vous serez notifié une fois le traitement terminé.',
        );

        $this->reset(['files', 'classe_id', 'filiar_id', 'promotion_id', 'subject_id', 'period', 'type']);
        $this->type = 'devoir';
        unset($this->filesList, $this->filesCount);
    }

    protected function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['o', 'Ko', 'Mo', 'Go'];
        $bytes = max($bytes, 0);
        $pow   = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow   = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    public function render()
    {
         /** @var \App\Models\User $user */
        $user = auth('tenant')->user();

        $view = view('livewire.tenants.send-epreuves-component');

        /** @disregard P1013 `layout()` est ajoutée à View par Livewire (macro) */
        return $view->layout($user->getDashboardLayout());

    }
}
