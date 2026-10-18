<?php

namespace App\Livewire\Tenants\ActionsTraits;

use App\Helpers\Support\TenantStorage;
use App\Models\Epreuve;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use WireUi\Traits\WireUiActions;



/**
 * Actions métier communes sur Epreuve (téléchargement, suppression,
 * publication, verrouillage, actions de masse).
 *
 * Prérequis sur le composant hôte :
 * - WireUiActions (pour $this->notification())
 * - WithPagination si la liste est paginée
 * - propriété / computed `epreuves` (unset pour rafraîchir)
 * - optionnel : propriété $selected = [] pour les actions de masse
 *
 * Surcharges utiles :
 * - findEpreuveForDownload()  → règles d’accès au téléchargement
 * - findOwnedEpreuve()        → règles d’accès propriétaire (delete / toggle)
 * - findEpreuveForManage()    → règles d’accès gestion (directeur)
 * - epreuveDeleteConfirmedEvent() → nom d’événement swal
 * - canManageAllEpreuves()    → true côté directeur
 */
/**
 * @property-read LengthAwarePaginator $epreuves
 */
trait EpreuveActionsTrait
{
	use WireUiActions;
    /** @var array<int> IDs sélectionnés pour actions de masse */
    // public array $selected = []; // à déclarer dans le composant hôte si besoin

    protected function epreuveDeleteConfirmedEvent(): string
    {
        return 'ConfirmToDeleteEpreuve';
    }

    protected function epreuveBulkDeleteConfirmedEvent(): string
    {
        return 'ConfirmToBulkDeleteEpreuves';
    }

    /**
     * Le composant hôte est-il en mode « gestion globale » (directeur) ?
     * Surcharger pour retourner true côté directeur.
     */
    protected function canManageAllEpreuves(): bool
    {
        return false;
    }

    /**
     * Épreuve accessible en téléchargement.
     */
    protected function findEpreuveForDownload(int $epreuveId): ?Epreuve
    {
        if ($this->canManageAllEpreuves()) {
            return Epreuve::query()->where('id', $epreuveId)->first();
        }

        return Epreuve::query()
            ->where('id', $epreuveId)
            ->where(function ($q) {
                $q->where('author_id', auth('tenant')->id())
                    ->orWhere('downloadable_by_others', true)
                    ->orWhere('published', true);
            })
            ->first();
    }

    /**
     * Épreuve appartenant à l’utilisateur connecté.
     */
    protected function findOwnedEpreuve(int $epreuveId): ?Epreuve
    {
        return Epreuve::query()
            ->where('id', $epreuveId)
            ->where('author_id', auth('tenant')->id())
            ->first();
    }

    /**
     * Épreuve accessible en gestion (directeur = toutes, sinon propriétaire).
     */
    protected function findEpreuveForManage(int $epreuveId): ?Epreuve
    {
        if ($this->canManageAllEpreuves()) {
            return Epreuve::query()->where('id', $epreuveId)->first();
        }

        return $this->findOwnedEpreuve($epreuveId);
    }

    protected function refreshEpreuvesList(): void
    {
        unset($this->epreuves);
        if (property_exists($this, 'selected')) {
            $this->selected = [];
        }
    }

    protected function adjustPaginationAfterDelete(): void
	{
		if (! method_exists($this, 'getPage') && ! property_exists($this, 'paginators')) {
			return;
		}

		// Force la relecture en base après la suppression
		unset($this->epreuves);

		if ($this->epreuves->currentPage() > 1 && $this->epreuves->isEmpty()) {
			$this->setPage($this->epreuves->currentPage() - 1);
			unset($this->epreuves);
		}
	}

    // ─── Téléchargement ───────────────────────────────────────────────

    public function trackDownload(int $epreuveId)
    {
        $epreuve = $this->findEpreuveForDownload($epreuveId);

        if (! $epreuve || ! File::exists($epreuve->path)) {
            $this->notification()->error(
                title: 'Épreuve introuvable',
                description: 'Le fichier a peut-être déjà été supprimé du serveur.',
            );
            $this->refreshEpreuvesList();

            return;
        }

        $epreuve->recordDownload();
        $this->refreshEpreuvesList();

        $this->notification()->info(title: 'Téléchargement de l\'épreuve en cours …');

        return response()->download($epreuve->path, $epreuve->filename);
    }

    // ─── Suppression individuelle ─────────────────────────────────────

