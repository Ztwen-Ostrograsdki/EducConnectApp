<?php

namespace App\Livewire\Tenants\Users\Teacher;

use Livewire\Attributes\On;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class TeacherDashboard extends Component
{
    use WireUiActions;

    public $counter = 0;


    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata()
    {
        $this->counter++;
    }
    
    #[On('TeacherWasBlockedLiveEvent')]
    public function teacherBlocked()
    {
        $this->notification()->send([
            'icon'        => 'warning',
            'title'       => "Votre compte enseignant a été bloqué",
            'timeout' => 0,
        ]);
        
        $this->counter++;

        return $this->redirect(route('tenant.my.profil'));
    }


    public function render()
    {
        return view('livewire.tenants.users.teacher.teacher-dashboard');
    }
}
