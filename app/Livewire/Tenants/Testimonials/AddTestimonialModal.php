<?php

namespace App\Livewire\Tenants\Testimonials;

use App\Events\DataUpdatedEvent;
use App\Models\Testimonial;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class AddTestimonialModal extends Component
{
    use WireUiActions;

    public bool $show = false;

    public string $content = '';

    #[On('open-testimonial-modal')]
    public function open(): void
    {
        $this->reset('content');
        $this->resetErrorBag();
        $this->show = true;
    }

    public function close(): void
    {
        $this->reset('content');
        $this->resetErrorBag();
        $this->show = false;
    }

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

            $this->close();

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: "Erreur",
                description: cutter($th->getMessage(), 2000),
            );
        }
    }

    public function render()
    {
        return view('livewire.tenants.testimonials.add-testimonial-modal');
    }
}