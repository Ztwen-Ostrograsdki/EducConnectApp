<?php

namespace App\Livewire\Tenants\Modules;

use App\Livewire\Tenants\ActionsTraits\ModuleActionsTrait;
use App\Models\ModuleSkills;
use App\Models\Skills;
use App\Models\Subject;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title('Modules & Compétences')]
class ManageModuleSkillsComponent extends Component
{
    use ModuleActionsTrait;
    use WireUiActions;
    use WithPagination;

    public ?int $selectedModuleId = null;

    public string $searchModules = '';
    public string $searchSkills = '';
    public string $searchAvailable = '';

    // Pour lier rapidement
    public ?int $skillToLinkId = null;
    public ?string $linkCode = null;
    public ?string $linkWeight = null;
    public ?int $linkCoef = null;

    protected $queryString = [
        'selectedModuleId' => ['except' => null, 'as' => 'module'],
    ];

    public function updatedSelectedModuleId(): void
    {
        $this->skillToLinkId = null;
        $this->linkCode = null;
        $this->linkWeight = null;
        $this->linkCoef = null;
        $this->searchAvailable = '';
        $this->refreshModuleSkillsList();
    }

    public function updatedSearchModules(): void
    {
        //
    }

    #[Computed]
    public function modules()
    {
        return Subject::query()
            ->module()
            ->active()
            ->when($this->searchModules, fn ($q) => $q->where(function ($q) {
                $q->where('name', 'like', '%' . $this->searchModules . '%')
                    ->orWhere('code', 'like', '%' . $this->searchModules . '%');
            }))
            ->withCount('moduleSkills')
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function selectedModule(): ?Subject
    {
        if (! $this->selectedModuleId) {
            return null;
        }

        return Subject::query()
            ->module()
            ->where('id', $this->selectedModuleId)
            ->first();
    }

    #[Computed]
    public function linkedSkills()
    {
        if (! $this->selectedModuleId) {
            return collect();
        }

        return ModuleSkills::query()
            ->with(['skill'])
            ->where('subject_id', $this->selectedModuleId)
            ->when($this->searchSkills, function ($q) {
                $q->whereHas('skill', fn ($sq) =>
                    $sq->where('content', 'like', '%' . $this->searchSkills . '%')
                        ->orWhere('code', 'like', '%' . $this->searchSkills . '%')
                );
            })
            ->orderBy('id')
            ->get();
    }

    #[Computed]
    public function availableSkills()
    {
        if (! $this->selectedModuleId) {
            return collect();
        }

        $linkedIds = ModuleSkills::query()
            ->where('subject_id', $this->selectedModuleId)
            ->pluck('skill_id');

        return Skills::query()
            ->active()
            ->whereNotIn('id', $linkedIds)
            ->when($this->searchAvailable, fn ($q) =>
                $q->where('content', 'like', '%' . $this->searchAvailable . '%')
                    ->orWhere('code', 'like', '%' . $this->searchAvailable . '%')
            )
            ->orderBy('content')
            ->limit(50)
            ->get();
    }

    public function selectModule(int $moduleId): void
    {
        $this->selectedModuleId = $moduleId;
        $this->updatedSelectedModuleId();
    }

    public function linkSelectedSkill(): void
    {
        if (! $this->selectedModuleId || ! $this->skillToLinkId) {
            $this->notification()->warning(
                title: 'Sélection incomplète',
                description: 'Choisissez un module et une compétence à lier.',
            );

            return;
        }

        $this->linkSkillToModule(
            $this->selectedModuleId,
            $this->skillToLinkId,
            [
                'code'   => $this->linkCode,
                'weight' => $this->linkWeight,
                'coef'   => $this->linkCoef,
            ]
        );

        $this->skillToLinkId = null;
        $this->linkCode = null;
        $this->linkWeight = null;
        $this->linkCoef = null;
        $this->searchAvailable = '';
    }

    public function quickLink(int $skillId): void
    {
        if (! $this->selectedModuleId) {
            return;
        }

        $this->linkSkillToModule($this->selectedModuleId, $skillId);
        $this->searchAvailable = '';
    }

    public function render()
    {
        return view('livewire.tenants.modules.manage-module-skills-component');
    }
}
