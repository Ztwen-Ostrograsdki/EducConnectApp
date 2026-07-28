<?php

namespace App\Livewire\Tenants\Students;


use App\Jobs\JobToGeneratePrintableMarksDataForThePrintViewComponent;
use App\Models\Classe;
use App\Models\Filiar;
use App\Models\Promotion;
use App\Models\SchoolYear;
use App\Models\Serial;
use App\Models\Subject;
use App\Services\MarksServices\MarkPrintColumns;
use App\Services\MarksServices\MarkPrintQuery;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Title("Impression des notes")]
#[Layout('livewire.layouts.tenant-auth-layout')]
class MarksPrintsManagerComponent extends Component
{
    use WireUiActions;

    public ?int $classe_id = null;
    public ?int $filiar_id = null;
    public ?int $serial_id = null;
    public ?int $promotion_id = null;
    public ?string $promotionInGroups = null;
    public ?string $level = null;

    public ?int $subject_id = null;
    public ?int $period = null;

    public string $leavesStatus = 'onlyActives';

    public array $leavesStatuses = [
        'onlyActives' => "Seulement apprenants actifs",
        'onlyLeaves'  => "Seulement apprenants abandons",
        'withLeaves'  => "Tous, abandons inclus",
    ];

    protected string $sessionKey = 'mark-list-selected-columns';

    public array $columns = [];
    public array $selectedColumns = [];

    public function mount(?string $classe_slug = null): void
    {
        $this->columns = MarkPrintColumns::$columns;
        $this->selectedColumns = session()->get($this->sessionKey, []);

        $this->selectedColumns = array_values(array_filter(
            $this->selectedColumns,
            fn (string $key) => array_key_exists($key, $this->columns)
        ));

        if (session()->has('print_marks_classe_selected'))    $this->classe_id = session('print_marks_classe_selected');
        if (session()->has('print_marks_filiar_selected'))    $this->filiar_id = session('print_marks_filiar_selected');
        if (session()->has('print_marks_serial_selected'))     $this->serial_id = session('print_marks_serial_selected');
        if (session()->has('print_marks_promotion_selected'))  $this->promotion_id = session('print_marks_promotion_selected');
        if (session()->has('print_marks_promotions_grouped_selected')) $this->promotionInGroups = session('print_marks_promotions_grouped_selected');
        if (session()->has('print_marks_level_selected'))      $this->level = session('print_marks_level_selected');
        if (session()->has('print_marks_subject_selected'))    $this->subject_id = session('print_marks_subject_selected');
        if (session()->has('print_marks_period_selected'))     $this->period = session('print_marks_period_selected');
        if (session()->has('print_marks_leaves_status'))       $this->leavesStatus = session('print_marks_leaves_status');
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
        return collect(MarkPrintColumns::defaultOrder($this->devoirsType, (bool) $this->subject_id))
            ->mapWithKeys(fn (string $key) => [$key => $this->columns[$key]['label'] ?? $key])
            ->toArray();
    }

