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
#[Title("Mise à jour d'une série")]
class ManageSerialComponent extends Component
{
    use WireUiActions;


    public Serial $serial;

    public string $name        = '';
    public string $code        = '';
    public string $description = '';
    public bool   $is_active   = true;
    public string $previewSlug = '';
    public string $serial_slug = '';

    public function mount(string $serial_slug): void
    {

        if(!$serial_slug) abort(404);

        $this->serial_slug  = $serial_slug;

        $serial = Serial::whereSlug($serial_slug)?->first();

        if(!$serial) abort(404);

        $this->serial      = $serial;
        $this->name        = $serial->name;
        $this->code        = $serial->code ?? '';
        $this->description = $serial->description ?? '';
        $this->is_active   = $serial->is_active;
        $this->previewSlug = $serial->slug;
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
                    Rule::unique('serials', 'name')
                        ->ignore($this->serial->id)
                        ->whereNull('deleted_at'),
                ],
                'code'        => 'nullable|string|max:20',
                'description' => 'nullable|string|max:255',
                'is_active'   => 'boolean',
            ]);

            $done = $this->serial->update([
                'slug'        => Str::slug($this->name),
                'name'        => $this->name,
                'code'        => $this->code ?: null,
                'description' => $this->description ?: null,
                'is_active'   => $this->is_active,
            ]);

            if($done){

                $this->notification()->success(
                    title: 'Série mise à joour',
                    description: "La série {$this->name} a été mise à jour avec succès.",
                );

                broadcast(new DataUpdatedEvent(tenant('id')));

                $this->redirect(route('tenant.serial.profil', ['serial_slug' => $this->serial_slug]), navigate: true);
            }

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Une erreur s\'est produite lors de la mise à jour de la série',
                description: 'Erreur : ' . cutter($th->getMessage(), 150),
            );
        }
    }


    public function render()
    {
        return view('livewire.tenants.serials.manage-serial-component');
    }
}
