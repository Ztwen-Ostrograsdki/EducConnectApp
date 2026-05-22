<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;
use Throwable;


class DropAllTenantsCommand extends Command
{
    protected $signature = '
        drop:tenants
    ';

    protected $description = 'Suppression de tous les données des tenants';

    public function handle(): int
    {
        $this->warn('⚠️ Suppression de tous les tenants!');


        try {

            /*
            |--------------------------------------------------------------------------
            | DROP TENANTS
            |--------------------------------------------------------------------------
            */

            $allTenants = Tenant::all();

            foreach ($allTenants as $tenant) {

                $this->warn("Suppression du tenant : {$tenant->id}");

                tenancy()->initialize($tenant);

                $tenant->delete();

                tenancy()->end();
            }

            $this->info('✅ Suppression terminée.');

            return self::SUCCESS;

        } catch (Throwable $e) {

            tenancy()->end();

            $this->error('❌ Une erreur est survenue.');

            $this->error($e->getMessage());

            report($e);

            return self::FAILURE;
        }
    }
}