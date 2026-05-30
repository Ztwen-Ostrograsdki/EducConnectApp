<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Faker\Factory;
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

            $n = trim($this->argument('name'));

            $school_name = str_replace('-', ' ', ucwords(trim($this->argument('name'))));

            $slug = Str::slug($n);

            $id = Str::slug($n);

            $domain = $slug . '.test';

            $faker = Factory::create('fr_FR');

            /** @noinspection PhpUndefinedMethodInspection */

            $enseignement_type = getRandomValueFromArray(config('app.enseignement_types'), 'secondaire');

            $periode_type = getRandomValueFromArray(config('app.periode_types'));

            $school_type = getRandomValueFromArray(config('app.school_types'));

            $devoirs_type = getRandomValueFromArray(config('app.devoirs_types'));

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
                'name' => $faker->firstName(),
                'prenames' => $faker->lastName(),
                'school_name' => $school_name,
                'enseignement_type' => $enseignement_type,
                'periode_type' => $periode_type,
                'school_type' => $school_type,
                'devoirs_type' => $devoirs_type,
                'school_slug' => $slug,
                'email' => $faker->email(),
                'contacts' => $faker->e164PhoneNumber(),
                'country' => $faker->country(),
                'city' => $faker->city(),
                'school_devise' => 'TRAVAIL - EXCELLENCE - REUSSITE',
                'job_name' => $faker->jobTitle() . '(' . $faker->company() . ')',
                'simple_name' => 'CEG' . Str::random(3),
                'domain_name' => $id,
                'status' => 'active',
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
                'name' => $tenant->name,
                'prenames' => $tenant->prenames,
                'email' => $tenant->email,
                'password' => Hash::make('password'),
                'school_name' => $tenant->school_name,
                'school_slug' => $tenant->school_slug,
                'contacts' => $tenant->contacts,
                'country' => $tenant->country,
                'city' => $tenant->city,
                'tenant_id' => $tenant->id,
                'job_name' => $tenant->job_name,
                'gender' => $tenant->gender,
                'email_verified_at' => now(),
                'adresse' => $tenant->adresse,
                'logged_count' => 0,
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

                $tenant->update(['role' => 'directeur']);
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
                    ['Ecole', $tenant->school_name],
                    ['Domaine', $domain],
                    ['Email', $tenant->email],
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