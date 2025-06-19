<?php

namespace App\Livewire\Tenant\Frontend\Shopping;

use App\Models\Order;
use App\Services\CartService;
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
            $stripe = new StripeClient(config('services.stripe.secret'));

            $session = $stripe->checkout->sessions->retrieve($sessionId);

            if ($session->payment_status !== 'paid') {
                Log::warning("Customer arrived at success page with unpaid session ID: {$sessionId}");
            }

            CartService::clearCart();

            session()->forget([
                'checkout_customer_info',
                'shipping_method',
                'shipping_carrier',
                'shipping_cost',
            ]);

        } catch (\Throwable $e) {
            Log::error("Order creation failed: " . $e->getMessage());
            abort(500, "An error occurred while finalizing your order. Please contact support.");
        }
    }

    public function render()
    {
        return view('livewire.tenant.frontend.shopping.checkout-success');
    }
}
