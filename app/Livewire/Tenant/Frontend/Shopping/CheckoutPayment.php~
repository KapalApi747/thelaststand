<?php

namespace App\Livewire\Tenant\Frontend\Shopping;

use App\Services\CartService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('t-shop-layout')]
class CheckoutPayment extends Component
{
    public $customerInfo = [];
    public $cartTotal = 0;
    public $shippingCost = 0;
    public $taxAmount = 0;
    public $grandTotal = 0;

    protected $listeners = ['shippingUpdated'];

    public function mount()
    {
        $this->customerInfo = session('checkout_customer_info', []);

        if (auth('customer')->check()) {
            $this->customerInfo = array_merge($this->customerInfo, [
                'name' => auth('customer')->user()->name,
                'email' => auth('customer')->user()->email,
            ]);
        }

        if (!session()->has('shipping_method')) {
            return redirect()->route('shop.checkout-shipping');
        }

        $this->updateCartTotals();
    }

    public function shippingUpdated($shippingInfo)
    {
        session()->put('shipping_cost', $shippingInfo['cost'] ?? 0);
        session()->put('shipping_method', $shippingInfo['method'] ?? null);
        session()->put('shipping_carrier', $shippingInfo['carrier'] ?? null);

        $this->updateCartTotals();
    }

    protected function updateCartTotals()
    {
        $this->cartTotal = CartService::cartTotal();
        $this->shippingCost = CartService::shippingCost();
        $this->grandTotal = CartService::grandTotal();
        $this->taxAmount = CartService::taxAmount();
    }

    public function render()
    {
        return view('livewire.tenant.frontend.shopping.checkout-payment');
    }
}
