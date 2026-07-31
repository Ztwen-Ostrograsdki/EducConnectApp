<?php

namespace App\Livewire\Tenants\Promotions;

use App\Models\Promotion;
use App\Models\SchoolYear;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;


#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title("Liste des enseignants d'une promotion")]
class PromotionTeachersComponent extends Component
{
    use WireUiActions;

    public ?Promotion $promotion;

    public string $promotion_slug;

    public int $counterh = 0;

    public function mount(string $promotion_slug)
    {

        if(!$promotion_slug) return abort(404);

        $this->promotion_slug  = $promotion_slug;

        $promotion = Promotion::withTrashed()->whereSlug($promotion_slug)?->first();

        if(!$promotion) return abort(404);

        $this->promotion       = $promotion;


    }

    #[Computed]
    public function activeYear(): ?SchoolYear
    {
        return SchoolYear::current()->first();
    }

    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata()
    {
        $this->counterh++;
    }
    
    public function render()
    {
        return view('livewire.tenants.promotions.promotion-teachers-component');
    }
}
