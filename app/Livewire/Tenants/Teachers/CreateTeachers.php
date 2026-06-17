<?php

namespace App\Livewire\Tenants\Teachers;

use App\Events\InitProcessToCreateTeachersEvent;
use App\Livewire\Traits\ValidatorTrait;
use App\Models\Teacher;
use App\Models\Tenant;
use App\Models\User;
use App\Tools\BeninData;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use PhpOffice\PhpSpreadsheet\IOFactory;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title("Créations | ajout des enseignants")]
class CreateTeachers extends Component
{
    use WireUiActions, WithFileUploads, ValidatorTrait;

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

    public $excelFile = null;
    public bool $showImportMode = false;
    public array $importErrors = [];


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
                app()->isProduction() ? 'email:rfc,dns' :'email',
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

        $countries = ['BENIN' => 'BENIN'];

        if(session()->has('showImportMode')){

            $this->showImportMode = session('showImportMode');

            session()->put('showImportMode', $this->showImportMode);
            
        }


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
        $teachers = session(
            'pending_teachers',
            []
        );

        $this->validate();

        $this->validatePhoneNumber();

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
            'name' => Str::upper($this->name),
            'department' => Str::upper($this->department),
            'gender' => $this->gender,
            'prenames' => ucwords($this->prenames),
            'job_name' => $this->job_name,
            'contacts' => $this->contacts,
            'country' => Str::upper($this->country),
            'city' => Str::upper($this->city),
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
            ->contains(fn ($t) => strtolower($t['email']) === strtolower($this->email));

        $nameExists = collect($teachers)
            ->where('uuid', '!=', $this->editingUuid)
            ->contains(fn ($t) => strtolower($t['name']) === $this->name && strtolower($t['prenames']) === $this->prenames);

        if ($emailExists) {
            $this->notification()->error(
                title: 'Erreur',
                description: 'Email déjà utilisé dans la liste.'
            );

            return;
        }
        
