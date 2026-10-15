<?php

namespace App\Livewire\Tenants;

use App\Livewire\Tenants\ActionsTraits\DocumentActionsTrait;
use App\Models\GeneratedDocument;
use App\Models\SchoolYear;
use App\Models\Teacher;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title('Documents téléchargeables')]
class UserDownloadableDocumentsPage extends Component
{
    use WireUiActions, WithPagination, DocumentActionsTrait;

    public string $search = '';

    /**
     * all | stats | timeplans | students | classes | ranking
     */
    #[Url]
    public string $scope = 'all';

    public string $pageTitle = 'Documents téléchargeables';

    public int $counter = 0;

    /** Types de documents visibles sur cette page */
    public const DOCUMENT_TYPES = [
        'moyenne_stats_list',
        'time_plan',
        'student_list',
        'classe_list',
        'marks_ranking_list',
    ];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedScope(): void
    {
        $this->resetPage();
    }

    #[On('DataUpdatedEventLiveEvent')]
    #[On('TimePlansPDFCompletedSuccessfullyLiveEvent')]
    #[On('StudentsPDFCompletedSuccessfullyLiveEvent')]
    #[On('ClassesPDFCompletedSuccessfullyLiveEvent')]
    #[On('MarksRankingPDFCompletedSuccessfullyLiveEvent')]
    #[On('MoyenneStatsPDFCompletedSuccessfullyLiveEvent')]
    public function reloaddata(): void
    {
        $this->counter++;
        $this->refreshDocumentsList();
        unset($this->teacherId);
    }

    #[Computed]
    public function activeYear(): ?SchoolYear
    {
        return SchoolYear::current()->first();
    }

    /**
     * Identifiant Teacher lié à l’utilisateur connecté (si disponible).
     */
    #[Computed]
    public function teacherId(): ?int
    {
        $user = auth('tenant')->user();

        if (! $user) {
            return null;
        }

        if (method_exists($user, 'teacher') && $user->teacher) {
            return (int) $user->teacher->id;
        }

        if (isset($user->teacher_id) && $user->teacher_id) {
            return (int) $user->teacher_id;
        }

        $id = Teacher::query()
            ->where('user_id', $user->id)
            ->value('id');

        return $id ? (int) $id : null;
    }

    #[Computed]
    public function documents()
    {
        $teacherId = $this->teacherId;
        $userId    = auth('tenant')->id();

        return GeneratedDocument::query()
            ->whereIn('type', self::DOCUMENT_TYPES)
            ->when(
                $this->activeYear,
                fn ($q) => $q->where('school_year_id', $this->activeYear->id)
            )
            ->where(function ($q) use ($teacherId, $userId) {
                if ($teacherId) {
                    $q->where('for_teacher_id', $teacherId);
                }

                $q->orWhere(function ($inner) {
                    $inner->where('for_teachers', true)
                        ->where('downloadable_by_others', true);
                });

                $q->orWhere(function ($inner) {
                    $inner->where('downloadable_by_others', true);
                        
                });

                if ($userId) {
                    $q->orWhere('user_id', $userId);
                }
            })
            ->when(
                $this->search !== '',
                fn ($q) => $q->where('filename', 'like', '%' . $this->search . '%')
            )
            ->when(
                $this->scope === 'stats',
                fn ($q) => $q->where('type', 'moyenne_stats_list')
            )
            ->when(
                $this->scope === 'timeplans',
                fn ($q) => $q->where('type', 'time_plan')
            )
            ->when(
                $this->scope === 'students',
                fn ($q) => $q->where('type', 'student_list')
            )
            ->when(
                $this->scope === 'classes',
                fn ($q) => $q->where('type', 'classe_list')
            )
            ->when(
                $this->scope === 'ranking',
                fn ($q) => $q->where('type', 'marks_ranking_list')
            )
            ->latest()
            ->paginate(9);
    }

    /**
     * Libellé et style selon le type de document.
     *
     * @return array{label: string, icon: string, color: string}
     */
    public static function typeMeta(string $type): array
    {
        return match ($type) {
            'moyenne_stats_list' => [
                'label' => 'Statistiques',
                'icon'  => 'bar-chart-3',
                'color' => 'emerald',
            ],
            'time_plan' => [
                'label' => 'Emploi du temps',
                'icon'  => 'calendar-range',
                'color' => 'violet',
            ],
            'student_list' => [
                'label' => 'Liste apprenants',
                'icon'  => 'users',
                'color' => 'sky',
            ],
            'classe_list' => [
                'label' => 'Liste classes',
                'icon'  => 'school',
                'color' => 'amber',
            ],
            'marks_ranking_list' => [
                'label' => 'Meilleurs élèves',
                'icon'  => 'trophy',
                'color' => 'orange',
            ],
            default => [
                'label' => 'Document',
                'icon'  => 'file-text',
                'color' => 'slate',
            ],
        };
    }

    /**
     * Téléchargement : accès élargi (partage enseignants / publics / propriétaire).
     */
    protected function findDocumentForDownload(int $documentId): ?GeneratedDocument
    {
        $teacherId = $this->teacherId;
        $userId    = auth('tenant')->id();

        return GeneratedDocument::query()
            ->where('id', $documentId)
            ->whereIn('type', self::DOCUMENT_TYPES)
            ->where(function ($q) use ($teacherId, $userId) {
                if ($teacherId) {
                    $q->where('for_teacher_id', $teacherId);
                }

                $q->orWhere(function ($inner) {
                    $inner->where('for_teachers', true)
                        ->where('downloadable_by_others', true);
                });

                $q->orWhere(function ($inner) {
                    $inner->where('downloadable_by_others', true)
                        ->whereIn('type', [
                            'moyenne_stats_list',
                            'student_list',
                            'classe_list',
                            'marks_ranking_list',
                        ]);
                });

                if ($userId) {
                    $q->orWhere('user_id', $userId);
                }
            })
            ->first();
    }

    public function render()
    {
        return view('livewire.tenants.user-downloadable-documents-page');
    }
}
