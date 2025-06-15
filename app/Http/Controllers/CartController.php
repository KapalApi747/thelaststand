<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\CartService;
use Illuminate\Http\Request;

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
