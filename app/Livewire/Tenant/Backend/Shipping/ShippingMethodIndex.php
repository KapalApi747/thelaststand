<?php

namespace App\Livewire\Tenant\Backend\Shipping;

use App\Models\ShippingMethod;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('t-dashboard-layout')]
class ShippingMethodIndex extends Component
{
    public $shippingMethods = [];

    public function mount()
    {
        $this->loadShippingMethods();
    }

    public function loadShippingMethods()
    {
        $this->shippingMethods = ShippingMethod::orderBy('label')->get();
    }

    public function toggleStatus($id)
    {
        $method = ShippingMethod::findOrFail($id);
        $method->enabled = !$method->enabled;
        $method->save();

        session()->flash('message', 'Shipping method status updated.');
        $this->loadShippingMethods();
    }

    public function delete($id)
    {
        $method = ShippingMethod::findOrFail($id);
        $method->delete();

        session()->flash('message', 'Shipping method deleted.');
        $this->loadShippingMethods();
    }

    public function render()
    {
        return view('livewire.tenant.backend.shipping.shipping-method-index');
    }
}
