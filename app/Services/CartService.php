<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;

/**
 * CartService beheert de winkelwagenlogica voor een specifieke tenant.
 * Alle gegevens worden opgeslagen in de sessie onder een unieke sleutel per tenant.
 */

class CartService
{
    /**
     * Voeg een product of een specifieke productvariant toe aan de winkelwagen.
     * Als het item al bestaat, verhoog de hoeveelheid met 1.
     */

    public static function addProductToCart(Product $product, ?ProductVariant $variant = null): void
    {
        $cartKey = 'cart_' . tenant()->id; // Unieke cart-key per tenant
        $cart = session()->get($cartKey, []);

        if ($variant) {
            // Als er een variant is, gebruik een unieke sleutel gebaseerd op de variant-ID
            $key = 'variant_' . $variant->id;

            if (isset($cart[$key])) {
                $cart[$key]['quantity']++;
            } else {
                $cart[$key] = [
                    'name' => $product->name . ' - ' . $variant->name,
                    'price' => $variant->price ?? $product->price,
                    'quantity' => 1,
                    'image' => $variant->images()->first()?->path
                        ?? $product->images()->where('is_main_image', true)->first()?->path,
                    'variant_id' => $variant->id,
                    'product_id' => $product->id,
                ];
            }
        } else {
            // Als er geen variant is, gebruik het standaard product
            $key = 'product_' . $product->id;

            if (isset($cart[$key])) {
                $cart[$key]['quantity']++;
            } else {
                $cart[$key] = [
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => 1,
                    'image' => $product->images()->where('is_main_image', true)->first()?->path,
                    'product_id' => $product->id,
                ];
            }
        }

        session()->put($cartKey, $cart); // Sla de bijgewerkte winkelwagen op in de sessie
    }

    /**
     * Haal de winkelwageninhoud op uit de sessie.
     */
    public static function retrieveCart(): array
    {
        $key = 'cart_' . tenant()->id;
        return session()->get($key, []);
    }

    /**
     * Bereken het totaalbedrag van de winkelwagen (exclusief verzendkosten).
     */
    public static function cartTotal(): float
    {
        $total = 0;

        foreach (self::retrieveCart() as $item) {
            $total += ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
        }

        return round($total, 2);
    }

    /**
     * Haal de verzendkosten op uit de sessie.
     */
    public static function shippingCost(): float
    {
        return session()->get('shipping_cost', 0);
    }

    /**
     * Bereken het totaalbedrag inclusief verzendkosten.
     */
    public static function grandTotal(): float
    {
        return self::cartTotal() + self::shippingCost();
    }

    /**
     * Bereken het btw-bedrag (21% inbegrepen in het totaal).
     * Gebaseerd op een berekening waarbij het totaalbedrag 121% is.
     */
    public static function taxAmount(): float
    {
        return round((self::grandTotal() * 21) / 121, 2);
    }

    /**
     * Leeg de winkelwagen voor de huidige tenant.
     */
    public static function clearCart(): void
    {
        session()->forget('cart_' . tenant()->id);
    }

}
