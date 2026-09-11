<?php

namespace App\Livewire\Tenants\Galleries;

use App\Events\DataUpdatedEvent;
use App\Helpers\Support\TenantStorage;
use App\Jobs\JobToCreateGalleryImages;
use App\Models\Gallery;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use WireUi\Traits\WireUiActions;

#[Title("Ajouter des images à la galerie")]
#[Layout('livewire.layouts.tenant-auth-layout')]
class CreateGalleryComponent extends Component
{
    use WithFileUploads, WireUiActions;

    /** @var array<int, array{image: mixed, title: string, description: string}> */
    public array $items = [];

    public function mount(): void
    {

         /** @var \App\Models\User $user **/
        $user = auth('tenant')->user();

        // Seul le directeur peut ajouter des images
        if (! $user?->hasRole('directeur')) {
            abort(403, 'Seul le directeur peut ajouter des images à la galerie.');
        }

        $this->addItem();
    }

    public function addItem(): void
    {
        $this->items[] = [
            'image'       => null,
            'title'       => '',
            'description' => '',
        ];
    }

    public function removeItem(int $index): void
    {
        if (count($this->items) <= 1) {
            return;
        }

        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function save(): void
    {
        /** @var \App\Models\User $user **/
        $user = auth('tenant')->user();

        if (! $user?->hasRole('directeur')) {
            $this->notification()->error(title: "Accès refusé", description: "Seul le directeur peut ajouter des images.");
            return;
        }

        $this->validate([
            'items'                 => ['required', 'array', 'min:1'],
            'items.*.image'         => ['required', 'image', 'max:5120'], // 5 Mo
            'items.*.title'         => ['nullable', 'string', 'max:255'],
            'items.*.description'   => ['nullable', 'string', 'max:2000'],
        ], [
            'items.*.image.required' => 'Une image est obligatoire pour chaque élément.',
            'items.*.image.image'    => 'Le fichier doit être une image.',
            'items.*.image.max'      => 'L\'image ne doit pas dépasser 5 Mo.',
        ]);

        $creatorId = $user->id;
        $tenantId  = tenant('id');
        $prepared  = [];

        try {
            foreach ($this->items as $item) {
                
                $path = TenantStorage::store($item['image'], 'galleries');

                $prepared[] = [
                    'path'        => $path,
                    'title'       => $item['title'] ?: null,
                    'description' => $item['description'] ?: null,
                ];
            }

            // Si plus d'une image → Job
            if (count($prepared) > 1) {
                JobToCreateGalleryImages::dispatch(
                    $tenantId,
                    $prepared,
                    $creatorId
                );

                $this->notification()->success(
                    title: "Traitement en cours",
                    description: count($prepared) . " image(s) sont en cours d'enregistrement. Vous serez notifié une fois terminé.",
                );
            } else {
                // Une seule image → création synchrone
                $data = $prepared[0];

                Gallery::create([
                    'uuid'        => (string) Str::uuid(),
                    'path'        => $data['path'],
                    'creator'     => $creatorId,
                    'title'       => $data['title'],
                    'description' => $data['description'],
                    'hidden'      => false,
                ]);

                $this->notification()->success(
                    title: "Image ajoutée",
                    description: "L'image a été ajoutée à la galerie avec succès.",
                );

                broadcast(new DataUpdatedEvent($tenantId));
            }

            $this->reset('items');
            $this->addItem();

        } catch (\Throwable $th) {
            // Nettoyage des fichiers déjà stockés en cas d'erreur
            foreach ($prepared as $p) {
                try {
                    TenantStorage::delete($p['path']);
                } catch (\Throwable) {}
            }

            $this->notification()->error(
                title: "Erreur",
                description: cutter($th->getMessage(), 2000),
            );
        }
    }

    public function render()
    {
        return view('livewire.tenants.galleries.create-gallery-component');
    }
}
