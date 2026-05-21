<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Stancl\Tenancy\Database\Models\Domain;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        require_once app_path('Helpers/helpers.php');
        require_once app_path('Helpers/modules.php');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Initialiser le tenant sur chaque requête
        $this->app->booted(function () {
            $request = request();
            $host = $request->getHost();

            // Ne pas initialiser si c'est un domaine central
            $centralDomains = config('tenancy.central_domains', []);

            if (! in_array($host, $centralDomains)) {
                try {
                    $domain = Domain::where('domain', $host)->first();
                    if ($domain) {
                        tenancy()->initialize($domain->tenant);
                    }
                } catch (\Exception $e) {
                    //
                }
            }
        });
    }
}
