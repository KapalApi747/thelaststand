<?php

use App\Livewire\Admin\Backend\Tenants\EditTenantProfile;
use App\Livewire\Admin\Backend\Tenants\TenantProfilePage;
use App\Livewire\Admin\Backend\Tenants\ViewTenantProfile;
use App\Livewire\Admin\Frontend\CentralLogin;
use App\Livewire\TenantRegistration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

foreach (config('tenancy.central_domains') as $domain) {

    Route::middleware('web')->domain($domain)->group(function () {

        require __DIR__.'/auth.php';

        /*Route::get('/', function () {
            return view('welcome');
        })->name('home');*/

        /*Route::view('dashboard', 'dashboard')
            ->middleware(['auth', 'verified'])
            ->name('dashboard');*/

        /*Route::middleware(['auth'])->group(function () {
            Route::redirect('settings', 'settings/profile');

            Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
            Volt::route('settings/password', 'settings.password')->name('settings.password');
            Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

            Route::view('dashboard', 'dashboard')->name('dashboard');
        });*/

        Route::get('/', function () {
            return view('central-landing');
        })->name('home');

        Route::get('/central-login', CentralLogin::class)->name('central-login');

        Route::post('/central-logout', function () {
            Auth::guard('central')->logout();
            request()->session()->regenerateToken();
            return redirect()->route('home');
        })->name('central-logout');

        Route::get('/tenant-register', TenantRegistration::class)->name('tenant-register');

        Route::middleware(['central.auth'])
            ->prefix('dashboard')
            ->as('dashboard.')
            ->group(function () {
                Route::get('/tenant-profiles', TenantProfilePage::class)->name('tenant-profiles');
                Route::get('/tenant-profiles/{tenant}', ViewTenantProfile::class)->name('tenant-profiles.view');
                Route::get('/tenant-profiles/{tenant}/edit', EditTenantProfile::class)->name('tenant-profiles.edit');
            });
    });
}