    public function confirmDelete(int $epreuveId): void
    {
        $epreuve = $this->findEpreuveForManage($epreuveId);

        if (! $epreuve) {
            $this->notification()->error(
                title: 'Suppression impossible',
                description: 'Épreuve introuvable ou accès refusé.',
            );

            return;
        }

        // Côté enseignant : locked bloque la suppression
        if (! $this->canManageAllEpreuves() && $epreuve->isLocked()) {
            $this->notification()->error(
                title: 'Épreuve verrouillée',
                description: 'Cette épreuve est verrouillée et ne peut pas être supprimée.',
            );

            return;
        }

        $this->dispatch('swal', [
            'title'              => 'Supprimer cette épreuve ?',
            'description'        => 'Cette action est irréversible. Le fichier sera définitivement supprimé du serveur.',
            'icon'               => 'question',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, supprimer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#06b6d4',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => $this->epreuveDeleteConfirmedEvent(),
            'onConfirmedParams'  => ['epreuveId' => $epreuveId],
        ]);
    }

    #[On('ConfirmToDeleteEpreuve')]
    public function deleteEpreuve(int $epreuveId): void
    {
        $epreuve = $this->findEpreuveForManage($epreuveId);

        if (! $epreuve) {
            $this->notification()->error(
                title: 'Suppression impossible',
                description: 'Épreuve introuvable ou accès refusé.',
            );

            return;
        }

        if (! $this->canManageAllEpreuves() && $epreuve->isLocked()) {
            $this->notification()->error(
                title: 'Épreuve verrouillée',
                description: 'Cette épreuve est verrouillée et ne peut pas être supprimée.',
            );

            return;
        }

        if ($epreuve->path && File::exists($epreuve->path)) {
            TenantStorage::delete($this->relativePathFromAbsolute($epreuve->path));
        }

        $epreuve->delete();

        $this->refreshEpreuvesList();
        $this->adjustPaginationAfterDelete();

        $this->notification()->success(title: 'Épreuve supprimée avec succès.');
    }

    // ─── Toggle published ─────────────────────────────────────────────

    public function togglePublished(int $epreuveId): void
    {
        $epreuve = $this->findEpreuveForManage($epreuveId);

        if (! $epreuve) {
            $this->notification()->error(
                title: 'Action impossible',
                description: 'Épreuve introuvable ou accès refusé.',
            );

            return;
        }

        $epreuve->update([
            'published' => ! (bool) $epreuve->published,
        ]);

        $this->refreshEpreuvesList();

        $published = (bool) $epreuve->fresh()->published;

        $this->notification()->success(
            title: $published ? 'Épreuve publiée' : 'Épreuve dépubliée',
            description: $published
                ? 'Cette épreuve est maintenant accessible à tous.'
                : 'Cette épreuve n\'est plus visible publiquement.',
        );
    }

    // ─── Toggle downloadable_by_others ────────────────────────────────

    public function toggleOthersVisibility(int $epreuveId): void
    {
        $epreuve = $this->findEpreuveForManage($epreuveId);

        if (! $epreuve) {
            $this->notification()->error(
                title: 'Action impossible',
                description: 'Épreuve introuvable ou accès refusé.',
            );

            return;
        }

        $epreuve->update([
            'downloadable_by_others' => ! (bool) $epreuve->downloadable_by_others,
        ]);

        $this->refreshEpreuvesList();

        $visible = (bool) $epreuve->fresh()->downloadable_by_others;

        $this->notification()->success(
            title: $visible ? 'Épreuve téléchargeable par les autres' : 'Épreuve privée',
            description: $visible
                ? 'Les utilisateurs autorisés pourront télécharger cette épreuve.'
                : 'Seul le propriétaire (et le directeur) peut télécharger cette épreuve.',
        );
    }

    // ─── Lock / Unlock individuel ─────────────────────────────────────

    public function toggleLock(int $epreuveId): void
    {
        // Seul le directeur (canManageAll) peut verrouiller / déverrouiller
        if (! $this->canManageAllEpreuves()) {
            $this->notification()->error(
                title: 'Action refusée',
                description: 'Seul le directeur peut verrouiller ou déverrouiller une épreuve.',
            );

            return;
        }

        $epreuve = $this->findEpreuveForManage($epreuveId);

        if (! $epreuve) {
            $this->notification()->error(
                title: 'Action impossible',
                description: 'Épreuve introuvable.',
            );

            return;
        }

        $epreuve->update([
            'locked' => ! (bool) $epreuve->locked,
        ]);

        $this->refreshEpreuvesList();

        $locked = (bool) $epreuve->fresh()->locked;

        $this->notification()->success(
            title: $locked ? 'Épreuve verrouillée' : 'Épreuve déverrouillée',
            description: $locked
                ? 'L\'enseignant ne peut plus supprimer cette épreuve.'
                : 'L\'enseignant peut à nouveau supprimer cette épreuve.',
        );
    }

