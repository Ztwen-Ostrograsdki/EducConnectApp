<?php

namespace App\Livewire\Tenants\Promotions;

use App\Helpers\ClasseHelpers;
use App\Models\Filiar;
use App\Models\Promotion;
use App\Models\SchoolYear;
use App\Models\Serial;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title("Mise à jour de promotion")]
class ManagePromotionComponent extends Component
{
    use WireUiActions;
    
    public string $promotion_slug;

    public Promotion $promotion;

    public string $name      = '';
    public string $code      = '';
    public string $level     = 'secondaire';
    public int    $order     = 1;
    public bool   $is_active = true;
    public string $previewSlug = '';
    public ?int $school_year_id;
    public string $school_year = '';
    public ?int   $filiar_id      = null;
    public ?int   $serial_id      = null;
    public ?string   $suffix      = '';

    public function mount(string $promotion_slug)
    {
        if(!$promotion_slug) return abort(404);

        $this->promotion_slug  = $promotion_slug;

        $promotion = Promotion::withTrashed()->whereSlug($promotion_slug)?->first();

        $active = SchoolYear::current()->first();
        
        if ($active) {

            $this->school_year_id = $active->id;

            $this->school_year = $active->slug;
        }

        if(!$promotion) return abort(404);

        $this->promotion       = $promotion;
        $this->name       = $promotion->name;
        $this->code       = $promotion->code ?? '';
        $this->level      = $promotion->level;
        $this->order      = $promotion->order;
        $this->is_active  = $promotion->is_active;
        $this->filiar_id  = $promotion->filiar_id;
        $this->serial_id  = $promotion->serial_id;
        $this->previewSlug = $promotion->slug;
    }

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

        $exists = Promotion::whereName($this->name)
                                ->when($this->serial_id, function($query){
                                    $query->where('serial_id', $this->serial_id);
                                })
                                ->when($this->filiar_id, function($query){
                                    $query->where('filiar_id', $this->filiar_id);
                                })
                                ->where('promotions.id', '<>', $this->promotion->id)
                                ->exists();

        if($exists){

            $this->addError('name', "Cette promotion {$this->code} existe déjà!");
        }
    }

    public function save()
    {
        try {
            $slug = Str::slug($this->name);

            $this->validate([
                'name'           => "required|string|max:100",
                'code'           => 'nullable|string|max:20',
                'level'          => 'required|in:primaire,secondaire,superieur',
                'filiar_id'      => 'nullable|exists:filiars,id',
                'serial_id'      => 'nullable|exists:serials,id',
                'order'          => 'required|integer|min:1',
                'is_active'      => 'boolean',
            ]);

            $this->promotion->update([
                'slug'      => $slug,
                'name'      => $this->name,
                'code'      => $this->code ?? $slug,
                'level'     => $this->level,
                'order'     => $this->order,
                'is_active' => $this->is_active,
                'filiar_id' => $this->filiar_id,
                'serial_id' => $this->serial_id,
            ]);

            $exists = Promotion::whereName($this->name)
                                ->when($this->serial_id, function($query){
                                    $query->where('serial_id', $this->serial_id);
                                })
                                ->when($this->filiar_id, function($query){
                                    $query->where('filiar_id', $this->filiar_id);
                                })
                                ->where('promotions.id', '<>', $this->promotion->id)
                                ->exists();

            if($exists){

                $this->addError('name', "Cette promotion existe déjà!");

                $this->notification()->error(
                    title: 'DUPLICATION DE PROMOTION',
                    description: 'Erreur : La promotion que vous essayez de créer existe déjà!',
                );

                return;
            }

            $this->promotion_slug = $slug;

            $this->notification()->success(
                    title: 'Promotion mise à jour',
                    description: "La promotion {$this->name} a été mise à jour avec succès.",
                );

            $this->redirect(route('tenant.promotion.profil', ['promotion_slug' => $this->promotion_slug]), navigate: true); 


        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Une erreur s\'est produite lors de la mise à jour de la promotion',
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
        return view('livewire.tenants.promotions.manage-promotion-component');
    }
}