    #[Computed]
    public function devoirsType(): string
    {
        return tenant()->devoirs_type ?? 'devoir1-devoir2';
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
            "level"             => $this->level,
            "subject_id"        => $this->subject_id,
            "leavesConfig"      => $this->leavesStatus,
        ];
    }

    #[Computed]
    public function allClassesCounter()
    {
        return $this->activeYear ? MarkPrintQuery::count($this->currentFilterConfig(), $this->activeYear->id) : 0;
    }

    #[Computed]
    public function currentDocTitle(): string
    {
        return MarkPrintQuery::resolveDocTitle(
            $this->currentFilterConfig(),
            $this->subject_id,
            $this->period,
            $this->activeYear->id
        );
    }

    public function updatedSubjectId(?string $value): void { session()->put('print_marks_subject_selected', $value); }
    public function updatedPeriod(?string $value): void { session()->put('print_marks_period_selected', $value); }
    public function updatedLeavesStatus(?string $value): void { session()->put('print_marks_leaves_status', $value); }
    public function updatedLevel(?string $value): void { session()->put('print_marks_level_selected', $value); }

    public function updatedClasseId(?string $value): void
    {
        if ($value) {
            $this->reset(['filiar_id', 'serial_id', 'promotion_id', 'promotionInGroups']);
            session()->forget(['print_marks_filiar_selected', 'print_marks_serial_selected', 'print_marks_promotion_selected', 'print_marks_promotions_grouped_selected']);
        }
        session()->put('print_marks_classe_selected', $value);
    }

    public function updatedFiliarId(?string $value): void
    {
        if ($value) {
            $this->reset(['classe_id', 'serial_id', 'promotion_id']);
            session()->forget(['print_marks_classe_selected', 'print_marks_serial_selected', 'print_marks_promotion_selected']);
        }
        session()->put('print_marks_filiar_selected', $value);
    }

    public function updatedSerialId(?string $value): void
    {
        if ($value) {
            $this->reset(['classe_id', 'filiar_id', 'promotion_id']);
            session()->forget(['print_marks_classe_selected', 'print_marks_filiar_selected', 'print_marks_promotion_selected']);
        }
        session()->put('print_marks_serial_selected', $value);
    }

    public function updatedPromotionId(?string $value): void
    {
        if ($value) {
            $this->reset(['classe_id', 'filiar_id', 'serial_id', 'promotionInGroups']);
            session()->forget(['print_marks_classe_selected', 'print_marks_filiar_selected', 'print_marks_serial_selected', 'print_marks_promotions_grouped_selected']);
        }
        session()->put('print_marks_promotion_selected', $value);
    }

    public function updatedPromotionInGroups(?string $value): void
    {
        if ($value) {
            $this->reset(['classe_id', 'promotion_id']);
            session()->forget(['print_marks_classe_selected', 'print_marks_promotion_selected']);
        }
        session()->put('print_marks_promotions_grouped_selected', $value);
    }

    public function resetFilters(): void
    {
        session()->forget([
            'print_marks_classe_selected', 'print_marks_filiar_selected', 'print_marks_serial_selected',
            'print_marks_promotion_selected', 'print_marks_promotions_grouped_selected', 'print_marks_level_selected',
            'print_marks_subject_selected', 'print_marks_period_selected', 'print_marks_leaves_status',
        ]);

        $this->reset(
            'classe_id', 'filiar_id', 'serial_id', 'promotion_id',
            'promotionInGroups', 'level', 'subject_id', 'period'
        );

        $this->leavesStatus = 'onlyActives';
    }

    public function initPrintProcess()
    {
        if (! $this->period) {
            $this->notification()->info(title: "Veuillez sélectionner une période avant de lancer l'impression.");
            return;
        }

        if (! $this->allClassesCounter) {
            $this->notification()->info(
                title: "La procédure ne peut être lancée : aucune classe trouvée",
                description: "Pour les conditions que vous avez définies, aucune classe n'a été trouvée.",
            );
            return;
        }

        JobToGeneratePrintableMarksDataForThePrintViewComponent::dispatch(
            tenantId:       tenant('id'),
            notifiableId:   auth('tenant')->user()->id,
            period:         $this->period,
            school_year_id: $this->activeYear->id,
            subject_id:     $this->subject_id,
            docTitle:       $this->currentDocTitle,
            config: [
                ...$this->currentFilterConfig(),
                'tableColumns' => MarkPrintColumns::build($this->selectedColumns, $this->devoirsType, (bool) $this->subject_id),
            ],
        );

        $this->notification()->success(title: 'Génération du document lancée');
    }

    #[Computed]
    public function classes()
    {
        return Classe::where('school_year_id', $this->activeYear?->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    public function render()
    {
        return view('livewire.tenants.students.marks-prints-manager-component');
    }
}