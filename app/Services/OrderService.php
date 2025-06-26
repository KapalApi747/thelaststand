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
     * Maak een nieuwe bestelling aan, inclusief klantgegevens, adressen, items en verzending.
     *
     * @param array $customerInfo Klantinformatie (inclusief adres)
     * @param array $cartItems Winkelwagenitems
     * @param array $shippingInfo Verzendinformatie
     * @param int|null $customerId Optioneel bestaande klant-ID
     * @return \App\Models\Order
     */
    public static function createOrder(array $customerInfo, array $cartItems, array $shippingInfo, ?int $customerId = null): Order
    {
        // Voer alles binnen een database-transactie uit voor consistentie
        $order = DB::transaction(function () use ($customerInfo, $cartItems, $shippingInfo, $customerId) {

            // Bereken totaal, verzendkosten en btw
            $cartTotal = CartService::cartTotal();
            $shippingCost = $shippingInfo['cost'] ?? 0;
            $grandTotal = $cartTotal + $shippingCost;
            $taxAmount = round(($grandTotal * 21) / 121, 2);

            // Maak gastklant aan indien geen klant-ID meegegeven
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

            // Sla het verzendadres op in het klantadresboek (indien nog niet bestaat)
            self::saveCustomerAddress($customerId, 'shipping', $customerInfo);

            // Sla eventueel afwijkend factuuradres op
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

            // Maak de bestelling aan in de database
            $order = Order::create([
                'customer_id' => $customerId,
                'order_number' => strtoupper('ORD-' . Str::random(10)),
                'total_amount' => $grandTotal,
                'tax_amount' => $taxAmount,
                'shipping_cost' => $shippingCost,
                'status' => 'pending',
            ]);

            // Sla het verzendadres op bij de bestelling
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

            // Voeg factuuradres toe als dat anders is dan verzendadres
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

            // Voeg elk item uit de winkelwagen toe aan de order_items
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

            // Maak een verzendrecord aan voor de bestelling
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

        return $order;
    }

    /**
     * Sla klantadres op als het nog niet bestaat in de database.
     */
    protected static function saveCustomerAddress(int $customerId, string $type, array $data): void
    {
        // Check of dit adres al bestaat voor deze klant en dit type
        $addressExistsOnDatabase = CustomerAddress::where('customer_id', $customerId)
            ->where('type', $type)
            ->where('address_line1', $data['address_line1'] ?? '')
            ->where('address_line2', $data['address_line2'] ?? null)
            ->where('city', $data['city'] ?? '')
            ->where('state', $data['state'] ?? '')
            ->where('zip', $data['zip'] ?? '')
            ->where('country', $data['country'] ?? '')
            ->exists();

        // Als het adres nog niet bestaat, voeg het dan toe
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
