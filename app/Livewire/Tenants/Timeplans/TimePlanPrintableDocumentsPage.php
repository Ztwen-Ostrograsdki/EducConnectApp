<?php

namespace App\Livewire\Tenants\TimePlans;

use App\Livewire\Tenants\ActionsTraits\DocumentActionsTrait;
use App\Models\GeneratedDocument;
use App\Models\SchoolYear;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title('Emplois du temps générés')]
class TimePlanPrintableDocumentsPage extends Component
{
    use WireUiActions, WithPagination, DocumentActionsTrait;

    public string $search = '';

    /** all | classes | teachers */
    #[Url]
    public string $scope = 'all';

    public string $pageTitle = 'Documents générés — Emplois du temps';

    public int $counter = 0;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedScope(): void
    {
        $this->resetPage();
    }

    #[On('DataUpdatedEventLiveEvent')]
    #[On('TimePlansPDFCompletedSuccessfullyLiveEvent')]
    public function reloaddata(): void
    {
        $this->counter++;
        $this->refreshDocumentsList();
    }

    #[Computed]
    public function activeYear(): ?SchoolYear
    {
        return SchoolYear::current()->first();
    }

    #[Computed]
    public function documents()
    {
        return GeneratedDocument::ofType('time_plan')
            ->forUser(auth('tenant')->id())
            ->when(
                $this->activeYear,
                fn ($q) => $q->where('school_year_id', $this->activeYear->id)
            )
            ->when(
                $this->search !== '',
                fn ($q) => $q->where('filename', 'like', '%' . $this->search . '%')
            )
            ->when(
                $this->scope === 'teachers',
                fn ($q) => $q->where('for_teachers', true)
            )
            ->when(
                $this->scope === 'classes',
                fn ($q) => $q->where(function ($inner) {
                    $inner->where('for_teachers', false)
                        ->orWhereNull('for_teachers');
                })
            )
            ->latest()
            ->paginate(9);
    }

    public function render()
    {
        return view('livewire.tenants.timeplans.time-plan-printable-documents-page');
    }
}
