<?php

namespace App\Jobs;

use App\Helpers\Services\EmailTemplateBuilder;
use App\Mail\MailToSendSchoolSpaceDataToNewTenantAfterRequestValidation;
use App\Models\RequestToCreateNewTenant;
use App\Models\Tenant;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class JobToCreateTenantSpace implements ShouldQueue
{
    use Queueable;

    public $tries = 2;

    public string $key;

    public User $user;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public RequestToCreateNewTenant $demande_request
    )
    {
        $this->key = Str::random(8);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        DB::beginTransaction();


        try {

            $demande_request = $this->demande_request;

            $defaul_password = $this->key;

            $domain = $demande_request->domain_name; 

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
                'adresse' => $demande_request->adresse,
                'request_id' => $demande_request->id,
                'status' => 'active',
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
                'name',
                'school_devise' => $demande_request->school_devise,
                'email_verified_at' => now(),
                'adresse' => $demande_request->adresse,
                'logged_count' => 0,
            ]);

            $this->user = $user;

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

            DB::commit();

        } catch (Throwable $th) {


            DB::rollBack();

            //throw $th;
        }


        
    }

    public function mailBuilder($key = null)
    {
        $key = $this->key;

        $demande_request = $this->demande_request;

        $lien_for_tenant = '';

        $receiver_html = EmailTemplateBuilder::render('email-for-assistant-request', [
            'lien' => $lien_for_tenant,
            'key' => $key,
            'full_name' => $demande_request->prenames . ' ' . $demande_request->name,
            'school_name' => $demande_request->school_name,
            'simple_name' => $demande_request->simple_name,
            'domain_name' => $demande_request->domain_name,
            'school_type' => $demande_request->school_type,
            'enseignement_type' => $demande_request->enseignement_type,
            'periode_type' => $demande_request->periode_type,
            'devoirs_type' => $demande_request->devoirs_type,
        ]);

        Mail::to($this->demande_request->email)->send(new MailToSendSchoolSpaceDataToNewTenantAfterRequestValidation($this->user, $key, $receiver_html));
    }


}
