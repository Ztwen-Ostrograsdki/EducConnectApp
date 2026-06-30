<?php

namespace App\Livewire\Tenants\Promotions;

use App\Events\DataUpdatedEvent;
use App\Helpers\ClasseHelpers;
use App\Models\Filiar;
use App\Models\Promotion;
use App\Models\Serial;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
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

    public ?int   $filiar_id      = null;
    public ?int   $serial_id      = null;
    public ?string   $suffix      = '';

    public string $previewSlug = '';

    public function updatedName(string $value): void
    {
        $this->previewSlug = Str::slug($value);
    }


    public function updated(string $propertyName)
    {
        if($propertyName == 'filiar_id' && $this->filiar_id){

            $this->serial_id = null;

            $this->suffix = '-' . Filiar::find($this->filiar_id)?->code;

        }
        elseif($propertyName == 'serial_id' && $this->serial_id){

            $this->filiar_id = null;

            $this->suffix = '-' . Serial::find($this->serial_id)?->code;

        }
        elseif(!$this->filiar_id && !$this->serial_id){

            $this->reset('suffix');
        }

        if($propertyName !== 'code'){

            $this->code = ClasseHelpers::getClasseNameFormatted($this->name)['code'] . '' . $this->suffix;
        }
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
                ],
                'code'      => 'nullable|string|max:20',
                'level'     => [
                    'required', Rule::in(config('app.levels'))
                ],
                'order'     => 'required|integer|min:1',
                'is_active' => 'boolean',
                'filiar_id'      => 'nullable|exists:filiars,id',
                'serial_id'      => 'nullable|exists:serials,id',
            ]);

            $exists = Promotion::whereName($this->name)
                                ->when($this->serial_id, function($query){
                                    $query->where('serial_id', $this->serial_id);
                                })
                                ->when($this->filiar_id, function($query){
                                    $query->where('filiar_id', $this->filiar_id);
                                })
                                ->exists();

            if($exists){

                $this->addError('name', "Cette promotion existe déjà!");

                $this->notification()->error(
                    title: 'DUPLICATION DE PROMOTION',
                    description: 'Erreur : La promotion que vous essayez de créer existe déjà!',
                );

                return;
            }

            $promotion = Promotion::create([
                'uuid'      => (string) Str::uuid(),
                'slug'      => $slug,
                'name'      => $this->name,
                'code'      => $this->code ?? $slug,
                'level'     => $this->level,
                'order'     => $this->order,
                'is_active' => $this->is_active,
                'filiar_id' => $this->filiar_id,
                'serial_id' => $this->serial_id,
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


    #[Computed]
    public function serials()
    {
        return Serial::where('is_active', true)->orderBy('name')->get(['id', 'name', 'code']);
    }

    #[Computed]
    public function filiars()
    {
        return Filiar::where('is_active', true)->orderBy('name')->get(['id', 'name', 'code']);
    }

    
    public function render()
    {
        return view('livewire.tenants.promotions.create-promotion-component');
    }
}
