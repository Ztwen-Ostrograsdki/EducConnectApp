<?php

namespace App\Jobs;

use App\Models\Tenant;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;

class JobToCreateTenantDirectories implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $tenantId,
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $tenant = Tenant::find($this->tenantId);

        if($tenant){

            $directories = [
                'profiles',
                'documents',
                'bulletins',
                'epreuves',
                'schools',
                'temp',
            ];

            foreach ($directories as $directory) {

                Storage::disk('public')->makeDirectory(
                    "tenants/{$tenant?->id}/{$directory}"
                );
            }
        }
    }
}
