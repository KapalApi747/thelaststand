<?php

namespace App\Livewire\Tenant\Frontend\Shopping;

use App\Mail\OrderConfirmation;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\CartService;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Stripe\StripeClient;

#[Layout('t-shop-layout')]
class CheckoutSuccess extends Component
{
    public Order $order;

    public bool $paymentConfirmed = false;
    public string $message = '';

    public function mount()
    {
        $sessionId = request()->query('session_id');

        if (!$sessionId) {
            abort(404, "No checkout session ID provided.");
        }

        // Find the order with this session ID (assumes you store it on orders)
        $this->order = Order::where('session_id', $sessionId)->first();

        if (!$this->order) {
            abort(404, "Order not found for this session.");
        }

        // If order already marked paid by webhook, just confirm
        if ($this->order->status === 'paid') {
            $this->paymentConfirmed = true;
            $this->message = 'Payment confirmed. Thank you for your order!';
            $this->clearCheckoutData();
            return;
        }

        // Fallback: check Stripe API to confirm payment status
        try {
            $stripe = new StripeClient(config('services.stripe.secret'));
            $session = $stripe->checkout->sessions->retrieve($sessionId);

            if ($session->payment_status === 'paid') {
                // Payment confirmed - update order & payment info
                $this->handlePaymentConfirmed($session);
                $this->paymentConfirmed = true;
                $this->message = 'Payment confirmed. Thank you for your order!';
                $this->clearCheckoutData();
            } else {
                // Payment still not confirmed
                $this->paymentConfirmed = false;
                $this->message = 'Payment not confirmed yet. If this is an error, please contact support.';
                Log::warning("Customer arrived at success page with unpaid session ID: {$sessionId}");
            }
        } catch (\Throwable $e) {
            Log::error("Stripe API error checking payment status: " . $e->getMessage());
            $this->paymentConfirmed = false;
            $this->message = 'An error occurred while verifying payment status. Please contact support.';
        }
    }

    protected function handlePaymentConfirmed($session)
    {
        // Update order status
        $this->order->update(['status' => 'paid']);

        // Save payment info (reuse your PaymentService)
        try {
            PaymentService::savePayment($this->order->id, $this->order->customer_id, [
                'payment_method' => $session->payment_method_types[0] ?? 'stripe',
                'transaction_id' => $session->payment_intent,
                'amount' => $session->amount_total / 100,
                'status' => 'completed',
                'provider' => 'stripe',
                'provider_customer_id' => $session->customer,
            ]);

            Mail::to($this->order->customer->email)->send(new OrderConfirmation($this->order));
        } catch (\Throwable $e) {
            Log::error("Failed to save payment info for order {$this->order->id}: " . $e->getMessage());
        }

        // Update stock for products or variants in the order items
        foreach ($this->order->items as $item) {
            if (!empty($item['variant_id'])) {
                $variant = ProductVariant::find($item['variant_id']);
                if ($variant) {
                    $variant->stock = max(0, $variant->stock - $item['quantity']);
                    $variant->save();
                }
            } else {
                $product = Product::find($item['product_id']);
                if ($product) {
                    $product->stock = max(0, $product->stock - $item['quantity']);
                    $product->save();
                }
            }
        }
    }

    protected function clearCheckoutData()
    {
        CartService::clearCart();

        session()->forget([
            'checkout_customer_info',
            'shipping_method',
            'shipping_carrier',
            'shipping_cost',
        ]);
    }

    public function render()
    {
        return view('livewire.tenant.frontend.shopping.checkout-success', [
            'paymentConfirmed' => $this->paymentConfirmed,
            'message' => $this->message,
        ]);
    }
}
