<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Throwable;

class CreateTenantTestCommand extends Command
{
    protected $signature = '
        tenant:test
        {name : Nom du tenant}
    ';

    protected $description = 'Créer automatiquement un tenant complet';

    public function handle(): int
    {
        $this->warn('⚡ Création du tenant en cours...');

        $tenant = null;

        try {

            /*
            |--------------------------------------------------------------------------
            | Formatage
            |--------------------------------------------------------------------------
            */

            $schoolName = trim($this->argument('name'));

            $slug = Str::slug($schoolName);

            $id = Str::slug($schoolName);

            $email = Str::lower(
                str_replace('-', '', $slug)
            ) . '@example.com';

            $domain = $slug . '.test';

            $tenantName = str_replace('-', ' ', ucwords($schoolName));

            /*
            |--------------------------------------------------------------------------
            | Vérification
            |--------------------------------------------------------------------------
            */

            if (Tenant::where('id', $slug)->exists()) {

                $this->error('❌ Ce tenant existe déjà.');

                return self::FAILURE;
            }

            /*
            |--------------------------------------------------------------------------
            | Transaction centrale
            |--------------------------------------------------------------------------
            */

            DB::beginTransaction();

            /*
            |--------------------------------------------------------------------------
            | Création tenant
            |--------------------------------------------------------------------------
            */

            $tenant = Tenant::create([
                'id' => $id,
                'name' => $tenantName,
            ]);

            $this->info('✅ Tenant créé avec succès.');

            $this->newLine();

            $this->warn('⚡ Initialisation de la creation du domaine lancée...');

            /*
            |--------------------------------------------------------------------------
            | Domaine
            |--------------------------------------------------------------------------
            */

            $tenant->domains()->create([
                'domain' => $domain,
            ]);
            
            $this->info("✅ Domaine et Tenant créés : {$tenant->id}");

            $this->newLine();

            $this->warn('⚡ Initialisation du tenant pour la création du user ...');

            /*
            |--------------------------------------------------------------------------
            | Initialisation tenant
            |--------------------------------------------------------------------------
            */

            tenancy()->initialize($tenant);

            /*
            |--------------------------------------------------------------------------
            | Admin tenant
            |--------------------------------------------------------------------------
            */

            $user = User::create([
                'name' => 'Admin ' . $schoolName,
                'email' => $email,
                'password' => Hash::make('password'),
            ]);

            /*
            |--------------------------------------------------------------------------
            | Role
            |--------------------------------------------------------------------------
            */

            Artisan::call('db:seed', [
                '--class' => RolesAndPermissionsSeeder::class,
                '--force' => true,
            ]);

            if (method_exists($user, 'assignRole')) {

                $user->assignRole('directeur');
            }


            $this->info("✅ User créé au nom de : {$user->name}");

            $this->newLine();

            $this->warn('⚡ Deconnexion du tenant ...');


            tenancy()->end();

            /*
            |--------------------------------------------------------------------------
            | Commit
            |--------------------------------------------------------------------------
            */

            DB::commit();

            /*
            |--------------------------------------------------------------------------
            | Success
            |--------------------------------------------------------------------------
            */

            $this->newLine();

            $this->info('✅ Processus terminé avec succès.');

            $this->table(
                ['Champ', 'Valeur'],
                [
                    ['Tenant', $tenant->id],
                    ['Ecole', $tenantName],
                    ['Domaine', $domain],
                    ['Email', $email],
                    ['Password', 'password'],
                ]
            );

            return self::SUCCESS;

        } catch (Throwable $th) {

            DB::rollBack();

            tenancy()->end();

            /*
            |--------------------------------------------------------------------------
            | Nettoyage COMPLET
            |--------------------------------------------------------------------------
            */

            try {

                if ($tenant) {

                    
                    /*
                    |--------------------------------------------------------------------------
                    | Supprime tenant + domains
                    |--------------------------------------------------------------------------
                    */

                    $tenant->delete();
                }

            } catch (Throwable $cleanupError) {

                $this->error(
                    'Erreur nettoyage : ' .
                    $cleanupError->getMessage()
                );
            }

            report($th);

            $this->newLine();

            $this->error(
                '❌ Création annulée : ' .
                $th->getMessage()
            );

            return self::FAILURE;
        }
    }
}