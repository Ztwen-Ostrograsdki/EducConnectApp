<?php

namespace App\Livewire\Tenants\TimePlans;

use App\Helpers\Support\TenantStorage;
use App\Models\GeneratedDocument;
use App\Models\SchoolYear;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title('Emplois du temps générés')]
class TimePlanPrintableDocumentsPage extends Component
{
    use WireUiActions, WithPagination;

    public string $search = '';

    /** all | classes | teachers */
    #[Url]
    public string $scope = 'all';

    public string $pageTitle = 'Documents générés — Emplois du temps';

    public int $counter = 0;

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
    public function reloaddata(): void
    {
        $this->counter++;
        unset($this->documents);
    }

    #[Computed]
    public function activeYear(): ?SchoolYear
    {
        return SchoolYear::current()->first();
    }

    #[Computed]
    public function documents()
    {
        return GeneratedDocument::ofType('time_plan')
            ->forUser(auth('tenant')->id())
            ->when(
                $this->activeYear,
                fn ($q) => $q->where('school_year_id', $this->activeYear->id)
            )
            ->when(
                $this->search !== '',
                fn ($q) => $q->where('filename', 'like', '%' . $this->search . '%')
            )
            ->when(
                $this->scope === 'teachers',
                fn ($q) => $q->where('for_teachers', true)
            )
            ->when(
                $this->scope === 'classes',
                fn ($q) => $q->where(function ($inner) {
                    $inner->where('for_teachers', false)
                        ->orWhereNull('for_teachers');
                })
            )
            ->latest()
            ->paginate(9);
    }

    /**
     * Téléchargement direct depuis Livewire 3.
     */
    public function trackDownload(int $documentId)
    {
        $doc = GeneratedDocument::query()
            ->where('id', $documentId)
            ->where('user_id', auth('tenant')->id())
            ->first();

        if (! $doc || ! File::exists($doc->path)) {
            $this->notification()->error(
                title: 'Document introuvable',
                description: 'Le fichier a peut-être déjà été supprimé du serveur.',
            );
            unset($this->documents);

            return;
        }

        $doc->recordDownload();

        unset($this->documents);

        $this->notification()->info(title: 'Téléchargement du document en cours ...');

        return response()->download($doc->path, $doc->filename);
    }

    public function confirmDelete(int $documentId): void
    {
        $this->dispatch('swal', [
            'title'              => 'Supprimer ce document ?',
            'description'        => 'Cette action est irréversible. Le fichier sera définitivement supprimé du serveur.',
            'icon'               => 'question',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, supprimer le doc',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#06b6d4',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToDeleteTimePlanDoc',
            'onConfirmedParams'  => ['documentId' => $documentId],
        ]);
    }

    #[On('ConfirmToDeleteTimePlanDoc')]
    public function deleteDocument(int $documentId): void
    {
        $doc = GeneratedDocument::query()
            ->where('id', $documentId)
            ->where('user_id', auth('tenant')->id())
            ->first();

        if (! $doc) {
            $this->notification()->error(title: 'Document introuvable ou déjà supprimé.');

            return;
        }

        TenantStorage::delete($this->relativePathFromAbsolute($doc->path));

        $doc->delete();

        unset($this->documents);

        if ($this->documents->currentPage() > 1 && $this->documents->isEmpty()) {
            $this->setPage($this->documents->currentPage() - 1);
            unset($this->documents);
        }

        $this->notification()->success(title: 'Document supprimé avec succès.');
    }

    /**
     * TenantStorage::delete() attend un chemin relatif au disque public.
     */
    protected function relativePathFromAbsolute(string $absolutePath): string
    {
        $absolutePath = str_replace('\\', '/', $absolutePath);
        $storageRoot = str_replace('\\', '/', Storage::disk('public')->path(''));

        return ltrim(Str::after($absolutePath, $storageRoot), '/');
    }

    public function render()
    {
        return view('livewire.tenants.timeplans.time-plan-printable-documents-page');
    }
}
