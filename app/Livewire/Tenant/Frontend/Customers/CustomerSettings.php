<?php

namespace App\Livewire\Tenant\Frontend\Customers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('t-shop-layout')]
class CustomerSettings extends Component
{
    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    protected function currentCustomer()
    {
        if ($customer = auth('customer')->user()) {
            return $customer;
        }

        if ($tenantUser = auth('web')->user()) {
            return $tenantUser->customers()->first();
        }

        return null;
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $customer = $this->currentCustomer();

        if (!$customer) {
            throw ValidationException::withMessages([
                'current_password' => 'User not found or unauthorized.',
            ]);
        }

        $tenantUser = null;
        if ($tenantUser = auth('web')->user()) {
            if (!Hash::check($this->current_password, $tenantUser->password)) {
                throw ValidationException::withMessages([
                    'current_password' => 'The existing password is incorrect.',
                ]);
            }
        } else {
            if (!Hash::check($this->current_password, $customer->password)) {
                throw ValidationException::withMessages([
                    'current_password' => 'The existing password is incorrect.',
                ]);
            }
        }

        // Update customer password
        $customer->password = Hash::make($this->new_password);
        $customer->save();

        // Update tenant user password if exists
        if ($tenantUser) {
            $tenantUser->password = Hash::make($this->new_password);
            $tenantUser->save();
        }

        // Clear form fields after successful update
        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);

        session()->flash('message', 'Password updated successfully.');
    }

    public function render()
    {
        return view('livewire.tenant.frontend.customers.customer-settings');
    }
}
