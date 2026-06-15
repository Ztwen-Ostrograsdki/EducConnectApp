<?php

namespace App\Livewire\Tenants\Teachers;

use App\Models\Teacher;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.layouts.print-layout')]
#[Title("Liste des enseignants")]
class PrintableListComponent extends Component
{
 
    /** @var string Colonne de tri */
    public string $sortBy = 'name';
 
    /** @var string Direction de tri */
    public string $sortDir = 'asc';

    public string $search = '';

    public string $city = '';

    public string $gender = '';

    public string $department = '';

    public ?string $status = null;

    public ?string $pdf_title = "Liste des enseignants ";
 
    /**
     * Retourne la liste paginée/filtrée des enseignants.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTeachers()
    {
        return  Teacher::query()
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
                $query->orwhere('identifiant', 'like', "%{$this->search}%");
                $query->orwhere('job_name', 'like', "%{$this->search}%");
                $query->orwhere('status', 'like', "%{$this->search}%");
            });
        })
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
        ->orderBy('users.prenames')
        ->get();


    }
 
    /**
     * Retourne la liste distincte des départements.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getDepartments()
    {
        return Teacher::join('users', 'users.id', '=', 'teachers.user_id')
                        ->whereNotNull('users.department')
                        ->where('users.department', '!=', '')
                        ->distinct()
                        ->pluck('users.department')
                        ->sort()
                        ->values();
    }
 
    /**
     * Retourne la date de génération du document.
     *
     * @return string
     */
    public function getPrintedAtProperty(): string
    {
        return now()->isoFormat('dddd D MMMM YYYY [à] HH:mm');
    }
 
    /**
     * @return \Illuminate\View\View
     */
    public function render(): \Illuminate\View\View
    {
        $printed_at  = now()->isoFormat('dddd D MMMM YYYY [à] HH:mm');

        $departments = $this->getDepartments();

        $teachers = $this->getTeachers();

        return view('livewire.tenants.teachers.printable-list-component', [
            'teachers'   => $teachers,
            'departments'  => $departments,
            'printed_at' => $printed_at,
            'allTeachers' => $teachers->count(),
            'totalActifs'      => $teachers->where('status', 'active')->count(),
        ]);
    }

   
}
