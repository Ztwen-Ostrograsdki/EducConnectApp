<?php

namespace App\Livewire\Tenants\Subjects;

use App\Livewire\Tenants\ActionsTraits\SubjectsActions;
use App\Models\SchoolYear;
use App\Models\Subject;
use App\Services\SubjectsServices\SubjectDetailsCacheService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title("Profil de matière")]
class SubjectProfil extends Component
{
    use WireUiActions, SubjectsActions;

    public Subject $subject;

    public string $subject_slug;

    public ?array $details = [] ;

    public $counterh = 0;

    public ?string $school_year_selected;

    public function mount(string $subject_slug)
    {

        if(!$subject_slug) return abort(404);

        $this->subject_slug = $subject_slug;

        $subject = Subject::withTrashed()->whereSlug($subject_slug)?->first();

        if(!$subject) return abort(404);

        $this->subject = $subject;

        $this->details = app(SubjectDetailsCacheService::class)->get($this->subject->id);

    }

    #[On('yearChanged')]
    public function onYearChanged(string $schoolYear)
    {
        $this->school_year_selected = $schoolYear;
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
        return view('livewire.tenants.subjects.subject-profil');
    }
}
