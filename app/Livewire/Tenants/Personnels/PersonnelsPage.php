<?php

namespace App\Livewire\Tenants\Personnels;

use App\Events\DataUpdatedEvent;
use App\Helpers\Support\TenantStorage;
use App\Models\Personnel;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title('Portail | Personnels')]
class PersonnelsPage extends Component
{
    use WireUiActions, WithPagination, WithFileUploads;

    #[Url(as: 'q', history: true)]
    public string $search = '';

    #[Url(as: 'gender', history: true)]
    public string $filterGender = '';

    #[Url(as: 'status', history: true)]
    public string $filterStatus = ''; // active | inactive | all

    #[Url(as: 'visibility', history: true)]
    public string $filterVisibility = ''; // visible | hidden | all

    public string $sortField = 'name';
    public string $sortDirection = 'asc';

    public int $perPage = 12;

    // Delete confirmation
    public bool $showDeleteModal = false;
    public ?int $deletingId = null;
    public string $deletingName = '';

    // Edit photo modal
    public bool $showPhotoModal = false;
    public ?int $photoPersonnelId = null;
    public string $photoPersonnelName = '';
    public $newProfilPhoto = null;

    protected $queryString = [
        'search'            => ['except' => ''],
        'filterGender'      => ['except' => ''],
        'filterStatus'      => ['except' => ''],
        'filterVisibility'  => ['except' => ''],
        'sortField'         => ['except' => 'name'],
        'sortDirection'     => ['except' => 'asc'],
        'perPage'           => ['except' => 12],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterGender(): void
    {
        $this->resetPage();
    }

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    public function updatingFilterVisibility(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    #[Computed]
    public function genders()
    {
        return config('app.genders', [
            'male'   => 'Masculin',
            'female' => 'Féminin',
        ]);
    }

    #[Computed]
    public function personnels()
    {
        $query = Personnel::query();

        // Recherche
        if (trim($this->search) !== '') {
            $term = '%' . trim($this->search) . '%';
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', $term)
                    ->orWhere('prenames', 'like', $term)
                    ->orWhere('title', 'like', $term)
                    ->orWhere('contacts', 'like', $term)
                    ->orWhere('grade', 'like', $term)
                    ->orWhereRaw("CONCAT(name, ' ', prenames) LIKE ?", [$term])
                    ->orWhereRaw("CONCAT(prenames, ' ', name) LIKE ?", [$term]);
            });
        }

        // Genre
        if ($this->filterGender !== '') {
            $query->where('gender', $this->filterGender);
        }

        // Statut actif
        if ($this->filterStatus === 'active') {
            $query->where('is_active', true);
        } elseif ($this->filterStatus === 'inactive') {
            $query->where('is_active', false);
        }

        // Visibilité
        if ($this->filterVisibility === 'visible') {
            $query->where('hidden', false);
        } elseif ($this->filterVisibility === 'hidden') {
            $query->where('hidden', true);
        }

        // Tri
        $allowed = ['name', 'prenames', 'title', 'grade', 'since', 'created_at'];
        $field = in_array($this->sortField, $allowed) ? $this->sortField : 'name';
        $direction = $this->sortDirection === 'desc' ? 'desc' : 'asc';

        $query->orderBy($field, $direction);

        if ($field !== 'name') {
            $query->orderBy('name', 'asc');
        }

        return $query->paginate($this->perPage);
    }

    public function confirmDelete(int $id): void
    {
        $personnel = Personnel::find($id);

        if (! $personnel) {
            $this->notification()->error(
                title: 'Erreur',
                description: 'Personnel introuvable.'
            );
            return;
        }

        $this->deletingId   = $id;
        $this->deletingName = $personnel->full_name;
        $this->showDeleteModal = true;
    }

    public function cancelDelete(): void
    {
        $this->reset('showDeleteModal', 'deletingId', 'deletingName');
    }

    public function deletePersonnel(): void
    {
        if (! $this->deletingId) {
            return;
        }

        $personnel = Personnel::find($this->deletingId);

        if (! $personnel) {
            $this->notification()->error(
                title: 'Erreur',
                description: 'Personnel introuvable.'
            );
            $this->cancelDelete();
            return;
        }

        // Supprime la photo si présente
        if ($personnel->profil_photo) {
            try {
                TenantStorage::delete(
                    $personnel->profil_photo
                );
            } catch (\Throwable $e) {

                $name = $personnel->full_name;

                $this->notification()->error(
                    title: "ECHEC SUPPRESSION DU PERSONNEL  « {$name} »",
                    description: "Une erreure s'est produite : " . cutter($e->getMessage(), 2000)
                );

                return;
            }
        }

        $name = $personnel->full_name;
        
        $personnel->delete();

        $this->cancelDelete();

        $this->notification()->success(
            title: 'Supprimé',
            description: "Le personnel « {$name} » a été supprimé."
        );

        broadcast(new DataUpdatedEvent(tenant('id')));
    }

