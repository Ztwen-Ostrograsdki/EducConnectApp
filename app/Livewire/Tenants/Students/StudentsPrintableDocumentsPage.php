<?php

namespace App\Livewire\Tenants\Students;

use App\Helpers\Support\TenantStorage;
use App\Models\Classe;
use App\Models\Filiar;
use App\Models\GeneratedDocument;
use App\Models\Promotion;
use App\Models\Serial;
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

    public ?string $targeted = null;
    
    public string $pageTitle = 'Documents générés - Liste Apprenants/Classes/Enseignants/...';



    public ?string $classe_slug = null;
    public ?int $classe_id = null;
    public ?Classe $classe = null;

    public ?string $filiar_slug = null;
    public ?int $filiar_id = null;
    public ?Filiar $filiar = null;

    public ?string $serial_slug = null;
    public ?int $serial_id = null;
    public ?Serial $serial = null;

    public ?string $promotion_slug = null;
    public ?int $promotion_id = null;
    public ?Promotion $promotion = null;

    public ?string $promotionsGrouped = null;

    public $counter = 0;


    public function mount(?string $classe_slug = null, ?string $filiar_slug = null, ?string $promotion_slug = null, ?string $promotionsGrouped = null, ?string $serial_slug = null)
    {
        self::initiator($classe_slug, $filiar_slug, $promotion_slug, $promotionsGrouped, $serial_slug);
    }

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
            ->when($this->classe, function($q){
                $q->where('classe_id', $this->classe->id);
            })
            ->when($this->filiar, function($q){
                $q->where('filiar_id', $this->filiar->id);
            })
            ->when($this->serial, function($q){
                $q->where('serial_id', $this->serial->id);
            })
            ->when($this->promotion, function($q){
                $q->where('promotion_id', $this->promotion->id);
            })
            ->when($this->promotionsGrouped, function($q){
                $q->where('promotionsGrouped', $this->promotionsGrouped);
            })
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



    public function initiator(?string $classe_slug = null, ?string $filiar_slug = null, ?string $promotion_slug = null, ?string $promotionsGrouped = null, ?string $serial_slug = null)
    {
        if($classe_slug){

            $classe = Classe::firstWhere('slug', $classe_slug);

            if(!$classe) return abort(404);

            $this->classe = $classe;

            $this->targeted = 'Classe ' . $classe->name;

        }

        if($filiar_slug){

            $filiar = Filiar::firstWhere('slug', $filiar_slug);

            if(!$filiar) return abort(404);

            $this->filiar = $filiar;

            $this->targeted = 'Filière ' . $filiar->name;

        }


        if($promotion_slug){

            $promotion = Promotion::firstWhere('slug', $promotion_slug);

            if(!$promotion) return abort(404);

            $this->promotion = $promotion;

            $this->targeted = 'Promotion ' . $promotion->name;

        }

        if($promotionsGrouped){

            $this->promotionsGrouped = $promotionsGrouped;

            $this->targeted = 'Promotion ' . $promotionsGrouped;

        }

        if($serial_slug){

            $serial = Serial::firstWhere('slug', $serial_slug);

            if(!$serial) return abort(404);

            $this->serial = $serial;

            $this->targeted = 'Série ' . $serial->name;

        }
    }

    public function render()
    {
        return view('livewire.tenants.students.students-printable-documents-page');
    }
}