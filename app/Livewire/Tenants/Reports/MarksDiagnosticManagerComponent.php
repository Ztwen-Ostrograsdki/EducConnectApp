<?php

namespace App\Livewire\Tenants\Reports;


use App\Jobs\JobToGeneratePrintableMarksDiagnosticDataForThePrintViewComponent;
use App\Models\Filiar;
use App\Models\Promotion;
use App\Models\SchoolYear;
use App\Models\Serial;
use App\Models\Subject;
use App\Services\MarksServices\MarkDiagnosticColumns;
use App\Services\MarksServices\MarkDiagnosticQuery;
use App\Services\MarksServices\MarkPrintQuery;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Title("Diagnostic des notes")]
#[Layout('livewire.layouts.tenant-auth-layout')]
class MarksDiagnosticManagerComponent extends Component
{
    use WireUiActions;

    public ?int $classe_id = null;
    public ?int $filiar_id = null;
    public ?int $serial_id = null;
    public ?int $promotion_id = null;
    public ?string $promotionInGroups = null;
    public ?int $subject_id = null;
    public ?int $period = null;

    public string $status = 'both';

    public array $checkedMarkTypes = [];

    public array $statusOptions = [
        'both'       => "Tous les enseignants",
        'hasMarks'   => "Ayant déjà des notes saisies (≥ 95%)",
        'hasntMarks' => "N'ayant pas encore de notes complètes",
    ];

    protected string $sessionKey = 'mark-diagnostic-selected-columns';

    public array $columns = [];
    public array $selectedColumns = [];

    public function mount(): void
    {
        $this->columns = MarkDiagnosticColumns::$columns;
        $this->selectedColumns = session()->get($this->sessionKey, []);

        $this->selectedColumns = array_values(array_filter(
            $this->selectedColumns,
            fn (string $key) => array_key_exists($key, $this->columns)
        ));

        if (session()->has('print_diag_classe_selected'))    $this->classe_id = session('print_diag_classe_selected');
        if (session()->has('print_diag_filiar_selected'))    $this->filiar_id = session('print_diag_filiar_selected');
        if (session()->has('print_diag_serial_selected'))     $this->serial_id = session('print_diag_serial_selected');
        if (session()->has('print_diag_promotion_selected'))  $this->promotion_id = session('print_diag_promotion_selected');
        if (session()->has('print_diag_promotions_grouped_selected')) $this->promotionInGroups = session('print_diag_promotions_grouped_selected');
        if (session()->has('print_diag_subject_selected'))    $this->subject_id = session('print_diag_subject_selected');
        if (session()->has('print_diag_period_selected'))     $this->period = session('print_diag_period_selected');
        if (session()->has('print_diag_status'))              $this->status = session('print_diag_status');

        $this->checkedMarkTypes = session()->get('print_diag_mark_types', array_keys($this->availableMarkTypes));
    }

    public function loadActivePeriod()
    {
        if ($this->activeYear && $this->activeYear->is_active && $this->activeYear->active_period) {

            $this->period = $this->activeYear->active_period;
        }
    }

    public function restoreSelects(): void
    {
        $this->selectedColumns = [];
        session()->put($this->sessionKey, []);
    }

    public function toggleColumn(string $key): void
    {
        $index = array_search($key, $this->selectedColumns, true);

        if ($index !== false) {
            unset($this->selectedColumns[$index]);
            $this->selectedColumns = array_values($this->selectedColumns);
        } else {
            $this->selectedColumns[] = $key;
        }

        session()->put($this->sessionKey, $this->selectedColumns);
    }

    public function toggleMarkType(string $type): void
    {
        $index = array_search($type, $this->checkedMarkTypes, true);

        if ($index !== false) {
            unset($this->checkedMarkTypes[$index]);
            $this->checkedMarkTypes = array_values($this->checkedMarkTypes);
        } else {
            $this->checkedMarkTypes[] = $type;
        }

        session()->put('print_diag_mark_types', $this->checkedMarkTypes);
    }

    #[Computed]
    public function availableMarkTypes(): array
    {
        $devoirsType = tenant()->devoirs_type ?? 'devoir1-devoir2';

        return array_merge(
            ['interro1' => 'Interro 1', 'interro2' => 'Interro 2', 'interro3' => 'Interro 3', 'interro4' => 'Interro 4'],
            MarkPrintQuery::devoirColumns($devoirsType)
        );
    }

    #[Computed]
    public function orderedColumns(): array
    {
        return collect($this->selectedColumns)
            ->mapWithKeys(fn (string $key) => [$key => $this->columns[$key]['label'] ?? $key])
            ->toArray();
    }

    #[Computed]
    public function defaultOrderedColumns(): array
    {
        return collect(MarkDiagnosticColumns::$defaultOrder)
            ->mapWithKeys(fn (string $key) => [$key => $this->columns[$key]['label'] ?? $key])
            ->toArray();
    }

    protected function buildTableColumns(): array
    {
        return MarkDiagnosticColumns::build($this->selectedColumns);
    }

    #[Computed]
    public function filiars() { return Filiar::where('is_active', true)->orderBy('name')->get(); }

    #[Computed]
    public function serials() { return Serial::where('is_active', true)->orderBy('name')->get(); }

    #[Computed]
    public function promotions() { return Promotion::where('is_active', true)->orderBy('name', 'desc')->get(); }

    #[Computed]
    public function promotionsGrouped()
    {
        return array_unique(Promotion::where('is_active', true)->orderBy('name', 'asc')->pluck('name')->toArray());
    }

