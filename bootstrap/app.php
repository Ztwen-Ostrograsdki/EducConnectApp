<?php

use App\Console\Commands\CreateTenantTestCommand;
use App\Console\Commands\DropAllTenantsCommand;
use App\Console\Commands\InitAdminCentralCommand;
use App\Console\Commands\RefreshEducCommand;
use App\Console\Commands\SyncTenantStatistics;
use App\Http\Middleware\CheckIfTenantDomainNotBlocked;
use App\Http\Middleware\CheckIfTenantDomainNotOpenOnlyForTenant;
use App\Http\Middleware\CheckSuperAdmin;
use App\Http\Middleware\EnsureTenantNotDeletedAt;
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
use Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedOnDomainException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',

        then: function () {
            $domains = config('tenancy.central_domains');
            if(count($domains)){
                foreach ($domains as $domain) {

                Route::middleware('web')
                    ->domain($domain)
                    ->group(base_path('routes/web.php'));
            }
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
            'tenant.domain.open' => CheckIfTenantDomainNotBlocked::class,
            'tenant.domain.open.for.others.too' => CheckIfTenantDomainNotOpenOnlyForTenant::class,
            'tenant.domain.not.deleted.at' => EnsureTenantNotDeletedAt::class,
            'tenant.auth' => TenantAuthenticate::class,
            'guest' => RedirectIfAuthenticated::class,
        ]);

    })
    ->withCommands([
        SyncTenantStatistics::class,
        RefreshEducCommand::class,
        CreateTenantTestCommand::class,
        InitAdminCentralCommand::class,
        DropAllTenantsCommand::class,
    ])
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function(TenantCouldNotBeIdentifiedOnDomainException $e, $request){
            abort(404);
        });
    })->create();
