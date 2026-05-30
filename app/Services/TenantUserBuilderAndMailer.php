<?php

namespace App\Services;

use App\Mail\MailToSendSchoolSpaceDataToNewTenantAfterRequestValidation;
use App\Models\Tenant;
use App\Models\User;
use App\Services\EmailTemplateBuilder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


class TenantUserBuilderAndMailer{

    public User $user;
    

    public string $default_password;

    public function __construct(
        public Tenant $tenant
    )
    {
        $this->default_password = Str::random(8);
    }


    public function run()
    {
        self::userBuilder($this->tenant);
    }


    public function userBuilder(Tenant $tenant)
    {
        DB::beginTransaction();

        try {
            tenancy()->initialize($tenant);
        
            $user = User::create([
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
                'name',
                'school_devise' => $tenant->school_devise,
                'email_verified_at' => now(),
                'adresse' => $tenant->adresse,
                'logged_count' => 0,
            ]);

            $this->user = $user;

            if (method_exists($user, 'assignRole')) {

                $user->assignRole('directeur');

                $tenant->update(['role' => 'directeur']);

                self::mailBuilder($tenant);
            }

            DB::commit();

        } catch (\Throwable $th) {
           
            DB::rollBack();
        }
    }

    public function mailBuilder(Tenant $tenant)
    {

        $lien_for_tenant = getTenantLoginUrl($tenant->domain_name); 

        $receiver_html = EmailTemplateBuilder::render('tenant-space-request-validated-template', [
            'lien' => $lien_for_tenant,
            'key' => $this->default_password,
            'full_name' => $tenant->prenames . ' ' . $tenant->name,
            'school_name' => $tenant->school_name,
            'simple_name' => $tenant->simple_name,
            'domain_name' => $tenant->domain_name,
            'school_type' => $tenant->school_type,
            'enseignement_type' => $tenant->enseignement_type,
            'periode_type' => $tenant->periode_type,
            'devoirs_type' => $tenant->devoirs_type,
        ]);

        Mail::to($tenant->email)->send(new MailToSendSchoolSpaceDataToNewTenantAfterRequestValidation($this->user, $this->default_password, $receiver_html));
    }
}