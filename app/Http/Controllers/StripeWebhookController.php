<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Tenant;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\Exception $e) {
            Log::error('❌ Stripe webhook signature verification failed: ' . $e->getMessage());
            return response('Invalid', 400);
        }

        $eventType = $event->type;
        $object = $event->data->object;

        switch ($eventType) {
            case 'checkout.session.completed':
                $this->handleCheckoutCompleted($object);
                break;

            case 'payment_intent.payment_failed':
                $this->handlePaymentFailed($object);
                break;

            case 'checkout.session.expired':
                $this->handleSessionExpired($object);
                break;

            default:
                Log::info("📬 Unhandled Stripe event: {$eventType}");
        }

        return response('ok', 200);
    }

    protected function handleCheckoutCompleted($session)
    {
        Log::debug('Stripe session data', (array)$session);

        $tenantId = $session->metadata->tenant_id ?? null;
        $orderId = $session->metadata->order_id ?? null;

        if (!$tenantId || !$orderId) {
            Log::warning('⚠️ Missing tenant_id or order_id in Stripe session metadata');
            return;
        }

        $tenant = Tenant::find($tenantId);
        if (!$tenant) {
            Log::warning("⚠️ Tenant not found for ID: {$tenantId}");
            return;
        }

        // Initialize tenancy for this tenant
        tenancy()->initialize($tenant);

        $order = Order::find($orderId);
        if (!$order) {
            Log::warning("⚠️ Order not found for ID: {$orderId}");
            return;
        }

        // Save payment info using your PaymentService
        try {
            Log::info("🔁 Attempting to save payment for Order #{$order->id}");

            PaymentService::savePayment($order->id, $order->customer_id, [
                'payment_method' => $session->payment_method_types[0] ?? 'stripe',
                'transaction_id' => $session->payment_intent,
                'amount' => $session->amount_total / 100,
                'status' => 'paid',
                'provider' => 'stripe',
                'provider_customer_id' => $session->customer,
            ]);

            Log::info("✅ Payment saved successfully");

            $order->update(['status' => 'paid']);

            // Update stock for products or variants
            foreach ($order->items as $item) {
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

            Log::info("✅ Order #{$orderId} marked as paid, payment saved and stock updated.");
        } catch (\Throwable $e) {
            Log::error("❌ Failed to save payment or update order: " . $e->getMessage());
        }
    }

    protected function handlePaymentFailed($paymentIntent)
    {
        $orderId = $paymentIntent->metadata->order_id ?? null;
        $tenantId = $paymentIntent->metadata->tenant_id ?? null;

        if (!$tenantId || !$orderId) {
            Log::warning('⚠️ Missing tenant_id or order_id in payment_intent metadata');
            return;
        }

        $tenant = Tenant::find($tenantId);
        if (!$tenant) {
            Log::warning("⚠️ Tenant not found for ID: {$tenantId}");
            return;
        }

        tenancy()->initialize($tenant);

        $order = Order::find($orderId);
        if ($order) {
            $order->update(['status' => 'failed']);
            Log::warning("❌ Payment failed for Order #{$orderId}");
        } else {
            Log::warning("⚠️ Order not found for failed payment intent (ID: {$paymentIntent->id})");
        }
    }

    protected function handleSessionExpired($session)
    {
        $tenantId = $session->metadata->tenant_id ?? null;
        $orderId = $session->metadata->order_id ?? null;

        if (!$tenantId || !$orderId) {
            Log::warning('⚠️ Missing tenant_id or order_id in expired session metadata');
            return;
        }

        $tenant = Tenant::find($tenantId);
        if (!$tenant) {
            Log::warning("⚠️ Tenant not found for ID: {$tenantId}");
            return;
        }

        tenancy()->initialize($tenant);

        $order = Order::find($orderId);
        if ($order) {
            $order->update(['status' => 'expired']);
            Log::info("⌛ Order #{$orderId} marked as expired");
        } else {
            Log::info("⌛ Session expired but order not found (ID: {$orderId})");
        }
    }
}
