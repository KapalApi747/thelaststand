<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderItem;
use App\Models\Shipment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    /**
     * Create a new order and associated data.
     *
     * @param array $customerInfo
     * @param array $cartItems
     * @param array $shippingInfo
     * @param int|null $customerId
     * @return \App\Models\Order
     */
    public static function createOrder(array $customerInfo, array $cartItems, array $shippingInfo, ?int $customerId = null): Order
    {
        return DB::transaction(function () use ($customerInfo, $cartItems, $shippingInfo, $customerId) {
            // 1. Calculate totals
            $cartTotal = CartService::cartTotal();
            $shippingCost = $shippingInfo['cost'] ?? 0;
            $grandTotal = $cartTotal + $shippingCost;
            $taxAmount = round(($grandTotal * 21) / 121, 2); // 21% VAT included

            // 1.5 Get or create customer
            if (!$customerId) {
                $guest = Customer::firstOrCreate(
                    ['email' => $customerInfo['email'] ?? null],
                    [
                        'name' => $customerInfo['name'] ?? 'Guest',
                        'phone' => $customerInfo['phone'] ?? null,
                        'password' => null,
                        'is_active' => false,
                    ]
                );

                $customerId = $guest->id;

                CustomerAddress::create([
                    'customer_id' => $customerId,
                    'type' => 'shipping',
                    'address_line1' => $customerInfo['address_line1'] ?? '',
                    'address_line2' => $customerInfo['address_line2'] ?? null,
                    'city' => $customerInfo['city'] ?? '',
                    'state' => $customerInfo['state'] ?? '',
                    'zip' => $customerInfo['zip'] ?? '',
                    'country' => $customerInfo['country'] ?? '',
                    'phone' => $customerInfo['phone'] ?? null,
                ]);

                if (!empty($customerInfo['billing_different'])) {
                    CustomerAddress::create([
                        'customer_id' => $customerId,
                        'type' => 'billing',
                        'address_line1' => $customerInfo['billing_address_line1'] ?? '',
                        'address_line2' => $customerInfo['billing_address_line2'] ?? null,
                        'city' => $customerInfo['billing_city'] ?? '',
                        'state' => $customerInfo['billing_state'] ?? '',
                        'zip' => $customerInfo['billing_zip'] ?? '',
                        'country' => $customerInfo['billing_country'] ?? '',
                        'phone' => $customerInfo['phone'] ?? null,
                    ]);
                }
            }

            // 2. Create Order
            $order = Order::create([
                'customer_id' => $customerId,
                'order_number' => strtoupper(Str::random(10)),
                'total_amount' => $grandTotal,
                'tax_amount' => $taxAmount,
                'shipping_cost' => $shippingCost,
                'status' => 'pending',
            ]);

            // 3. Store Order Address
            OrderAddress::create([
                'order_id' => $order->id,
                'type' => 'shipping',
                'full_name' => $customerInfo['name'] ?? 'Guest',
                'address_line1' => $customerInfo['address_line1'] ?? '',
                'address_line2' => $customerInfo['address_line2'] ?? null,
                'city' => $customerInfo['city'] ?? '',
                'state' => $customerInfo['state'] ?? '',
                'zip' => $customerInfo['zip'] ?? '',
                'country' => $customerInfo['country'] ?? '',
                'phone' => $customerInfo['phone'] ?? null,
            ]);

            if (!empty($customerInfo['billing_different'])) {
                OrderAddress::create([
                    'order_id' => $order->id,
                    'type' => 'billing',
                    'full_name' => $customerInfo['name'] ?? 'Guest',
                    'address_line1' => $customerInfo['billing_address_line1'] ?? '',
                    'address_line2' => $customerInfo['billing_address_line2'] ?? null,
                    'city' => $customerInfo['billing_city'] ?? '',
                    'state' => $customerInfo['billing_state'] ?? '',
                    'zip' => $customerInfo['billing_zip'] ?? '',
                    'country' => $customerInfo['billing_country'] ?? '',
                    'phone' => $customerInfo['phone'] ?? null,
                ]);
            }

            // 4. Add Order Items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                ]);
            }

            // 5. Save shipment info if available
            Shipment::create([
                'order_id' => $order->id,
                'tracking_number' => null,
                'carrier' => $shippingInfo['carrier'] ?? null,
                'status' => 'pending',
                'shipping_cost' => $shippingCost,
                'shipping_method' => $shippingInfo['method'] ?? null,
            ]);

            return $order;
        });
    }
}
