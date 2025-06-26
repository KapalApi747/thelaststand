<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\CartService;
use Illuminate\Http\Request;

/**
 * Verantwoordelijk voor winkelwagenacties binnen het publieke storefront van THE LAST STAND.
 *
 * Momenteel ondersteunt deze controller het toevoegen van producten (eventueel met varianten)
 * aan de sessie-gebaseerde winkelwagen via de CartService.
 *
 * Bij succesvol toevoegen wordt de gebruiker teruggeleid met een bevestigingsbericht.
 *
 * @zie \App\Services\CartService::addProductToCart
 */

class CartController extends Controller
{
    public function add(Request $request)
    {
        $product = Product::findOrFail($request->input('product_id'));
        $variant = $request->filled('variant_id')
            ? ProductVariant::findOrFail($request->input('variant_id'))
            : null;

        CartService::addProductToCart($product, $variant);

        $productName = $product->name;
        $variantName = $variant ? ' (' . $variant->name . ')' : '';

        return redirect()->back()->with('message', "✔️ {$productName}{$variantName} added to cart!");
    }
}
