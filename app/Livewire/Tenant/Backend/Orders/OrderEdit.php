<?php

namespace App\Livewire\Tenant\Backend\Orders;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('t-dashboard-layout')]
class OrderEdit extends Component
{
    public Order $order;

    public $customerName;
    public $customerEmail;
    public $customerPhone;

    public $status;
    public $taxAmount;
    public $shippingCost;

    public array $billingAddress = [];
    public array $shippingAddress = [];
    public array $customer = [];

    public function mount(Order $order)
    {
        $this->order = $order->load(['customer', 'addresses']);

        $this->status = $order->status;
        $this->taxAmount = $order->tax_amount;
        $this->shippingCost = $order->shipping_cost;

        $this->customerName = $order->customer->name;
        $this->customerEmail = $order->customer->email;
        $this->customerPhone = $order->customer->phone;

        $this->setAddresses();
    }

    public function rules(): array
    {
        return [
            // Order fields
            'status' => 'required|in:pending,processing,completed,cancelled',
            'taxAmount' => 'required|numeric|min:0',
            'shippingCost' => 'required|numeric|min:0',

            // Customer fields
            'customerName' => 'required|string|max:255',
            'customerEmail' => 'required|email|max:255',
            'customerPhone' => 'nullable|string|max:50',

            // Shipping address fields
            'shippingAddress.full_name' => 'required|string|max:255',
            'shippingAddress.address_line1' => 'required|string|max:255',
            'shippingAddress.address_line2' => 'nullable|string|max:255',
            'shippingAddress.city' => 'required|string|max:100',
            'shippingAddress.state' => 'required|string|max:100',
            'shippingAddress.zip' => 'required|string|max:20',
            'shippingAddress.country' => 'required|string|max:100',
            'shippingAddress.phone' => 'required|string|max:50',

            // Billing address fields (optional fallback)
            'billingAddress.full_name' => 'nullable|string|max:255',
            'billingAddress.address_line1' => 'nullable|string|max:255',
            'billingAddress.address_line2' => 'nullable|string|max:255',
            'billingAddress.city' => 'nullable|string|max:100',
            'billingAddress.state' => 'nullable|string|max:100',
            'billingAddress.zip' => 'nullable|string|max:20',
            'billingAddress.country' => 'nullable|string|max:100',
            'billingAddress.phone' => 'nullable|string|max:50',
        ];
    }

    protected function setAddresses()
    {
        $shipping = $this->order->addresses->firstWhere('type', 'shipping');
        $billing = $this->order->addresses->firstWhere('type', 'billing');

        $this->shippingAddress = $shipping ? $shipping->toArray() : [];
        $this->billingAddress = $billing ? $billing->toArray() : [];
    }

    public function saveOrder()
    {
        $this->validate();

        DB::transaction(function () {
            $this->order->update([
                'status' => $this->status,
                'tax_amount' => $this->taxAmount,
                'shipping_cost' => $this->shippingCost,
            ]);

            $this->order->customer->update([
                'name' => $this->customerName,
                'email' => $this->customerEmail,
                'phone' => $this->customerPhone,
            ]);

            foreach ([
                         'shipping' => $this->shippingAddress, 'billing' => $this->billingAddress] as $type => $data) {
                if (empty($data)) continue;

                $address = $this->order->addresses->firstWhere('type', $type);

                if ($address) {
                    $address->update($data);
                } else {
                    $this->order->addresses()->create(array_merge($data, ['type' => $type]));
                }
            }
        });

        session()->flash('message', 'Order updated successfully!');
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function render()
    {
        return view('livewire.tenant.backend.orders.order-edit');
    }
}
