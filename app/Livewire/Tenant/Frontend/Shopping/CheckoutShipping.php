<?php

namespace App\Livewire\Tenant\Frontend\Shopping;

use App\Services\CartService;
use App\Services\OrderService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('t-shop-layout')]
class CheckoutShipping extends Component
{
    public $shippingMethod = null;
    public $carrier = null;

    public $shippingOptions = [
        'parcel' => [
            'label' => 'Parcel (Standard Shipping)',
            'cost' => 5.00,
            'carriers' => ['PostNL', 'DHL', 'FedEx', 'UPS'],
        ],
        'mail' => [
            'label' => 'Mail (Letter/Small Package)',
            'cost' => 2.50,
            'carriers' => ['PostNL'],
        ],
        'pickup' => [
            'label' => 'Pickup (In-Store Pickup)',
            'cost' => 0.00,
            'carriers' => [],
        ],
        'express' => [
            'label' => 'Express (Faster Parcel Shipping)',
            'cost' => 15.00,
            'carriers' => ['DHL', 'FedEx', 'UPS'],
        ],
        'courier' => [
            'label' => 'Courier (Personal Delivery)',
            'cost' => 25.00,
            'carriers' => ["Yuri's Special Delivery Service"],
        ],
    ];

    protected $rules = [
        'shippingMethod' => 'required|in:parcel,mail,pickup,express,courier',
        'carrier' => 'nullable|string',
    ];

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
