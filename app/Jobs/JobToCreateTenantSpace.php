<?php

namespace App\Jobs;

use App\Models\RequestToCreateNewTenant;
use App\Models\Tenant;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Throwable;

class JobToCreateTenantSpace implements ShouldQueue
{
    use Queueable;

    public $tries = 2;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public RequestToCreateNewTenant $demande_request
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        DB::beginTransaction();


        try {

            $demande_request = $this->demande_request;

            $defaul_password = Str::random(8);

            $domain = $demande_request->domain_name; 

            // 'uuid',
            // 'id',
            // 'name',               // Nom de Tenant
            // 'prenames',               // Prénoms tenant
            // 'school_name',               // Nom de l'école
            // 'school_slug',                    // Subdomain (ex: ecole-lumiere)
            // 'enseignement_type',       // general | technique | hybride
            // 'periode_type',            // semestre | trimestre
            // 'status',                  // pending | active | suspended | cancelled
            // 'email',                   // Email de contact de l'école
            // 'contacts',               // Téléphone de l'école
            // 'adresse',                 // Adresse physique
            // 'country',                 // Pays physique
            // 'city',                 // Ville physique
            // 'logo',                    // Chemin du logo
            // 'date_expiration_abonnement',
            // 'school_devise',
            // 'types_devoirs', //devoir1-devoir2 ou devoir-compo
            // 'school_type', //Privé ou public
            // 'domain_blocked',
            // 'open_only_for_tenant',
            // 'role',
            // 'job_name',
            // 'profil_photo',
            // 'request_id',

            $tenant = Tenant::create([
                'id' => Str::uuid(),
                'name' => $demande_request->name,
                'prenames' => $demande_request->prenames,
                'school_name' => $demande_request->school_name,
                'simple_name' => $demande_request->simple_name,
                'domain_name' => $demande_request->domain_name,
                'enseignement_type' => $demande_request->enseignement_type,
                'periode_type' => $demande_request->periode_type,
                'school_type' => $demande_request->school_type,
                'devoirs_type' => $demande_request->devoirs_type,
                'school_slug' => $domain,
                'email' => $demande_request->email,
                'contacts' => $demande_request->contacts,
                'country' => $demande_request->country,
                'city' => $demande_request->city,
                'school_devise' => $demande_request->school_devise,
                'job_name' => $demande_request->job_name,
            ]);

            $tenant->domains()->create([
                'domain' => $domain,
            ]);

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
                'password' => Hash::make($defaul_password),
                'school_name' => $tenant->school_name,
                'school_slug' => $tenant->school_slug,
                'contacts' => $tenant->contacts,
                'country' => $tenant->country,
                'city' => $tenant->city,
                'tenant_id' => $tenant->id,
                'job_name' => $tenant->job_name,
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

            $tenant->update(['role' => 'directeur']);

            DB::commit();

        } catch (Throwable $th) {


            DB::rollBack();

            //throw $th;
        }


        
    }
}
