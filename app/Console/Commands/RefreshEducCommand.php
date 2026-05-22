<?php

namespace App\Console\Commands;

use App\Models\CentralUser;
use App\Models\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Throwable;

class RefreshEducCommand extends Command
{
    protected $signature = '
        app:refresh-educ
        {--reset : Réinitialise aussi la base centrale}
    ';

    protected $description = 'Réinitialise les bases tenants et éventuellement la base centrale';

    public function handle(): int
    {
        $this->warn('⚠️ Réinitialisation de la plateforme');

        /*
        |--------------------------------------------------------------------------
        | Sauvegardes
        |--------------------------------------------------------------------------
        */

        $centralUsers = [];
        $tenants = [];
        $domains = [];

        if ($this->option('reset')) {

            $this->info('Sauvegarde des données centrales...');

            $centralUsers = CentralUser::query()
                ->get()
                ->makeVisible([
                    'password',
                    'remember_token',
                ])
                ->map(fn ($user) => collect($user)
                    ->except('id')
                    ->toArray())
                ->toArray();

            $tenants = Tenant::all()
                ->map(fn ($tenant) => collect($tenant)
                    ->except('id')
                    ->toArray())
                ->toArray();

            $domains = DB::table('domains')
                ->get()
                ->map(fn ($domain) => collect((array) $domain)
                    ->except('id')
                    ->toArray())
                ->toArray();
        }

        try {

            /*
            |--------------------------------------------------------------------------
            | RESET BASE CENTRALE
            |--------------------------------------------------------------------------
            */

            if ($this->option('reset')) {

                $this->warn('Reset de la base centrale...');

                Artisan::call('migrate:fresh', [
                    '--force' => true,
                ]);

                $this->line(Artisan::output());

                DB::transaction(function () use (
                    $centralUsers,
                    $tenants,
                    $domains
                ) {

                    if (! empty($centralUsers)) {

                        CentralUser::insert($centralUsers);
                    }

                    if (! empty($tenants)) {

                        Tenant::insert($tenants);
                    }

                    if (! empty($domains)) {

                        DB::table('domains')->insert($domains);
                    }
                });
            }

            /*
            |--------------------------------------------------------------------------
            | RESET TENANTS
            |--------------------------------------------------------------------------
            */

            $allTenants = Tenant::all();

            foreach ($allTenants as $tenant) {

                $this->warn("Reset tenant : {$tenant->id}");

                tenancy()->initialize($tenant);

                Artisan::call('migrate:fresh', [
                    '--path' => 'database/migrations/tenant',
                    '--force' => true,
                ]);

                $this->line(Artisan::output());

                tenancy()->end();
            }

            $this->info('✅ Réinitialisation terminée.');

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