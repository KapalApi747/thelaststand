<?php

namespace App\Livewire\Tenant\Frontend\Shopping;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('t-shop-layout')]
class CheckoutSuccess extends Component
{
    public function mount()
    {
        session()->forget('cart_' . tenant()->id);
    }
    public function render()
    {
        return view('livewire.tenant.frontend.shopping.checkout-success');
    }
}
