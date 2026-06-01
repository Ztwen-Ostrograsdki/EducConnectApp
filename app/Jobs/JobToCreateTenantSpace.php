<?php

namespace App\Jobs;

use App\Helpers\TenantHelper;
use App\Models\RequestToCreateNewTenant;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Attributes\Timeout;
use Illuminate\Queue\Attributes\Tries;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Throwable;


#[Timeout(300)]
#[Tries(3)]
class JobToCreateTenantSpace implements ShouldQueue
{
    use Queueable, Batchable;

    public string $key, $default_password;

    public User $user;

    public $deleteWhenMissingModels = true;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public RequestToCreateNewTenant $demande_request
    )
    {
        $this->default_password = Str::random(8);
    }


    public function handle(): void 
    {
        $tenant = null;

        try {
            $demande_request = $this->demande_request;

            $domain = $demande_request->domain_name;
            // Vérifier si le tenant existe déjà (sécurité retry)
            $tenant = Tenant::find($domain);

            if (!$tenant) {
                DB::beginTransaction();

                $tenant = Tenant::create([
                    'id'               => $domain,
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
                    'adresse' => $demande_request->adresse,
                    'request_id' => $demande_request->id,
                    'gender' => $demande_request->gender,
                    'status' => 'active',
                ]);

                $tenant->domains()->create([
                    'domain' => TenantHelper::generateDomain($domain),
                ]);

                DB::commit();
            }

            // Contexte tenant — transaction séparée
            tenancy()->initialize($tenant);

            try {
                $user = User::firstOrCreate(
                    ['email' => $tenant->email],
                    [
                        'name' => $tenant->name,
                        'prenames' => $tenant->prenames,
                        'email' => $tenant->email,
                        'password' => Hash::make($this->default_password),
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
                    ]
                );

                Role::firstOrCreate([
                    'name'       => 'directeur',
                    'guard_name' => 'tenant'
                ]);

                if (method_exists($user, 'assignRole')) {
                    $user->assignRole('directeur');
                    $tenant->update(['role' => 'directeur']);
                }

            } finally {
                tenancy()->end(); // ← toujours exécuté, succès ou erreur
            }

        } catch (Throwable $th) {

            $tenant = Tenant::find($domain);

            if($tenant) $tenant->forceDelete();

            DB::rollBack();
            
            $this->fail($th);
        }
    }
}
