<?php

namespace App\Livewire\Tenants\Teachers;

use App\Events\InitProcessToCreateTeachersEvent;
use App\Events\TeacherBatchProgressUpdatedEvent;
use App\Jobs\JobToCreateTeacher;
use App\Models\Tenant;
use App\Models\User;
use App\Tools\BeninData;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.tenant-auth-layout')]
class CreateTeachers extends Component
{
    use WireUiActions;

    public $adresse;

    public ?string $birth_date;

    public $department_key;

    public $department_name, $department;

    public $done = false;

    public $error_message = '';

    public $cities = [];

    public $showTeacherRemoveModal = false;

    public ?string $deletingUuid = null;




    public string $name = '';
    public string $prenames = '';
    public string $job_name = '';

    public string $country = '';
    public ?string $city = '';

    public string $email = '';
    public string $contacts = '';

    public string $gender = '';

    public ?string $editingUuid = null;


    public int $step = 1;

    public function mount(): void
    {
        // session()->forget('pending_teachers');
        session()->put(
            'pending_teachers',
            session('pending_teachers', [])
        );
    }

    protected function rules(): array
    {
        return [

            'name' => 'required|string|max:255',
            'prenames' => 'required|string|max:255',
            'job_name' => 'required|string|max:255',

            'country' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'gender' => 'required|string|max:10',

            'email' => [
                'required',
                'email',
                'max:255',
                function ($attribute, $value, $fail) {
                    $existsUser = User::where('email', $value)->exists();
                    $existsInTenants = Tenant::where('email', $value)->exists();

                    if ($existsUser || $existsInTenants) {
                        $fail("Vous ne pouvez pas utiliser cet email.");
                    }
                }
            ],

            'contacts' => [
                'required',
                'string',
                'between:4,50',
                function ($attribute, $value, $fail) {
                    $existsUser = User::where('contacts', $value)->exists();
                    $existsInTenants = Tenant::where('contacts', $value)->exists();

                    if ($existsUser || $existsInTenants) {
                        $fail("Vous ne pouvez pas utiliser cet contact.");
                    }
                }
            ],

            'department' => 'required|string',
        ];
    }

    public function render()
    {
        $imports = [];

        $genders = config('app.genders');

        $departments = BeninData::getDepartments();

        $countries = ['Bénin' => 'Bénin'];


        return view('livewire.tenants.teachers.create-teachers', compact('imports', 'countries', 'departments', 'genders'));
    }

    public function updatedDepartment(?string $department)
    {
        if($department){

            $this->cities = [];

            $this->city = null;

            $departments = BeninData::getDepartments();

            $department_key = array_keys($departments, $department)[0];

            $this->cities = BeninData::getCities($department_key);

        }

    }

    public function addTeacher(): void
    {
        $this->validate();

        $teachers = session(
            'pending_teachers',
            []
        );

        // Session

        $emailExists = collect($teachers)

            ->contains(
                fn ($teacher) =>
                    strtolower($teacher['email'])
                    ===
                    strtolower($this->email)
            );

        if ($emailExists) {

            $this->notification()->error(
                title: 'Email déjà utilisé',
                description: 'Cet email existe déjà.'
            );

            return;
        }

        // Base

        if (
            User::query()
                ->where('email', $this->email)
                ->exists()
        ) {

            $this->notification()->error(
                title: 'Email existant',
                description: 'Cet email est déjà enregistré.'
            );

            return;
        }

        $teachers[] = [
            'uuid' => (string) Str::uuid(),
            'name' => $this->name,
            'department' => $this->department,
            'gender' => $this->gender,
            'prenames' => $this->prenames,
            'job_name' => $this->job_name,
            'contacts' => $this->contacts,
            'country' => $this->country,
            'city' => $this->city,
            'birth_date' => $this->birth_date,
            'email' => $this->email,
        ];

        session([
            'pending_teachers' => $teachers
        ]);

        $this->resetForm();

        $this->notification()->success(
            title: 'Succès',
            description: 'Enseignant ajouté.'
        );
    }

