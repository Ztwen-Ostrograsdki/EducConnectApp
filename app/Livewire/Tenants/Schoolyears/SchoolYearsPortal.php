<?php

namespace App\Livewire\Tenants\Schoolyears;

use App\Livewire\Tenants\ActionsTraits\SchoolYearsActions;
use App\Models\SchoolYear;
use App\Services\DashboardCounterService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

#[Title('Portail des années scolaires')]
#[Layout('livewire.layouts.tenant-auth-layout')]
class SchoolYearsPortal extends Component
{
    use WireUiActions, WithPagination, SchoolYearsActions;

    public int $counter = 0;

    public ?string $search = '';

    #[On("NewSchoolYearCreatedLiveEvent")]
    public function newSchoolYearCreated()
    {
        $this->counter++;
    }

    #[On("SchoolYearUpdatedLiveEvent")]
    public function schoolYearUpdated()
    {
        $this->counter++;
    }

    #[On('DataUpdatedEventLiveEvent')]
    public function reloadData(): void
    {
        $this->counter++;
    }

    #[Computed]
    public function stats(): array
    {
        return app(DashboardCounterService::class)->getMany([
            'students',
            'students_in_classe',
            'teachers_in_classes',
            'teachers',
            'classes_actives',
            'classes_closeds',
            'classes_unactives',
            'promotions_actives',
            'serials_actives',
            'filiars_actives',
        ]);
    }

    #[Computed]
    public function schoolYears()
    {
        return SchoolYear::orderBy('min_year', 'desc')
                           ->whereNotNull('slug')
                           ->when($this->search && trim(strlen($this->search)) > 2, fn($q) =>
                                $q->where(function ($q) {
                                    $q->where('slug', 'like', '%' . $this->search . '%');
                                })
                            )
                           ->paginate(4);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPromotion()
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['search']);
        $this->resetPage();
    }


    public function render()
    {
        
        
        return view('livewire.tenants.schoolyears.school-years-portal');
    }
}
