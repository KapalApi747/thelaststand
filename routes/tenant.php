<?php

declare(strict_types=1);

use App\Http\Controllers\CartController;
use App\Http\Controllers\StripeWebhookController;
use App\Livewire\Tenant\Backend\Categories\CategoryCreation;
use App\Livewire\Tenant\Backend\Categories\CategoryEdit;
use App\Livewire\Tenant\Backend\Categories\CategoryManagement;
use App\Livewire\Tenant\Backend\Customers\CustomerCreation;
use App\Livewire\Tenant\Backend\Customers\CustomerEdit;
use App\Livewire\Tenant\Backend\Customers\CustomerIndex;
use App\Livewire\Tenant\Backend\Customers\CustomerView;
use App\Livewire\Tenant\Backend\Orders\OrderEdit;
use App\Livewire\Tenant\Backend\Orders\OrderIndex;
use App\Livewire\Tenant\Backend\Orders\OrderView;
use App\Livewire\Tenant\Backend\Pages\PageEdit;
use App\Livewire\Tenant\Backend\Pages\PageIndex;
use App\Livewire\Tenant\Backend\Payouts\TenantPayoutIndex;
use App\Livewire\Tenant\Backend\Payouts\TenantPayoutView;
use App\Livewire\Tenant\Backend\Products\ProductCreation;
use App\Livewire\Tenant\Backend\Products\ProductEdit;
use App\Livewire\Tenant\Backend\Products\ProductManagement;
use App\Livewire\Tenant\Backend\Products\ProductView;
use App\Livewire\Tenant\Backend\Profile\StoreSettings;
use App\Livewire\Tenant\Backend\Roles\RoleCreation;
use App\Livewire\Tenant\Backend\Roles\RoleEdit;
use App\Livewire\Tenant\Backend\Roles\RoleIndex;
use App\Livewire\Tenant\Backend\Shipping\ShippingMethodEdit;
use App\Livewire\Tenant\Backend\Shipping\ShippingMethodForm;
use App\Livewire\Tenant\Backend\Shipping\ShippingMethodIndex;
use App\Livewire\Tenant\Backend\Statistics\ShopStatistics;
use App\Livewire\Tenant\Backend\TenantDashboard;
use App\Livewire\Tenant\Backend\Users\AccountEdit;
use App\Livewire\Tenant\Backend\Users\UserEdit;
use App\Livewire\Tenant\Backend\Users\UserIndex;
use App\Livewire\Tenant\Backend\Users\UserRegistration;
use App\Livewire\Tenant\Backend\Users\UserView;
use App\Livewire\Tenant\Frontend\Customers\CustomerAddresses;
use App\Livewire\Tenant\Frontend\Customers\CustomerLogin;
use App\Livewire\Tenant\Frontend\Customers\CustomerOrders;
use App\Livewire\Tenant\Frontend\Customers\CustomerOrderView;
use App\Livewire\Tenant\Frontend\Customers\CustomerProfile;
use App\Livewire\Tenant\Frontend\Customers\CustomerRegistration;
use App\Livewire\Tenant\Frontend\Customers\CustomerSettings;
use App\Livewire\Tenant\Frontend\Main\Cart;
use App\Livewire\Tenant\Frontend\Main\Homepage;
use App\Livewire\Tenant\Frontend\Main\PageShow;
use App\Livewire\Tenant\Frontend\Main\ShopProducts;
use App\Livewire\Tenant\Frontend\Shopping\CheckoutCancel;
use App\Livewire\Tenant\Frontend\Shopping\CheckoutForm;
use App\Livewire\Tenant\Frontend\Shopping\CheckoutPayment;
use App\Livewire\Tenant\Frontend\Shopping\CheckoutShipping;
use App\Livewire\Tenant\Frontend\Shopping\CheckoutSuccess;
use App\Livewire\Tenant\Frontend\Shopping\Products\ProductShow;
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

