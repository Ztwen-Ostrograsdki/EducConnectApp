<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Throwable;

#[Signature('remigrate:table {table_path}')]
#[Description('Opération remigration (suppression puis recréation === Mise à jour) de table dans la base de donées')]
class RemigrateSpecificTableCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $table_path = $this->argument('table_path');

        $table_name = Str::between($table_path, 'create_', '_table');

        if($table_path){

            if(Schema::hasTable($table_name)){
            
                $this->line('---------------------------------------------------------------------');
                $this->line('');
                $this->warn('⚠️ Remigration de la table <<' . $table_name . '>>');
                $this->line('');
                $this->line('---------------------------------------------------------------------');

                try {

                    /*
                    |--------------------------------------------------------------------------
                    | RESET BASE CENTRALE
                    |--------------------------------------------------------------------------
                    */

                    Artisan::call('migrate:refresh --path=database/migrations/' . $table_path);

                    $this->line(Artisan::output());

                    /*
                    |--------------------------------------------------------------------------
                    | RESET TENANTS
                    |--------------------------------------------------------------------------
                    */

                    $this->info('✅ Remigration terminée.');

                    return self::SUCCESS;

                } catch (Throwable $e) {

                    $this->error('❌ Une erreur est survenue.');

                    $this->error($e->getMessage());

                    report($e);

                    return self::FAILURE;
                }
            }
            else{
                return $this->error("❌ Le processus a échoué la table {$table_path} n'existe pas!");
            }
        }
    }
}
