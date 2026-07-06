<?php

namespace App\Livewire\Tenants\Parents;


use App\Events\InitProcessToCreateTutorsEvent;
use App\Livewire\Traits\ValidatorTrait;
use App\Models\Tenant;
use App\Models\Tutor;
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
#[Title("Créations | ajout des tuteurs")]
class CreateTutors extends Component
{
    use WireUiActions, WithFileUploads, ValidatorTrait;

    public $department_key;
    public $department_name, $department;
    public $cities = [];

    public $showTutorRemoveModal = false;
    public ?string $deletingUuid = null;

    public string $name = '';
    public string $prenames = '';
    public string $job_name = '';

    public string $country = '';
    public ?string $city = '';

    public string $email = '';
    public string $contacts = '';

    public string $gender = '';

    public ?string $birth_date = null;

    public ?string $editingUuid = null;

    public $excelFile = null;
    public bool $showImportMode = false;
    public array $importErrors = [];

    public function mount(): void
    {
        session()->put(
            'pending_tutors',
            session('pending_tutors', [])
        );
    }

    protected function rules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'prenames' => 'required|string|max:255',
            'job_name' => 'nullable|string|max:255',

            'country' => 'required|string|max:100',
            'city'    => 'required|string|max:100',
            'gender'  => 'required|string|max:10',

            'email' => [
                'required',
                app()->isProduction() ? 'email:rfc,dns' : 'email',
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
        $genders = config('app.genders');
        $departments = BeninData::getDepartments();
        $countries = ['BENIN' => 'BENIN'];

        if (session()->has('showImportMode')) {
            $this->showImportMode = session('showImportMode');
            session()->put('showImportMode', $this->showImportMode);
        }

        return view('livewire.tenants.parents.create-tutors', compact('countries', 'departments', 'genders'));
    }

    public function updatedDepartment(?string $department)
    {
        if ($department) {
            $this->cities = [];
            $this->city = null;

            $departments = BeninData::getDepartments();
            $department_key = array_keys($departments, $department)[0];

            $this->cities = BeninData::getCities($department_key);
        }
    }

    public function addTutor(): void
    {
        $tutors = session('pending_tutors', []);

        $this->validate();
        $this->validatePhoneNumber();

        $emailExists = collect($tutors)->contains(
            fn ($tutor) => strtolower($tutor['email']) === strtolower($this->email)
        );

        if ($emailExists) {
            $this->notification()->error(
                title: 'Email déjà utilisé',
                description: 'Cet email existe déjà.'
            );
            return;
        }

        if (User::query()->where('email', $this->email)->exists()) {
            $this->notification()->error(
                title: 'Email existant',
                description: 'Cet email est déjà enregistré.'
            );
            return;
        }

        $tutors[] = [
            'uuid'       => (string) Str::uuid(),
            'name'       => Str::upper($this->name),
            'department' => Str::upper($this->department),
            'gender'     => $this->gender,
            'prenames'   => ucwords($this->prenames),
            'job_name'   => $this->job_name,
            'contacts'   => $this->contacts,
            'country'    => Str::upper($this->country),
            'city'       => Str::upper($this->city),
            'birth_date' => $this->birth_date,
            'email'      => $this->email,
        ];

        session(['pending_tutors' => $tutors]);

        $this->resetForm();

        $this->notification()->success(
            title: 'Succès',
            description: 'Tuteur ajouté.'
        );
    }

    public function getTutorsProperty(): array
    {
        return session('pending_tutors', []);
    }

    public function deleteTutor(string $uuid): void
    {
        $this->deletingUuid = $uuid;
        $uuid = $this->deletingUuid;

        $tutors = collect(session('pending_tutors', []))
            ->reject(fn ($t) => $t['uuid'] === $uuid)
            ->values()
            ->toArray();

        session(['pending_tutors' => $tutors]);

        $this->notification()->success(
            title: 'RETRAIT TUTEUR/PARENT',
            description: 'Tuteur/Parent retiré de la liste.'
        );
    }


    public function resetModal()
    {
        $this->reset('deletingUuid', 'showTutorRemoveModal');
    }

    public function editTutor(string $uuid): void
    {
        $tutor = collect(session('pending_tutors', []))->firstWhere('uuid', $uuid);

        if (! $tutor) {
            $this->notification()->error(
                title: 'Erreur',
                description: 'Tuteur introuvable.'
            );
            return;
        }

        $this->editingUuid = $uuid;

        $this->name       = $tutor['name'];
        $this->prenames   = $tutor['prenames'];
        $this->email      = $tutor['email'];
        $this->department = $tutor['department'];
        $this->gender     = $tutor['gender'];
        $this->contacts   = $tutor['contacts'];
        $this->job_name   = $tutor['job_name'];
        $this->country    = $tutor['country'];
        $this->birth_date = $tutor['birth_date'];
        $this->city       = $tutor['city'];

        if ($this->department) {
            $this->cities = [];
            $this->city = null;

            $departments = BeninData::getDepartments();
            $department_key = array_keys($departments, $this->department)[0];

            $this->cities = BeninData::getCities($department_key);
            $this->city = $tutor['city'];
        }

        $this->notification()->info(
            title: 'Mode édition',
            description: 'Vous modifiez ce tuteur.'
        );
    }

