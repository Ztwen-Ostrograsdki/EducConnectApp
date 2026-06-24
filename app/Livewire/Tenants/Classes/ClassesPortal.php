<?php

namespace App\Livewire\Tenants\Classes;

use App\Livewire\Tenants\ActionsTraits\ClassesActions;
use App\Models\Classe;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title("Portail des classes")]
class ClassesPortal extends Component
{

    use ClassesActions;
    
    // ─── Render ───────────────────────────────────────────────────────

    public function render()
    {
        $yearId = $this->activeYear?->id;

        $classes = Classe::query()
            ->where('school_year_id', $yearId)
            ->with(['promotion', 'filiar', 'serial', 'principal', 'students'])
            ->withCount('students')
            ->when($this->search, fn($q) =>
                $q->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('code', 'like', '%' . $this->search . '%');
                })
            )
            ->when($this->promotion, fn($q) => $q->where('promotion_id', $this->promotion))
            ->when($this->filiar,    fn($q) => $q->where('filiar_id', $this->filiar))
            ->when($this->serial,    fn($q) => $q->where('serial_id', $this->serial))
            ->when($this->level,     fn($q) => $q->where('level', $this->level))
            ->orderBy('name')
            ->paginate($this->perPage);

        return view('livewire.tenants.classes.classes-portal', compact('classes'));
    }
}