<?php

namespace App\Livewire\Tenant\Backend\Shipping;

use App\Models\ShippingMethod;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('t-dashboard-layout')]
class ShippingMethodForm extends Component
{
    public $code = '';
    public $label = '';
    public $cost = 0.00;
    public $carriers = '';
    public $enabled = true;

    protected function rules()
    {
        return [
            'code' => 'required|string|max:255|unique:shipping_methods,code',
            'label' => 'required|string|max:255',
            'cost' => 'required|numeric|min:0',
            'carriers' => 'nullable|string',
            'enabled' => 'boolean',
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save()
    {
        $this->validate();

        $data = [
            'code' => $this->code,
            'label' => $this->label,
            'cost' => $this->cost,
            'carriers' => array_filter(array_map('trim', explode(',', $this->carriers))),
            'enabled' => $this->enabled,
        ];

        try {
            ShippingMethod::create($data);
            session()->flash('message', 'Shipping method created.');

            $this->reset(); // Clear the form after successful submission
        } catch (\Exception $e) {
            Log::error('Shipping method creation failed: ' . $e->getMessage(), [
                'data' => $data,
            ]);

            session()->flash('message', 'Something went wrong while saving the shipping method.');
        }
    }

    public function render()
    {
        return view('livewire.tenant.backend.shipping.shipping-method-form');
    }
}
