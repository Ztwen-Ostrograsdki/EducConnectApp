<?php

use App\Http\Middleware\CheckSuperAdmin;
use App\Http\Middleware\InitializeTenancyByDomainForLivewire;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\TenantAuthenticate;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

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
            InitializeTenancyByDomainForLivewire::class
        );

        $middleware->web(
            remove: [
                PreventRequestForgery::class,
            ]);
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
            'check.super.admin' => CheckSuperAdmin::class,
            'tenant.init' => InitializeTenancyByDomainForLivewire::class,
            'tenant.auth' => TenantAuthenticate::class,
            'guest' => RedirectIfAuthenticated::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
