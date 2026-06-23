<?php

namespace App\Livewire\Tenants\Filiars;

use App\Events\DataUpdatedEvent;
use App\Models\Filiar;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title("Edition d'une filière")]
class ManageFiliarComponent extends Component
{

    use WireUiActions;

    public Filiar $filiar;

    public string $name        = '';
    public string $code        = '';
    public string $description = '';
    public bool   $is_active   = true;
    public string $previewSlug = '';
    public string $filiar_slug = '';

    public function mount(string $filiar_slug): void
    {

        if(!$filiar_slug) abort(404);

        $this->filiar_slug = $filiar_slug;

        $filiar = Filiar::firstWhere('slug', $filiar_slug);

        if(!$filiar) abort(404);

        $this->filiar      = $filiar;
        $this->name        = $filiar->name;
        $this->code        = $filiar->code ?? '';
        $this->description = $filiar->description ?? '';
        $this->is_active   = $filiar->is_active;
        $this->previewSlug = $filiar->slug;
    }

    public function updatedName(string $value): void
    {
        $this->previewSlug = Str::slug($value);
    }

    public function save(): void
    {
        try {
            $this->validate([
                'name' => [
                    'required', 'string', 'max:100',
                    Rule::unique('filiars', 'name')
                        ->ignore($this->filiar->id)
                        ->whereNull('deleted_at'),
                ],
                'code'        => 'nullable|string|max:20',
                'description' => 'nullable|string|max:255',
                'is_active'   => 'boolean',
            ]);

            $done = $this->filiar->update([
                'slug'        => Str::slug($this->name),
                'name'        => $this->name,
                'code'        => $this->code ?: null,
                'description' => $this->description ?: null,
                'is_active'   => $this->is_active,
            ]);

            if($done){

                $this->notification()->success(
                    title: 'Nouvelle Filière créée',
                    description: "La filière {$this->name} a été mise à jour avec succès.",
                );

                broadcast(new DataUpdatedEvent(tenant('id')));

                $this->redirectRoute('tenant.filiar.profil', ['filiar_slug' => $this->filiar_slug]);

            }

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Une erreur s\'est produite lors de la mise à jour de la filière',
                description: 'Erreur : ' . cutter($th->getMessage(), 150),
            );
        }
    }


    public function render()
    {
        return view('livewire.tenants.filiars.manage-filiar-component');
    }
}
