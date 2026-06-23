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
#[Title("Creation d'une filière")]
class CreateFiliarComponent extends Component
{
    use WireUiActions;


    public string $name        = '';
    public string $code        = '';
    public string $description = '';
    public bool   $is_active   = true;
    public string $previewSlug = '';

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
                    Rule::unique('filiars', 'name')->whereNull('deleted_at'),
                ],
                'code'        => 'nullable|string|max:20',
                'description' => 'nullable|string|max:255',
                'is_active'   => 'boolean',
            ]);

            $filiar = Filiar::create([
                'uuid'        => (string) Str::uuid(),
                'slug'        => Str::slug($this->name),
                'name'        => $this->name,
                'code'        => $this->code ?: null,
                'description' => $this->description ?: null,
                'is_active'   => $this->is_active,
            ]);


            if($filiar){

                $this->notification()->success(
                    title: 'Nouvelle Filière créée',
                    description: "La filière {$this->name} a été créée avec succès.",
                );


                broadcast(new DataUpdatedEvent(tenant('id')));

                $this->reset();
            }
            

        } catch (\Throwable $th) {

            $this->notification()->error(
                title: 'Une erreur s\'est produite lors de la création de la filière',
                description: 'Erreur : ' . cutter($th->getMessage(), 150),
            );
        }
        
        
    }
    
    public function render()
    {
        return view('livewire.tenants.filiars.create-filiar-component');
    }
}
