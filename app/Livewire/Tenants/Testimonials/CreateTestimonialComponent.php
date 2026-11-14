<?php

namespace App\Livewire\Tenants\Testimonials;

use App\Events\DataUpdatedEvent;
use App\Models\Testimonial;
use Illuminate\Support\Str;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Title("Ajouter un témoignage")]
class CreateTestimonialComponent extends Component
{
    use WireUiActions;

    public string $content = '';

    public function save(): void
    {
        $this->validate([
            'content' => ['required', 'string', 'min:10', 'max:2000'],
        ], [
            'content.required' => 'Le contenu du témoignage est obligatoire.',
            'content.min'      => 'Le témoignage doit contenir au moins 10 caractères.',
            'content.max'      => 'Le témoignage ne peut pas dépasser 2000 caractères.',
        ]);



        try {

            /** @var \App\Models\User $user **/
            $user = auth('tenant')->user();

            Testimonial::create([
                'uuid'    => (string) Str::uuid(),
                'user_id' => $user->id,
                'content' => $this->content,
                'hidden'  => $user->hasRole('directeur') ? false : true,
            ]);

            $this->notification()->success(
                title: "Témoignage ajouté",
                description: "Votre témoignage a été enregistré avec succès.",
            );

            broadcast(new DataUpdatedEvent(tenant('id')));

            $this->reset('content');

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: "Erreur",
                description: cutter($th->getMessage(), 2000),
            );
        }
    }

    public function render()
    {
        /** @var \App\Models\User $user **/
        $user = auth('tenant')->user();
        
        $view = view('livewire.tenants.testimonials.create-testimonial-component');

        /** @disregard P1013 `layout()` est ajoutée à View par Livewire (macro) */
        return $view->layout($user->getDashboardLayout());
    }
}
