<?php

namespace App\Livewire\Tenant\Frontend\Shopping;

use App\Models\Order;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Stripe\StripeClient;

#[Layout('t-shop-layout')]
class CheckoutSuccess extends Component
{
    public Order $order;

    public function mount()
    {
        $sessionId = request()->query('session_id');

        if (!$sessionId) {
            abort(404, "No checkout session ID provided.");
        }

        try {
            // Initialize Stripe client
            $stripe = new StripeClient(config('services.stripe.secret'));

            // Retrieve the checkout session from Stripe
            $session = $stripe->checkout->sessions->retrieve($sessionId);

            if ($session->payment_status !== 'paid') {
                Log::warning("Customer arrived at success page with unpaid session ID: {$sessionId}");
                // We still let them land, but the order won't be marked paid
            }

            // Gather data
            $customerInfo = session('checkout_customer_info', []);
            $cartItems = CartService::retrieveCart();
            $authCustomerId = auth('customer')->id();

            $shippingInfo = [
                'method' => session('shipping_method'),
                'carrier' => session('shipping_carrier'),
                'cost' => session('shipping_cost'),
            ];

            // Save the order
            //$this->order = OrderService::createOrder($customerInfo, $cartItems, $shippingInfo, $authCustomerId);

            // Include order ID in session metadata for webhook reference (optional double-save fallback)
            // You can use this order ID when creating the Stripe Checkout session originally

            // Clear cart + shipping info
            CartService::clearCart();

            session()->forget([
                'checkout_customer_info',
                'shipping_method',
                'shipping_carrier',
                'shipping_cost',
            ]);

        } catch (\Throwable $e) {
            Log::error("Order creation failed: " . $e->getMessage());
            abort(500, "An error occurred while finalizing your order.");
        }
    }

    public function render()
    {
        return view('livewire.tenant.frontend.shopping.checkout-success');
    }
}
