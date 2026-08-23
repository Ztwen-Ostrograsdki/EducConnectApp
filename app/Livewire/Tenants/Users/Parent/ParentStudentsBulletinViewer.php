<?php

namespace App\Livewire\Tenants\Users\Parent;

use App\Models\SchoolYear;
use App\Models\Student;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Title("Bulletin de notes enfant")]
class ParentStudentsBulletinViewer extends Component
{
    use WireUiActions;

    public $counter = 0;

    public ?string $student_uuid;

    public ?int $period = null;

    public ?string $classe_slug;

    public function mount(string $student_uuid)
    {
        if(!(tenant('tutors_can_see_bulletin') && tenant('tutors_can_download_bulletin'))){

            return abort(403);
        }
        
        $this->student_uuid = $student_uuid;

        $this->classe_slug = $student_uuid;

        $this->loadActivePeriod();
    }

    public function loadActivePeriod()
    {
        if ($this->activeYear && $this->activeYear->is_active && $this->activeYear->active_period) {

            $this->period = $this->activeYear->active_period;
        }
    }

    
    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata()
    {
        $this->counter++;
    }

    #[Computed]
    public function student()
    {
        $student = Student::firstWhere('uuid', $this->student_uuid);

        if (!$student) return abort(404);

        return $student;
    }

    #[Computed]
    public function activeYear(): ?SchoolYear
    {
        return SchoolYear::current()->first();
    }

    /**
     * Classe active de l'apprenant pour l'année en cours.
     * Adapte le nom de la relation si besoin ('yearlyClasseStudents' supposé BelongsTo/HasMany
     * vers YearlyClasseStudent, lui-même lié à Classe via classe()).
     */
    #[Computed]
    public function currentClasse()
    {
        $classe_rel = $this->student->currentClasse();

        if($classe_rel) return $classe_rel->classe;

        return null;

    }


    #[On("StudentDataUpdatedEventLiveEvent")]
    public function studentDataUpdated()
    {
        $this->counter++;
    }

    public function render()
    {
        return view('livewire.tenants.users.parent.parent-students-bulletin-viewer');
    }
}
