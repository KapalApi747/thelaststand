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
        Customer::factory(100)->create()->each(function ($customer) use ($products) {
            // Biased signup dates (recent-heavy)
            $signupDate = now()->subDays(
                fake()->biasedNumberBetween(0, 180, fn($x) => (1 - $x) ** 1.1)
            );
            $customer->update(['created_at' => $signupDate]);

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
            for ($i = 0; $i < $ordersCount; $i++) {
                // Ensure order is after signup but before now
                $orderCreatedAt = fake()->dateTimeBetween($signupDate, now());

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

            OrderItem::factory()->create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'price' => $product->price,
                'quantity' => rand(1, 3),
            ]);
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
