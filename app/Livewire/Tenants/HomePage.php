<?php

namespace App\Livewire\Tenants;

use App\Models\Filiar;
use App\Models\Gallery;
use App\Models\Personnel;
use App\Models\SchoolYear;
use App\Models\Serial;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.layouts.tenants-default-layout')]
#[Title("Page d'acceuil")]
class HomePage extends Component
{

    public int $counter = 0;

    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata()
    {
        $this->counter++;

        unset($this->personnels, $this->testimonials, $this->serials, $this->filiars);
    }
    
    #[Computed]
    public function activeYear()
    {
        return SchoolYear::current()?->first();
    }

    #[Computed]
    public function personnels()
    {
        if(tenancy()->tenant->hide_personnels_on_home_page) return [];

        return Personnel::query()->where('school_year_id', $this->activeYear->id)->active()->visible()->orderBy('name')->get();
    } 
    
    #[Computed]
    public function background_image()
    {
        return tenancy()->tenant->background_image_url;
    }


    #[Computed]
    public function galleries()
    {
        if(tenancy()->tenant->hide_galleries_on_home_page) return [];

        return Gallery::query()->visible()->orderBy('created_at')->get();
    }
    
    #[Computed]
    public function filiars()
    {
        if(tenancy()->tenant->hide_filiars_on_home_page) return [];

        return Filiar::active()->orderBy('name')->get();
    }

    
    #[Computed]
    public function serials()
    {
        if(tenancy()->tenant->hide_serials_on_home_page) return [];

        return Serial::active()->orderBy('name')->get();
    }

    #[Computed]
    public function testimonials()
    {
        if(tenancy()->tenant->hide_testimonials_on_home_page) return [];

        return Testimonial::where('hidden', false)->orderBy('created_at')->get();
    }


    public function openTestimonialModal()
    {
        $this->dispatch('open-add-testimonial-modal');
    }


    public function logout()
    {
        if(auth('tenant')->check()){

            Auth::guard('tenant')->logout();
        }

        session()->invalidate();

        session()->regenerate();

        $this->redirect(route('login'), navigate: false);

    }

    public function render()
    {
        return view('livewire.tenants.home-page');
    }
}