        if ($nameExists) {
            $this->notification()->error(
                title: 'Erreur',
                description: 'Nom et Prénoms déjà utilisés dans la liste.'
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
            description: 'Données enseignant modifiées avec succès.'
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

        $domain = request()->getSchemeAndHttpHost();

        InitProcessToCreateTeachersEvent::dispatch(tenant('id'), $teachers, $domain);

        $this->resetExcept('showImportMode');

        $this->resetErrorBag();

        session()->forget('pending_teachers');
    }
    
    public function clearAddedData()
    {
        
        $this->resetExcept('showImportMode');

        $this->resetErrorBag();

        session()->forget('pending_teachers');

        $this->notification()->success(
            title: 'Nettoyage effectué!',
            description: 'Les données ajoutées ont été nettoyées'
        );
        
    }



    /**
     * Déclenché quand un fichier est uploadé
     */
    public function updatedExcelFile(): void
    {
        $this->importErrors = [];

        $this->validate([
            'excelFile' => 'required|file|mimes:xlsx,xls|max:2048',
        ]);

        try {
            $path = $this->excelFile->getRealPath();

            $spreadsheet = IOFactory::load($path);
            $sheet       = $spreadsheet->getActiveSheet();
            $rows        = $sheet->toArray(null, true, true, true);

            // Retire la ligne d'en-tête
            array_shift($rows);

            $teachers        = session('pending_teachers', []);
            $existingEmails  = collect($teachers)->pluck('email')->map('strtolower')->toArray();
            $errors          = [];

            foreach ($rows as $index => $row) {
                $line  = $index + 2; // ligne Excel (header = 1)
                $email = strtolower(trim($row['C'] ?? ''));

                $name = Str::upper(trim($row['A'] ?? ''));

                $prenames = ucwords(trim($row['B'] ?? ''));

                // Colonnes attendues dans le fichier Excel :
                // A = name, B = prenames, C = email, D = contacts,
                // E = gender, F = country, G = department, H = city,
                // I = job_name, J = birth_date

                if (empty($email)) {
                    $errors[] = "Ligne {$line} : email manquant.";
                    continue;
                }

                if (empty($name) || empty($prenames)) {
                    $errors[] = "Ligne {$line} : nom ou prénoms manquants.";
                    continue;
                }

                if (in_array($email, $existingEmails)) {
                    $errors[] = "Ligne {$line} : email {$email} déjà dans la liste.";
                    continue;
                }

                if (User::where('email', $email)->exists()) {
                    $errors[] = "Ligne {$line} : email {$email} déjà en base.";
                    continue;
                }

                if (empty($contacts)) {
                    $errors[] = "Ligne {$line} : N° contact manquant.";
                    continue;
                }

                if (!empty($contacts)) {
                    $phoneError = $this->validatePhoneNumberSilently((string) $contacts);

                    if ($phoneError !== null) {
                        $errors[] = "Ligne {$line} : {$phoneError}";
                        continue;
                    }
                }

                if(!empty($email)){
                    
                    $emailError = $this->validateEmailSilently($email, true);

                    if ($emailError !== null) {
                        $errors[] = "Ligne {$line} : {$emailError}";
                        continue;
                    }
                }

                $email_existed_in_db1 = User::where('email', $email)->orWhere('contacts', $contacts)->first();

                $email_existed_in_db2 = Teacher::firstWhere('email', $email);

                if($email_existed_in_db1 || $email_existed_in_db2){

                    $errors[] = "Ligne {$line} : L'adresse mail ou le contact existe déjà dans la base de données.";
                        continue;

                }

                $names_existed_in_db = User::where('name', $name)->where('prenames', $prenames)->first();

                if($names_existed_in_db){

                    $errors[] = "Ligne {$line} :  L'enseignant {$name} {$prenames} existe déjà dans la base de données.";
                        continue;

                }

                $teachers[] = [
                    'uuid'       => (string) Str::uuid(),
                    'name'       => $name,
                    'prenames'   => $prenames,
                    'email'      => $email,
                    'contacts'   => $contacts,
                    'gender'     => trim($row['E'] ?? ''),
                    'country'    => Str::upper(trim($row['F']) ?? ''),
                    'department' => Str::upper(trim($row['G']) ?? ''),
                    'city'       => Str::upper(trim($row['H']) ?? ''),
                    'job_name'   => trim($row['I'] ?? ''),
                    'birth_date' => trim($row['J'] ?? '') ?: null,
                ];

                $existingEmails[] = $email;
            }

            session(['pending_teachers' => $teachers]);

            $this->importErrors = $errors;
            $this->excelFile    = null;
            $this->showImportMode = false;

            $successCount = count($teachers) - (count(session('pending_teachers', [])) - count($teachers));

            if(count($teachers)){
                if (! empty($errors)) {
                    $this->notification()->warning(
                        title: 'Importation partiellement réussi',
                        description: count($teachers) . ' enseignant(s) chargé(s) depuis le fichier. Avec ' . count($errors) . ' ligne(s) ignorée(s). Voir les détails.',
                    );
                }
                else{
                    $this->notification()->success(
                        title: 'Importation des données réussie',
                        description: count($teachers) . ' enseignant(s) chargé(s) depuis le fichier.',
                    );
                }
            }
            else{

                if (! empty($errors)) {
                    $this->notification()->error(
                        title: 'Echec du chargement des données depuis le fichier',
                        description: count($errors) . ' ligne(s) ignorée(s). Voir les détails.',
                    );
                }
            }

            

        } catch (\Throwable $e) {
            $this->notification()->error(
                title: 'Erreur de lecture',
                description: 'Impossible de lire le fichier : ' . $e->getMessage(),
            );
        }
    }

    public function toggleImportMode(): void
    {
        $this->showImportMode = ! $this->showImportMode;

        session()->put('showImportMode', $this->showImportMode);

        $this->importErrors   = [];
        $this->excelFile      = null;
    }

}