    // ─── Actions de masse ─────────────────────────────────────────────

    /**
     * IDs sélectionnés valides (présents en base et accessibles).
     *
     * @return \Illuminate\Support\Collection<int, Epreuve>
     */
    protected function selectedEpreuves()
    {
        $ids = property_exists($this, 'selected') ? array_filter($this->selected ?? []) : [];

        if (empty($ids)) {
            return collect();
        }

        $query = Epreuve::query()->whereIn('id', $ids);

        if (! $this->canManageAllEpreuves()) {
            $query->where('author_id', auth('tenant')->id());
        }

        return $query->get();
    }

    public function bulkPublish(): void
    {
        $items = $this->selectedEpreuves();

        if ($items->isEmpty()) {
            $this->notification()->warning(title: 'Aucune épreuve sélectionnée.');

            return;
        }

        Epreuve::query()->whereIn('id', $items->pluck('id'))->update(['published' => true]);
        $this->refreshEpreuvesList();

        $this->notification()->success(
            title: 'Publication effectuée',
            description: $items->count() . ' épreuve(s) publiée(s).',
        );
    }

    public function bulkUnpublish(): void
    {
        $items = $this->selectedEpreuves();

        if ($items->isEmpty()) {
            $this->notification()->warning(title: 'Aucune épreuve sélectionnée.');

            return;
        }

        Epreuve::query()->whereIn('id', $items->pluck('id'))->update(['published' => false]);
        $this->refreshEpreuvesList();

        $this->notification()->success(
            title: 'Dépublication effectuée',
            description: $items->count() . ' épreuve(s) dépubliée(s).',
        );
    }

    public function bulkMakeDownloadable(): void
    {
        $items = $this->selectedEpreuves();

        if ($items->isEmpty()) {
            $this->notification()->warning(title: 'Aucune épreuve sélectionnée.');

            return;
        }

        Epreuve::query()->whereIn('id', $items->pluck('id'))->update(['downloadable_by_others' => true]);
        $this->refreshEpreuvesList();

        $this->notification()->success(
            title: 'Visibilité mise à jour',
            description: $items->count() . ' épreuve(s) rendue(s) téléchargeable(s).',
        );
    }

    public function bulkMakePrivate(): void
    {
        $items = $this->selectedEpreuves();

        if ($items->isEmpty()) {
            $this->notification()->warning(title: 'Aucune épreuve sélectionnée.');

            return;
        }

        Epreuve::query()->whereIn('id', $items->pluck('id'))->update(['downloadable_by_others' => false]);
        $this->refreshEpreuvesList();

        $this->notification()->success(
            title: 'Visibilité mise à jour',
            description: $items->count() . ' épreuve(s) rendue(s) privée(s).',
        );
    }

    public function bulkLock(): void
    {
        if (! $this->canManageAllEpreuves()) {
            $this->notification()->error(title: 'Action refusée', description: 'Seul le directeur peut verrouiller des épreuves.');

            return;
        }

        $items = $this->selectedEpreuves();

        if ($items->isEmpty()) {
            $this->notification()->warning(title: 'Aucune épreuve sélectionnée.');

            return;
        }

        Epreuve::query()->whereIn('id', $items->pluck('id'))->update(['locked' => true]);
        $this->refreshEpreuvesList();

        $this->notification()->success(
            title: 'Verrouillage effectué',
            description: $items->count() . ' épreuve(s) verrouillée(s).',
        );
    }

    public function bulkUnlock(): void
    {
        if (! $this->canManageAllEpreuves()) {
            $this->notification()->error(title: 'Action refusée', description: 'Seul le directeur peut déverrouiller des épreuves.');

            return;
        }

        $items = $this->selectedEpreuves();

        if ($items->isEmpty()) {
            $this->notification()->warning(title: 'Aucune épreuve sélectionnée.');

            return;
        }

        Epreuve::query()->whereIn('id', $items->pluck('id'))->update(['locked' => false]);
        $this->refreshEpreuvesList();

        $this->notification()->success(
            title: 'Déverrouillage effectué',
            description: $items->count() . ' épreuve(s) déverrouillée(s).',
        );
    }

