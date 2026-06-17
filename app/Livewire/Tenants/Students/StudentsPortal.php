<?php

namespace App\Livewire\Tenants\Students;

use App\Livewire\Tenants\ActionsTraits\StudentsActions;
use App\Models\GeneratedDocument;
use App\Models\Student;
use App\Services\PDFFactory;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('livewire.layouts.tenant-auth-layout')]
class StudentsPortal extends Component
{
    use StudentsActions;

    public function onReloadDashboard()
    {
        $this->counter = randomNumber();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function printStudentList()
    {
        $students = $this->getStudentsData()->get();

        $printed_at  = now()->isoFormat('dddd D MMMM YYYY [à] HH:mm');

        $pdf_title = "Liste des apprenants";

        $viewData = [
            'students'        => $students,
            'printed_at'      => $printed_at,
            'allStudents'     => $students->count(),
            'totalActifs'     => $students->where('status', 'active')->count(),
            'pdf_title'       => $pdf_title,
        ];

        PDFFactory::dispatch(
            view:          'livewire.tenants.students.printable-list-component',
            data:           $viewData,
            filename:      'liste-apprenants',
            category:      'students',
            overrides:    ['landscape' => true],
            documentType:  'student_list',
            tenantId:      tenant('id'),
            notifiableId: auth('tenant')->user()->id
        );

        $this->notification()->success(
            title: 'Génération du document lancée',
        );
    }


    public function render()
    {
        $allStudentsCounter = Student::all()->count();

        $genders = config('app.genders');

        $activesStudentsCounter = Student::whereStatus('active')->count();

        $students = $this->getStudentsData()->paginate($this->perPage);

        return view('livewire.tenants.Students.Students-portal', compact('students', 'allStudentsCounter', 'activesStudentsCounter', 'genders'));
    }


    public function trackDownload(int $documentId)
    {
        $doc = GeneratedDocument::where('id', $documentId)
            ->where('user_id', auth('tenant')->user()->id)
            ->first();

        if (! $doc) return abort(404, "Document introuvable ou déjà supprimé!");

        $doc?->recordDownload();

        $this->notification()->success(
            title: 'Téléchargement de la liste est en cours...',
        );

        return response()->download($doc->path, $doc->filename);
    }


    public function getStudentsData()
    {
        return Student::query()
        ->select('students.*')
        ->withTrashed()
        ->when($this->search, function (Builder $query) {
            $query->where('email', 'like', "%{$this->search}%");
            $query->orwhere('name', 'like', "%{$this->search}%");
            $query->orwhere('prenames', 'like', "%{$this->search}%");
            $query->orwhere('contacts', 'like', "%{$this->search}%");
            $query->orwhere('adresse', 'like', "%{$this->search}%");
            $query->orwhere('city', 'like', "%{$this->search}%");
            $query->orwhere('department', 'like', "%{$this->search}%");
            $query->orwhere('country', 'like', "%{$this->search}%");
            $query->orwhere('educMaster', 'like', "%{$this->search}%");
            $query->orwhere('gender', 'like', "%{$this->search}%");
            $query->orwhere('birth_date', 'like', "%{$this->search}%");
            $query->orwhere('birth_place', 'like', "%{$this->search}%");
            $query->orwhere('father_full_name', 'like', "%{$this->search}%");
            $query->orwhere('mother_full_name', 'like', "%{$this->search}%");
            $query->orwhere('matricule', 'like', "%{$this->search}%");
            $query->orwhere('status', 'like', "%{$this->search}%");
        })
        ->when($this->city, function (Builder $query) {
            $query->where('city', $this->city);
        })
        ->when($this->department, function (Builder $query) {
            $query->where('department', $this->department);
        })
        ->when($this->gender, function (Builder $query) {
            $query->where('gender', $this->gender);
        })
        ->orderBy('students.name')
        ->orderBy('students.prenames');
        
    }
    
}
