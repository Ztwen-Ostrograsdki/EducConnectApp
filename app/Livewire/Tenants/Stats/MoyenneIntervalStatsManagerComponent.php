<?php

namespace App\Livewire\Tenants\Stats;

use App\Jobs\JobToGeneratePrintableMoyenneIntervalStatsDataForThePrintViewComponent;
use App\Models\Classe;
use App\Models\Filiar;
use App\Models\Promotion;
use App\Models\SchoolYear;
use App\Models\Serial;
use App\Services\ClassesServices\MoyenneIntervalStatsQuery;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Title("Statistiques des moyennes par intervalle")]
#[Layout('livewire.layouts.tenant-auth-layout')]
class MoyenneIntervalStatsManagerComponent extends Component
{
    use WireUiActions;

    public ?int $classe_id = null;
    public ?int $filiar_id = null;
    public ?int $serial_id = null;
    public ?int $promotion_id = null;
    public ?string $promotionInGroups = null;

    public ?int $period = null;

    public string $groupedBy = 'classe_id';

    public string $breakpointsInput = '7, 9, 10, 12, 14, 16';

    public array $groupedByOptions = [
        'classe_id'         => "Par classe",
        'promotionInGroups' => "Par promotion",
        'filiar_id'         => "Par filière",
        'serial_id'         => "Par série",
    ];

    public function mount(): void
    {
       

        if (session()->has('print_moystats_classe_selected'))    $this->classe_id = session('print_moystats_classe_selected');
        if (session()->has('print_moystats_filiar_selected'))    $this->filiar_id = session('print_moystats_filiar_selected');
        if (session()->has('print_moystats_serial_selected'))     $this->serial_id = session('print_moystats_serial_selected');
        if (session()->has('print_moystats_promotion_selected'))  $this->promotion_id = session('print_moystats_promotion_selected');
        if (session()->has('print_moystats_promotions_grouped_selected')) $this->promotionInGroups = session('print_moystats_promotions_grouped_selected');
        
        if (session()->has('print_moystats_period_selected')){

            $this->period = session('print_moystats_period_selected');

        }  
        else{
            $this->loadActivePeriod();
        }  

        session()->put('print_moystats_period_selected', $this->period);
        
        
        if (session()->has('print_moystats_grouped_by')) {

            $this->groupedBy = session('print_moystats_grouped_by');
        }   
        
        session()->put('print_moystats_grouped_by', $this->groupedBy);
       


        $breakpoints = session()->get('print_moystats_breakpoints', [7, 9, 10, 12, 14, 16]);
        $this->breakpointsInput = implode(', ', $breakpoints);
    }


    public function loadActivePeriod()
    {
        if ($this->activeYear && $this->activeYear->is_active && $this->activeYear->active_period) {

            $this->period = $this->activeYear->active_period;
        }
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
    public function classes()
    {
        return Classe::where('school_year_id', $this->activeYear?->id)
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
        ];
    }

    /**
     * Parse "7, 9, 10, 12" -> [7.0, 9.0, 10.0, 12.0], en ignorant les valeurs
     * invalides/hors-bornes plutôt que de faire planter la saisie utilisateur.
     */
    #[Computed]
    public function parsedBreakpoints(): array
    {
        return collect(explode(',', $this->breakpointsInput))
            ->map(fn ($v) => trim($v))
            ->filter(fn ($v) => $v !== '' && is_numeric($v))
            ->map(fn ($v) => (float) $v)
            ->filter(fn ($v) => $v > 0 && $v < 20)
            ->unique()
            ->sort()
            ->values()
            ->all();
    }

    #[Computed]
    public function intervalLabelsPreview(): array
    {
        $intervals = MoyenneIntervalStatsQuery::buildIntervals($this->parsedBreakpoints);

        return MoyenneIntervalStatsQuery::intervalLabels($intervals);
    }

    #[Computed]
    public function allClassesCounter()
    {
        return $this->activeYear ? MoyenneIntervalStatsQuery::count($this->currentFilterConfig(), $this->activeYear->id) : 0;
    }

    #[Computed]
    public function currentDocTitle(): string
    {
        return MoyenneIntervalStatsQuery::resolveDocTitle(
            $this->currentFilterConfig(), $this->groupedBy, (int) $this->period, $this->activeYear
        );
    }