    #[Computed]
    public function subjects() { return Subject::where('is_active', true)->orderBy('name')->get(); }

    #[Computed]
    public function classes()
    {
        return \App\Models\Classe::where('school_year_id', $this->activeYear?->id)
            ->where('is_active', true)->orderBy('name')->get();
    }

    #[Computed]
    public function activeYear(): ?SchoolYear { return SchoolYear::current()->first(); }

    #[Computed]
    public function periods_types() { return $this->activeYear?->getPeriods() ?? []; }

    protected function currentFilterConfig(): array
    {
        return [
            "classe_id"         => $this->classe_id,
            "filiar_id"         => $this->filiar_id,
            "serial_id"         => $this->serial_id,
            "promotion_id"      => $this->promotion_id,
            "promotionInGroups" => $this->promotionInGroups,
            "subject_id"        => $this->subject_id,
        ];
    }

    #[Computed]
    public function allAssignmentsCounter()
    {
        return $this->activeYear ? MarkDiagnosticQuery::count($this->currentFilterConfig(), $this->activeYear->id) : 0;
    }

    #[Computed]
    public function currentDocTitle(): string
    {
        return MarkDiagnosticQuery::resolveDocTitle(
            $this->currentFilterConfig(),
            (int) $this->period,
            $this->activeYear,
            $this->status
        );
    }

    public function updatedSubjectId(?string $value): void { session()->put('print_diag_subject_selected', $value); }
    public function updatedPeriod(?string $value): void { session()->put('print_diag_period_selected', $value); }
    public function updatedStatus(?string $value): void { session()->put('print_diag_status', $value); }

    public function updatedClasseId(?string $value): void
    {
        if ($value) {
            $this->reset(['filiar_id', 'serial_id', 'promotion_id', 'promotionInGroups']);
            session()->forget(['print_diag_filiar_selected', 'print_diag_serial_selected', 'print_diag_promotion_selected', 'print_diag_promotions_grouped_selected']);
        }
        session()->put('print_diag_classe_selected', $value);
    }

    public function updatedFiliarId(?string $value): void
    {
        if ($value) {
            $this->reset(['classe_id', 'serial_id', 'promotion_id']);
            session()->forget(['print_diag_classe_selected', 'print_diag_serial_selected', 'print_diag_promotion_selected']);
        }
        session()->put('print_diag_filiar_selected', $value);
    }

    public function updatedSerialId(?string $value): void
    {
        if ($value) {
            $this->reset(['classe_id', 'filiar_id', 'promotion_id']);
            session()->forget(['print_diag_classe_selected', 'print_diag_filiar_selected', 'print_diag_promotion_selected']);
        }
        session()->put('print_diag_serial_selected', $value);
    }

    public function updatedPromotionId(?string $value): void
    {
        if ($value) {
            $this->reset(['classe_id', 'filiar_id', 'serial_id', 'promotionInGroups']);
            session()->forget(['print_diag_classe_selected', 'print_diag_filiar_selected', 'print_diag_serial_selected', 'print_diag_promotions_grouped_selected']);
        }
        session()->put('print_diag_promotion_selected', $value);
    }

    public function updatedPromotionInGroups(?string $value): void
    {
        if ($value) {
            $this->reset(['classe_id', 'promotion_id']);
            session()->forget(['print_diag_classe_selected', 'print_diag_promotion_selected']);
        }
        session()->put('print_diag_promotions_grouped_selected', $value);
    }

    public function resetFilters(): void
    {
        session()->forget([
            'print_diag_classe_selected', 'print_diag_filiar_selected', 'print_diag_serial_selected',
            'print_diag_promotion_selected', 'print_diag_promotions_grouped_selected',
            'print_diag_subject_selected', 'print_diag_period_selected', 'print_diag_status',
            'print_diag_mark_types',
        ]);

        $this->reset('classe_id', 'filiar_id', 'serial_id', 'promotion_id', 'promotionInGroups', 'subject_id', 'period');

        $this->status = 'both';
        $this->checkedMarkTypes = array_keys($this->availableMarkTypes);
    }

    public function initPrintProcess()
    {
        if (! $this->period) {
            $this->notification()->info(title: "Veuillez sélectionner une période avant de lancer l'impression.");
            return;
        }

        if (empty($this->checkedMarkTypes)) {
            $this->notification()->info(title: "Veuillez cocher au moins un type de note à vérifier.");
            return;
        }

        if (! $this->allAssignmentsCounter) {
            $this->notification()->info(
                title: "La procédure ne peut être lancée : aucune affectation trouvée",
                description: "Pour les conditions que vous avez définies, aucune affectation enseignant/classe/matière n'a été trouvée.",
            );
            return;
        }

        JobToGeneratePrintableMarksDiagnosticDataForThePrintViewComponent::dispatch(
            tenantId:       tenant('id'),
            notifiableId:   auth('tenant')->user()->id,
            period:         $this->period,
            status:         $this->status,
            markTypes:      $this->checkedMarkTypes,
            school_year_id: $this->activeYear->id,
            docTitle:       $this->currentDocTitle,
            config: [
                ...$this->currentFilterConfig(),
                'tableColumns' => $this->buildTableColumns(),
            ],
        );

        $this->notification()->success(title: 'Génération du document lancée');
    }

    public function render()
    {
        return view('livewire.tenants.reports.marks-diagnostic-manager-component');
    }
}