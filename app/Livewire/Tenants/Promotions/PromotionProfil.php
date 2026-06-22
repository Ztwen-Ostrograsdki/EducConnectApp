<?php

namespace App\Livewire\Tenants\Promotions;

use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title("Profil de promotion")]
class PromotionProfil extends Component
{
    public string $promotion_slug;

    public string $promotion_name = 'Promotion Nom';

    public ?string $school_year_selected;

    public function mount(string $promotion_slug)
    {

        $this->promotion_slug = $promotion_slug;

        $this->promotion_name = Str::random(6);

    }

    #[On('yearChanged')]
    public function onYearChanged(string $schoolYear)
    {
        $this->school_year_selected = $schoolYear;
    }

    public function render()
    {
        return view('livewire.tenants.promotions.promotion-profil');
    }
}
