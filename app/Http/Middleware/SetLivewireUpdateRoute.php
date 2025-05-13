<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

class SetLivewireUpdateRoute
{
    public function handle($request, Closure $next)
    {
        $host = Request::getHost();
        $centralDomains = config('tenancy.central_domains');

        if (in_array($host, $centralDomains)) {
            // Central domain (e.g., myapp.local)
            Livewire::setUpdateRoute(function ($handle) {
                return Route::middleware('web')->post('/livewire/update', $handle);
            });
        } else {
            // Tenant domain (e.g., tenant1.myapp.local)
            Livewire::setUpdateRoute(function ($handle) {
                return Route::middleware([
                    'web',
                    InitializeTenancyByDomain::class,
                    PreventAccessFromCentralDomains::class,
                ])->post('/livewire/update', $handle);
            });
        }

        return $next($request);
    }
}

