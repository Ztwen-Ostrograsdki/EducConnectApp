<?php

namespace App\Livewire\Tenants\Promotions;

use App\Events\DataUpdatedEvent;
use App\Models\Promotion;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title("Creation de promotion")]
class CreatePromotionComponent extends Component
{
    use WireUiActions;

    #[Validate('required|string|max:100')]
    public string $name = '';

    #[Validate('nullable|string|max:20')]
    public ?string $code;

    #[Validate('required|in:primaire,secondaire,superieur')]
    public string $level = 'secondaire';

    #[Validate('required|integer|min:1')]
    public int $order = 1;

    #[Validate('boolean')]
    public bool $is_active = true;

    public string $previewSlug = '';

    public function updatedName(string $value): void
    {
        $this->previewSlug = Str::slug($value);
    }

    public function save(): void
    {
        try {

            $this->validate();

            $slug = Str::slug($this->name);

            $this->validate([
                'name' => [
                    'required',
                    'string',
                    'max:100',
                    'unique:promotions,slug',
                        
                ],
                'code'      => 'nullable|string|max:20',
                'level'     => [
                    'required', Rule::in(config('app.levels'))
                ],
                'order'     => 'required|integer|min:1',
                'is_active' => 'boolean',
            ]);

            $promotion = Promotion::create([
                'uuid'      => (string) Str::uuid(),
                'slug'      => $slug,
                'name'      => $this->name,
                'code'      => $this->code ?? $slug,
                'level'     => $this->level,
                'order'     => $this->order,
                'is_active' => $this->is_active,
            ]);

            if($promotion){

                $this->notification()->success(
                    title: 'Nouvelle Promotion créée',
                    description: "La promotion {$this->name} a été créée avec succès.",
                );

                broadcast(new DataUpdatedEvent(tenant('id')));

                $this->reset();
            }

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Une erreur s\'est produite lors de la création de la promotion',
                description: 'Erreur : ' . cutter($th->getMessage(), 150),
            );
        }

        
    }

    
    public function render()
    {
        return view('livewire.tenants.promotions.create-promotion-component');
    }
}
