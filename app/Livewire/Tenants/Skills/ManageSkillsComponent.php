<?php

namespace App\Livewire\Tenants\Skills;

use App\Livewire\Tenants\ActionsTraits\SkillActionsTrait;
use App\Models\Filiar;
use App\Models\Serial;
use App\Models\Skills;
use App\Models\Subject;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title('Gestion des compétences')]
class ManageSkillsComponent extends Component
{
    use SkillActionsTrait;
    use WireUiActions;
    use WithPagination;

    public string $search = '';
    public string $filterActive = 'all'; // all | active | inactive

    // Formulaire création / édition
    public bool $showForm = false;
    public ?int $editingId = null;

    public string $content = '';
    public ?string $code = null;
    public ?string $weight = null;
    public ?int $coef = null;
    public bool $is_active = true;
    public ?int $filiar_id = null;
    public ?int $serial_id = null;
    public ?int $subject_id = null;

    public array $selected = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'filterActive' => ['except' => 'all'],
    ];

    protected function rules(): array
    {
        return [
            'content'    => ['required', 'string', 'min:3'],
            'code'       => ['nullable', 'string', 'max:50'],
            'weight'     => ['nullable', 'string', 'max:50'],
            'coef'       => ['nullable', 'integer', 'min:0'],
            'is_active'  => ['boolean'],
            'filiar_id'  => ['nullable', 'exists:filiars,id'],
            'serial_id'  => ['nullable', 'exists:serials,id'],
            'subject_id' => ['nullable', 'exists:subjects,id'],
        ];
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterActive(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function skills()
    {
        return Skills::query()
            ->with(['filiar', 'serial', 'subject'])
            ->search($this->search)
            ->when($this->filterActive === 'active', fn ($q) => $q->where('is_active', true))
            ->when($this->filterActive === 'inactive', fn ($q) => $q->where('is_active', false))
            ->latest()
            ->paginate(12);
    }

    #[Computed]
    public function filiars()
    {
        return Filiar::query()->orderBy('name')->get(['id', 'name']);
    }

    #[Computed]
    public function serials()
    {
        return Serial::query()->orderBy('name')->get(['id', 'name']);
    }

    #[Computed]
    public function subjects()
    {
        return Subject::query()->orderBy('name')->get(['id', 'name', 'is_module']);
    }

    public function openCreateForm(): void
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editingId = null;
    }

    public function openEditForm(int $skillId): void
    {
        $skill = $this->findSkillForManage($skillId);

        if (! $skill) {
            $this->notification()->error(title: 'Compétence introuvable');

            return;
        }

        $this->editingId  = $skill->id;
        $this->content    = $skill->content ?? '';
        $this->code       = $skill->code;
        $this->weight     = $skill->weight;
        $this->coef       = $skill->coef;
        $this->is_active  = (bool) $skill->is_active;
        $this->filiar_id  = $skill->filiar_id;
        $this->serial_id  = $skill->serial_id;
        $this->subject_id = $skill->subject_id;
        $this->showForm   = true;
    }

    public function saveSkill(): void
    {
        $this->validate();

        $data = [
            'content'    => $this->content,
            'code'       => $this->code,
            'weight'     => $this->weight,
            'coef'       => $this->coef,
            'is_active'  => $this->is_active,
            'filiar_id'  => $this->filiar_id,
            'serial_id'  => $this->serial_id,
            'subject_id' => $this->subject_id,
        ];

        if ($this->editingId) {
            $skill = $this->findSkillForManage($this->editingId);
            if (! $skill) {
                $this->notification()->error(title: 'Compétence introuvable');

                return;
            }
            $skill->update($data);
            $this->notification()->success(title: 'Compétence mise à jour.');
        } else {
            Skills::create($data);
            $this->notification()->success(title: 'Compétence créée.');
        }

        $this->resetForm();
        $this->showForm = false;
        $this->refreshSkillsList();
    }

    public function cancelForm(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    protected function resetForm(): void
    {
        $this->editingId  = null;
        $this->content    = '';
        $this->code       = null;
        $this->weight     = null;
        $this->coef       = null;
        $this->is_active  = true;
        $this->filiar_id  = null;
        $this->serial_id  = null;
        $this->subject_id = null;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.tenants.skills.manage-skills-component');
    }
}
