<?php

namespace App\Livewire\Tenants\Promotions;

use App\Models\Promotion;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title("Profil de promotion")]
class PromotionProfil extends Component
{
    public $counter = 1;

    public Promotion $promotion;

    public string $promotion_slug;

    public string $promotion_name = 'Nom de la promotion';

    public ?string $school_year_selected;

    public string $grid_cols = '2xl:grid-cols-7';


    public function mount(string $promotion_slug)
    {
        if(!$promotion_slug) return abort(404);

        $this->promotion_slug  = $promotion_slug;

        $promotion = Promotion::whereSlug($promotion_slug)?->first();

        if(!$promotion) return abort(404);

        $this->promotion       = $promotion;
        $this->promotion_name       = $promotion->name;
    }

    #[Computed]
    public function kpis()
    {
        $data = [
            ['Classes', __zero($this->promotion->getPromotionClassesOfSchoolYearCount()), 'text-amber-400'], 
            ['Enseignants', __zero($this->promotion->getPromotionTeachersOfSchoolYearCount()), 'text-violet-400'],
            ['Apprenants', __zero($this->promotion->getPromotionStudentsOfSchoolYearCount()), 'text-indigo-400'], 
            ['Meilleure classe', '-', 'text-emerald-400'], 
            ['Faible classe', '-', 'text-rose-400'], 
            ['Meilleur élève', '-', 'text-sky-400'], 
            ['Meilleur moyenne', '-', 'text-sky-400'],  
        ];

        return $data;
    }

    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata()
    {
        $this->counter++;
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
