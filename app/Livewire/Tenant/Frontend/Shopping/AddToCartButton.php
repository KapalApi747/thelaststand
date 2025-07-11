<?php

namespace App\Livewire\Tenant\Frontend\Shopping;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\CartService;
use Livewire\Component;

class AddToCartButton extends Component
{
    public Product $product;
    public ?ProductVariant $variant = null;

    public function addToCart()
    {
        /*$cartTenantKey = 'cart_' . tenant()->id;
        $cart = session()->get($cartTenantKey, []);

        if ($this->variant) {
            $key = 'variant_' . $this->variant->id;

            if (isset($cart[$key])) {
                $cart[$key]['quantity']++;
            } else {
                $cart[$key] = [
                    'name' => $this->product->name . ' - ' . $this->variant->name,
                    'price' => $this->variant->price ?? $this->product->price,
                    'quantity' => 1,
                    'image' => $this->variant->images()->first()?->path
                        ?? $this->product->images()->where('is_main_image', true)->first()?->path,
                    'variant_id' => $this->variant->id,
                    'product_id' => $this->product->id,
                ];
            }
        } else {
            $key = 'product_' . $this->product->id;

            if (isset($cart[$key])) {
                $cart[$key]['quantity']++;
            } else {
                $cart[$key] = [
                    'name' => $this->product->name,
                    'price' => $this->product->price,
                    'quantity' => 1,
                    'image' => $this->product->images()->where('is_main_image', true)->first()?->path,
                    'product_id' => $this->product->id,
                ];
            }
        }

        session()->put($cartTenantKey, $cart);*/

        CartService::addProductToCart($this->product, $this->variant);

        $this->dispatch('cart-updated');
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Product added to cart successfully!']);
    }

    public function render()
    {
        return view('livewire.tenant.frontend.shopping.add-to-cart-button');
    }
}
