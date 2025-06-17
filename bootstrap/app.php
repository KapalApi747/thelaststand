<?php

use App\Http\Middleware\AuthenticateCentral;
use App\Http\Middleware\AuthenticateCustomer;
use App\Http\Middleware\AuthenticateTenant;
use App\Http\Middleware\OnlyTenancyOnTenantDomain;
use App\Http\Middleware\SessionCookie;
use App\Http\Middleware\SetLivewireUpdateRoute;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        $middleware->prepend(SessionCookie::class);

        $middleware->append(SetLivewireUpdateRoute::class);

        $middleware->alias([
            'central.auth' => AuthenticateCentral::class,
            'tenant.auth' => AuthenticateTenant::class,
            'customer.auth' => AuthenticateCustomer::class,

            'tenant.onlytenancyontenant' => OnlyTenancyOnTenantDomain::class,
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
        ]);

        $middleware->group('universal', []);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
