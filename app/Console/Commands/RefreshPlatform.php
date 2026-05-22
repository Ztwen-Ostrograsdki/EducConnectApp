<?php

namespace App\Console\Commands;

use App\Models\CentralUser;
use App\Models\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Stancl\Tenancy\Facades\Tenancy;

class RefreshPlatform extends Command
{
    protected $signature = 'app:refresh-educ';

    protected $description = 'Réinitialise toute la plateforme sans perdre les tenants ni les utilisateurs centraux';

    public function handle(): void
    {
        $this->warn('⚠️ Réinitialisation complète de la plateforme');

        /*
        |--------------------------------------------------------------------------
        | Sauvegarde des données centrales
        |--------------------------------------------------------------------------
        */

        $this->info('Sauvegarde des utilisateurs centraux...');
        $centralUsers = CentralUser::all()->toArray();

        $this->info('Sauvegarde des tenants...');
        $tenants = Tenant::all()->toArray();

        /*
        |--------------------------------------------------------------------------
        | RESET BASE CENTRALE
        |--------------------------------------------------------------------------
        */

        $this->info('Reset de la base centrale...');

        Artisan::call('migrate:fresh', [
            '--force' => true,
        ]);

        $this->line(Artisan::output());

        /*
        |--------------------------------------------------------------------------
        | RESTAURATION DES DONNÉES CENTRALES
        |--------------------------------------------------------------------------
        */

        $this->info('Restauration des utilisateurs centraux...');

        foreach ($centralUsers as $user) {

            unset($user['id']);

            CentralUser::create($user);
        }

        $this->info('Restauration des tenants...');

        foreach ($tenants as $tenantData) {

            $domains = $tenantData['domains'] ?? [];

            unset($tenantData['id']);

            $tenant = Tenant::create($tenantData);

            /*
            |--------------------------------------------------------------------------
            | Recréation DB tenant
            |--------------------------------------------------------------------------
            */

            $this->info("Réinitialisation du tenant : {$tenant->id}");

            tenancy()->initialize($tenant);

            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            $tables = DB::select('SHOW TABLES');

            $databaseName = DB::getDatabaseName();
            $key = "Tables_in_{$databaseName}";

            foreach ($tables as $table) {

                $tableName = $table->$key;

                DB::statement("DROP TABLE IF EXISTS `$tableName`");
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            /*
            |--------------------------------------------------------------------------
            | Relancer migrations tenant
            |--------------------------------------------------------------------------
            */

            Artisan::call('tenants:migrate', [
                '--tenants' => [$tenant->id],
                '--force' => true,
            ]);

            $this->line(Artisan::output());

            tenancy()->end();
        }

        $this->info('✅ Plateforme réinitialisée avec succès.');
    }
}