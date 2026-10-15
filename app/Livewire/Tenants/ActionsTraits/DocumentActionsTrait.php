<?php

namespace App\Livewire\Tenants\ActionsTraits;


use App\Helpers\Support\TenantStorage;
use App\Models\GeneratedDocument;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use WireUi\Traits\WireUiActions;

/**
 * Actions métier communes sur GeneratedDocument (téléchargement, suppression,
 * visibilité partagée). À utiliser dans les composants Livewire qui listent
 * des documents générés.
 *
 * Prérequis sur le composant hôte :
 * - WireUiActions (pour $this->notification())
 * - WithPagination si la liste est paginée
 * - propriété / computed `documents` (unset pour rafraîchir)
 *
 * Surcharges utiles :
 * - findDocumentForDownload()  → règles d’accès au téléchargement
 * - findOwnedDocument()        → règles d’accès propriétaire (delete / toggle)
 * - documentDeleteConfirmedEvent() → nom d’événement swal (si besoin)
 */
trait DocumentActionsTrait
{
    /**
     * Nom de l’événement Livewire déclenché après confirmation SweetAlert.
     * Surcharger si la page doit coexister avec un autre handler de suppression.
     */
    protected function documentDeleteConfirmedEvent(): string
    {
        return 'ConfirmToDeleteGeneratedDoc';
    }

    /**
     * Document accessible en téléchargement.
     * Par défaut : propriétaire uniquement. Surcharger pour élargir (partage, for_teachers…).
     */
    protected function findDocumentForDownload(int $documentId): ?GeneratedDocument
    {
        return $this->findOwnedDocument($documentId);
    }

    /**
     * Document appartenant à l’utilisateur connecté (suppression, toggle visibilité).
     */
    protected function findOwnedDocument(int $documentId): ?GeneratedDocument
    {
        return GeneratedDocument::query()
            ->where('id', $documentId)
            ->where('user_id', auth('tenant')->id())
            ->first();
    }

    /**
     * Invalide le computed / cache de la liste de documents.
     */
    protected function refreshDocumentsList(): void
    {
        unset($this->documents);
    }

    /**
     * Après suppression : recule d’une page si la page courante est vide.
     */
    protected function adjustPaginationAfterDelete(): void
    {
        if (! method_exists($this, 'getPage') && ! property_exists($this, 'paginators')) {
            return;
        }

        try {
            if ($this->documents->currentPage() > 1 && $this->documents->isEmpty()) {
                $this->setPage($this->documents->currentPage() - 1);
                $this->refreshDocumentsList();
            }
        } catch (\Throwable) {
            // Liste non paginée ou computed non disponible
        }
    }

    /**
     * Téléchargement direct (Livewire 3).
     */
    public function trackDownload(int $documentId)
    {
        $doc = $this->findDocumentForDownload($documentId);

        if (! $doc || ! File::exists($doc->path)) {
            $this->notification()->error(
                title: 'Document introuvable',
                description: 'Le fichier a peut-être déjà été supprimé du serveur.',
            );
            $this->refreshDocumentsList();

            return;
        }

        $doc->recordDownload();
        $this->refreshDocumentsList();

        $this->notification()->info(title: 'Téléchargement du document en cours …');

        return response()->download($doc->path, $doc->filename);
    }

    /**
     * Confirmation SweetAlert avant suppression.
     */
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
            'onConfirmed'        => $this->documentDeleteConfirmedEvent(),
            'onConfirmedParams'  => ['documentId' => $documentId],
        ]);
    }

    /**
     * Suppression effective (fichier + enregistrement DB).
     * Écoute l’événement standard ; les pages avec un ancien nom d’événement
     * peuvent aussi appeler deleteDocument() directement ou surcharger
     * documentDeleteConfirmedEvent().
     */
    #[On('ConfirmToDeleteGeneratedDoc')]
    public function deleteDocument(int $documentId): void
    {
        $doc = $this->findOwnedDocument($documentId);

        if (! $doc) {
            $this->notification()->error(
                title: 'Suppression impossible',
                description: 'Vous ne pouvez supprimer que les documents que vous avez générés.',
            );

            return;
        }

        if ($doc->path && File::exists($doc->path)) {
            TenantStorage::delete($this->relativePathFromAbsolute($doc->path));
        }

        $doc->delete();

        $this->refreshDocumentsList();
        $this->adjustPaginationAfterDelete();

        $this->notification()->success(title: 'Document supprimé avec succès.');
    }

    /**
     * Bascule downloadable_by_others (visibilité pour les autres utilisateurs).
     * Réservé au propriétaire du document.
     */
    public function toggleOthersVisibility(int $documentId): void
    {
        $doc = $this->findOwnedDocument($documentId);

        if (! $doc) {
            $this->notification()->error(
                title: 'Action impossible',
                description: 'Vous ne pouvez modifier que les documents que vous avez générés.',
            );

            return;
        }

        $doc->update([
            'downloadable_by_others' => ! (bool) $doc->downloadable_by_others,
        ]);

        $this->refreshDocumentsList();

        $visible = (bool) $doc->fresh()->downloadable_by_others;

        $this->notification()->success(
            title: $visible
                ? 'Document visible par les autres'
                : 'Document privé',
            description: $visible
                ? 'Les utilisateurs autorisés pourront télécharger ce document.'
                : 'Seul vous pouvez télécharger ce document.',
        );
    }

    /**
     * TenantStorage::delete() attend un chemin relatif au disque public ;
     * GeneratedDocument::path stocke souvent un chemin absolu.
     */
    protected function relativePathFromAbsolute(string $absolutePath): string
    {
        $absolutePath = str_replace('\\', '/', $absolutePath);
        $storageRoot  = str_replace('\\', '/', Storage::disk('public')->path(''));

        return ltrim(Str::after($absolutePath, $storageRoot), '/');
    }
}
