<?php

namespace App\Livewire\Tenant\Frontend\Shopping;

use App\Models\Product;
use Livewire\Component;

class AddToCartButton extends Component
{
    public Product $product;

    public function addToCart()
    {
        $cartTenantKey = 'cart_' . tenant()->id;
        $cart = session()->get($cartTenantKey, []);

        if (isset($cart[$this->product->id])) {
            $cart[$this->product->id]['quantity']++;
        } else {
            $cart[$this->product->id] = [
                'name' => $this->product->name,
                'price' => $this->product->price,
                'quantity' => 1,
                'image' => $this->product->images()->where('is_main_image', true)->first()?->path
                    ?? $this->product->images->first()?->path,
            ];
        }

        session()->put($cartTenantKey, $cart);

        $this->dispatch('cart-updated');
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Product added to cart successfully!']);
    }

    public function render()
    {
        return view('livewire.tenant.frontend.shopping.add-to-cart-button');
    }
}
