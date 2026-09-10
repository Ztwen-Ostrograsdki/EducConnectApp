<?php

namespace App\Livewire\Tenants;

use App\Models\Personnel;
use App\Models\SchoolYear;
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

        unset($this->personnels);
    }
    
    #[Computed]
    public function activeYear()
    {
        return SchoolYear::current()?->first();
    }

    #[Computed]
    public function personnels()
    {
        return Personnel::query()->where('school_year_id', $this->activeYear->id)->active()->visible()->orderBy('name')->get();
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
