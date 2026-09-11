<?php

namespace App\Livewire\Tenants\Testimonials;

use App\Livewire\Tenants\ActionsTraits\TestimonialActions;
use App\Models\Testimonial;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

#[Title("Gestion des témoignages")]
#[Layout('livewire.layouts.tenant-auth-layout')]
class ManageTestimonialsComponent extends Component
{
    use WithPagination, WireUiActions, TestimonialActions;

    #[Url]
    public string $search = '';

    #[Url]
    public string $status = 'all'; // all | visible | hidden

    public int $perPage = 15;

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
        $query = Testimonial::query()
            ->with('user')
            ->latest();

        if ($this->search) {
            $query->where('content', 'like', '%' . $this->search . '%');
        }

        if ($this->status === 'visible') {
            $query->visible();
        } elseif ($this->status === 'hidden') {
            $query->hidden();
        }

        $testimonials = $query->paginate($this->perPage);

        return view('livewire.tenants.testimonials.manage-testimonials-component', [
            'testimonials' => $testimonials,
        ]);
    }
}
