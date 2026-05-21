<?php

namespace App\Livewire\Tenants\Subjects;

use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('livewire.layouts.tenant-auth-layout')]
class SubjectProfil extends Component
{
    public string $subject_slug;

    public ?string $school_year_selected;

    public function mount(string $subject_slug)
    {

        $this->subject_slug = $subject_slug;

    }

    #[On('yearChanged')]
    public function onYearChanged(string $schoolYear)
    {
        $this->school_year_selected = $schoolYear;
    }

    public function render()
    {
        return view('livewire.tenants.subjects.subject-profil');
    }
}
