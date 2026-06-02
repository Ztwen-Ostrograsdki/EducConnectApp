<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Tenant;

class InitializeTenantStorage extends Command
{
    protected $signature = 'tenants:init-storage';

    protected $description = 'Créer les dossiers de stockage pour tous les tenants';

    public function handle()
    {
        Tenant::all()->each(function ($tenant) {

            $folders = [
                'profiles',
                'documents',
                'bulletins',
                'epreuves',
                'schools',
                'temp',
            ];

            foreach ($folders as $folder) {

                Storage::disk('public')
                    ->makeDirectory(
                        "tenants/{$tenant->id}/{$folder}"
                    );
            }
        });

        $this->info('Terminé');
    }
}