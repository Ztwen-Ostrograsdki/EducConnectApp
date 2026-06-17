<?php

namespace App\Livewire\Tenants\Students;

use App\Events\InitProcessToCreateStudentsEvent;
use App\Livewire\Traits\BeninPhoneValidation;
use App\Models\Student;
use App\Tools\BeninData;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use PhpOffice\PhpSpreadsheet\IOFactory;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title("Ajout des apprenants")]
class CreateStudents extends Component
{

    use WireUiActions, WithFileUploads, BeninPhoneValidation;

    public string $adresse;

    public ?string $email = '';

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

    public ?string $editingUuid = null;

    public $excelFile = null;
    public bool $showImportMode = false;
    public array $importErrors = [];

    public int $step = 1;

    public function mount(): void
    {
        // session()->forget('pending_students');
        session()->put(
            'pending_students',
            session('pending_students', [])
        );
    }

    protected function rules(): array
    {
        return [
            'name'                 => 'required|string|max:255',
            'prenames'             => 'required|string|max:255',
            'country'              => 'required|string|max:100',
            'city'                 => 'required|string|max:100',
            'gender'               => 'required|string|max:10',
            'educMaster'           => 'required|string|unique:students',
            'department'           => 'required|string',
            'birth_date'           => 'required|date',
            'birth_place'          => 'required|string',
            'contacts'             => 'required|string|min:10',
            'email'                => 'nullable|email|unique:students',
            'mother_full_name'     => 'string|nullable',
            'father_full_name'     => 'string|nullable',
        ];
    }

