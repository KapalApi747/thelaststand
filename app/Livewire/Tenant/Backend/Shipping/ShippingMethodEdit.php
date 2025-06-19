<?php

namespace App\Livewire\Tenant\Backend\Shipping;

use App\Models\ShippingMethod;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('t-dashboard-layout')]
class ShippingMethodEdit extends Component
{
    public ShippingMethod $shippingMethod;

    public $code;
    public $label;
    public $cost;
    public $carriers;
    public $enabled;

    public function mount(ShippingMethod $shippingMethod)
    {
        $this->shippingMethod = $shippingMethod;
        $this->code = $shippingMethod->code;
        $this->label = $shippingMethod->label;
        $this->cost = $shippingMethod->cost;
        $this->carriers = implode(', ', $shippingMethod->carriers ?? []);
        $this->enabled = $shippingMethod->enabled;
    }

    protected function rules()
    {
        return [
            'code' => 'required|string|max:255|unique:shipping_methods,code,' . $this->shippingMethod->id,
            'label' => 'required|string|max:255',
            'cost' => 'required|numeric|min:0',
            'carriers' => 'nullable|string',
            'enabled' => 'boolean',
        ];
    }

    public function update()
    {
        $this->validate();

        try {
            $this->shippingMethod->update([
                'code' => $this->code,
                'label' => $this->label,
                'cost' => $this->cost,
                'carriers' => array_filter(array_map('trim', explode(',', $this->carriers))),
                'enabled' => $this->enabled,
            ]);

            session()->flash('message', 'Shipping method updated successfully.');
            return redirect()->route('tenant-dashboard.shipping-method-index');
        } catch (\Exception $e) {
            Log::error('Failed to update shipping method: ' . $e->getMessage());
            session()->flash('message', 'Something went wrong while updating.');
        }
    }

    public function render()
    {
        return view('livewire.tenant.backend.shipping.shipping-method-edit');
    }
}
