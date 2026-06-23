<?php

namespace App\Livewire\Tenants\Serials;

use App\Events\DataUpdatedEvent;
use App\Models\Serial;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title("Création d'une série")]
class CreateSerialComponent extends Component
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
                    Rule::unique('serials', 'name'),
                ],
                'code'        => 'nullable|string|max:20',
                'description' => 'nullable|string|max:255',
                'is_active'   => 'boolean',
            ]);

            $serial = Serial::create([
                'uuid'        => (string) Str::uuid(),
                'slug'        => Str::slug($this->name),
                'name'        => $this->name,
                'code'        => $this->code ?? Str::slug($this->name),
                'description' => $this->description ?: null,
                'is_active'   => $this->is_active,
            ]);

            if($serial){

                $this->notification()->success(
                    title: 'Nouvelle série créée',
                    description: "La série {$this->name} a été créée avec succès.",
                );

                broadcast(new DataUpdatedEvent(tenant('id')));

                $this->reset();
            }

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Une erreur s\'est produite lors de la création de la série',
                description: 'Erreur : ' . cutter($th->getMessage(), 150),
            );
        }
    }

    
    public function render()
    {
        return view('livewire.tenants.serials.create-serial-component');
    }
}
