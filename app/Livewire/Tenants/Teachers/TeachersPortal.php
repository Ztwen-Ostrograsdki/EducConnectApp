<?php

namespace App\Livewire\Tenants\Teachers;

use App\Livewire\Tenants\ActionsTraits\TeachersActions;
use App\Models\GeneratedDocument;
use App\Models\SchoolYear;
use App\Models\Teacher;
use App\Services\PDFFactory;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title('Portails des enseignants')]
class TeachersPortal extends Component
{
    use TeachersActions;

    public string $search = '';

    public string $city = '';

    public string $gender = '';

    public string $department = '';

    public ?string $status = null;

    public int $perPage = 12;


    
    public function mount(?string $status = null)
    {
        if($status) $this->status = $status;
    }

    #[Computed]
    public function activeYear(): ?SchoolYear
    {
        return SchoolYear::current()->first();
    }

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

    #[On("TeachersPDFCompletedSuccessfullyLiveEvent")]
    public function pdfUpdated()
    {
        $this->onReloadDashboard();
    }

    public function printTeachersList()
    {
        $teachers = $this->getTeachersData()->get();

        $printed_at  = now()->isoFormat('dddd D MMMM YYYY [à] HH:mm');

        $pdf_title = "Liste des enseignants";

        $viewData = [
            'teachers' => $teachers,
            'printed_at' => $printed_at,
            'allTeachers' => $teachers->count(),
            'totalActifs'      => $teachers->where('status', 'active')->count(),
            'pdf_title' => $pdf_title,
            'target'          => 'teachers',
            'eventName'       => 'TeachersPDFCompletedSuccessfullyLiveEvent',
        ];

        PDFFactory::dispatch(
            view:          'livewire.tenants.teachers.printable-list-component',
            data:           $viewData,
            filename:      'liste-enseignants',
            category:      'teachers',
            overrides:    ['landscape' => true],
            documentType:  'teacher_list',
            tenantId:      tenant('id'),
            notifiableId: auth('tenant')->user()->id
        );

        $this->notification()->success(
            title: 'Génération du document lancée',
        );
    }


    public function render()
    {
        $allTeachersCounter = Teacher::all()->count();

        $genders = config('app.genders');

        $activesTeachersCounter = Teacher::whereStatus('active')->count();

        $teachers = $this->getTeachersData()->paginate($this->perPage);

        return view('livewire.tenants.teachers.teachers-portal', compact('teachers', 'allTeachersCounter', 'activesTeachersCounter', 'genders'));
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


    public function getTeachersData()
    {
        return Teacher::query()
        ->select('teachers.*')
        ->join('users', 'users.id', '=', 'teachers.user_id')
        ->with(['user'])
        ->withTrashed()
        ->when($this->search, function (Builder $query) {
            $query->whereHas('user', function ($query) {
                $query->where('email', 'like', "%{$this->search}%");
                $query->orwhere('name', 'like', "%{$this->search}%");
                $query->orwhere('prenames', 'like', "%{$this->search}%");
                $query->orwhere('contacts', 'like', "%{$this->search}%");
                $query->orwhere('adresse', 'like', "%{$this->search}%");
                $query->orwhere('city', 'like', "%{$this->search}%");
                $query->orwhere('department', 'like', "%{$this->search}%");
                $query->orwhere('country', 'like', "%{$this->search}%");
                $query->orwhere('gender', 'like', "%{$this->search}%");
                $query->orwhere('birth_date', 'like', "%{$this->search}%");
                $query->orwhere('birth_place', 'like', "%{$this->search}%");
                $query->orwhere('job_name', 'like', "%{$this->search}%");
                $query->orwhere('status', 'like', "%{$this->search}%");
            });
        })
        ->orwhere('identifiant', 'like', "%{$this->search}%")
        ->when($this->city, function (Builder $query) {
            $query->whereHas('user', function ($query) {
                $query->where('city', $this->city);
            });
        })
        ->when($this->department, function (Builder $query) {
            $query->whereHas('user', function ($query) {
                $query->where('department', $this->department);
            });
        })
        ->when($this->gender, function (Builder $query) {
            $query->whereHas('user', function ($query) {
                $query->where('gender', $this->gender);
            });
        })
        ->orderBy('users.name')
        ->orderBy('users.prenames');
        
    }

}