    public function updateTutor(): void
    {
        $this->validate();

        $tutors = session('pending_tutors', []);

        $emailExists = collect($tutors)
            ->where('uuid', '!=', $this->editingUuid)
            ->contains(fn ($t) => strtolower($t['email']) === strtolower($this->email));

        $nameExists = collect($tutors)
            ->where('uuid', '!=', $this->editingUuid)
            ->contains(fn ($t) => strtolower($t['name']) === strtolower($this->name) && strtolower($t['prenames']) === strtolower($this->prenames));

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

        $tutors = collect($tutors)
            ->map(function ($tutor) {
                if ($tutor['uuid'] !== $this->editingUuid) {
                    return $tutor;
                }

                return [
                    ...$tutor,
                    'name'       => $this->name,
                    'prenames'   => $this->prenames,
                    'email'      => $this->email,
                    'department' => $this->department,
                    'city'       => $this->city,
                    'country'    => $this->country,
                    'gender'     => $this->gender,
                    'job_name'   => $this->job_name,
                    'birth_date' => $this->birth_date,
                    'contacts'   => $this->contacts,
                ];
            })
            ->values()
            ->toArray();

        session(['pending_tutors' => $tutors]);

        $this->resetForm();

        $this->notification()->success(
            title: 'Mis à jour',
            description: 'Données tuteur modifiées avec succès.'
        );
    }

    public function resetForm(): void
    {
        $this->reset([
            'name', 'prenames', 'email', 'contacts', 'birth_date',
            'city', 'department', 'country', 'job_name', 'gender', 'editingUuid',
        ]);
    }

    public function finish(): void
    {
        $tutors = session('pending_tutors', []);

        if (empty($tutors)) {
            $this->notification()->error(
                title: 'Erreur',
                description: 'Aucun tuteur à traiter.'
            );
            return;
        }

        $domain = request()->getSchemeAndHttpHost();

        InitProcessToCreateTutorsEvent::dispatch(tenant('id'), $tutors, $domain);

        $this->resetExcept('showImportMode');
        $this->resetErrorBag();

        session()->forget('pending_tutors');
    }

    public function clearAddedData()
    {
        $this->resetExcept('showImportMode');
        $this->resetErrorBag();

        session()->forget('pending_tutors');

        $this->notification()->success(
            title: 'Nettoyage effectué!',
            description: 'Les données ajoutées ont été nettoyées'
        );
    }

    public function updatedExcelFile(): void
    {
        $this->importErrors = [];

        $this->validate([
            'excelFile' => 'required|file|mimes:xlsx,xls|max:2048',
        ]);

        try {
            $path = $this->excelFile->getRealPath();

            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, true);

            array_shift($rows);

            $tutors = session('pending_tutors', []);
            $existingEmails = collect($tutors)->pluck('email')->map('strtolower')->toArray();
            $errors = [];

            foreach ($rows as $index => $row) {
                $line = $index + 2;
                $email = strtolower(trim($row['C'] ?? ''));
                $name = Str::upper(trim($row['A'] ?? ''));
                $prenames = ucwords(trim($row['B'] ?? ''));
                $contacts = trim($row['D'] ?? '');

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

                $phoneError = $this->validatePhoneNumberSilently((string) $contacts);

                if ($phoneError !== null) {
                    $errors[] = "Ligne {$line} : {$phoneError}";
                    continue;
                }

                $emailError = $this->validateEmailSilently($email, true);

                if ($emailError !== null) {
                    $errors[] = "Ligne {$line} : {$emailError}";
                    continue;
                }

                $email_existed_in_db1 = User::where('email', $email)->orWhere('contacts', $contacts)->first();
                $email_existed_in_db2 = Tutor::firstWhere('email', $email);

                if ($email_existed_in_db1 || $email_existed_in_db2) {
                    $errors[] = "Ligne {$line} : L'adresse mail ou le contact existe déjà dans la base de données.";
                    continue;
                }

                $names_existed_in_db = User::where('name', $name)->where('prenames', $prenames)->first();

                if ($names_existed_in_db) {
                    $errors[] = "Ligne {$line} : Le tuteur {$name} {$prenames} existe déjà dans la base de données.";
                    continue;
                }

                $tutors[] = [
                    'uuid'       => (string) Str::uuid(),
                    'name'       => $name,
                    'prenames'   => $prenames,
                    'email'      => $email,
                    'contacts'   => $contacts,
                    'gender'     => trim($row['E'] ?? ''),
                    'country'    => Str::upper(trim($row['F'] ?? '')),
                    'department' => Str::upper(trim($row['G'] ?? '')),
                    'city'       => Str::upper(trim($row['H'] ?? '')),
                    'job_name'   => trim($row['I'] ?? ''),
                    'birth_date' => trim($row['J'] ?? '') ?: null,
                ];

                $existingEmails[] = $email;
            }

            session(['pending_tutors' => $tutors]);

            $this->importErrors = $errors;
            $this->excelFile = null;
            $this->showImportMode = false;

            if (count($tutors)) {
                if (! empty($errors)) {
                    $this->notification()->warning(
                        title: 'Importation partiellement réussie',
                        description: count($tutors) . ' tuteur(s) chargé(s) depuis le fichier. Avec ' . count($errors) . ' ligne(s) ignorée(s). Voir les détails.',
                    );
                } else {
                    $this->notification()->success(
                        title: 'Importation des données réussie',
                        description: count($tutors) . ' tuteur(s) chargé(s) depuis le fichier.',
                    );
                }
            } elseif (! empty($errors)) {
                $this->notification()->error(
                    title: 'Echec du chargement des données depuis le fichier',
                    description: count($errors) . ' ligne(s) ignorée(s). Voir les détails.',
                );
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

        $this->importErrors = [];
        $this->excelFile = null;
    }
}