<?php

namespace App\Jobs;

use App\Events\ProcessToCreateTenantSpaceFailedEvent;
use App\Events\SendCredentialsToCreatedTenantEvent;
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
#[Tries(1)]
class JobToCreateTenantSpace implements ShouldQueue
{
    use Queueable, Batchable;

    public string $default_password;

    public bool $deleteWhenMissingModels = true;

    /**
     * Seul l'ID est sérialisé — évite que QueueTenancyBootstrapper
     * lise un tenant_id vide depuis le modèle RequestToCreateNewTenant.
     *
     * @param int    $demande_request_id
     * @param string $default_password
     */
    public function __construct(
        public int $demande_request_id,
        public string $space_url,
    ) {
        $this->default_password = Str::random(8);
    }


    /**
     * @return void
     */
    public function handle(): void
    {
        $demande_request = RequestToCreateNewTenant::findOrFail($this->demande_request_id);

        $domain = null;
        $tenant = null;

        try {
            $domain = $demande_request->domain_name;

            // Sécurité retry : le tenant existe peut-être déjà
            $tenant = Tenant::find($domain);

            if (! $tenant) {
                $tenant = Tenant::create([
                        'id'               => $domain,
                        'name'             => $demande_request->name,
                        'prenames'         => $demande_request->prenames,
                        'school_name'      => $demande_request->school_name,
                        'simple_name'      => $demande_request->simple_name,
                        'domain_name'      => $demande_request->domain_name,
                        'enseignement_type' => $demande_request->enseignement_type,
                        'periode_type'     => $demande_request->periode_type,
                        'school_type'      => $demande_request->school_type,
                        'devoirs_type'     => $demande_request->devoirs_type,
                        'school_slug'      => $domain,
                        'email'            => $demande_request->email,
                        'contacts'         => $demande_request->contacts,
                        'country'          => $demande_request->country,
                        'city'             => $demande_request->city,
                        'department'       => $demande_request->department,
                        'school_devise'    => $demande_request->school_devise,
                        'job_name'         => $demande_request->job_name,
                        'adresse'          => $demande_request->adresse,
                        'request_id'       => $demande_request->id,
                        'gender'           => $demande_request->gender,
                        'status'           => 'active',
                    ]);

                    $tenant->domains()->create([
                        'domain' => TenantHelper::generateDomain($domain),
                    ]);


            }

            // Initialisation manuelle obligatoire : ce job est dispatché
            // depuis le contexte central (pas de tenant HTTP actif).
            try {
                if ($tenant instanceof Tenant && $tenant->exists) {
                    tenancy()->initialize($tenant);
                }

                /** @var User $user */
                $user = User::firstOrCreate(
                    ['email' => $tenant->email],
                    [
                        'name'               => $tenant->name,
                        'prenames'           => $tenant->prenames,
                        'email'              => $tenant->email,
                        'password'           => Hash::make($this->default_password),
                        'school_name'        => $tenant->school_name,
                        'school_slug'        => $tenant->school_slug,
                        'contacts'           => $tenant->contacts,
                        'country'            => $tenant->country,
                        'department'         => $tenant->department,
                        'city'               => $tenant->city,
                        'tenant_id'          => $tenant->id,
                        'job_name'           => $tenant->job_name,
                        'gender'             => $tenant->gender,
                        'email_verified_at'  => now(),
                        'adresse'            => $tenant->adresse,
                        'logged_count'       => 0,
                    ]
                );

                Role::firstOrCreate([
                    'name'       => 'directeur',
                    'guard_name' => 'tenant',
                ]);

                if (method_exists($user, 'assignRole')) {
                    $user->assignRole('directeur');
                    $tenant->update(['role' => 'directeur']);
                }

                if($tenant && $user){

                    SendCredentialsToCreatedTenantEvent::dispatch($tenant->id, $this->space_url, true);
                }

            } finally {
                tenancy()->end();
            }

        } catch (Throwable $th) {

            ProcessToCreateTenantSpaceFailedEvent::dispatch(
                $this->demande_request_id
            );

            if ($domain && $tenant = Tenant::find($domain)) {
                $tenant->forceDelete();
            }

            DB::rollBack();

            $this->fail($th);
        }
    }
}