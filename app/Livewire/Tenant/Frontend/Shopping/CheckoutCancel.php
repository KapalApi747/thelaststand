<?php

namespace App\Livewire\Tenant\Frontend\Shopping;

use App\Models\Order;
use App\Services\CartService;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Stripe\StripeClient;

#[Layout('t-shop-layout')]
class CheckoutCancel extends Component
{
    public ?Order $order = null;

    public function mount()
    {
        $sessionId = request()->query('session_id');

        if (!$sessionId) {
            abort(404, "No checkout session ID provided.");
        }

        try {
            if ($sessionId) {
                $stripe = new StripeClient(config('services.stripe.secret'));
                $session = $stripe->checkout->sessions->retrieve($sessionId);

                $this->order = Order::where('session_id', $sessionId)->first();

                if (!$this->order) {
                    Log::warning("Order not found for Stripe session ID: {$sessionId}");
                }
            }

            // Fallback: check session for current order ID
            if (!$this->order && session()->has('current_order_id')) {
                $this->order = Order::find(session('current_order_id'));

                if ($this->order) {
                    Log::info("Fallback to session order ID: {$this->order->id}");
                }
            }

            if ($this->order) {
                $this->order->update(['status' => 'cancelled']);

                CartService::clearCart();

                session()->forget([
                    'current_order_id',
                    'checkout_customer_info',
                    'shipping_method',
                    'shipping_carrier',
                    'shipping_cost',
                ]);
            } else {
                Log::warning("CheckoutCancel: Unable to resolve order from session or session_id.");
            }
        } catch (\Throwable $e) {
            Log::error("CheckoutCancel: " . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.tenant.frontend.shopping.checkout-cancel');
    }
}
