<?php

namespace App\Livewire\Tenants\Personnels;

use App\Events\DataUpdatedEvent;
use App\Helpers\Support\TenantStorage;
use App\Livewire\Traits\ValidatorTrait;
use App\Models\Personnel;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title("Édition d'un personnel")]
class EditPersonnel extends Component
{
    use WireUiActions, WithFileUploads, ValidatorTrait;

    public Personnel $personnel;

    public string $name = '';
    public string $prenames = '';
    public string $title = '';
    public string $grade = '';
    public string $contacts = '';
    public string $gender = '';
    public string $description = '';
    public ?string $birth_date = null;
    public ?string $since = null;
    public ?string $ended_at = null;
    public bool $is_active = true;
    public bool $hidden = false;

    public $profil_photo = null;
    public int $counter = 0;

    public function mount(Personnel $personnel): void
    {
        $this->personnel = $personnel;

        $this->name        = $personnel->name ?? '';
        $this->prenames    = $personnel->prenames ?? '';
        $this->title       = $personnel->title ?? '';
        $this->grade       = $personnel->grade ?? '';
        $this->contacts    = $personnel->contacts ?? '';
        $this->gender      = $personnel->gender ?? '';
        $this->description = $personnel->description ?? '';
        $this->birth_date  = optional($personnel->birth_date)->format('Y-m-d');
        $this->since       = optional($personnel->since)->format('Y-m-d');
        $this->ended_at    = optional($personnel->ended_at)->format('Y-m-d');
        $this->is_active   = (bool) $personnel->is_active;
        $this->hidden      = (bool) $personnel->hidden;
    }

    protected function rules(): array
    {
        return [
            'name'         => 'required|string|max:255',
            'prenames'     => 'required|string|max:255',
            'title'        => 'required|string|max:255',
            'gender'       => 'required|string|max:20',
            'contacts'     => ['nullable', 'string', 'between:4,50'],
            'birth_date'   => 'nullable|date',
            'since'        => 'nullable|date',
            'ended_at'     => 'nullable|date|after_or_equal:since',
            'grade'        => 'nullable|string|max:100',
            'description'  => 'nullable|string|max:2000',
            'is_active'    => 'boolean',
            'hidden'       => 'boolean',
            'profil_photo' => 'nullable|image|max:2048',
        ];
    }

    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata()
    {
        $this->counter++;
    }

    #[Computed]
    public function genders()
    {
        return config('app.genders');
    }

    public function update()
    {
        $this->validate();

        if (! empty($this->contacts)) {
            $this->validatePhoneNumber();
        }

        // Vérifie doublon nom + prénoms (hors l'enregistrement courant)
        $exists = Personnel::query()
            ->where('id', '!=', $this->personnel->id)
            ->where('name', Str::upper($this->name))
            ->where('prenames', ucwords($this->prenames))
            ->exists();

        if ($exists) {
            $this->notification()->error(
                title: 'Conflit',
                description: 'Un autre personnel porte déjà ce nom et ces prénoms.'
            );
            return;
        }

        $data = [
            'name'        => Str::upper($this->name),
            'prenames'    => ucwords($this->prenames),
            'title'       => $this->title,
            'grade'       => $this->grade ?: null,
            'contacts'    => $this->contacts ?: null,
            'gender'      => $this->gender,
            'birth_date'  => $this->birth_date,
            'since'       => $this->since,
            'ended_at'    => $this->ended_at,
            'description' => $this->description ?: null,
            'is_active'   => $this->is_active,
            'hidden'      => $this->hidden,
        ];

        if ($this->profil_photo) {

            try {
                TenantStorage::delete(
                    $this->personnel->profil_photo
                );
            } catch (\Throwable $th) {
                return $this->notification()->error(
                    title: 'ECHEC DE LA MISE A JOUR DE LA PHOTO',
                    description: "Une erreur s'est produite : " . cutter($th->getMessage(), 2000),
                );
            }

            $path = TenantStorage::store(
                $this->profil_photo,
                'profiles'
            );

            $data['profil_photo'] = $path;
        }

        $this->personnel->update($data);

        $this->notification()->success(
            title: 'Mise à jour réussie',
            description: 'Les informations du personnel ont été enregistrées.'
        );

        broadcast(new DataUpdatedEvent(tenant('id')));

        $this->redirect(route('tenant.personnels.page'), navigate: true);
    }

    public function removePhoto()
    {
        $personnel = $this->personnel;

        if (! $personnel || ! $personnel->profil_photo) {
            return;
        }

        try {
            $model = $personnel;

            TenantStorage::delete(
                $model->profil_photo
            );

            $done = $model->update([
                'profil_photo' => null,
            ]);

            if ($done) {

                $this->notification()->send([
                    'icon'        => 'success',
                    'title'       => 'Mise à jour de la photo réussie',
                    'timeout'     => 0,
                    'description' => 'La photo de profil a bien été retirée',
                ]);

                $this->reset('profil_photo');

                broadcast(new DataUpdatedEvent(tenant('id')));

            } else {

                $this->notification()->send([
                    'icon'        => 'error',
                    'title'       => 'La suppression de La photo a échoué',
                    'timeout'     => 0,
                    'description' => "La photo de profil n'a pas été mise à jour",
                ]);
            }
        } catch (\Throwable $e) {

            return $this->notification()->error(
                title: 'ECHEC DE LA MISE A JOUR DE LA PHOTO',
                description: "Une erreur s'est produite : " . cutter($e->getMessage(), 2000),
            );
        }
    }

    /**
     * Remplit le champ description avec une citation aléatoire
     * depuis config/citation.php
     */
    public function fillRandomCitation(): void
    {
        $citations = config('citations.citations', []);

        if (empty($citations)) {
            $this->notification()->error(
                title: 'Aucune citation',
                description: 'Le fichier de configuration des citations est vide ou introuvable.'
            );
            return;
        }

        $quote = collect($citations)->random();

        $text   = $quote['text'] ?? '';

        $this->description = $text;

    }

    public function render()
    {
        return view('livewire.tenants.personnels.edit-personnel');
    }
}