    public function confirmBulkDelete(): void
    {
        $items = $this->selectedEpreuves();

        if ($items->isEmpty()) {
            $this->notification()->warning(title: 'Aucune épreuve sélectionnée.');

            return;
        }

        // Côté enseignant : exclure les locked
        if (! $this->canManageAllEpreuves()) {
            $locked = $items->filter(fn (Epreuve $e) => $e->isLocked());
            if ($locked->isNotEmpty()) {
                $this->notification()->error(
                    title: 'Suppression partielle impossible',
                    description: $locked->count() . ' épreuve(s) sont verrouillées et ne peuvent pas être supprimées. Désélectionnez-les.',
                );

                return;
            }
        }

        $this->dispatch('swal', [
            'title'              => 'Supprimer ' . $items->count() . ' épreuve(s) ?',
            'description'        => 'Cette action est irréversible. Les fichiers seront définitivement supprimés du serveur.',
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, tout supprimer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#ef4444',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => $this->epreuveBulkDeleteConfirmedEvent(),
        ]);
    }

    #[On('ConfirmToBulkDeleteEpreuves')]
    public function bulkDelete(): void
    {
        $items = $this->selectedEpreuves();

        if ($items->isEmpty()) {
            return;
        }

        $deleted = 0;

        foreach ($items as $epreuve) {
            if (! $this->canManageAllEpreuves() && $epreuve->isLocked()) {
                continue;
            }

            if ($epreuve->path && File::exists($epreuve->path)) {
                TenantStorage::delete($this->relativePathFromAbsolute($epreuve->path));
            }

            $epreuve->delete();
            $deleted++;
        }

        $this->refreshEpreuvesList();
        $this->adjustPaginationAfterDelete();

        $this->notification()->success(
            title: 'Suppression terminée',
            description: $deleted . ' épreuve(s) supprimée(s).',
        );
    }

    /**
     * Actions de masse par lot / enseignant / matière / classe.
     */
    public function bulkActionByGroup(string $action, string $groupBy, mixed $value): void
    {
        if (! in_array($action, ['publish', 'unpublish', 'downloadable', 'private', 'lock', 'unlock', 'delete'], true)) {
            return;
        }

        if (in_array($action, ['lock', 'unlock'], true) && ! $this->canManageAllEpreuves()) {
            $this->notification()->error(title: 'Action refusée');

            return;
        }

        $query = Epreuve::query();

        if (! $this->canManageAllEpreuves()) {
            $query->where('author_id', auth('tenant')->id());
        }

        match ($groupBy) {
            'lot'          => $query->where('lot', $value),
            'teacher_name' => $query->where('teacher_name', $value),
            'subject_id'   => $query->where('subject_id', $value),
            'classe_id'    => $query->where('classe_id', $value),
            default        => null,
        };

        $ids = $query->pluck('id');

        if ($ids->isEmpty()) {
            $this->notification()->warning(title: 'Aucune épreuve trouvée pour ce groupe.');

            return;
        }

        match ($action) {
            'publish'      => Epreuve::whereIn('id', $ids)->update(['published' => true]),
            'unpublish'    => Epreuve::whereIn('id', $ids)->update(['published' => false]),
            'downloadable' => Epreuve::whereIn('id', $ids)->update(['downloadable_by_others' => true]),
            'private'      => Epreuve::whereIn('id', $ids)->update(['downloadable_by_others' => false]),
            'lock'         => Epreuve::whereIn('id', $ids)->update(['locked' => true]),
            'unlock'       => Epreuve::whereIn('id', $ids)->update(['locked' => false]),
            'delete'       => $this->deleteByIds($ids->all()),
            default        => null,
        };

        $this->refreshEpreuvesList();

        $this->notification()->success(
            title: 'Action de groupe effectuée',
            description: $ids->count() . ' épreuve(s) traitée(s).',
        );
    }

    protected function deleteByIds(array $ids): void
    {
        $items = Epreuve::query()->whereIn('id', $ids)->get();

        foreach ($items as $epreuve) {
            if (! $this->canManageAllEpreuves() && $epreuve->isLocked()) {
                continue;
            }

            if ($epreuve->path && File::exists($epreuve->path)) {
                TenantStorage::delete($this->relativePathFromAbsolute($epreuve->path));
            }

            $epreuve->delete();
        }
    }

    protected function relativePathFromAbsolute(string $absolutePath): string
    {
        $absolutePath = str_replace('\\', '/', $absolutePath);
        $storageRoot  = str_replace('\\', '/', Storage::disk('public')->path(''));

        return ltrim(Str::after($absolutePath, $storageRoot), '/');
    }
}
