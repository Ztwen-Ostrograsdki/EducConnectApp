<?php

namespace App\Livewire\Tenants;

use App\Jobs\JobToUpdateStudentData;
use App\Livewire\Traits\ValidatorTrait;
use App\Models\Student;
use App\Models\User;
use App\Tools\BeninData;
use Illuminate\Support\Str;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Title("Mise à jour donnees utilisateur")]
class StudentDataManagerByDirectorComponent extends Component
{
    use WireUiActions, ValidatorTrait;

    public string $studentUuid = '';

    public string $matricule = '';

    public Student $student;

    public string $adresse;

    public ?string $email = null;

    public ?string $birth_date;

    public ?string $birth_place;

    public $department_key;

    public $department_name, $department;

    public $done = false;

    public $error_message = '';

    public $cities = [];

    public $showStudentRemoveModal = false;

    public ?string $deletingUuid = null;

    public string $name = '';
    public string $prenames = '';

    public ?string $father_full_name;
    public ?string $mother_full_name;

    public string $country = '';
    public ?string $city = '';

    public string $educMaster = '';

    public string $gender = '';


    protected function rules(): array
    {
        return [
            'name'                 => 'required|string|max:255',
            'prenames'             => 'required|string|max:255',
            'country'              => 'required|string|max:100',
            'city'                 => 'required|string|max:100',
            'gender'               => 'required|string|max:10',
            'educMaster'           => 'required|string',
            'department'           => 'required|string',
            'birth_date'           => 'required|date',
            'birth_place'          => 'required|string',
            'contacts'             => 'required|string|min:10',
            'email'                => 'nullable|email',
            'mother_full_name'     => 'string|nullable',
            'father_full_name'     => 'string|nullable',
        ];
    }

    public function mount(string $studentUuid)
    {

        if(!$studentUuid) return abort(404);

        $student = Student::withTrashed()->whereUuid($studentUuid)->first();

        if(!$student) return abort(404);

        $this->studentUuid = $studentUuid;

        $this->student = $student;

        $this->name = $student->name;
        $this->prenames = $student->prenames;
        $this->contacts = $student->contacts;
        $this->educMaster = $student->educMaster;
        $this->mother_full_name = $student->mother_full_name;
        $this->father_full_name = $student->father_full_name;
        $this->country = normalizeString($student->country);
        $this->gender = normalizeString($student->gender);
        $this->department = normalizeString($student->department);
        $this->birth_date = $student->birth_date?->format('Y-m-d');
        $this->birth_place = $student->birth_place;
        $this->email = $student->email;

        if($this->department){

            $this->cities = [];

            $this->city = null;

            $departments = BeninData::getDepartments();

            $department_key = array_keys($departments, $this->department)[0];

            $this->cities = BeninData::getCities($department_key);

            $this->city = normalizeString($student->city);

        }
    }

    function normalizeString(?string $value): string
    {
        return Str::upper(Str::ascii($value ?? ''));
    }

    public function render()
    {
        $genders = config('app.genders');

        $departments = BeninData::getDepartments();

        $countries = ['BENIN' => 'BENIN'];

        return view('livewire.tenants.student-data-manager-by-director-component', compact('countries', 'departments', 'genders'))->layout(auth('tenant')->user()->getDashboardLayout());
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


    public function resetForm(): void
    {
        $this->reset([
            'name',
            'prenames',
            'contacts',
            'educMaster',
            'birth_date',
            'birth_place',
            'city',
            'department',
            'country',
            'gender',
            'editingUuid',
            'email',
            'father_full_name',
            'mother_full_name',
        ]);
    }

    public function finish(): void
    {
        $this->validate();

        if(!$this->validatePhoneNumber()) return ;

        if($this->email){

            $existed_email1 = Student::withTrashed()->where('email', $this->email)->where('uuid', '<>', $this->studentUuid)->exists();

            $existed_email2 = User::withTrashed()->where('email', $this->email)->exists();

            if($existed_email1 || $existed_email2){

                $this->addError('email', "Cet adresse mail est déjà utilisée!");

                return;
            }
        }

        $existed_full_name = Student::withTrashed()->where('name', $this->name)->where('prenames', $this->prenames)->where('uuid', '<>', $this->studentUuid)->exists();

        $existed_educ = Student::withTrashed()->where('educMaster', $this->educMaster)->where('uuid', '<>', $this->studentUuid)->exists();


        if($existed_educ){

            $this->addError('email', "Un apprenant est déjà enregistré sous ce numero!");

            return;
        }

        if($existed_full_name){
            
            $this->addError('name', "Cet apprenant est déjà enregistré");

            $this->addError('prenames', "Cet apprenant est déjà enregistré");

            return;
        }


        $domain = request()->getSchemeAndHttpHost();

        $data = [
            'name' => Str::upper($this->name),
            'department' => normalizeString($this->department),
            'gender' => normalizeString($this->gender),
            'prenames' => ucwords($this->prenames),
            'contacts' => $this->contacts,
            'country' => normalizeString($this->country),
            'city' => normalizeString($this->city),
            'father_full_name' => normalizeString($this->father_full_name),
            'mother_full_name' => normalizeString($this->mother_full_name),
            'birth_date' => $this->birth_date,
            'birth_place' => normalizeString($this->birth_place),
            'educMaster' => $this->educMaster,
            'email' => $this->email,
        ];

        JobToUpdateStudentData::dispatch(
            tenantId: tenant('id'),
            studentId: $this->student->id,
            data: $data,
            domain: $domain
        );


        $this->resetErrorBag();

    }
    



}
