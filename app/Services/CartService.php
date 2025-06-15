<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;

class CartService
{
    public static function addProductToCart(Product $product, ?ProductVariant $variant = null): void
    {
        $cartKey = 'cart_' . tenant()->id;
        $cart = session()->get($cartKey, []);

        if ($variant) {
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

        session()->put($cartKey, $cart);
    }

    public static function retrieveCart(): array
    {
        $key = 'cart_' . tenant()->id;
        return session()->get($key, []);
    }

    public static function cartTotal(): float
    {
        $total = 0;

        foreach (self::retrieveCart() as $item) {
            $total += ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
        }

        return round($total, 2);
    }

    public static function shippingCost(): float
    {
        return session()->get('shipping_cost', 0);
    }

    public static function grandTotal(): float
    {
        return self::cartTotal() + self::shippingCost();
    }

    public static function taxAmount(): float
    {
        return round((self::grandTotal() * 21) / 121, 2);
    }

    public static function clearCart(): void
    {
        session()->forget('cart_' . tenant()->id);
    }

}
