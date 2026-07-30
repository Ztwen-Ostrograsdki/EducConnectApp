<?php

namespace App\Livewire\Tenants\Subjects;

use App\Models\Filiar;
use App\Models\SchoolYear;
use App\Models\Serial;
use App\Models\Subject;
use App\Models\YearlyPromotionSpecialitySubjectCoef;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

class YearlySubjectCoefPerPromotionOrClasseComponent extends Component
{
    use WireUiActions, WithPagination;

    public Subject $subject;

    public string $search     = '';
    public ?int   $filiar_id  = null;
    public ?int   $serial_id  = null;
    public int    $perPage    = 15;

    // Reset pagination
    public function updatedSearch(): void    { $this->resetPage(); }
    public function updatedFiliarId(): void
    {
        $this->serial_id = null;
        $this->resetPage();
    }
    public function updatedSerialId(): void
    {
        $this->filiar_id = null;
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'filiar_id', 'serial_id']);
        $this->resetPage();
    }

    #[Computed]
    public function activeYear(): ?SchoolYear
    {
        return SchoolYear::current()->first();
    }

    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata(): void
    {
        // Force le recalcul des computed
        unset($this->promotionsData);
        unset($this->promotionsDataGrouped);
    }

    #[Computed]
    public function serials()
    {
        return Serial::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'code']);
    }

    #[Computed]
    public function filiars()
    {
        return Filiar::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'code']);
    }

    #[Computed]
    public function promotionsData()
    {
        return YearlyPromotionSpecialitySubjectCoef::query()
            ->with(['filiar', 'serial', 'schoolYear'])
            ->where('subject_id', $this->subject->id)
            ->when($this->search, function ($q) {
                $q->where('promotion', 'like', '%' . $this->search . '%');
            })
            ->when($this->serial_id, fn ($q) => $q->where('serial_id', $this->serial_id))
            ->when($this->filiar_id, fn ($q) => $q->where('filiar_id', $this->filiar_id))
            ->orderBy('promotion')
            ->orderBy('id')
            ->paginate($this->perPage);
    }

    #[Computed]
    public function promotionsDataGrouped()
    {
        return $this->promotionsData->getCollection()->groupBy('promotion');
    }

    public function render()
    {
        return view('livewire.tenants.subjects.yearly-subject-coef-per-promotion-or-classe-component');
    }
}