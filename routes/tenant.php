<?php

declare(strict_types=1);

use App\Livewire\Tenant\Backend\Categories\CategoryManagement;
use App\Livewire\Tenant\Backend\Orders\OrderIndex;
use App\Livewire\Tenant\Backend\Orders\OrderView;
use App\Livewire\Tenant\Backend\Products\ProductEdit;
use App\Livewire\Tenant\Backend\Products\ProductManagement;
use App\Livewire\Tenant\Backend\Products\ProductView;
use App\Livewire\Tenant\Backend\Profile\StoreSettings;
use App\Livewire\Tenant\Backend\TenantDashboard;
use App\Livewire\Tenant\Backend\Users\UserEdit;
use App\Livewire\Tenant\Backend\Users\UserIndex;
use App\Livewire\Tenant\Backend\Users\UserRegistration;
use App\Livewire\Tenant\Backend\Users\UserView;
use App\Livewire\Tenant\Frontend\Main\Cart;
use App\Livewire\Tenant\Frontend\Main\ShopProducts;
use App\Livewire\Tenant\Frontend\Shopping\CheckoutCancel;
use App\Livewire\Tenant\Frontend\Shopping\CheckoutSuccess;
use App\Livewire\TenantLogin;
use Illuminate\Support\Facades\Route;
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

    /*Livewire::setScriptRoute(function ($handle) {
        return Route::get('/livewire/livewire.js', $handle);
    });*/

    Route::get('/', TenantLogin::class)->name('tenant.login');

    Route::prefix('shop')
        ->as('shop.')
        ->group(function () {
            Route::get('products', ShopProducts::class)->name('shop-products');
            Route::get('cart', Cart::class)->name('shop-cart');

            Route::get('/checkout/success', CheckoutSuccess::class)->name('checkout-success');
            Route::get('/checkout/cancel', CheckoutCancel::class)->name('checkout-cancel');
        });

    Route::middleware(['web','tenant.auth'])
        ->prefix('tenant-dashboard')
        ->as('tenant-dashboard.')
        ->group(function () {
            Route::get('/', TenantDashboard::class)->name('index');
            Route::get('/profile-settings', StoreSettings::class)->name('store-settings');

            Route::get('/products', ProductManagement::class)->name('product-management');
            Route::get('/products/{product:slug}', ProductView::class)->name('product-view');
            Route::get('/products/{product:slug}/edit', ProductEdit::class)->name('product-edit');

            Route::get('/categories', CategoryManagement::class)->name('category-management');

            Route::get('/users', UserIndex::class)->name('user-index');
            Route::get('/user-register', UserRegistration::class)->name('user-register');
            Route::get('/users/{user:slug}', UserView::class)->name('user-view');
            Route::get('/users/{user:slug}/edit', UserEdit::class)->name('user-edit');

            Route::get('/orders', OrderIndex::class)->name('order-index');
            Route::get('/orders/{order}', OrderView::class)->name('order-view');
        });
});

