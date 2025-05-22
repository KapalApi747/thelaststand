<?php

declare(strict_types=1);

use App\Livewire\Tenant\Backend\ProductManagement;
use App\Livewire\Tenant\Backend\StoreSettings;
use App\Livewire\Tenant\Backend\TenantDashboard;
use App\Livewire\TenantLogin;
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

    Livewire::setScriptRoute(function ($handle) {
        return Route::get('/livewire/livewire.js', $handle);
    });

    Route::get('/', TenantLogin::class)->name('tenant.login');

    Route::middleware(['web','tenant.auth'])
        ->prefix('tenant-dashboard')
        ->as('tenant-dashboard.')
        ->group(function () {
            Route::get('/', TenantDashboard::class)->name('index');
            Route::get('/store-settings', StoreSettings::class)->name('store-settings');
            Route::get('/product-management', ProductManagement::class)->name('product-management');
        });
});

