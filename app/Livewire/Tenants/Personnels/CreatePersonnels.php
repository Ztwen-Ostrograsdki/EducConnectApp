<?php

namespace App\Livewire\Tenants\Personnels;

use App\Jobs\JobToCreatePersonnels;
use App\Livewire\Traits\ValidatorTrait;
use App\Models\Personnel;
use App\Models\SchoolYear;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title('Créations | ajout des personnels')]
class CreatePersonnels extends Component
{
    use WireUiActions, WithFileUploads, ValidatorTrait;

    public ?string $birth_date = null;
    public ?string $since = null;

    public $done = false;
    public $error_message = '';

    public $showPersonnelRemoveModal = false;
    public ?string $deletingUuid = null;

    public string $name = '';
    public string $prenames = '';
    public string $title = '';
    public string $grade = '';
    public string $contacts = '';
    public string $gender = '';
    public string $description = '';

    public ?string $editingUuid = null;

    public int $step = 1;
    public int $counter = 0;

    public function mount(): void
    {
        session()->put(
            'pending_personnels',
            session('pending_personnels', [])
        );
    }

    protected function rules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'prenames'    => 'required|string|max:255',
            'title'       => 'required|string|max:255',
            'gender'      => 'required|string|max:20',
            'contacts'    => [
                'nullable',
                'string',
                'between:4,50',
            ],
            'birth_date'  => 'nullable|date',
            'since'       => 'nullable|date',
            'grade'       => 'nullable|string|max:100',
            'description' => 'nullable|string|max:2000',
        ];
    }

    #[Computed]
    public function genders()
    {
        return config('app.genders');
    }

    public function addPersonnel(): void
    {
        $personnels = session('pending_personnels', []);

        $this->validate();

        if (! empty($this->contacts)) {
            $this->validatePhoneNumber();
        }

        // Doublon dans la session (nom + prénoms)
        $nameExists = collect($personnels)->contains(
            fn ($p) =>
                strtolower($p['name']) === strtolower($this->name)
                && strtolower($p['prenames']) === strtolower($this->prenames)
        );

        if ($nameExists) {
            $this->notification()->error(
                title: 'Déjà présent',
                description: 'Ce personnel (nom + prénoms) existe déjà dans la liste.'
            );
            return;
        }

        // Doublon contact en session
        if (! empty($this->contacts)) {
            $contactExists = collect($personnels)->contains(
                fn ($p) =>
                    ! empty($p['contacts'])
                    && strtolower($p['contacts']) === strtolower($this->contacts)
            );

            if ($contactExists) {
                $this->notification()->error(
                    title: 'Contact déjà utilisé',
                    description: 'Ce numéro de contact existe déjà dans la liste.'
                );
                return;
            }
        }

        // Vérification légère en base (nom + prénoms)
        $existsInDb = Personnel::query()
            ->where('name', Str::upper($this->name))
            ->where('prenames', ucwords($this->prenames))
            ->exists();

        if ($existsInDb) {
            $this->notification()->error(
                title: 'Déjà enregistré',
                description: 'Ce personnel existe déjà en base de données.'
            );
            return;
        }

        $personnels[] = [
            'uuid'        => (string) Str::uuid(),
            'name'        => Str::upper($this->name),
            'prenames'    => ucwords($this->prenames),
            'title'       => $this->title,
            'grade'       => $this->grade ?: null,
            'contacts'    => $this->contacts ?: null,
            'gender'      => $this->gender,
            'birth_date'  => $this->birth_date,
            'since'       => $this->since,
            'description' => $this->description ?: null,
        ];

        session(['pending_personnels' => $personnels]);

        $this->resetForm();

        $this->notification()->success(
            title: 'Succès',
            description: 'Personnel ajouté à la liste.'
        );
    }

    public function getPersonnelsProperty(): array
    {
        return session('pending_personnels', []);
    }

    public function deletePersonnel(string $uuid): void
    {
        $this->deletingUuid = $uuid;

        $personnels = session('pending_personnels', []);

        $personnels = collect($personnels)
            ->reject(fn ($p) => $p['uuid'] === $uuid)
            ->values()
            ->toArray();

        session(['pending_personnels' => $personnels]);

        $this->notification()->success(
            title: 'RETRAIT PERSONNEL',
            description: 'Personnel retiré de la liste.'
        );
    }

    public function resetModal()
    {
        $this->reset('deletingUuid', 'showPersonnelRemoveModal');
    }

    public function editPersonnel(string $uuid): void
    {
        $personnel = collect(session('pending_personnels', []))
            ->firstWhere('uuid', $uuid);

        if (! $personnel) {
            $this->notification()->error(
                title: 'Erreur',
                description: 'Personnel introuvable.'
            );
            return;
        }

        $this->editingUuid = $uuid;

        $this->name        = $personnel['name'];
        $this->prenames    = $personnel['prenames'];
        $this->title       = $personnel['title'] ?? '';
        $this->grade       = $personnel['grade'] ?? '';
        $this->contacts    = $personnel['contacts'] ?? '';
        $this->gender      = $personnel['gender'] ?? '';
        $this->birth_date  = $personnel['birth_date'] ?? null;
        $this->since       = $personnel['since'] ?? null;
        $this->description = $personnel['description'] ?? '';

        $this->notification()->info(
            title: 'Mode édition',
            description: 'Vous modifiez ce personnel.'
        );
    }

    public function updatePersonnel(): void
    {
        $this->validate();

        if (! empty($this->contacts)) {
            $this->validatePhoneNumber();
        }

        $personnels = session('pending_personnels', []);

        // Doublon nom + prénoms (hors lui-même)
        $nameExists = collect($personnels)
            ->where('uuid', '!=', $this->editingUuid)
            ->contains(
                fn ($p) =>
                    strtolower($p['name']) === strtolower($this->name)
                    && strtolower($p['prenames']) === strtolower($this->prenames)
            );

        if ($nameExists) {
            $this->notification()->error(
                title: 'Erreur',
                description: 'Nom et Prénoms déjà utilisés dans la liste.'
            );
            return;
        }

        // Doublon contact (hors lui-même)
        if (! empty($this->contacts)) {
            $contactExists = collect($personnels)
                ->where('uuid', '!=', $this->editingUuid)
                ->contains(
                    fn ($p) =>
                        ! empty($p['contacts'])
                        && strtolower($p['contacts']) === strtolower($this->contacts)
                );

            if ($contactExists) {
                $this->notification()->error(
                    title: 'Erreur',
                    description: 'Contact déjà utilisé dans la liste.'
                );
                return;
            }
        }

        $personnels = collect($personnels)
            ->map(function ($personnel) {
                if ($personnel['uuid'] !== $this->editingUuid) {
                    return $personnel;
                }

                return [
                    ...$personnel,
                    'name'        => Str::upper($this->name),
                    'prenames'    => ucwords($this->prenames),
                    'title'       => $this->title,
                    'grade'       => $this->grade ?: null,
                    'contacts'    => $this->contacts ?: null,
                    'gender'      => $this->gender,
                    'birth_date'  => $this->birth_date,
                    'since'       => $this->since,
                    'description' => $this->description ?: null,
                ];
            })
            ->values()
            ->toArray();

        session(['pending_personnels' => $personnels]);

        $this->resetForm();

        $this->notification()->success(
            title: 'Mis à jour',
            description: 'Données du personnel modifiées avec succès.'
        );
    }

    public function resetForm(): void
    {
        $this->reset([
            'name',
            'prenames',
            'title',
            'grade',
            'contacts',
            'gender',
            'birth_date',
            'since',
            'description',
            'editingUuid',
        ]);
    }

    public function finish(): void
    {
        $personnels = session('pending_personnels', []);

        if (empty($personnels)) {
            $this->notification()->error(
                title: 'Erreur',
                description: 'Aucun personnel à traiter.'
            );
            return;
        }

        JobToCreatePersonnels::dispatch(
            tenant('id'),
            $personnels,
            $this->activeYear->id,
        );

        $this->reset();
        $this->resetErrorBag();

        session()->forget('pending_personnels');

        $this->notification()->success(
            title: 'Lancement réussi',
            description: 'La création des personnels a été lancée en arrière-plan.'
        );
    }

    public function clearAddedData(): void
    {
        $this->reset();
        $this->resetErrorBag();

        session()->forget('pending_personnels');

        $this->notification()->success(
            title: 'Nettoyage effectué !',
            description: 'Les données ajoutées ont été nettoyées.'
        );
    }


    #[Computed]
    public function activeYear()
    {
        return SchoolYear::current()?->first();
    }


    public function render()
    {
        return view('livewire.tenants.personnels.create-personnels');
    }
}
