<?php

namespace App\Livewire\Tenants\Promotions;

use App\Models\Promotion;
use Illuminate\Support\Str;
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

    public function mount(string $promotion_slug)
    {
        if(!$promotion_slug) return abort(404);

        $this->promotion_slug  = $promotion_slug;

        $promotion = Promotion::whereSlug($promotion_slug)?->first();

        if(!$promotion) return abort(404);

        $this->promotion       = $promotion;
        $this->name       = $promotion->name;
        $this->code       = $promotion->code ?? '';
        $this->level      = $promotion->level;
        $this->order      = $promotion->order;
        $this->is_active  = $promotion->is_active;
        $this->previewSlug = $promotion->slug;
    }

    public function updatedName(string $value): void
    {
        $this->previewSlug = Str::slug($value);
    }

    public function save(): void
    {
        try {
            $this->validate([
                'name'      => "required|string|max:100|unique:promotions,slug," . Str::slug($this->name) . ",slug,deleted_at,NULL,id,{$this->promotion->id}",
                'code'      => 'nullable|string|max:20',
                'level'     => 'required|in:primaire,secondaire,superieur',
                'order'     => 'required|integer|min:1',
                'is_active' => 'boolean',
            ]);

            $this->promotion->update([
                'slug'      => Str::slug($this->name),
                'name'      => $this->name,
                'code'      => $this->code ?: null,
                'level'     => $this->level,
                'order'     => $this->order,
                'is_active' => $this->is_active,
            ]);

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
    
    public function render()
    {
        return view('livewire.tenants.promotions.manage-promotion-component');
    }
}