    public function toggleHidden(int $id): void
    {
        $personnel = Personnel::find($id);

        if (! $personnel) {
            $this->notification()->error(
                title: 'Erreur',
                description: 'Personnel introuvable.'
            );
            return;
        }

        $personnel->hidden = ! $personnel->hidden;
        $personnel->save();

        $status = $personnel->hidden ? 'masqué' : 'visible';

        $this->notification()->success(
            title: 'Visibilité mise à jour',
            description: "« {$personnel->full_name} » est maintenant {$status}."
        );

        broadcast(new DataUpdatedEvent(tenant('id')));
    }

    public function toggleActive(int $id): void
    {
        $personnel = Personnel::find($id);

        if (! $personnel) {
            $this->notification()->error(
                title: 'Erreur',
                description: 'Personnel introuvable.'
            );
            return;
        }

        $personnel->is_active = ! $personnel->is_active;
        $personnel->save();

        $status = $personnel->is_active ? 'actif' : 'inactif';

        $this->notification()->success(
            title: 'Statut mis à jour',
            description: "« {$personnel->full_name} » est maintenant {$status}."
        );

        broadcast(new DataUpdatedEvent(tenant('id')));
    }

    public function openPhotoModal(int $id): void
    {
        $personnel = Personnel::find($id);

        if (! $personnel) {
            $this->notification()->error(
                title: 'Erreur',
                description: 'Personnel introuvable.'
            );
            return;
        }

        $this->photoPersonnelId   = $id;
        $this->photoPersonnelName = $personnel->full_name;
        $this->newProfilPhoto     = null;
        $this->showPhotoModal     = true;
        $this->resetErrorBag('newProfilPhoto');
    }

    public function closePhotoModal(): void
    {
        $this->reset('showPhotoModal', 'photoPersonnelId', 'photoPersonnelName', 'newProfilPhoto');
        $this->resetErrorBag('newProfilPhoto');
    }

    public function updatePhoto()
    {
        $this->validate([
            'newProfilPhoto' => 'required|image|max:2048',
        ], [
            'newProfilPhoto.required' => 'Veuillez sélectionner une image.',
            'newProfilPhoto.image'    => 'Le fichier doit être une image.',
            'newProfilPhoto.max'      => 'L\'image ne doit pas dépasser 2 Mo.',
        ]);

        $personnel = Personnel::find($this->photoPersonnelId);

        if (! $personnel) {
            $this->notification()->error(
                title: 'Erreur',
                description: 'Personnel introuvable.'
            );
            $this->closePhotoModal();
            return;
        }

        $model = $personnel;

        try {
            if($model->profil_photo){

                TenantStorage::delete(
                    $model->profil_photo
                );
            }
            
        } catch (\Throwable $th) {
            
            return $this->notification()->error(
                title: 'ECHEC DE LA MISE A JOUR DE LA PHOTO',
                description: "Une erreur s'est produite : " . cutter($th->getMessage(), 2000),
            );
        }

        $path = TenantStorage::store(
            $this->newProfilPhoto,
            'profiles'
        );

        $done = $model->update([
            'profil_photo' => $path,
        ]);

        if($done){

            $this->closePhotoModal();

            $this->notification()->success(
                title: 'Photo mise à jour',
                description: "La photo de « {$personnel->full_name} » a été enregistrée."
            );

            broadcast(new DataUpdatedEvent(tenant('id')));

        }else{

            $this->notification()->send([
                'icon'        => 'error',
                'title'       => "La mise à jour échouée",
                'timeout' => 0,
                'description' => "La photo de profil n'a pas été mise à jour",
            ]);
        }
    }

    public function removePhoto(int $id)
    {
        $personnel = Personnel::find($id);

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

            if($done){

                $this->notification()->send([
                    'icon'        => 'success',
                    'title'       => "Mise à jour de la photo réussie",
                    'timeout' => 0,
                    'description' => "La photo de profil a bien été retirée",
                ]);

                broadcast(new DataUpdatedEvent(tenant('id')));


            }else{

                $this->notification()->send([
                    'icon'        => 'error',
                    'title'       => "La suppression de La photo a échoué",
                    'timeout' => 0,
                    'description' => "La photo de profil n'a pas été mise à jour",
                ]);
            }
        } catch (\Throwable $e) {
            return $this->notification()->error(
                title: 'ECHEC DE LA MISE A JOUR DE LA PHOTO',
                description: "Une erreur s'est produite : " . cutter($e->getMessage(), 2000),
            );
        }

        $personnel->update(['profil_photo' => null]);

        $this->notification()->success(
            title: 'Photo retirée',
            description: "La photo de « {$personnel->full_name} » a été supprimée."
        );
    }

    public function clearFilters(): void
    {
        $this->reset([
            'search',
            'filterGender',
            'filterStatus',
            'filterVisibility',
            'sortField',
            'sortDirection',
        ]);
        $this->sortField = 'name';
        $this->sortDirection = 'asc';
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.tenants.personnels.personnels-page');
    }
}
