<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\CustomerPaymentAccount;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Shipment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TenantOrderDataSeeder extends Seeder
{
    public function run()
    {
        $products = Product::all();

        if ($products->isEmpty()) {
            $this->command->error('No products found! Seed products before running this.');
            return;
        }

        // Create 100 customers over the past 6 months
        Customer::factory(200)->create()->each(function ($customer) use ($products) {

            $signupDate = Carbon::instance(fake()->dateTimeBetween('-12 months', 'now'));
            $customer->forceFill([
                'created_at' => $signupDate,
                'updated_at' => $signupDate,
            ])
                ->saveQuietly();

            // Every customer gets a shipping address
            CustomerAddress::factory()->create([
                'customer_id' => $customer->id,
                'type' => 'shipping',
            ]);

            // 50% chance of also getting a billing address
            if (rand(0, 1)) {
                CustomerAddress::factory()->create([
                    'customer_id' => $customer->id,
                    'type' => 'billing',
                ]);
            }

            if (rand(0, 1)) {
                CustomerPaymentAccount::factory()->create(['customer_id' => $customer->id]);
            }

            $ordersCount = rand(1, 3);

            $daysSinceSignup = $signupDate->diffInDays(now());
            $segmentSize = max((int) floor($daysSinceSignup / $ordersCount), 1);

            for ($i = 0; $i < $ordersCount; $i++) {
                $segmentStart = $signupDate->copy()->addDays($segmentSize * $i);
                $segmentEnd = $segmentStart->copy()->addDays($segmentSize - 1);

                // Clamp to now
                $now = now();
                if ($segmentEnd->greaterThan($now)) {
                    $segmentEnd = $now;
                }

                // Ensure start <= end
                if ($segmentStart->greaterThan($segmentEnd)) {
                    $segmentStart = $segmentEnd->copy()->subDay(); // go back 1 day
                }

                // Now safely generate order creation date
                $orderCreatedAt = fake()->dateTimeBetween($segmentStart, $segmentEnd);

                $orderCreatedAt = Carbon::instance($orderCreatedAt);

                $order = Order::factory()->create([
                    'customer_id' => $customer->id,
                    'created_at' => $orderCreatedAt,
                ]);

                $this->createOrderDetails($order, $products);
            }
        });
    }

    /**
     * Helper method to create order details (items, payments, addresses, shipment).
     */
    protected function createOrderDetails($order, $products)
    {
        $orderItemsCount = rand(1, 2);
        for ($j = 0; $j < $orderItemsCount; $j++) {
            $product = $products->random();

            $pickVariant = rand(1, 100) <= 70; // 70% chance to pick variant

            if ($pickVariant && $product->variants->isNotEmpty()) {
                $productVariant = $product->variants->random();

                OrderItem::factory()->create([
                    'order_id' => $order->id,
                    'product_id' => $productVariant->product_id,
                    'product_variant_id' => $productVariant->id,
                    'product_name' => $product->name . ' - ' . $productVariant->name,
                    'price' => $productVariant->price,
                    'quantity' => rand(1, 3),
                ]);
            } else {
                // Pick base product, no variant
                OrderItem::factory()->create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_variant_id' => null,
                    'product_name' => $product->name,
                    'price' => $product->price,
                    'quantity' => rand(1, 3),
                ]);
            }
        }

        $itemTotal = OrderItem::where('order_id', $order->id)
            ->sum(DB::raw('price * quantity'));

        $totalAmount = $itemTotal + $order->shipping_cost;

        $taxRate = 0.21;
        $taxAmount = round($totalAmount * $taxRate, 2);

        $order->update([
            'total_amount' => $totalAmount,
            'tax_amount' => $taxAmount,
        ]);

        $paymentStatus = in_array($order->status, ['refunded', 'failed', 'cancelled'])
            ? $order->status
            : ($order->status === 'pending' ? 'pending' : 'completed');

        Payment::factory()->create([
            'order_id' => $order->id,
            'customer_id' => $order->customer_id,
            'amount' => $totalAmount,
            'status' => $paymentStatus,
        ]);

        OrderAddress::factory()->create(['order_id' => $order->id, 'type' => 'billing']);
        OrderAddress::factory()->create(['order_id' => $order->id, 'type' => 'shipping']);

        Shipment::factory()->create(['order_id' => $order->id, 'shipping_cost' => $order->shipping_cost]);
    }
}
