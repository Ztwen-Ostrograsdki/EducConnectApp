<?php

namespace App\Livewire\Tenants\ActionsTraits;

use App\Models\GeneratedDocument;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\On;
use Livewire\WithPagination;

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
/**
 * @property-read LengthAwarePaginator $documents
 */
trait DocumentActionsTrait
{
    use HandlesTenantStoredFiles, WithPagination;

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

        if (! $doc || ! $this->storedFileExists($doc->path)) {
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

        return $this->downloadStoredFile($doc->path, $doc->filename);
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

        $this->deleteStoredFile($doc->path);

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
}
