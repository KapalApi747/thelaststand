<?php

namespace App\Livewire\Tenant\Frontend\Shopping;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('t-shop-layout')]
class CheckoutCancel extends Component
{
    public function render()
    {
        return view('livewire.tenant.frontend.shopping.checkout-cancel');
    }
}
