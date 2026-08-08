<?php

namespace App\Livewire\Tenants\Reports;

use App\Jobs\JobToGeneratePrintableBulletinsDataForThePrintViewComponent;
use App\Models\Classe;
use App\Models\Filiar;
use App\Models\Promotion;
use App\Models\SchoolYear;
use App\Models\Serial;
use App\Services\BulletinsServices\BulletinPrintQuery;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Title("Impression des bulletins")]
#[Layout('livewire.layouts.tenant-auth-layout')]
class BulletinsPrintsManagerComponent extends Component
{
    use WireUiActions;

    public ?int $classe_id = null;
    public ?int $filiar_id = null;
    public ?int $serial_id = null;
    public ?int $promotion_id = null;
    public ?string $promotionInGroups = null;
    public ?int $period = null;

    public string $leavesStatus = 'onlyActives';

    public array $leavesStatuses = [
        'onlyActives' => "Seulement apprenants actifs",
        'onlyLeaves'  => "Seulement apprenants abandons",
        'withLeaves'  => "Tous, abandons inclus",
    ];

    public function mount(): void
    {
        $this->loadActivePeriod();
        
        if (session()->has('print_bulletins_classe_selected'))    $this->classe_id = session('print_bulletins_classe_selected');
        if (session()->has('print_bulletins_filiar_selected'))    $this->filiar_id = session('print_bulletins_filiar_selected');
        if (session()->has('print_bulletins_serial_selected'))     $this->serial_id = session('print_bulletins_serial_selected');
        if (session()->has('print_bulletins_promotion_selected'))  $this->promotion_id = session('print_bulletins_promotion_selected');
        if (session()->has('print_bulletins_promotions_grouped_selected')) $this->promotionInGroups = session('print_bulletins_promotions_grouped_selected');
        if (session()->has('print_bulletins_leaves_status'))       $this->leavesStatus = session('print_bulletins_leaves_status');
        if (session()->has('print_bulletins_period_selected'))       $this->period = session('print_bulletins_period_selected');

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
            "leavesConfig"      => $this->leavesStatus,
        ];
    }

    #[Computed]
    public function allStudentsCounter()
    {
        return $this->activeYear ? BulletinPrintQuery::count($this->currentFilterConfig(), $this->activeYear->id) : 0;
    }

    #[Computed]
    public function currentDocTitle(): string
    {
        return BulletinPrintQuery::resolveDocTitle($this->currentFilterConfig(), (int) $this->period, $this->activeYear);
    }

    #[Computed]
    public function isLastPeriod(): bool
    {
        if (! $this->period) return false;

        $lastIndex = collect($this->periods_types)->pluck('index')->max();

        return (int) $this->period === $lastIndex;
    }

    public function updatedPeriod(?string $value): void { session()->put('print_bulletins_period_selected', $value); }
    public function updatedLeavesStatus(?string $value): void { session()->put('print_bulletins_leaves_status', $value); }

    public function updatedClasseId(?string $value): void
    {
        if ($value) {
            $this->reset(['filiar_id', 'serial_id', 'promotion_id', 'promotionInGroups']);
            session()->forget(['print_bulletins_filiar_selected', 'print_bulletins_serial_selected', 'print_bulletins_promotion_selected', 'print_bulletins_promotions_grouped_selected']);
        }
        session()->put('print_bulletins_classe_selected', $value);
    }

    public function updatedFiliarId(?string $value): void
    {
        if ($value) {
            $this->reset(['classe_id', 'serial_id', 'promotion_id']);
            session()->forget(['print_bulletins_classe_selected', 'print_bulletins_serial_selected', 'print_bulletins_promotion_selected']);
        }
        session()->put('print_bulletins_filiar_selected', $value);
    }

    public function updatedSerialId(?string $value): void
    {
        if ($value) {
            $this->reset(['classe_id', 'filiar_id', 'promotion_id']);
            session()->forget(['print_bulletins_classe_selected', 'print_bulletins_filiar_selected', 'print_bulletins_promotion_selected']);
        }
        session()->put('print_bulletins_serial_selected', $value);
    }

    public function updatedPromotionId(?string $value): void
    {
        if ($value) {
            $this->reset(['classe_id', 'filiar_id', 'serial_id', 'promotionInGroups']);
            session()->forget(['print_bulletins_classe_selected', 'print_bulletins_filiar_selected', 'print_bulletins_serial_selected', 'print_bulletins_promotions_grouped_selected']);
        }
        session()->put('print_bulletins_promotion_selected', $value);
    }

    public function updatedPromotionInGroups(?string $value): void
    {
        if ($value) {
            $this->reset(['classe_id', 'promotion_id']);
            session()->forget(['print_bulletins_classe_selected', 'print_bulletins_promotion_selected']);
        }
        session()->put('print_bulletins_promotions_grouped_selected', $value);
    }

    public function resetFilters(): void
    {
        session()->forget([
            'print_bulletins_classe_selected', 'print_bulletins_filiar_selected', 'print_bulletins_serial_selected',
            'print_bulletins_promotion_selected', 'print_bulletins_promotions_grouped_selected'
            , 'print_bulletins_leaves_status', 'print_bulletins_period_selected',
        ]);

        $this->reset('classe_id', 'filiar_id', 'serial_id', 'promotion_id', 'promotionInGroups');

        $this->leavesStatus = 'onlyActives';
        $this->period = $this->activeYear?->active_period;
    }

    public function initPrintProcess()
    {
        if (! $this->period) {
            $this->notification()->info(title: "Veuillez sélectionner une période avant de lancer l'impression.");
            return;
        }

        if (! $this->allStudentsCounter) {
            $this->notification()->info(
                title: "La procédure ne peut être lancée : aucun apprenant trouvé",
                description: "Pour les conditions que vous avez définies, aucun apprenant n'a été trouvé.",
            );
            return;
        }

        JobToGeneratePrintableBulletinsDataForThePrintViewComponent::dispatch(
            tenantId:       tenant('id'),
            notifiableId:   auth('tenant')->user()->id,
            period:         $this->period,
            school_year_id: $this->activeYear->id,
            docTitle:       $this->currentDocTitle,
            config:         $this->currentFilterConfig(),
        );

        $this->notification()->success(title: 'Génération du document lancée');
    }

    public function render()
    {
        return view('livewire.tenants.reports.bulletins-prints-manager-component');
    }
}