Route::middleware(['universal', InitializeTenancyByDomain::class])->group(function () {
    Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])->name('stripe-webhook');

    Livewire::setScriptRoute(function ($handle) {
        return Route::get('/livewire/livewire.js', $handle);
    });
});

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {

    Route::get('/', Homepage::class)->name('tenant-homepage');

    Route::get('/{slug}', PageShow::class)
        ->name('page-show')
        ->where('slug', 'privacy-policy|terms-of-service|about-us|cookies-policy');

    Route::prefix('shop')
        ->as('shop.')
        ->group(function () {

            Route::get('login', TenantLogin::class)->name('login');
            Route::get('register', CustomerRegistration::class)->name('customer-register');

            Route::middleware(['customer.auth'])->group(function () {
                Route::post('/logout', function () {
                    Auth::guard('customer')->logout();
                    Auth::guard('web')->logout();
                    request()->session()->regenerateToken();
                    return redirect()->route('shop.shop-products');
                })->name('customer-logout');

                Route::get('my-orders', CustomerOrders::class)->name('customer-orders');
                Route::get('my-orders/{order:order_number}', CustomerOrderView::class)->name('customer-order-view');
                Route::get('my-addresses', CustomerAddresses::class)->name('customer-addresses');
                Route::get('my-profile', CustomerProfile::class)->name('customer-profile');
                Route::get('my-settings', CustomerSettings::class)->name('customer-settings');
            });

            Route::get('products', ShopProducts::class)->name('shop-products');
            Route::get('products/{slug}', ProductShow::class)->name('shop-product-show');

            Route::get('cart', Cart::class)->name('shop-cart');
            Route::post('cart/add', [CartController::class, 'add'])->name('add-to-cart');

            Route::get('/checkout', CheckoutForm::class)->name('checkout-form');
            Route::get('/checkout/shipping', CheckoutShipping::class)->name('checkout-shipping');
            Route::get('/checkout/payment', CheckoutPayment::class)->name('checkout-payment');
            Route::get('/checkout/success', CheckoutSuccess::class)->name('checkout-success');
            Route::get('/checkout/cancel', CheckoutCancel::class)->name('checkout-cancel');
        });

    Route::middleware(['web','tenant.auth'])
        ->prefix('tenant-dashboard')
        ->as('tenant-dashboard.')
        ->group(function () {

            Route::get('/', TenantDashboard::class)->name('index');

            Route::get('/account/{user}/edit', AccountEdit::class)->name('account-edit');

            Route::middleware(['role:admin'])->group(function () {
                Route::get('/profile-settings', StoreSettings::class)->name('store-settings');
            });

            Route::middleware(['permission:manage products|manage categories'])->group(function () {
                Route::get('/products', ProductManagement::class)->name('product-management');
                Route::get('/product-creation', ProductCreation::class)->name('product-creation');
                Route::get('/products/{product:slug}', ProductView::class)->name('product-view');
                Route::get('/products/{product:slug}/edit', ProductEdit::class)->name('product-edit');

                Route::get('/categories', CategoryManagement::class)->name('category-management');
                Route::get('/categories/category-creation', CategoryCreation::class)->name('category-creation');
                Route::get('/categories/{category:slug}/edit', CategoryEdit::class)->name('category-edit');
            });

            Route::middleware(['role:admin'])->group(function () {
                Route::get('/users', UserIndex::class)->name('user-index');
                Route::get('/user-register', UserRegistration::class)->name('user-register');
                Route::get('/users/{user:slug}', UserView::class)->name('user-view');
                Route::get('/users/{user:slug}/edit', UserEdit::class)->name('user-edit');
            });

            Route::middleware(['role:admin'])->group(function () {
                Route::get('/customers', CustomerIndex::class)->name('customer-index');
                Route::get('/customers/customer-creation', CustomerCreation::class)->name('customer-creation');
                Route::get('/customers/{customer}', CustomerView::class)->name('customer-view');
                Route::get('/customers/{customer}/edit', CustomerEdit::class)->name('customer-edit');
            });

            Route::middleware(['role:admin'])->group(function () {
                Route::get('/roles', RoleIndex::class)->name('role-index');
                Route::get('/roles/role-creation', RoleCreation::class)->name('role-creation');
                Route::get('/roles/{role}', RoleEdit::class)->name('role-edit');
            });

            Route::middleware(['permission:manage orders'])->group(function () {
                Route::get('/orders', OrderIndex::class)->name('order-index');
                Route::get('/orders/{order}', OrderView::class)->name('order-view');
                Route::get('orders/{order}/edit', OrderEdit::class)->name('order-edit');
            });

            Route::middleware(['role:admin|analyst'])->group(function () {
                Route::get('/statistics', ShopStatistics::class)->name('shop-statistics');
            });

            Route::middleware(['role:admin'])->group(function () {
                Route::get('/payouts', TenantPayoutIndex::class)->name('tenant-payouts');
                Route::get('/payouts/{payoutId}', TenantPayoutView::class)->name('tenant-payout-view');
            });

            Route::middleware(['role:admin'])->group(function () {
                Route::get('/shipping-methods', ShippingMethodIndex::class)->name('shipping-method-index');
                Route::get('/shipping-methods-add', ShippingMethodForm::class)->name('shipping-method-form');
                Route::get('/shipping-methods/{shippingMethod}/edit', ShippingMethodEdit::class)->name('shipping-method-edit');
            });

            Route::middleware(['role:admin'])->group(function () {
                Route::get('/pages', PageIndex::class)->name('page-index');
                Route::get('/pages/{page}/edit', PageEdit::class)->name('page-edit');
            });
        });
});

