<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use App\Models\TenantStatistic;
use Illuminate\Console\Command;

class SyncTenantStatistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenants:sync-stats
                            {--tenant= : Sync a specific tenant by ID}
                            {--all : Sync all tenants}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize tenant statistics to central database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $tenantId = $this->option('tenant');
        $all      = $this->option('all');

        if (!$tenantId && !$all) {
            $this->error('Please specify --tenant=ID or --all');
            return Command::FAILURE;
        }

        if ($tenantId) {
            $tenant = Tenant::find($tenantId);

            if (!$tenant) {
                $this->error("Tenant [{$tenantId}] not found.");
                return Command::FAILURE;
            }

            $this->syncTenant($tenant);
            return Command::SUCCESS;
        }

        // Sync all tenants
        $tenants = Tenant::where('statut', 'active')->get();
        $bar     = $this->output->createProgressBar($tenants->count());
        $bar->start();

        foreach ($tenants as $tenant) {
            $this->syncTenant($tenant);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("✅ Synced {$tenants->count()} tenants successfully.");

        return Command::SUCCESS;
    }

    /**
     * Sync statistics for a single tenant.
     *
     * @param Tenant $tenant
     * @return void
     */
    private function syncTenant(Tenant $tenant): void
    {
        try {
            TenantStatistic::syncForTenant($tenant);
            $this->line("  ✔ {$tenant->name} ({$tenant->id})");
        } catch (\Exception $e) {
            $this->error("  ✘ {$tenant->name}: {$e->getMessage()}");
        } finally {
            // S'assurer de revenir au contexte central
            tenancy()->end();
        }
    }
}