<?php

namespace App\Livewire\Tenant\Frontend\Shopping;

use App\Models\Product;
use App\Models\ProductVariant;
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

        // STOCK CHECK
        foreach ($cart as $item) {
            $quantity = $item['quantity'];

            if (!empty($item['product_variant_id'])) {
                $variant = ProductVariant::find($item['product_variant_id']);
                if (!$variant || $variant->stock < $quantity) {
                    $this->dispatch('notify-error', [
                        'message' => "Sorry, not enough stock for variant of: {$item['name']}."
                    ]);
                    return;
                }
            } else {
                $product = Product::find($item['product_id']);
                if (!$product || $product->stock < $quantity) {
                    $this->dispatch('notify-error', [
                        'message' => "Sorry, not enough stock for: {$item['name']}."
                    ]);
                    return;
                }
            }
        }

        $authCustomer = auth('customer')->user();
        $guest = session('checkout_customer_info');

        $stripe = new StripeClient(config('services.stripe.secret'));

        $lineItems = [];

        foreach ($cart as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $item['name'],
                    ],
                    'unit_amount' => (int) round($item['price'] * 100), // still recommend rounding
                ],
                'quantity' => $item['quantity'],
            ];
        }

        // 💡 Determine Stripe Customer ID
        $customerId = null;

        if ($authCustomer) {
            // Try to get existing Stripe customer ID
            $paymentAccount = $authCustomer->getPaymentAccount('stripe');

            if (!$paymentAccount) {
                // Create new Stripe customer
                $stripeCustomer = $stripe->customers->create([
                    'email' => $authCustomer->email,
                    'name' => $authCustomer->name,
                    'metadata' => ['tenant_id' => tenant()->id],
                ]);

                // Save Stripe customer ID to customer_payment_accounts
                $authCustomer->paymentAccounts()->create([
                    'provider' => 'stripe',
                    'provider_customer_id' => $stripeCustomer->id,
                ]);

                $customerId = $stripeCustomer->id;
            } else {
                $customerId = $paymentAccount->provider_customer_id;
            }
        } elseif ($guest && isset($guest['email'])) {
            // Create a Stripe customer for guest (not stored)
            $stripeCustomer = $stripe->customers->create([
                'email' => $guest['email'],
                'name' => $guest['name'] ?? '',
                'phone' => $guest['phone'] ?? '',
                'address' => [
                    'line1' => $guest['address_line1'] ?? '',
                    'city' => $guest['city'] ?? '',
                    'state' => $guest['state'] ?? '',
                    'postal_code' => $guest['zip'] ?? '',
                    'country' => $guest['country'] ?? '',
                ],
                'metadata' => ['tenant_id' => tenant()->id],
            ]);

            $customerId = $stripeCustomer->id;
        }

        $checkoutData = [
            'customer' => $customerId,
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('shop.checkout-success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('shop.checkout-cancel'),
            'billing_address_collection' => 'required',
            'phone_number_collection' => ['enabled' => true],
            'payment_intent_data' => [
                'shipping' => [
                    'name' => $authCustomer->name ?? $guest['name'] ?? '',
                    'address' => [
                        'line1' => $guest['address_line1'] ?? '',
                        'line2' => $guest['address_line2'] ?? '',
                        'city' => $guest['city'] ?? '',
                        'state' => $guest['state'] ?? '',
                        'postal_code' => $guest['postal_code'] ?? '',
                        'country' => $guest['country'] ?? '',
                    ],
                ],
            ],
            'metadata' => [
                'tenant_id' => (string) tenant()->id,
                'customer_type' => $authCustomer ? 'logged-in' : 'guest',
                'customer_email' => (string) ($authCustomer->email ?? $guest['email'] ?? 'unknown'),
            ],
        ];

        $session = $stripe->checkout->sessions->create($checkoutData);

        return redirect()->away($session->url);
    }

    public function render()
    {
        return view('livewire.tenant.frontend.shopping.stripe-payment-button');
    }
}
