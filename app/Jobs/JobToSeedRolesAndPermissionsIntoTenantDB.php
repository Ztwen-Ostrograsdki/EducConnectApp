<?php

namespace App\Jobs;

use App\Events\FailedToSeedRolesAndPermissionsIntoCreatedTenantDBEvent;
use App\Models\Tenant;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Attributes\Delay;
use Illuminate\Queue\Attributes\Timeout;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

#[Timeout(500)]
#[Delay(600)]
class JobToSeedRolesAndPermissionsIntoTenantDB implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Tenant $tenant,
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

        $tenant = $this->tenant;

        tenancy()->initialize($tenant);

        try {

            Artisan::call('db:seed', [
                    '--class' => RolesAndPermissionsSeeder::class,
                    '--force' => true,
                ]);

            

            DB::commit();


        } 
        catch (\Throwable $th) {

            broadcast(new FailedToSeedRolesAndPermissionsIntoCreatedTenantDBEvent($this->tenant, $th->getMessage()));

            DB::rollBack();
        }


        tenancy()->end();

    }
}
