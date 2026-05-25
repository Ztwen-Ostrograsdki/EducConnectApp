<?php

namespace App\Livewire\Actions;

use App\Events\NewRequestCreatedEvent;
use App\Models\RequestToCreateNewTenant;
use App\Models\Tenant;
use App\Tools\BeninData;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.default-app-layout')]
#[Title("Page de soumission de demande de domaine")]
class RequestPage extends Component
{
    use WireUiActions;
    
    public $school_name;
    public $school_devise;

    public $name;
    public $prenames;
    public $job_name;
    public $contacts;
    public $adresse;
    public $country;
    public $city;
    public $email;
    public $profil_photo;

    public $enseignement_type;
    public $school_type;
    public $devoirs_type;
    public $periode_type;
    public $logo;

    public $department_key;

    public $department_name, $department;

    public $done = true;

    public $cities = [];
    // public $cities = [], $enseignement_types = [], $periode_types = [], $school_types = [], $devoirs_types = [], $departments = [], $countries = [];



    public function mount()
    {
        
        // $this->enseignement_types = BeninData::getSytems();

        // $this->periode_types = config('app.periode_types');

        // $this->school_types = config('app.school_types');

        // $this->devoirs_types = config('app.devoirs_types');

        // $this->departments = BeninData::getDepartments();

        // $this->countries = ['Bénin' => 'Bénin'];


    }



    protected function rules()
    {
        return [
            'school_name' => 'required|string|max:255',
            'school_devise' => 'nullable|string|max:255',

            'name' => 'required|string|max:255',
            'prenames' => 'required|string|max:255',
            'job_name' => 'required|string|max:255',

            'contacts' => 'required|string|max:50',
            'adresse' => 'nullable|string|max:255',
            'country' => 'required|string|max:100',
            'city' => 'required|string|max:100',

            'email' => [
                'required',
                'email',
                'max:255',
                function ($attribute, $value, $fail) {
                    $existsInRequest = RequestToCreateNewTenant::where('email', $value)->exists();
                    $existsInTenants = Tenant::where('email', $value)->exists();

                    if ($existsInRequest || $existsInTenants) {
                        $fail("Vous ne pouvez pas utiliser cet email.");
                    }
                }
            ],

            'profil_photo' => 'nullable|string|max:255',
            'logo' => 'nullable|string|max:255',

            'enseignement_type' => 'required|string',
            'school_type' => 'required|string',
            'devoirs_type' => 'required|string',
            'department' => 'required|string',
            'periode_type' => 'required|string',
        ];
    }

    public function submit()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'prenames' => $this->prenames,
            'job_name' => $this->job_name,
            'contacts' => $this->contacts,
            'country' => $this->country,
            'city' => $this->city,
            'school_name' => $this->school_name,
            'school_devise' => $this->school_devise,
            'email' => $this->email,
            'enseignement_type' => $this->enseignement_type,
            'school_type' => $this->school_type,
            'devoirs_type' => $this->devoirs_type,
            'periode_type' => $this->periode_type,
            'status' => 'pending',
            'validated' => false,
            'school_slug' => Str::slug($this->school_name),
            'adresse' => $this->city . ' ( ' . $this->department .  ')',
        ];

        $this->done = true;
        // $this->done = RequestToCreateNewTenant::create($data);

        if($this->done){
            $this->notification()->success(
                'Demande envoyée',
                'Votre demande a été soumise avec succès! Vous recevrez un courriel contenant les information de votre espace. Veuillez cependant à ne pas partager les détails que vous recevrez!'
            );

            broadcast(new NewRequestCreatedEvent($data));

            $this->reset();
        }
    }

    public function render()
    {

        $enseignement_types = BeninData::getSytems();

        $periode_types = config('app.periode_types');

        $school_types = config('app.school_types');

        $devoirs_types = config('app.devoirs_types');

        $departments = BeninData::getDepartments();

        $countries = ['Bénin' => 'Bénin'];

        
        return view('livewire.actions.request-page', compact('enseignement_types', 'periode_types', 'school_types', 'devoirs_types', 'departments', 'countries'));
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
}
