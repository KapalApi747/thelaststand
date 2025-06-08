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

class TenantOrderDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Fetch all products once to assign to order items
        $products = Product::all();

        if ($products->isEmpty()) {
            $this->command->error('No products found! Seed products before running this.');
            return;
        }

        // Create 50 customers, each with 1-3 orders
        Customer::factory(50)->create()->each(function ($customer) use ($products) {

            // Create 1–2 addresses per customer
            CustomerAddress::factory(rand(1, 2))->create([
                'customer_id' => $customer->id,
            ]);

            // Create 0–2 payment accounts per customer
            if (rand(0, 1)) {
                CustomerPaymentAccount::factory(1)->create([
                    'customer_id' => $customer->id,
                ]);
            }

            // Create 1-3 orders per customer
            $ordersCount = rand(1, 3);
            for ($i = 0; $i < $ordersCount; $i++) {
                $order = Order::factory()->create([
                    'customer_id' => $customer->id,
                ]);

                // Create 1-5 order items per order
                $orderItemsCount = rand(1, 5);
                for ($j = 0; $j < $orderItemsCount; $j++) {
                    // Pick a random product
                    $product = $products->random();

                    OrderItem::factory()->create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'price' => $product->price,
                        'quantity' => rand(1, 3),
                    ]);
                }

                // Create 1-2 payments per order
                $paymentsCount = rand(1, 2);
                for ($k = 0; $k < $paymentsCount; $k++) {
                    Payment::factory()->create([
                        'order_id' => $order->id,
                        'amount' => $order->total_amount / $paymentsCount, // split amount evenly
                    ]);
                }

                // Create billing and shipping addresses
                OrderAddress::factory()->create([
                    'order_id' => $order->id,
                    'type' => 'billing',
                ]);

                OrderAddress::factory()->create([
                    'order_id' => $order->id,
                    'type' => 'shipping',
                ]);

                // Create shipment for the order
                if (rand(0, 1)) { // ~50% of orders get a shipment
                    Shipment::factory()->create([
                        'order_id' => $order->id,
                    ]);
                }
            }
        });
    }
}
