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
#[Title("Edition de matière")]
class ManageSubjectComponent extends Component
{
    use WireUiActions;

    public Subject $subject;

    public string $name        = '';
    public string $code        = '';
    public string $description = '';
    public string $type        = 'scientifique';
    public string $level       = 'secondaire';
    public bool   $is_active   = true;
    public string $previewSlug = '';

    public function mount(Subject $subject): void
    {
        $this->subject     = $subject;
        $this->name        = $subject->name;
        $this->code        = $subject->code ?? '';
        $this->description = $subject->description ?? '';
        $this->type        = $subject->type;
        $this->level       = $subject->level;
        $this->is_active   = $subject->is_active;
        $this->previewSlug = $subject->slug;
    }

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
                    Rule::unique('subjects', 'name')
                        ->ignore($this->subject->id)
                        ->whereNull('deleted_at'),
                ],
                'code'        => 'nullable|string|max:20',
                'description' => 'nullable|string|max:255',
                'type'        => ['required', Rule::in($validTypes)],
                'level'       => 'required|in:primaire,secondaire,superieur',
                'is_active'   => 'boolean',
            ]);

            $done = $this->subject->update([
                'slug'        => Str::slug($this->name),
                'name'        => $this->name,
                'code'        => $this->code ?: null,
                'description' => $this->description ?: null,
                'type'        => $this->type,
                'level'       => $this->level,
                'is_active'   => $this->is_active,
            ]);


            if($done){

                $this->notification()->success(
                    title: 'Mise à jour de la matière',
                    description: "La matière {$this->name} a été mise à jour avec succès.",
                );

                broadcast(new DataUpdatedEvent(tenant('id')));

                $this->redirect(route('tenant.subject.profil', ['subject_slug' => $this->subject_slug]), navigate: true);

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
        return view('livewire.tenants.subjects.manage-subject-component',['subjectTypes' => config('app.subject_types'),]);
    }
}
