<?php

namespace App\Livewire\Tenants\Personnels;

use App\Models\Personnel;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

#[Title("Les personnels")]
#[Layout('livewire.layouts.tenants-default-layout')]
class PersonnelsPage extends Component
{
    use WithPagination, WireUiActions;

    #[Url(history: false)]
    public string $search = '';

    #[Url(history: false)]
    public string $gradeFilter = '';

    #[Url(history: false)]
    public string $genderFilter = '';

    public int $perPage = 12;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingGradeFilter(): void
    {
        $this->resetPage();
    }

    public function updatingGenderFilter(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function personnels()
    {
        return Personnel::query()
            ->visible()
            ->active()
            ->with('schoolYear')
            ->when($this->search, function ($q) {
                $q->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                      ->orWhere('prenames', 'like', "%{$this->search}%")
                      ->orWhere('title', 'like', "%{$this->search}%");
                });
            })
            ->when($this->gradeFilter, fn ($q) => $q->where('grade', $this->gradeFilter))
            ->gender($this->genderFilter ?: null)
            ->orderBy('name')
            ->orderBy('prenames')
            ->paginate($this->perPage);
    }

    #[Computed]
    public function grades()
    {
        return Personnel::visible()
            ->active()
            ->whereNotNull('grade')
            ->distinct()
            ->orderBy('grade')
            ->pluck('grade');
    }

    #[Computed]
    public function stats(): array
    {
        $base = Personnel::query()->visible()->active();

        return [
            'total'   => (clone $base)->count(),
            'hommes'  => (clone $base)->where('gender', 'M')->count(),
            'femmes'  => (clone $base)->where('gender', 'F')->count(),
        ];
    }

    public function render()
    {
        return view('livewire.tenants.personnels.personnels-page');
    }
}