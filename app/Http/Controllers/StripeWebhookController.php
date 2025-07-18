<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmation;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Tenant;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Stripe\Webhook;

/**
 * Verwerkt inkomende Stripe-webhookevents voor het multi-tenant e-commerceplatform THE LAST STAND.
 *
 * Deze controller valideert de binnenkomende Stripe-events met behulp van het webhookgeheim
 * en handelt vervolgens het juiste eventtype af.
 *
 * Ondersteunde eventtypes:
 * - checkout.session.completed → Markeert de bestelling als betaald, slaat de betaling op, past voorraad aan, verstuurt bevestigingsmail.
 * - payment_intent.payment_failed → Markeert de bestelling als mislukt.
 * - checkout.session.expired → Markeert de bestelling als verlopen.
 *
 * Multitenancy wordt dynamisch geïnitialiseerd op basis van de `tenant_id` uit de Stripe-metadata.
 *
 * @zie https://stripe.com/docs/webhooks
 * @zie \App\Services\PaymentService::savePayment
 */

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

        // Tenancy initialization
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
                'status' => 'completed',
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

            Mail::to($order->customer->email)->send(new OrderConfirmation($order));

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
