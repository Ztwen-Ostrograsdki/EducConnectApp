<?php

namespace App\Livewire\Tenants\Students;

use App\Helpers\Support\TenantStorage;
use App\Models\GeneratedDocument;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title('Gestion des documents générés sur les apprenants')]
class StudentsPrintableDocumentsPage extends Component
{
    use WireUiActions, WithPagination;

    public string $search = '';

    public $counter = 0;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata()
    {
        $this->counter++;
    }

    #[Computed]
    public function documents()
    {
        return GeneratedDocument::ofType('student_list')
            ->forUser(auth('tenant')->id())
            ->when($this->search, fn ($q) =>
                $q->where('filename', 'like', '%' . $this->search . '%')
            )
            ->latest()
            ->paginate(9);
    }

    /**
     * Retourne une réponse de téléchargement de fichier directement depuis
     * l'action Livewire — supporté nativement depuis Livewire 3.
     */
    public function trackDownload(int $documentId)
    {
        $doc = GeneratedDocument::where('id', $documentId)
            ->where('user_id', auth('tenant')->user()->id)
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
            'confirmButtonColor' => '#a855f7',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToDeleteDoc',
            'onConfirmedParams'  => ['documentId' => $documentId],
        ]);

    }

    #[On("ConfirmToDeleteDoc")]
    public function deleteDocument(int $documentId): void
    {
        $doc = GeneratedDocument::where('id', $documentId)
            ->where('user_id', auth('tenant')->user()->id)
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
            unset($this->documents); // <-- indispensable : force le recalcul avec la nouvelle page
        }

        $this->notification()->success(title: 'Document supprimé avec succès.');
    }

    /**
     * TenantStorage::delete() attend un chemin relatif au disque 'public',
     * alors que GeneratedDocument::path stocke un chemin absolu (issu de
     * PDFFactory::outputPath()). On reconvertit ici.
     */
    protected function relativePathFromAbsolute(string $absolutePath): string
    {
        $absolutePath = str_replace('\\', '/', $absolutePath);
        $storageRoot  = str_replace('\\', '/', Storage::disk('public')->path(''));

        return ltrim(Str::after($absolutePath, $storageRoot), '/');
    }

    public function render()
    {
        return view('livewire.tenants.students.students-printable-documents-page');
    }
}