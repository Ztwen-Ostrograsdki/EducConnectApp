<?php

namespace App\Helpers\Support;


use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;

class TenantStorage
{
    protected static function tenantFolder(): string
    {
        return 'tenants/' . tenant()->id;
    }

    public static function ensureDirectory(string $directory): string
    {
        $path = self::tenantFolder() . '/' . trim($directory, '/');

        if (! Storage::disk('public')->exists($path)) {
            Storage::disk('public')->makeDirectory($path);
        }

        return $path;
    }

    public static function store($file, string $directory): string
    {
        $path = self::ensureDirectory($directory);

        return $file->store($path, 'public');
    }

    public static function delete(?string $file): void
    {
        if (! $file) {
            return;
        }

        Storage::disk('public')->delete($file);
    }

    public static function url(?string $file): ?string
    {
        if (! $file) {
            return null;
        }

        return Storage::url($file);
    }

    public static function dataUri(?string $file, ?string $fallback = 'images/doc-header.png'): ?string
    {
        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        // 1. Image personnalisée du tenant
        if ($file && $disk->exists($file)) {
            $mime = $disk->mimeType($file) ?: 'image/png';

            return 'data:' . $mime . ';base64,' . base64_encode($disk->get($file));
        }

        // 2. Image par défaut (public/images/doc-header.png)
        if ($fallback && is_file($path = public_path($fallback))) {
            $mime = mime_content_type($path) ?: 'image/png';

            return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
        }

        return null;
    }
}