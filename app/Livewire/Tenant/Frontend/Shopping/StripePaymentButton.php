<?php

namespace App\Livewire\Tenant\Frontend\Shopping;

use Livewire\Component;
use Stripe\StripeClient;

class StripePaymentButton extends Component
{
    public function checkoutStripe()
    {
        $cartTenantKey = 'cart_' . tenant()->id;
        $cart = session($cartTenantKey, []);

        if (empty($cart)) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Your cart is empty.']);
            return;
        }

        $customer = auth('customer')->user();
        $guest = session('checkout_customer_info');

        $stripe = new StripeClient(config('services.stripe.secret'));

        $lineItems = [];

        foreach($cart as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $item['name'],
                    ],
                    'unit_amount' => $item['price'] * 100,
                ],
                'quantity' => $item['quantity'],
            ];
        }

        $checkoutData = [
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('shop.checkout-success'),
            'cancel_url' => route('shop.checkout-cancel'),
        ];

        if ($customer) {
            $checkoutData['customer_email'] = $customer->email;
        } elseif (!empty($guest['email']) && filter_var($guest['email'], FILTER_VALIDATE_EMAIL)) {
            $checkoutData['customer_email'] = $guest['email'];
        }

        $checkoutData['metadata'] = [
            'tenant_id' => tenant()->id,
            'customer_type' => $customer ? 'logged-in' : 'guest',
            'customer_email' => $checkoutData['customer_email'] ?? 'unknown',
        ];

        $session = $stripe->checkout->sessions->create($checkoutData);

        return redirect()->away($session->url);
    }

    public function render()
    {
        return view('livewire.tenant.frontend.shopping.stripe-payment-button');
    }
}