    public function render()
    {
        $imports = [];

        $genders = config('app.genders');

        $departments = BeninData::getDepartments();

        $countries = ['Bénin' => 'Bénin'];

        if(session()->has('showImportMode')){

            $this->showImportMode = session('showImportMode');

            session()->put('showImportMode', $this->showImportMode);
            
        }


        return view('livewire.tenants.students.create-students', compact('imports', 'countries', 'departments', 'genders'));
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

    public function addStudent(): void
    {
        $students = session(
            'pending_students',
            []
        );

        $this->validate();

        if(!$this->validatePhoneNumber()) return ;

        // Session

        if($this->email){

            $emailExists = collect($students)

                ->contains(
                    fn ($student) =>
                        strtolower($student['email'])
                        ===
                        strtolower($this->email)
                );

            if ($emailExists) {

                $this->notification()->error(
                    title: 'Email déjà utilisé',
                    description: 'Cet Email existe déjà.'
                );

                return;
            }
        }

        $fullNameExists = collect($students)

            ->contains(
                fn ($student) =>
                    strtolower($student['name'])
                    ===
                    strtolower($this->name)
                    &&
                    strtolower($student['prenames'])
                    ===
                    strtolower($this->prenames)
            );

        if ($fullNameExists) {

            $this->notification()->error(
                title: 'Nom et prénoms déjà utilisé',
                description: 'Cet Nom et prénoms existe déjà.'
            );

            return;
        }

        // Base

        if($this->email){

            if (Student::query()->where('email', $this->email)->exists()) {

                $this->notification()->error(
                    title: 'Email existant',
                    description: 'Cet Email est déjà enregistré.'
                );

                return;
            }
        }

        if (Student::query()->where('name', $this->name)->where('prenames', $this->prenames)->exists()) {

            $this->notification()->error(
                title: 'Nom et Prénoms existant',
                description: 'Cet Nom et Prénoms est déjà enregistré.'
            );

            return;
        }

        $students[] = [
            'uuid' => (string) Str::uuid(),
            'name' => Str::upper($this->name),
            'department' => Str::upper($this->department),
            'gender' => $this->gender,
            'prenames' => ucwords($this->prenames),
            'contacts' => $this->contacts,
            'country' => Str::upper($this->country),
            'city' => Str::upper($this->city),
            'father_full_name' => Str::upper($this->father_full_name),
            'mother_full_name' => Str::upper($this->mother_full_name),
            'birth_date' => $this->birth_date,
            'birth_place' => $this->birth_place,
            'educMaster' => $this->educMaster,
            'email' => $this->email,
        ];

        session([
            'pending_students' => $students
        ]);

        $this->resetForm();

        $this->notification()->success(
            title: 'Succès',
            description: 'Elève ajouté.'
        );
    }

    public function getStudentsProperty(): array
    {
        return session('pending_students', []);
    }

    public function deleteStudent(string $uuid): void
    {
        $this->deletingUuid = $uuid;

        $this->showStudentRemoveModal = true;

    }

    public function confirmDeleteStudent(): void
    {
        $uuid = $this->deletingUuid;

        $students = session('pending_students', []);

        $students = collect($students)
            ->reject(fn ($t) => $t['uuid'] === $uuid)
            ->values()
            ->toArray();

        session([
            'pending_students' => $students
        ]);

        $this->notification()->success(
            title: 'Supprimé',
            description: 'Apprenant retiré.'
        );

        $this->resetModal();
    }

    public function resetModal()
    {
        $this->reset('deletingUuid', 'showStudentRemoveModal');
    }

    public function editStudent(string $uuid): void
    {
        $student = collect(session('pending_students', []))
            ->firstWhere('uuid', $uuid);

        if (! $student) {
            $this->notification()->error(
                title: 'Erreur',
                description: 'Apprenant introuvable.'
            );

            return;
        }

        $this->editingUuid = $uuid;

        $this->name = $student['name'];
        $this->prenames = $student['prenames'];
        $this->department = $student['department'];
        $this->gender = $student['gender'];
        $this->contacts = $student['contacts'];
        $this->country = $student['country'];
        $this->birth_date = $student['birth_date'];
        $this->birth_place = $student['birth_place'];
        $this->city = $student['city'];
        $this->email = $student['email'];
        $this->mother_full_name = $student['mother_full_name'];
        $this->father_full_name = $student['father_full_name'];

        if($this->department){

            $departments = BeninData::getDepartments();

            $this->cities = [];

            $this->city = null;

            $departments = BeninData::getDepartments();

            $department_key = array_keys($departments, $this->department)[0];

            $this->cities = BeninData::getCities($department_key);

            $this->city = $student['city'];

        }

        $this->notification()->info(
            title: 'Mode édition',
            description: 'Vous modifiez cette donnée.'
        );
    }

    public function updateStudent(): void
    {
        $this->validate();

        $this->validatePhoneNumber();

        $students = session('pending_students', []);

        // Vérifier doublon email (hors lui-même)
        $existsData = collect($students)
            ->where('uuid', '!=', $this->editingUuid)
            ->contains(fn ($t) =>
                strtolower($t['name']) === strtolower($this->name) && strtolower($t['prenames']) === strtolower($this->prenames)
            );

        if ($existsData) {
            $this->notification()->error(
                title: 'Erreur',
                description: 'Donnée déjà utilisée dans la liste.'
            );

            return;
        }

        if($this->email){
            $existsData = collect($students)
                ->where('uuid', '!=', $this->editingUuid)
                ->contains(fn ($t) =>
                    strtolower($t['email']) === strtolower($this->email)
                );

            if ($existsData) {
                $this->notification()->error(
                    title: 'Erreur',
                    description: 'Email déjà utilisée dans la liste.'
                );

                return;
            }
        }

        $students = collect($students)
            ->map(function ($student) {

                if ($student['uuid'] !== $this->editingUuid) {
                    return $student;
                }

                return [
                    ...$student,
                    'name' => $this->name,
                    'prenames' => $this->prenames,
                    'department' => $this->department,
                    'city' => $this->city,
                    'country' => $this->country,
                    'gender' => $this->gender,
                    'birth_date' => $this->birth_date,
                    'birth_place' => $this->birth_place,
                    'contacts' => $this->contacts,
                    'email' => $this->email,
                    'educMaster' => $this->educMaster,
                    'father_full_name' => $this->father_full_name,
                    'mother_full_name' => $this->mother_full_name,
                ];
            })
            ->values()
            ->toArray();

        session([
            'pending_students' => $students
        ]);

        $this->resetForm();

        $this->notification()->success(
            title: 'Donnée mise à jour',
            description: 'Donnée modifiée avec succès.'
        );
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
        $students = session('pending_students', []);

        if (empty($students)) {
            $this->notification()->error(
                title: 'Erreur',
                description: 'Aucun élève à traiter.'
            );

            return;
        }

        $domain = request()->getSchemeAndHttpHost();

        InitProcessToCreateStudentsEvent::dispatch(tenant('id'), $students, $domain);

        $this->resetExcept('showImportMode');

        $this->resetErrorBag();

        session()->forget('pending_students');
    }
    
    public function clearAddedData()
    {
        
        $this->resetExcept('showImportMode');

        $this->resetErrorBag();

        session()->forget('pending_students');

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

            $students        = session('pending_students', []);

            $errors          = [];

            foreach ($rows as $index => $row) {
                $line  = $index + 2; // ligne Excel (header = 1)
                $name = strtolower(trim($row['A'] ?? ''));
                $prenames = strtolower(trim($row['B'] ?? ''));
                $email = trim($row['K'] ?? '');

                $educMaster = $row['F'];

                $contacts = $row['J'];

                // Colonnes attendues dans le fichier Excel :
                // A = name, B = prenames, C = country, D = city,
                // E = sexe, F = educMaster, G = department, H = birth_date,
                // I = birth_place, J = contacts, K = email, L = mother_full_name, M = father_full_name

                if (empty($name)) {
                    $errors[] = "Ligne {$line} : nom manquant.";
                    continue;
                }
                
                if (empty($prenames)) {
                    $errors[] = "Ligne {$line} : prénoms manquant.";
                    continue;
                }

                if (empty($educMaster)) {
                    $errors[] = "Ligne {$line} : N° EducMaster manquant.";
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
                    
                    $emailError = $this->validateEmailSilently($email);

                    if ($emailError !== null) {
                        $errors[] = "Ligne {$line} : {$emailError}";
                        continue;
                    }
                }

                foreach($students as $st){

                    if($st['name'] === $name && $st['prenames'] === $prenames){

                        $errors[] = "Ligne {$line} : Nom et prénoms {$name} {$prenames} exite déjà dans la liste.";
                        continue;
                    }

                    if($email && $st['email'] === $email){

                        $errors[] = "Ligne {$line} : Email {$email} existe déjà dans la liste.";
                        continue;
                    }

                    if($educMaster && $st['educMaster'] === $educMaster){

                        $errors[] = "Ligne {$line} : Le N° EducMaster {$educMaster} est déjà dans la liste.";
                        continue;
                    }
                }

                $existed_in_db = Student::where('name', $name)->where('prenames', $prenames);

                if($email) $existed_in_db->orWhere('email', $email);

                if($educMaster) $existed_in_db->orWhere('educMaster', $educMaster);

                if($existed_in_db->count() > 0){

                    $errors[] = "Ligne {$line} : Les données existent apparemment déjà dans la base de données.";
                        continue;

                }

                $students[] = [
                    'uuid'              => (string) Str::uuid(),
                    'name'              => Str::upper(trim($row['A']) ?? ''),
                    'prenames'          => ucwords(trim($row['B'] ) ?? ''),
                    'contacts'          =>$contacts,
                    'gender'            => trim($row['E'] ?? ''),
                    'country'           => Str::upper(trim($row['C']) ?? ''),
                    'department'        => Str::upper(trim($row['G']) ?? ''),
                    'city'              => Str::upper(trim($row['D']) ?? ''),
                    'birth_date'        => trim($row['H'] ?? '') ?: null,
                    'birth_place'       => trim($row['I'] ?? '') ?: null,
                    'father_full_name'  => trim($row['M'] ?? '') ?: null,
                    'mother_full_name'  => trim($row['L'] ?? '') ?: null,
                    'educMaster'        => $educMaster,
                    'email'             => $email,
                ];

                $existedData[] = $name . ' ' . $prenames;
            }

            session(['pending_students' => $students]);

            $this->importErrors = $errors;
            $this->excelFile    = null;
            $this->showImportMode = false;

            if(count($students)){
                if (! empty($errors)) {
                    $this->notification()->warning(
                        title: 'Importation partiellement réussi',
                        description: count($students) . ' élève(s) chargé(s) depuis le fichier. Avec ' . count($errors) . ' ligne(s) ignorée(s). Voir les détails.',
                    );
                }
                else{
                    $this->notification()->success(
                        title: 'Importation des données réussie',
                        description: count($students) . ' élève(s) chargé(s) depuis le fichier.',
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
