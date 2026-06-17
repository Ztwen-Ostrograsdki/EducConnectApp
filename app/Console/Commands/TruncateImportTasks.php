<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant;

class TruncateImportTasks extends Command
{
    protected $signature = 'import:truncate
                            {--force : Forcer sans confirmation}
                            {--tenant= : Tronquer uniquement pour un tenant spécifique}';

    protected $description = 'Tronque les tables import_tasks (tenants), job_batches et failed_jobs (central)';

    public function handle(): int
    {
        $tenantId = $this->option('tenant');

        if (! $this->option('force')) {
            $this->warn('⚠️  Cette action est irréversible !');
            $this->table(
                ['Table', 'Portée'],
                [
                    ['import_tasks', $tenantId ? "tenant {$tenantId}" : 'tous les tenants'],
                    ['job_batches',  'central'],
                    ['failed_jobs',  'central'],
                ]
            );

            if (! $this->confirm('Confirmer la suppression ?')) {
                $this->info('Opération annulée.');
                return self::SUCCESS;
            }
        }

        // Tables centrales (toujours)
        $this->truncateCentral();

        // Tables tenant(s)
        if ($tenantId) {
            $this->truncateTenant($tenantId);
        } else {
            $this->truncateAllTenants();
        }

        $this->newLine();
        $this->info('✅  Truncate terminé avec succès.');

        return self::SUCCESS;
    }

    protected function truncateCentral(): void
    {
        $this->newLine();
        $this->info('📦 Tables centrales');

        DB::table('job_batches')->truncate();
        $this->line('  <fg=green>✓</> job_batches vidée');

        DB::table('failed_jobs')->truncate();
        $this->line('  <fg=green>✓</> failed_jobs vidée');
    }

    protected function truncateTenant(string $tenantId): void
    {
        $tenant = Tenant::find($tenantId);

        if (! $tenant) {
            $this->error("  Tenant [{$tenantId}] introuvable.");
            return;
        }

        $this->newLine();
        $this->info("🏫 Tenant [{$tenantId}]");

        tenancy()->initialize($tenant);

        $count = DB::table('import_tasks')->count();
        DB::table('import_tasks')->truncate();
        $this->line("  <fg=green>✓</> import_tasks vidée ({$count} lignes supprimées)");

        tenancy()->end();
    }

    protected function truncateAllTenants(): void
    {
        $tenants = Tenant::all();

        if ($tenants->isEmpty()) {
            $this->warn('Aucun tenant trouvé.');
            return;
        }

        $this->newLine();
        $this->info("🏫 {$tenants->count()} tenant(s)");

        foreach ($tenants as $tenant) {
            tenancy()->initialize($tenant);

            $count = DB::table('import_tasks')->count();
            DB::table('import_tasks')->truncate();
            $this->line("  <fg=green>✓</> [{$tenant->id}] import_tasks vidée ({$count} lignes supprimées)");

            tenancy()->end();
        }
    }
}