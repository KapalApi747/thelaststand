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
        $this->cart = session()->get('cart', []);
    }

    public function removeFromCart($productId)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
        }
        session()->put('cart', $cart);
        $this->dispatch('cart-updated');
    }

    public function updateProductQuantity($productId, $quantity)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = max(1, (int)$quantity);
        }
        session()->put('cart', $cart);
        $this->dispatch('cart-updated');
    }

    public function render()
    {
        return view('livewire.tenant.frontend.main.cart');
    }
}
