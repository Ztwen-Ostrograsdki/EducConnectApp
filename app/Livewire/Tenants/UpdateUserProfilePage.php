<?php

namespace App\Livewire\Tenants;

use App\Models\User;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Title('Mettre à jour le profil')]
class UpdateUserProfilePage extends Component
{
    use WireUiActions;

    public User $user;

    /** Sections ouvertes (clés) */
    public array $openSections = [
        'civil' => true,
        'contact' => false,
        'identity' => false,
        'career' => false,
        'grades' => false,
        'notes' => false,
    ];

    // —— État civil ——
    public string $name = '';
    public string $prenames = '';
    public ?string $gender = null;
    public ?string $birth_date = null;
    public ?string $birth_place = null;
    public ?string $marital_status = null;
    public ?string $job_name = null;

    // —— Coordonnées ——
    public ?string $contacts = null;
    public ?string $adresse = null;
    public ?string $country = null;
    public ?string $city = null;
    public ?string $department = null;

    // —— Pièce d’identité / matricule ——
    public ?string $matricule_number = null;
    public ?string $ID_card_numder = null;
    public ?string $ID_card_type = null;
    public ?string $ID_card_delivery_place = null;
    public ?string $ID_card_expired_date = null;

    // —— Carrière / diplômes ——
    public ?string $first_take_job_date = null;
    public ?string $academic_graduate = null;
    public ?string $professionnal_graduate = null;
    public ?string $body = null;
    public ?string $official_status = null;
    public ?string $references_of_recrutement = null;

    // —— Grades / indices ——
    public ?string $current_grade = null;
    public ?string $current_index = null;
    public ?string $payed_grade = null;
    public ?string $payed_index = null;

    // —— Observations ——
    public ?string $observations = null;

    public function mount(?string $uuid = null): void
    {
        /**@var \App\Models\User */
        $auth = auth('tenant')->user();

        if ($uuid) {
            $this->user = User::query()->where('uuid', $uuid)->firstOrFail();

            // Seul le propriétaire ou un directeur/admin peut éditer
            if ((int) $this->user->id !== (int) $auth->id
                && ! $auth->hasAnyRole(['director', 'admin', 'super-admin'])) {
                abort(403);
            }
        } else {
            $this->user = $auth;
        }

        $this->fillFromUser();
    }

    protected function fillFromUser(): void
    {
        $u = $this->user;

        $this->name = (string) ($u->name ?? '');
        $this->prenames = (string) ($u->prenames ?? '');
        $this->gender = $u->gender;
        $this->birth_date = optional($u->birth_date)?->format('Y-m-d');
        $this->birth_place = $u->birth_place;
        $this->marital_status = $u->marital_status;
        $this->job_name = $u->job_name;

        $this->contacts = $u->contacts;
        $this->adresse = $u->adresse;
        $this->country = $u->country;
        $this->city = $u->city;
        $this->department = $u->department;

        $this->matricule_number = $u->matricule_number;
        $this->ID_card_numder = $u->ID_card_numder;
        $this->ID_card_type = $u->ID_card_type;
        $this->ID_card_delivery_place = $u->ID_card_delivery_place;
        $this->ID_card_expired_date = optional($u->ID_card_expired_date)?->format('Y-m-d')
            ?? (is_string($u->ID_card_expired_date) ? $u->ID_card_expired_date : null);

        $this->first_take_job_date = optional($u->first_take_job_date)?->format('Y-m-d')
            ?? (is_string($u->first_take_job_date) ? $u->first_take_job_date : null);
        $this->academic_graduate = $u->academic_graduate;
        $this->professionnal_graduate = $u->professionnal_graduate;
        $this->body = $u->body;
        $this->official_status = $u->official_status;
        $this->references_of_recrutement = $u->references_of_recrutement;

        $this->current_grade = $u->current_grade;
        $this->current_index = $u->current_index !== null ? (string) $u->current_index : null;
        $this->payed_grade = $u->payed_grade;
        $this->payed_index = $u->payed_index !== null ? (string) $u->payed_index : null;

        $this->observations = $u->observations;
    }

    public function toggleSection(string $key): void
    {
        if (! array_key_exists($key, $this->openSections)) {
            return;
        }

        $this->openSections[$key] = ! $this->openSections[$key];
    }

    public function openAll(): void
    {
        foreach ($this->openSections as $k => $_) {
            $this->openSections[$k] = true;
        }
    }

    public function closeAll(): void
    {
        foreach ($this->openSections as $k => $_) {
            $this->openSections[$k] = false;
        }
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'prenames' => ['required', 'string', 'max:180'],
            'gender' => ['nullable', 'string', 'in:M,F,Masculin,Féminin,Feminin,masculin,feminin'],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'birth_place' => ['nullable', 'string', 'max:150'],
            'marital_status' => ['nullable', 'string', 'max:80'],
            'job_name' => ['nullable', 'string', 'max:150'],

            'contacts' => ['nullable', 'string', 'max:120'],
            'adresse' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'department' => ['nullable', 'string', 'max:100'],

            'matricule_number' => ['nullable', 'string', 'max:80'],
            'ID_card_numder' => ['nullable', 'string', 'max:80'],
            'ID_card_type' => ['nullable', 'string', 'max:80'],
            'ID_card_delivery_place' => ['nullable', 'string', 'max:150'],
            'ID_card_expired_date' => ['nullable', 'date'],

            'first_take_job_date' => ['nullable', 'date'],
            'academic_graduate' => ['nullable', 'string', 'max:150'],
            'professionnal_graduate' => ['nullable', 'string', 'max:150'],
            'body' => ['nullable', 'string', 'max:150'],
            'official_status' => ['nullable', 'string', 'max:120'],
            'references_of_recrutement' => ['nullable', 'string', 'max:255'],

            'current_grade' => ['nullable', 'string', 'max:80'],
            'current_index' => ['nullable', 'string', 'max:40'],
            'payed_grade' => ['nullable', 'string', 'max:80'],
            'payed_index' => ['nullable', 'string', 'max:40'],

            'observations' => ['nullable', 'string', 'max:2000'],
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required' => 'Le nom est obligatoire.',
            'prenames.required' => 'Les prénoms sont obligatoires.',
            'birth_date.before' => 'La date de naissance doit être antérieure à aujourd’hui.',
        ];
    }

    public function save(): void
    {
        $data = $this->validate();

        // Normalisation légère du genre
        if (! empty($data['gender'])) {
            $g = mb_strtolower(trim($data['gender']));
            if (in_array($g, ['m', 'masculin'], true) || str_starts_with($g, 'm')) {
                $data['gender'] = 'M';
            } elseif (in_array($g, ['f', 'feminin', 'féminin'], true) || str_starts_with($g, 'f')) {
                $data['gender'] = 'F';
            }
        }

        $this->user->update($data);
        $this->user->refresh();
        $this->fillFromUser();

        $this->notification()->success(
            title: 'Profil mis à jour',
            description: 'Les informations ont été enregistrées avec succès.',
        );

        $this->dispatch('user-profile-updated', userId: $this->user->id);
    }

    public function resetForm(): void
    {
        $this->fillFromUser();
        $this->resetValidation();

        $this->notification()->info(
            title: 'Formulaire réinitialisé',
            description: 'Les valeurs d’origine ont été restaurées.',
        );
    }

    public function render()
    {
         /**@var \App\Models\User */
        $user = auth('tenant')->user();

        return view('livewire.tenants.update-user-profile-page')->layout($user->getDashboardLayout());

    }
}
