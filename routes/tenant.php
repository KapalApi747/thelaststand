<?php

declare(strict_types=1);

use App\Http\Middleware\AuthenticateTenant;
use App\Livewire\TenantDashboard;
use App\Livewire\TenantLogin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {

    Route::get('/', TenantLogin::class)->name('tenant.login');

    //Route::get('/tenant-login', TenantLogin::class)->name('tenant.login');

    Route::get('/test-tenant', function () {
        return 'You are inside a tenant: ' . tenant('id');
    })->name('tenant.test');

    Route::get('/debug-session', function () {
        return [
            'session' => session()->all(),
            'user' => auth()->user(),
            'cookie' => request()->cookie(config('session.cookie')),
            'domain' => request()->getHost(),
        ];
    });

    Route::get('/tenant-dashboard', TenantDashboard::class)
        ->name('tenant.dashboard');

    Route::get('/test-page-1', \App\Livewire\TestPageOne::class)->name('tenant.test1');
    Route::get('/test-page-2', \App\Livewire\TestPageTwo::class)->name('tenant.test2');
});

