<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ForceDropAndRemigrationExcludesExceptsTablesCommand extends Command
{
    /**
     * Nom et signature de la commande.
     *
     * @var string
     */
    protected $signature = 'migrate:force-except
                            {--except=users : Tables à préserver, séparées par une virgule}
                            {--tenant= : ID du tenant à cibler (optionnel)}
                            {--path= : Chemin des migrations (optionnel)}
                            {--seed : Lancer les seeders après la migration}';

    /**
     * Description de la commande.
     *
     * @var string
     */
    protected $description = 'Droppe et re-migre toutes les tables sauf celles spécifiées (défaut : users)';

    /**
     * Exécute la commande.
     *
     * @return int
     */
    public function handle(): int
    {
        // --- Résolution des tables protégées ---
        $exceptInput = $this->option('except');
        $protectedTables = collect(explode(',', $exceptInput))
            ->map(fn (string $t) => trim($t))
            ->filter()
            ->values()
            ->toArray();

        // Toujours protéger la table migrations pour ne pas perdre l'historique
        $protectedTables[] = 'migrations';
        $protectedTables = array_unique($protectedTables);

        // --- Initialisation du tenant si précisé ---
        $tenantId = $this->option('tenant');

        if ($tenantId) {
            if (! function_exists('tenancy')) {
                $this->error('Le package stancl/tenancy n\'est pas installé ou configuré.');
                return self::FAILURE;
            }

            $tenant = \Stancl\Tenancy\Database\Models\Tenant::find($tenantId);

            if (! $tenant) {
                $this->error("Tenant [{$tenantId}] introuvable.");
                return self::FAILURE;
            }

            tenancy()->initialize($tenant);
            $this->info("Tenant [{$tenantId}] initialisé.");
        }

        // --- Confirmation de sécurité ---
        $this->warn('⚠️  Cette commande va SUPPRIMER des tables en base de données.');
        $this->line('Tables protégées : <comment>' . implode(', ', $protectedTables) . '</comment>');

        if (! $this->confirm('Confirmer la suppression et la remigration ?', false)) {
            $this->info('Opération annulée.');
            return self::SUCCESS;
        }

        // --- Récupération de toutes les tables existantes ---
        $allTables = $this->getAllTables();

        if (empty($allTables)) {
            $this->warn('Aucune table trouvée en base de données.');
            return self::SUCCESS;
        }

        // --- Filtrage : exclure les tables protégées ---
        $tablesToDrop = array_filter(
            $allTables,
            fn (string $table) => ! in_array($table, $protectedTables, true)
        );

        if (empty($tablesToDrop)) {
            $this->warn('Toutes les tables sont protégées. Rien à supprimer.');
            return self::SUCCESS;
        }

        // --- Affichage des tables qui seront supprimées ---
        $this->line('');
        $this->line('Tables qui seront <error>supprimées</error> :');
        foreach ($tablesToDrop as $table) {
            $this->line("  - {$table}");
        }
        $this->line('');

        // --- Drop des tables ---
        $this->dropTables($tablesToDrop);

        // --- Remigration ---
        $this->info('Lancement des migrations...');

        $migrateOptions = ['--force' => true];

        if ($this->option('path')) {
            $migrateOptions['--path'] = $this->option('path');
        }

        $this->call('migrate', $migrateOptions);

        // --- Seeders optionnels ---
        if ($this->option('seed')) {
            $this->info('Lancement des seeders...');
            $this->call('db:seed', ['--force' => true]);
        }

        // --- Fin du contexte tenant ---
        if ($tenantId && function_exists('tenancy')) {
            tenancy()->end();
            $this->info("Contexte tenant [{$tenantId}] terminé.");
        }

        $this->info('✅ Migration forcée terminée avec succès.');

        return self::SUCCESS;
    }

    /**
     * Retourne la liste de toutes les tables de la base active.
     *
     * @return array<string>
     */
    protected function getAllTables(): array
    {
        $driver = DB::getDriverName();

        return match ($driver) {
            'mysql', 'mariadb' => $this->getMysqlTables(),
            'pgsql'            => $this->getPgsqlTables(),
            'sqlite'           => $this->getSqliteTables(),
            default            => [],
        };
    }

    /**
     * Récupère les tables MySQL / MariaDB.
     *
     * @return array<string>
     */
    protected function getMysqlTables(): array
    {
        $database = DB::getDatabaseName();

        $rows = DB::select(
            'SELECT table_name FROM information_schema.tables WHERE table_schema = ?',
            [$database]
        );

        return array_map(fn ($row) => $row->table_name ?? $row->TABLE_NAME, $rows);
    }

    /**
     * Récupère les tables PostgreSQL.
     *
     * @return array<string>
     */
    protected function getPgsqlTables(): array
    {
        $rows = DB::select(
            "SELECT tablename FROM pg_tables WHERE schemaname = 'public'"
        );

        return array_map(fn ($row) => $row->tablename, $rows);
    }

    /**
     * Récupère les tables SQLite.
     *
     * @return array<string>
     */
    protected function getSqliteTables(): array
    {
        $rows = DB::select(
            "SELECT name FROM sqlite_master WHERE type = 'table' AND name != 'sqlite_sequence'"
        );

        return array_map(fn ($row) => $row->name, $rows);
    }

    /**
     * Droppe les tables en désactivant les contraintes FK.
     *
     * @param  array<string>  $tables
     * @return void
     */
    protected function dropTables(array $tables): void
    {
        Schema::disableForeignKeyConstraints();

        $bar = $this->output->createProgressBar(count($tables));
        $bar->start();

        foreach ($tables as $table) {
            Schema::dropIfExists($table);
            $this->line("  <comment>Dropped :</comment> {$table}");
            $bar->advance();
        }

        $bar->finish();
        $this->line('');

        Schema::enableForeignKeyConstraints();
    }
}
