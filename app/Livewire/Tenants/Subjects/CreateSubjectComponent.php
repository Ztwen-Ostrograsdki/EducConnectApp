<?php

namespace App\Livewire\Tenants\Subjects;

use App\Events\DataUpdatedEvent;
use App\Models\Subject;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title("Création d'une matière")]
class CreateSubjectComponent extends Component
{
    use WireUiActions;


    public string $name        = '';
    public string $code        = '';
    public string $description = '';
    public string $type        = 'scientifique';
    public string $level       = 'secondaire';
    public bool   $is_active   = true;
    public string $previewSlug = '';

    public function updatedName(string $value): void
    {
        $this->previewSlug = Str::slug($value);
    }

    public function save(): void
    {
        $validTypes = array_keys(config('app.subject_types'));

        try {
            $this->validate([
                'name' => [
                    'required', 'string', 'max:100',
                    Rule::unique('subjects', 'name')->whereNull('deleted_at'),
                ],
                'code'        => 'nullable|string|max:20',
                'description' => 'nullable|string|max:255',
                'type'        => ['required', Rule::in($validTypes)],
                'level'       => 'required|in:primaire,secondaire,superieur',
                'is_active'   => 'boolean',
            ]);

            $Subject = Subject::create([
                'uuid'        => (string) Str::uuid(),
                'slug'        => Str::slug($this->name),
                'name'        => $this->name,
                'code'        => $this->code ?? Str::slug($this->name),
                'description' => $this->description ?: null,
                'type'        => $this->type,
                'level'       => $this->level,
                'is_active'   => $this->is_active,
            ]);

            if($Subject){

                $this->notification()->success(
                    title: 'Nouvelle matière créée',
                    description: "La matière {$this->name} a été créée avec succès.",
                );

                broadcast(new DataUpdatedEvent(tenant('id')));

                $this->reset();


            }
        } catch (\Throwable $th) {

            $this->notification()->error(
                title: 'Une erreur s\'est produite lors de la mise à jour de la matière',
                description: 'Erreur : ' . cutter($th->getMessage(), 150),
            );
        }

    }

    public function render()
    {
        return view('livewire.tenants.subjects.create-subject-component', [
            'subjectTypes' => config('app.subject_types'),
        ]);
    }
}
