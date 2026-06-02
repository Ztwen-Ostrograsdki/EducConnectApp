<?php

namespace App\Helpers\Support;


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
}