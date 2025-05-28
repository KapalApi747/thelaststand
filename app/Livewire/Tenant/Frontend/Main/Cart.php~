<?php

namespace App\Livewire\Tenant\Frontend\Main;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[layout('t-shop-layout')]
class Cart extends Component
{
    public $cart = [];

    protected $listeners = ['cart-updated' => 'refreshCart'];

    public function mount()
    {
        $this->refreshCart();
    }

    public function refreshCart()
    {
        $cartTenantKey = 'cart_' . tenant()->id;
        $this->cart = session()->get($cartTenantKey, []);
    }

    public function removeFromCart($productId)
    {
        $cartTenantKey = 'cart_' . tenant()->id;
        $cart = session()->get($cartTenantKey, []);
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
        }
        session()->put($cartTenantKey, $cart);
        $this->dispatch('cart-updated');
    }

    public function updateProductQuantity($productId, $quantity)
    {
        $cartTenantKey = 'cart_' . tenant()->id;
        $cart = session()->get($cartTenantKey, []);
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = max(1, (int)$quantity);
        }
        session()->put($cartTenantKey, $cart);
        $this->dispatch('cart-updated');
    }

    public function render()
    {
        return view('livewire.tenant.frontend.main.cart');
    }
}
