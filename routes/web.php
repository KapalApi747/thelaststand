<?php

use App\Livewire\Admin\EditTenantProfile;
use App\Livewire\Admin\TenantProfilePage;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;



foreach (config('tenancy.central_domains') as $domain) {

    Route::middleware('web')->domain($domain)->group(function () {

        Route::get('/', function () {
            return view('welcome');
        })->name('home');

        require __DIR__.'/auth.php';

        Route::view('dashboard', 'dashboard')
            ->middleware(['auth', 'verified'])
            ->name('dashboard');

        Route::middleware(['auth'])->group(function () {
            Route::redirect('settings', 'settings/profile');

            Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
            Volt::route('settings/password', 'settings.password')->name('settings.password');
            Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

            Route::view('dashboard', 'dashboard')->name('dashboard');
        });

        Route::middleware(['auth'])
            ->prefix('dashboard')
            ->as('dashboard.')
            ->group(function () {
                Route::get('/tenant-profiles', TenantProfilePage::class)->name('admin.tenant-profiles');
                Route::get('/tenant-profiles/{tenant}/edit', EditTenantProfile::class)->name('admin.tenant-profiles.edit');
            });
    });
}