    public function updatedBreakpointsInput(): void
    {
        session()->put('print_moystats_breakpoints', $this->parsedBreakpoints);
    }

    public function updatedPeriod(?string $value): void { session()->put('print_moystats_period_selected', $value); }
    public function updatedGroupedBy(?string $value): void { session()->put('print_moystats_grouped_by', $value); }

    public function updatedClasseId(?string $value): void
    {
        if ($value) {
            $this->reset(['filiar_id', 'serial_id', 'promotion_id', 'promotionInGroups']);
            session()->forget(['print_moystats_filiar_selected', 'print_moystats_serial_selected', 'print_moystats_promotion_selected', 'print_moystats_promotions_grouped_selected']);
        }
        session()->put('print_moystats_classe_selected', $value);
    }

    public function updatedFiliarId(?string $value): void
    {
        if ($value) {
            $this->reset(['classe_id', 'serial_id', 'promotion_id']);
            session()->forget(['print_moystats_classe_selected', 'print_moystats_serial_selected', 'print_moystats_promotion_selected']);
        }
        session()->put('print_moystats_filiar_selected', $value);
    }

    public function updatedSerialId(?string $value): void
    {
        if ($value) {
            $this->reset(['classe_id', 'filiar_id', 'promotion_id']);
            session()->forget(['print_moystats_classe_selected', 'print_moystats_filiar_selected', 'print_moystats_promotion_selected']);
        }
        session()->put('print_moystats_serial_selected', $value);
    }

    public function updatedPromotionId(?string $value): void
    {
        if ($value) {
            $this->reset(['classe_id', 'filiar_id', 'serial_id', 'promotionInGroups']);
            session()->forget(['print_moystats_classe_selected', 'print_moystats_filiar_selected', 'print_moystats_serial_selected', 'print_moystats_promotions_grouped_selected']);
        }
        session()->put('print_moystats_promotion_selected', $value);
    }

    public function updatedPromotionInGroups(?string $value): void
    {
        if ($value) {
            $this->reset(['classe_id', 'promotion_id']);
            session()->forget(['print_moystats_classe_selected', 'print_moystats_promotion_selected']);
        }
        session()->put('print_moystats_promotions_grouped_selected', $value);
    }

    public function resetFilters(): void
    {
        session()->forget([
            'print_moystats_classe_selected', 'print_moystats_filiar_selected', 'print_moystats_serial_selected',
            'print_moystats_promotion_selected', 'print_moystats_promotions_grouped_selected',
             'print_moystats_breakpoints',
        ]);

        $this->reset('classe_id', 'filiar_id', 'serial_id', 'promotion_id', 'promotionInGroups');

        $this->groupedBy = 'classe_id';
        
        $this->breakpointsInput = '7, 9, 10, 12, 14, 16';
    }

    public function initPrintProcess()
    {
        if (! $this->period) {
            $this->notification()->info(title: "Veuillez sélectionner une période avant de lancer l'impression.");
            return;
        }

        if (count($this->parsedBreakpoints) < 1) {
            $this->notification()->info(title: "Veuillez saisir au moins un seuil de moyenne valide (entre 0 et 20).");
            return;
        }

        if (! $this->allClassesCounter) {
            $this->notification()->info(
                title: "La procédure ne peut être lancée : aucune classe trouvée",
                description: "Pour les conditions que vous avez définies, aucune classe n'a été trouvée.",
            );
            return;
        }

        JobToGeneratePrintableMoyenneIntervalStatsDataForThePrintViewComponent::dispatch(
            tenantId:       tenant('id'),
            notifiableId:   auth('tenant')->user()->id,
            period:         $this->period,
            groupedBy:      $this->groupedBy,
            breakpoints:    $this->parsedBreakpoints,
            school_year_id: $this->activeYear->id,
            docTitle:       $this->currentDocTitle,
            config:         $this->currentFilterConfig(),
        );

        $this->notification()->success(title: 'Génération du document lancée');
    }

    public function render()
    {
        return view('livewire.tenants.stats.moyenne-interval-stats-manager-component');
    }
}