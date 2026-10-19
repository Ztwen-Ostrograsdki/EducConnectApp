<?php

namespace App\Livewire\Tenants\ActionsTraits;

use App\Helpers\Support\TenantStorage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Opérations fichier unifiées pour les chemins stockés via TenantStorage / disk public.
 *
 * Gère les chemins relatifs (recommandé) et absolus (rétrocompat).
 * À utiliser dans DocumentActionsTrait, EpreuveActionsTrait, etc.
 *
 * Méthodes exposées :
 * - absolutePath()           → chemin disque pour File::exists / download
 * - relativePathFromStored() → chemin relatif pour TenantStorage::delete
 * - storedFileExists()       → existence réelle du fichier
 * - deleteStoredFile()       → suppression physique (TenantStorage)
 * - downloadStoredFile()     → response()->download() prête
 */
trait HandlesTenantStoredFiles
{
    /**
     * Résout un chemin stocké (relatif OU absolu) en chemin absolu disque.
     */
    protected function absolutePath(string $path): string
    {
        $path = str_replace('\\', '/', trim($path));

        if ($path === '') {
            return $path;
        }

        // Déjà absolu (Unix ou Windows)
        if (str_starts_with($path, '/') || preg_match('/^[A-Za-z]:\//', $path)) {
            return $path;
        }

        // Relatif au disk public (TenantStorage::store, PDFFactory::outputPath)
        return Storage::disk('public')->path($path);
    }

    /**
     * Normalise un chemin stocké en chemin relatif au disk public
     * (attendu par TenantStorage::delete).
     */
    protected function relativePathFromStored(string $path): string
    {
        $path = str_replace('\\', '/', trim($path));

        if ($path === '') {
            return '';
        }

        // Déjà relatif
        if (! str_starts_with($path, '/') && ! preg_match('/^[A-Za-z]:\//', $path)) {
            return ltrim($path, '/');
        }

        $storageRoot = str_replace('\\', '/', Storage::disk('public')->path(''));

        return ltrim(Str::after($path, $storageRoot), '/');
    }

    /**
     * Alias rétrocompat (anciens traits utilisaient relativePathFromAbsolute).
     */
    protected function relativePathFromAbsolute(string $path): string
    {
        return $this->relativePathFromStored($path);
    }

    /**
     * Le fichier existe-t-il réellement sur le disque ?
     */
    protected function storedFileExists(?string $path): bool
    {
        if (! filled($path)) {
            return false;
        }

        return File::exists($this->absolutePath($path));
    }

    /**
     * Supprime le fichier physique via TenantStorage.
     * Ne lève pas d'exception si le fichier est déjà absent.
     */
    protected function deleteStoredFile(?string $path): void
    {
        if (! filled($path)) {
            return;
        }

        try {
            $relative = $this->relativePathFromStored($path);

            if ($relative !== '') {
                TenantStorage::delete($relative);
            }
        } catch (\Throwable) {
            // Fichier absent ou chemin invalide — la suppression DB continue
        }
    }

    /**
     * Prépare une réponse de téléchargement HTTP.
     *
     * @return BinaryFileResponse|null  null si le fichier est introuvable
     */
    protected function downloadStoredFile(?string $path, ?string $downloadName = null): ?BinaryFileResponse
    {
        if (! $this->storedFileExists($path)) {
            return null;
        }

        $absolute = $this->absolutePath($path);
        $name     = $downloadName ?: basename($this->relativePathFromStored($path) ?: $absolute);

        return response()->download($absolute, $name);
    }
}
