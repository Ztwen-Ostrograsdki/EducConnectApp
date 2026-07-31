<?php

namespace App\Livewire\Tenants\Classes;

use App\Jobs\JobToGeneratePrintableMarksRankingDataForThePrintViewComponent;
use App\Models\Filiar;
use App\Models\Promotion;
use App\Models\SchoolYear;
use App\Models\Serial;
use App\Models\Subject;
use App\Services\MarksServices\MarkRankingPrintColumns;
use App\Services\MarksServices\MarkRankingPrintQuery;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Title("Impression des classements")]
#[Layout('livewire.layouts.tenant-auth-layout')]
class MarkRankingPrintsManagerComponent extends Component
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
    public ?string $gender = null;

    public string $targeted = 'best';
    public int $limit = 10;
    public string $groupedBy = 'classe_id';

    public array $leavesStatuses = [
        'onlyActives' => "Seulement apprenants actifs",
        'onlyLeaves'  => "Seulement apprenants abandons",
        'withLeaves'  => "Tous, abandons inclus",
    ];

    public array $targetedOptions = [
        'best'  => "Les meilleurs",
        'worst' => "Les plus faibles",
    ];

    public array $groupedByOptions = [
        'classe_id'         => "Par classe",
        'filiar_id'         => "Par filière",
        'serial_id'         => "Par série",
        'promotion_id'      => "Par promotion (spécifique)",
        'promotionInGroups' => "Par promotion (groupée)",
    ];

    protected string $sessionKey = 'mark-ranking-selected-columns';

    public array $selectedColumns = [];

    public function mount(): void
    {
        $this->selectedColumns = session()->get($this->sessionKey, []);

        if (session()->has('print_ranking_classe_selected'))    $this->classe_id = session('print_ranking_classe_selected');
        if (session()->has('print_ranking_filiar_selected'))    $this->filiar_id = session('print_ranking_filiar_selected');
        if (session()->has('print_ranking_serial_selected'))     $this->serial_id = session('print_ranking_serial_selected');
        if (session()->has('print_ranking_promotion_selected'))  $this->promotion_id = session('print_ranking_promotion_selected');
        if (session()->has('print_ranking_promotions_grouped_selected')) $this->promotionInGroups = session('print_ranking_promotions_grouped_selected');
        if (session()->has('print_ranking_level_selected'))      $this->level = session('print_ranking_level_selected');
        if (session()->has('print_ranking_subject_selected'))    $this->subject_id = session('print_ranking_subject_selected');
        if (session()->has('print_ranking_period_selected'))     $this->period = session('print_ranking_period_selected');
        if (session()->has('print_ranking_leaves_status'))       $this->leavesStatus = session('print_ranking_leaves_status');
        if (session()->has('print_ranking_gender_selected'))     $this->gender = session('print_ranking_gender_selected');
        if (session()->has('print_ranking_targeted'))            $this->targeted = session('print_ranking_targeted');
        if (session()->has('print_ranking_limit'))               $this->limit = (int) session('print_ranking_limit');
        if (session()->has('print_ranking_grouped_by'))          $this->groupedBy = session('print_ranking_grouped_by');
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
    public function availableColumns(): array
    {
        return MarkRankingPrintColumns::columns((bool) $this->subject_id);
    }

    #[Computed]
    public function orderedColumns(): array
    {
        $columns = $this->availableColumns;

        return collect($this->selectedColumns)
            ->mapWithKeys(fn (string $key) => [$key => $columns[$key]['label'] ?? $key])
            ->toArray();
    }

    #[Computed]
    public function defaultOrderedColumns(): array
    {
        $columns = $this->availableColumns;

        return collect(MarkRankingPrintColumns::$defaultOrder)
            ->mapWithKeys(fn (string $key) => [$key => $columns[$key]['label'] ?? $key])
            ->toArray();
    }

    protected function buildTableColumns(): array
    {
        return MarkRankingPrintColumns::build($this->selectedColumns, (bool) $this->subject_id);
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
            "level"             => $this->level,
            "subject_id"        => $this->subject_id,
            "leavesConfig"      => $this->leavesStatus,
            "gender"            => $this->gender,
        ];
    }

    #[Computed]
    public function currentDocTitle(): string
    {
        return MarkRankingPrintQuery::resolveDocTitle(
            $this->currentFilterConfig(),
            $this->subject_id,
            $this->targeted,
            $this->limit,
            $this->groupedBy
        );
    }

    public function updatedSubjectId(?string $value): void { session()->put('print_ranking_subject_selected', $value); }
    public function updatedPeriod(?string $value): void { session()->put('print_ranking_period_selected', $value); }
    public function updatedLeavesStatus(?string $value): void { session()->put('print_ranking_leaves_status', $value); }
    public function updatedLevel(?string $value): void { session()->put('print_ranking_level_selected', $value); }
    public function updatedGender(?string $value): void { session()->put('print_ranking_gender_selected', $value); }
    public function updatedTargeted(?string $value): void { session()->put('print_ranking_targeted', $value); }
    public function updatedLimit($value): void { session()->put('print_ranking_limit', (int) $value); }
    public function updatedGroupedBy(?string $value): void { session()->put('print_ranking_grouped_by', $value); }

    public function updatedClasseId(?string $value): void
    {
        if ($value) {
            $this->reset(['filiar_id', 'serial_id', 'promotion_id', 'promotionInGroups']);
            session()->forget(['print_ranking_filiar_selected', 'print_ranking_serial_selected', 'print_ranking_promotion_selected', 'print_ranking_promotions_grouped_selected']);
        }
        session()->put('print_ranking_classe_selected', $value);
    }

    public function updatedFiliarId(?string $value): void
    {
        if ($value) {
            $this->reset(['classe_id', 'serial_id', 'promotion_id']);
            session()->forget(['print_ranking_classe_selected', 'print_ranking_serial_selected', 'print_ranking_promotion_selected']);
        }
        session()->put('print_ranking_filiar_selected', $value);
    }

    public function updatedSerialId(?string $value): void
    {
        if ($value) {
            $this->reset(['classe_id', 'filiar_id', 'promotion_id']);
            session()->forget(['print_ranking_classe_selected', 'print_ranking_filiar_selected', 'print_ranking_promotion_selected']);
        }
        session()->put('print_ranking_serial_selected', $value);
    }

    public function updatedPromotionId(?string $value): void
    {
        if ($value) {
            $this->reset(['classe_id', 'filiar_id', 'serial_id', 'promotionInGroups']);
            session()->forget(['print_ranking_classe_selected', 'print_ranking_filiar_selected', 'print_ranking_serial_selected', 'print_ranking_promotions_grouped_selected']);
        }
        session()->put('print_ranking_promotion_selected', $value);
    }

    public function updatedPromotionInGroups(?string $value): void
    {
        if ($value) {
            $this->reset(['classe_id', 'promotion_id']);
            session()->forget(['print_ranking_classe_selected', 'print_ranking_promotion_selected']);
        }
        session()->put('print_ranking_promotions_grouped_selected', $value);
    }

    public function resetFilters(): void
    {
        session()->forget([
            'print_ranking_classe_selected', 'print_ranking_filiar_selected', 'print_ranking_serial_selected',
            'print_ranking_promotion_selected', 'print_ranking_promotions_grouped_selected', 'print_ranking_level_selected',
            'print_ranking_subject_selected', 'print_ranking_period_selected', 'print_ranking_leaves_status',
            'print_ranking_gender_selected', 'print_ranking_targeted', 'print_ranking_limit', 'print_ranking_grouped_by',
        ]);

        $this->reset(
            'classe_id', 'filiar_id', 'serial_id', 'promotion_id', 'promotionInGroups',
            'level', 'subject_id', 'period', 'gender'
        );

        $this->leavesStatus = 'onlyActives';
        $this->targeted = 'best';
        $this->limit = 10;
        $this->groupedBy = 'classe_id';
    }

    public function initPrintProcess()
    {
        if (! $this->period) {
            $this->notification()->info(title: "Veuillez sélectionner une période avant de lancer l'impression.");
            return;
        }

        if ($this->limit < 1) {
            $this->notification()->info(title: "La limite doit être un entier positif.");
            return;
        }

        JobToGeneratePrintableMarksRankingDataForThePrintViewComponent::dispatch(
            tenantId:       tenant('id'),
            notifiableId:   auth('tenant')->user()->id,
            period:         $this->period,
            targeted:       $this->targeted,
            limit:          $this->limit,
            groupedBy:      $this->groupedBy,
            school_year_id: $this->activeYear->id,
            subject_id:     $this->subject_id,
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
        return view('livewire.tenants.classes.mark-ranking-prints-manager-component');
    }
}