    public function getTeachersProperty(): array
    {
        return session('pending_teachers', []);
    }

    public function deleteTeacher(string $uuid): void
    {
        $this->deletingUuid = $uuid;

        $this->showTeacherRemoveModal = true;

    }

    public function confirmDeleteTeacher(): void
    {
        $uuid = $this->deletingUuid;

        $teachers = session('pending_teachers', []);

        $teachers = collect($teachers)
            ->reject(fn ($t) => $t['uuid'] === $uuid)
            ->values()
            ->toArray();

        session([
            'pending_teachers' => $teachers
        ]);

        $this->notification()->success(
            title: 'Supprimé',
            description: 'Enseignant retiré.'
        );
    }

    public function resetModal()
    {
        $this->reset('deletingUuid', 'showTeacherRemoveModal');
    }

    public function editTeacher(string $uuid): void
    {
        $teacher = collect(session('pending_teachers', []))
            ->firstWhere('uuid', $uuid);

        if (! $teacher) {
            $this->notification()->error(
                title: 'Erreur',
                description: 'Enseignant introuvable.'
            );

            return;
        }

        $this->editingUuid = $uuid;

        $this->name = $teacher['name'];
        $this->prenames = $teacher['prenames'];
        $this->email = $teacher['email'];
        $this->department = $teacher['department'];
        $this->gender = $teacher['gender'];
        $this->contacts = $teacher['contacts'];
        $this->job_name = $teacher['job_name'];
        $this->country = $teacher['country'];
        $this->birth_date = $teacher['birth_date'];
        $this->city = $teacher['city'];

        if($this->department){

            $departments = BeninData::getDepartments();

            $this->cities = [];

            $this->city = null;

            $departments = BeninData::getDepartments();

            $department_key = array_keys($departments, $this->department)[0];

            $this->cities = BeninData::getCities($department_key);

            $this->city = $teacher['city'];

        }

        $this->notification()->info(
            title: 'Mode édition',
            description: 'Vous modifiez cet enseignant.'
        );
    }

    public function updateTeacher(): void
    {
        $this->validate();

        $teachers = session('pending_teachers', []);

        // Vérifier doublon email (hors lui-même)
        $emailExists = collect($teachers)
            ->where('uuid', '!=', $this->editingUuid)
            ->contains(fn ($t) =>
                strtolower($t['email']) === strtolower($this->email)
            );

        if ($emailExists) {
            $this->notification()->error(
                title: 'Erreur',
                description: 'Email déjà utilisé dans la liste.'
            );

            return;
        }

        $teachers = collect($teachers)
            ->map(function ($teacher) {

                if ($teacher['uuid'] !== $this->editingUuid) {
                    return $teacher;
                }

                return [
                    ...$teacher,
                    'name' => $this->name,
                    'prenames' => $this->prenames,
                    'email' => $this->email,
                    'department' => $this->department,
                    'city' => $this->city,
                    'country' => $this->country,
                    'gender' => $this->gender,
                    'job_name' => $this->job_name,
                    'birth_date' => $this->birth_date,
                    'contacts' => $this->contacts,
                ];
            })
            ->values()
            ->toArray();

        session([
            'pending_teachers' => $teachers
        ]);

        $this->resetForm();

        $this->notification()->success(
            title: 'Mis à jour',
            description: 'Enseignant modifié avec succès.'
        );
    }

    public function resetForm(): void
    {
        $this->reset([
            'name',
            'prenames',
            'email',
            'contacts',
            'birth_date',
            'city',
            'department',
            'country',
            'job_name',
            'gender',
            'editingUuid',
        ]);
    }

    public function finish(): void
    {
        $teachers = session('pending_teachers', []);

        if (empty($teachers)) {
            $this->notification()->error(
                title: 'Erreur',
                description: 'Aucun enseignant à traiter.'
            );

            return;
        }

        InitProcessToCreateTeachersEvent::dispatch(tenant('id'), $teachers);

        $this->notification()->success(
            title: 'Lancement',
            description: 'Création des enseignants en cours...'
        );


    }

}
