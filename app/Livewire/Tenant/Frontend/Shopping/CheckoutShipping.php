<?php

namespace App\Livewire\Tenant\Frontend\Shopping;

use App\Models\ShippingMethod;
use App\Services\CartService;
use App\Services\OrderService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('t-shop-layout')]
class CheckoutShipping extends Component
{
    public $shippingMethod = null;
    public $carrier = null;

    public $shippingOptions = [];

    protected $rules = [
        'shippingMethod' => 'required|in:parcel,mail,pickup,express,courier',
        'carrier' => 'nullable|string',
    ];

    public function mount()
    {
        session()->forget('shipping_method');
        session()->forget('shipping_carrier');
        session()->forget('shipping_cost');

        $this->shippingOptions = ShippingMethod::where('enabled', true)
            ->get()
            ->keyBy('code')
            ->map(fn ($method) => [
                'label' => $method->label,
                'cost' => $method->cost,
                'carriers' => $method->carriers,
            ])
            ->toArray();
    }

    public function updatedShippingMethod($value)
    {
        if (empty($this->shippingOptions[$value]['carriers'])) {
            $this->carrier = null;
        }
        $this->validateOnly('shippingMethod');
        $this->sendShippingInfo();
    }

    public function updatedCarrier()
    {
        $this->validateOnly('carrier');
        $this->sendShippingInfo();
    }

    public function sendShippingInfo()
    {
        $shippingCost = $this->shippingMethod
            ? $this->shippingOptions[$this->shippingMethod]['cost']
            : 0;

        $shippingInfo = [
            'method' => $this->shippingMethod,
            'carrier' => $this->carrier,
            'cost' => $shippingCost,
        ];

        session()->put('shipping_cost', $shippingCost);
        session()->put('shipping_method', $this->shippingMethod);
        session()->put('shipping_carrier', $this->carrier);

        $this->dispatch('shippingUpdated', $shippingInfo);
    }

    public function confirmShipping()
    {
        $this->sendShippingInfo();

        // Gather necessary data
        $customerInfo = session('checkout_customer_info', []);
        $cartItems = CartService::retrieveCart();
        $shippingInfo = [
            'method' => $this->shippingMethod,
            'carrier' => $this->carrier,
            'cost' => $this->shippingOptions[$this->shippingMethod]['cost'] ?? 0,
        ];
        $authCustomerId = auth('customer')->id();

        if ($authCustomerId) {
            $customerId = $authCustomerId;
        } else {
            $customerId = null;
        }

        $order = OrderService::createOrder($customerInfo, $cartItems, $shippingInfo, $customerId);

        // Store the created order ID in session for payment step
        session()->put('current_order_id', $order->id);

        return redirect()->route('shop.checkout-payment');
    }

    public function render()
    {
        return view('livewire.tenant.frontend.shopping.checkout-shipping');
    }
}
