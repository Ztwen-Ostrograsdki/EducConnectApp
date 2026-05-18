<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__.'/../routes/console.php',
        health: '/up',

        then: function () {
            foreach (config('tenancy.central_domains') as $domain) {

                Route::middleware('web')
                    ->domain($domain)
                    ->group(base_path('routes/web.php'));
            }

            Route::middleware('web')
                ->group(base_path('routes/tenant.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        
        $middleware->prependToGroup('web',
            \App\Http\Middleware\InitializeTenancyByDomainForLivewire::class
        );
        
        $middleware->web(
            remove: [
            PreventRequestForgery::class,
        ]);
        $middleware->alias([
            'role'       => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'check.super.admin'  => \App\Http\Middleware\CheckSuperAdmin::class,
            'tenant.init' => \App\Http\Middleware\InitializeTenancyByDomainForLivewire::class,
            'tenant.auth' => \App\Http\Middleware\TenantAuthenticate::class,
        ]);

       
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
