<?php

namespace App\Livewire\Tenants\Personnels;

use App\Events\DataUpdatedEvent;
use App\Helpers\Support\TenantStorage;
use App\Models\Personnel;
use App\Models\SchoolYear;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use WireUi\Traits\WireUiActions;

#[Title("Gestion du personnel")]
#[Layout('livewire.layouts.tenant-auth-layout')]
class PersonnelFormComponent extends Component
{
    use WithFileUploads, WireUiActions;

    public ?Personnel $personnel = null;

    public $photo;

    public string $name = '';
    public string $prenames = '';
    public ?string $birth_date = null;
    public ?string $contacts = null;
    public ?string $title = null;
    public ?string $description = null;
    public ?int $school_year_id = null;
    public bool $is_active = true;
    public bool $hidden = false;
    public ?string $gender = null;
    public ?string $grade = null;
    public ?string $since = null;
    public ?string $ended_at = null;

    public function mount(?string $uuid = null): void
    {
        if (!$uuid) {
            return;
        }

        $personnel = Personnel::where('uuid', $uuid)->first();

        if (!$personnel) {
            abort(404);
        }

        $this->personnel = $personnel;

        $this->fill([
            'name'           => $personnel->name,
            'prenames'       => $personnel->prenames,
            'birth_date'     => $personnel->birth_date?->format('Y-m-d'),
            'contacts'       => $personnel->contacts,
            'title'          => $personnel->title,
            'description'    => $personnel->description,
            'school_year_id' => $personnel->school_year_id,
            'is_active'      => $personnel->is_active,
            'hidden'         => $personnel->hidden,
            'gender'         => $personnel->gender,
            'grade'          => $personnel->grade,
            'since'          => $personnel->since?->format('Y-m-d'),
            'ended_at'       => $personnel->ended_at?->format('Y-m-d'),
        ]);
    }

    protected function rules(): array
    {
        return [
            'name'           => ['required', 'string', 'max:100'],
            'prenames'       => ['required', 'string', 'max:150'],
            'birth_date'     => ['nullable', 'date', 'before:today'],
            'contacts'       => ['nullable', 'string', 'max:50'],
            'title'          => ['nullable', 'string', 'max:150'],
            'description'    => ['nullable', 'string', 'max:2000'],
            'school_year_id' => ['nullable', 'exists:school_years,id'],
            'is_active'      => ['boolean'],
            'hidden'         => ['boolean'],
            'gender'         => ['nullable', 'in:M,F'],
            'grade'          => ['nullable', 'string', 'max:100'],
            'since'          => ['nullable', 'date'],
            'ended_at'       => ['nullable', 'date', 'after_or_equal:since'],
            'photo'          => ['nullable', 'image', 'max:2048'],
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'name'           => 'nom',
            'prenames'       => 'prénoms',
            'birth_date'     => 'date de naissance',
            'contacts'       => 'contact',
            'title'          => 'poste',
            'school_year_id' => 'année scolaire',
            'gender'         => 'sexe',
            'grade'          => 'grade',
            'since'          => 'date de prise de fonction',
            'ended_at'       => 'date de fin',
            'photo'          => 'photo',
        ];
    }

    /**
     * ------------------------------------------------------------
     *  ENREGISTREMENT (création ou édition)
     * ------------------------------------------------------------
     */
    public function save(): void
    {
        $this->validate();

        try {
            $data = [
                'name'           => $this->name,
                'prenames'       => $this->prenames,
                'birth_date'     => $this->birth_date,
                'contacts'       => $this->contacts,
                'title'          => $this->title,
                'description'    => $this->description,
                'school_year_id' => $this->school_year_id,
                'is_active'      => $this->is_active,
                'hidden'         => $this->hidden,
                'gender'         => $this->gender,
                'grade'          => $this->grade,
                'since'          => $this->since,
                'ended_at'       => $this->ended_at,
            ];

            if ($this->photo) {

                if ($this->personnel?->profil_photo) {
                    TenantStorage::delete($this->personnel->profil_photo);
                }

                $data['profil_photo'] = TenantStorage::store($this->photo, 'personnels');
            }

            $isCreation = !$this->personnel;

            if ($this->personnel) {
                $done = $this->personnel->update($data);
            } else {
                $this->personnel = Personnel::create($data);
                $done = (bool) $this->personnel->exists;
            }

            if ($done) {

                $this->notification()->send([
                    'icon'        => 'success',
                    'title'       => $isCreation ? 'Personnel ajouté' : 'Personnel mis à jour',
                    'timeout'     => 0,
                    'description' => $isCreation
                        ? "{$this->personnel->getFullName()} a été ajouté(e) au personnel"
                        : "Les informations de {$this->personnel->getFullName()} ont été mises à jour",
                ]);

                $this->reset('photo');

                broadcast(new DataUpdatedEvent(tenant('id')));

                $this->dispatch('PersonnelSaved', personnelUuid: $this->personnel->uuid);

            } else {

                $this->notification()->send([
                    'icon'        => 'error',
                    'title'       => "Échec de l'enregistrement",
                    'timeout'     => 0,
                    'description' => "Une erreur est survenue, veuillez réessayer",
                ]);
            }

        } catch (\Throwable $th) {

            $this->notification()->send([
                'icon'        => 'error',
                'title'       => "Échec de l'enregistrement",
                'timeout'     => 0,
                'description' => "Une erreur est survenue : " . cutter($th->getMessage(), 2000),
            ]);
        }
    }

    /**
     * ------------------------------------------------------------
     *  RETRAIT DE LA PHOTO ACTUELLE (édition uniquement)
     * ------------------------------------------------------------
     */
    public function removePhoto(): void
    {
        if (!$this->personnel?->profil_photo) {
            return;
        }

        $this->dispatch('swal', [
            'title'              => "Retirer la photo de profil ?",
            'text'               => "Cette action supprimera définitivement la photo actuelle de {$this->personnel->getFullName()}.",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, retirer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#ef4444',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToRemovePhoto',
        ]);
    }

    #[On('ConfirmToRemovePhoto')]
    public function OnRemovePhoto(): void
    {
        if (!$this->personnel?->profil_photo) {
            return;
        }

        TenantStorage::delete($this->personnel->profil_photo);

        $done = $this->personnel->update(['profil_photo' => null]);

        if ($done) {

            $this->notification()->send([
                'icon'        => 'success',
                'title'       => 'Photo retirée',
                'timeout'     => 0,
                'description' => "La photo de profil a bien été retirée",
            ]);

            broadcast(new DataUpdatedEvent(tenant('id')));

        } else {

            $this->notification()->send([
                'icon'        => 'error',
                'title'       => "Échec de la suppression",
                'timeout'     => 0,
                'description' => "La photo n'a pas pu être retirée",
            ]);
        }
    }

    #[Computed]
    public function schoolYears()
    {
        return SchoolYear::orderByDesc('min_year')->get();
    }

    #[Computed]
    public function genders()
    {
        return config('app.genders');
    }

    #[Computed]
    public function currentPhotoUrl(): ?string
    {
        return $this->personnel?->profil_photo_url;
    }

    public function render()
    {
        return view('livewire.tenants.personnels.personnel-form-component');
    }
}