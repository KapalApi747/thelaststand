<?php

namespace App\Livewire\Tenant\Frontend\Main;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\CartService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('t-shop-layout')]
class Cart extends Component
{
    public $cart = [];

    protected $listeners = ['cart-updated' => 'refreshCart', 'notify-error' => 'handleError'];

    public function mount()
    {
        $this->refreshCart();
    }

    public function refreshCart()
    {
        $cartTenantKey = 'cart_' . tenant()->id;
        $this->cart = session()->get($cartTenantKey, []);
    }

    public function removeFromCart($itemKey)
    {
        $cartTenantKey = 'cart_' . tenant()->id;
        $cart = session()->get($cartTenantKey, []);

        if (isset($cart[$itemKey])) {
            unset($cart[$itemKey]);
        }

        session()->put($cartTenantKey, $cart);
        $this->dispatch('cart-updated');
    }

    public function updateProductQuantity($itemKey, $quantity)
    {
        $cartTenantKey = 'cart_' . tenant()->id;
        $cart = session()->get($cartTenantKey, []);

        if (!isset($cart[$itemKey])) return;

        // 🔒 Strictly sanitize input
        if (!is_numeric($quantity) || $quantity < 1) {
            $quantity = 1;
        } else {
            $quantity = (int) $quantity;
        }

        $item = $cart[$itemKey];

        // 🔍 Determine stock
        if (isset($item['variant_id'])) {
            $variant = ProductVariant::find($item['variant_id']);
            $stock = $variant?->stock ?? 0;
        } else {
            $product = Product::find($item['product_id']);
            $stock = $product?->stock ?? 0;
        }

        // ⛔ Handle out of stock
        if ($stock === 0) {
            $this->dispatch('notify-error', [
                'message' => "{$item['name']} is currently out of stock."
            ]);
            return;
        }

        // ⚠ Cap to available stock and notify
        if ($quantity > $stock) {
            $quantity = $stock;
            $this->dispatch('notify-error', [
                'message' => "Only $stock in stock for {$item['name']}."
            ]);
        }

        // ✅ Save updated quantity
        $cart[$itemKey]['quantity'] = $quantity;
        session()->put($cartTenantKey, $cart);
        $this->dispatch('cart-updated');
    }

    public function handleError($payload)
    {
        session()->flash('stockError', $payload['message']);
    }

    public function checkForStockIssues(): bool
    {
        foreach ($this->cart as $item) {
            $stock = 0;
            if (isset($item['variant_id'])) {
                $variant = ProductVariant::find($item['variant_id']);
                $stock = $variant?->stock ?? 0;
            } else {
                $product = Product::find($item['product_id']);
                $stock = $product?->stock ?? 0;
            }

            if ($item['quantity'] > $stock || $stock === 0) {
                return true;
            }
        }
        return false;
    }

    public function goToCheckout()
    {
        return redirect()->route('shop.checkout-form');
    }

    public function render()
    {
        return view('livewire.tenant.frontend.main.cart', [
            'cartTotal' => CartService::cartTotal(),
            'taxAmount' => CartService::taxAmount(),
        ]);
    }
}
