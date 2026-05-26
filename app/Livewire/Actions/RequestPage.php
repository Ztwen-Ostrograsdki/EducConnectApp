<?php

namespace App\Livewire\Actions;

use App\Events\NewRequestCreatedEvent;
use App\Models\RequestToCreateNewTenant;
use App\Models\Tenant;
use App\Tools\BeninData;
use Illuminate\Support\Facades\DB;
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
    public $simple_name;
    public $domain_name;
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

    public $done = false;

    public $error_message = '';

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
            'simple_name' => 'required|string|between:3,50',
            'school_devise' => 'nullable|string|max:255',

            'name' => 'required|string|max:255',
            'prenames' => 'required|string|max:255',
            'job_name' => 'required|string|max:255',

            'contacts' => 'required|string|max:50',
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

            'domain_name' => [
                'required',
                'string',
                'between:4,50',
                function ($attribute, $value, $fail) {
                    $existsInRequest = RequestToCreateNewTenant::where('domain_name', $value)->exists();
                    $existsInTenants = Tenant::where('domain_name', $value)->exists();

                    if ($existsInRequest || $existsInTenants) {
                        $fail("Vous ne pouvez pas utiliser cet nom de domaine.");
                    }
                }
            ],

            'enseignement_type' => 'required|string',
            'school_type' => 'required|string',
            'devoirs_type' => 'required|string',
            'department' => 'required|string',
            'periode_type' => 'required|string',
        ];
    }

    public function submit()
    {
        $this->error_message = '';

        $this->resetErrorBag();
        
        if($this->validate()){

            DB::beginTransaction();


                try {

                    $data = [
                    'name' => $this->name,
                    'simple_name' => $this->simple_name,
                    'domain_name' => $this->domain_name,
                    'department' => $this->department,
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
                    'adresse' => $this->city . ' (' . $this->department .  ')',
                ];

                $this->done = RequestToCreateNewTenant::create($data);

                if($this->done){

                    $this->notification()->success(
                        'Demande envoyée',
                        'Votre demande a été soumise avec succès! Vous recevrez un courriel contenant les information de votre espace. Veuillez cependant à ne pas partager les détails que vous recevrez!'
                    );

                    broadcast(new NewRequestCreatedEvent($data));

                    $this->reset();

                    $this->done = true;
                }

                DB::commit();
                
            } catch (\Throwable $th) {

                $this->error_message = $th->getMessage();

                $this->notification()->error('Requête echouée', $this->error_message);
                DB::rollBack();
            }
        }
        else{

            $this->error_message = "Formulaire incorrect!";
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


    public function updatedDomainName(?string $string)
    {
        $this->validateOnly('domain_name');
    }


    public function resetForm()
    {
        $this->reset();
    }
}
