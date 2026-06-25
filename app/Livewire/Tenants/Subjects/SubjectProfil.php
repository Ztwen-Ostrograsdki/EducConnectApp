<?php

namespace App\Livewire\Tenants\Subjects;

use App\Models\Subject;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title("Profil de matière")]
class SubjectProfil extends Component
{
    use WireUiActions;

    public Subject $subject;

    public string $subject_slug;

    public ?string $school_year_selected;

    public function mount(string $subject_slug)
    {

        if(!$subject_slug) return abort(404);

        $this->subject_slug  = $subject_slug;

        $subject = Subject::whereSlug($subject_slug)?->first();

        if(!$subject) return abort(404);

        $this->subject     = $subject;

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
