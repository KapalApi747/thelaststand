<?php

namespace App\Services;

use App\Mail\OrderConfirmation;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Shipment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OrderService
{
    /**
     * Create a new order and associated data.
     *
     * @param array $customerInfo
     * @param array $cartItems
     * @param array $shippingInfo
     * @param int $customerId
     * @return \App\Models\Order
     */
    public static function createOrder(array $customerInfo, array $cartItems, array $shippingInfo, ?int $customerId = null): Order
    {
        $order = DB::transaction(function () use ($customerInfo, $cartItems, $shippingInfo, $customerId) {

            // 1. Calculate totals
            $cartTotal = CartService::cartTotal();
            $shippingCost = $shippingInfo['cost'] ?? 0;
            $grandTotal = $cartTotal + $shippingCost;
            $taxAmount = round(($grandTotal * 21) / 121, 2);

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
            }

            self::saveCustomerAddress($customerId, 'shipping', $customerInfo);

            if (!empty($customerInfo['billing_different'])) {
                $billingInfo = [
                    'address_line1' => $customerInfo['billing_address_line1'] ?? '',
                    'address_line2' => $customerInfo['billing_address_line2'] ?? null,
                    'city' => $customerInfo['billing_city'] ?? '',
                    'state' => $customerInfo['billing_state'] ?? '',
                    'zip' => $customerInfo['billing_zip'] ?? '',
                    'country' => $customerInfo['billing_country'] ?? '',
                    'phone' => $customerInfo['phone'] ?? null,
                ];

                self::saveCustomerAddress($customerId, 'billing', $billingInfo);
            }

            // 2. Create Order
            $order = Order::create([
                'customer_id' => $customerId,
                'order_number' => strtoupper('ORD-' . Str::random(10)),
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
                    'product_variant_id' => $item['variant_id'] ?? null,
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

        Mail::to($order->customer->email)->send(new OrderConfirmation($order));

        return $order;
    }

    protected static function saveCustomerAddress(int $customerId, string $type, array $data): void
    {
        $addressExistsOnDatabase = CustomerAddress::where('customer_id', $customerId)
            ->where('type', $type)
            ->where('address_line1', $data['address_line1'] ?? '')
            ->where('address_line2', $data['address_line2'] ?? null)
            ->where('city', $data['city'] ?? '')
            ->where('state', $data['state'] ?? '')
            ->where('zip', $data['zip'] ?? '')
            ->where('country', $data['country'] ?? '')
            ->exists();

        if (!$addressExistsOnDatabase) {
            CustomerAddress::create([
                'customer_id' => $customerId,
                'type' => $type,
                'address_line1' => $data['address_line1'] ?? '',
                'address_line2' => $data['address_line2'] ?? null,
                'city' => $data['city'] ?? '',
                'state' => $data['state'] ?? '',
                'zip' => $data['zip'] ?? '',
                'country' => $data['country'] ?? '',
            ]);
        }
    }

}
