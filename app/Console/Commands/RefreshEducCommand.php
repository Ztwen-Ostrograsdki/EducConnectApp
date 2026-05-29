<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
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

            }
            else {

                $this->warn('Reset de la base des tenants...');

                Artisan::call('tenants:migrate-fresh');

                $this->line(Artisan::output());

            }

            /*
            |--------------------------------------------------------------------------
            | RESET TENANTS
            |--------------------------------------------------------------------------
            */

            $this->info('✅ Réinitialisation terminée.');

            return self::SUCCESS;

        } catch (Throwable $e) {

            $this->error('❌ Une erreur est survenue.');

            $this->error($e->getMessage());

            report($e);

            return self::FAILURE;
        }
    }
}