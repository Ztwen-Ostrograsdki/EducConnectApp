<?php

namespace App\Livewire\Tenants\Galleries;

use App\Livewire\Tenants\ActionsTraits\GalleryActions;
use App\Models\Gallery;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

#[Title("Gestion de la galerie")]
#[Layout('livewire.layouts.tenant-auth-layout')]
class ManageGalleriesComponent extends Component
{
    use WithPagination, WireUiActions, GalleryActions;

    #[Url]
    public string $search = '';

    #[Url]
    public string $status = 'all'; // all | visible | hidden

    public int $perPage = 12;

    public int $counter = 0;

    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata()
    {
        $this->counter++;
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'status']);
        $this->resetPage();
    }

    public function render()
    {
        $query = Gallery::query()
            ->with('creatorUser')
            ->latest();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->status === 'visible') {
            $query->visible();
        } elseif ($this->status === 'hidden') {
            $query->hidden();
        }

        $galleries = $query->paginate($this->perPage);

        return view('livewire.tenants.galleries.manage-galleries-component', [
            'galleries' => $galleries,
        ]);
    }
}
