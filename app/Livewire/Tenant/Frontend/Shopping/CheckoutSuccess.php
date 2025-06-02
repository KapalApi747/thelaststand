<?php

namespace App\Livewire\Tenant\Frontend\Shopping;

use App\Models\Order;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\PaymentService;
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
                abort(400, "Payment not completed.");
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
            $this->order = OrderService::createOrder($customerInfo, $cartItems, $shippingInfo, $authCustomerId);

            $customerId = $this->order->customer_id;

            // Save payment info with PaymentService
            PaymentService::savePayment($this->order->id, $customerId, [
                'payment_method' => $session->payment_method_types[0] ?? 'stripe',
                'transaction_id' => $session->payment_intent,
                'amount' => $session->amount_total / 100,
                'status' => 'paid',
                'provider' => 'stripe',
                'provider_customer_id' => $session->customer,
            ]);

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
        return view('livewire.tenant.frontend.shopping.checkout-success', [
            'order' => $this->order
        ]);
    }
}
