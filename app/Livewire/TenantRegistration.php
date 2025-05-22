<?php

namespace App\Livewire;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class TenantRegistration extends Component
{
    use WithFileUploads;

    public $tenant;
    public $store_name;
    public $domain;
    public $email;
    public $password;
    public $logo;
    public $successMessage;

    public function tenantRegistrationFunction() {

        $this->validate([
            'store_name' => 'required|string|max:255',
            'domain' => 'required|string|max:255|unique:domains,domain',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|dimensions:width<=200,height<=200|max:2048',
        ]);

        // Step 1: Create the tenant and attach the domain
        $tenant = Tenant::create([
            'id' => $this->domain, // You can use domain as tenant id
            'store_name' => $this->store_name,
            'plan' => 'free', // Optional: You can set a default plan here
        ]);

        $tenant->domains()->create([
            'domain' => $this->domain . '.myapp.local'
        ]);

        // Step 2: Migrate the tenant database
        $tenant->run(function () {
            // This runs inside the tenant context
            // Optional: You can seed here too if needed
        });

        // Step 3: Create a default user for the tenant
        $tenant->run(function () {
            User::create([
                'name' => $this->store_name . ' Admin',
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);
        });

        // Step 4: Upload logo if present
        $tenantId = $tenant->id;
        $path = "tenant{$tenantId}/assets/img";
        $file = $this->logo;

        try {
            $filename = 'store_logo.png';
            $file->storeAs($path, $filename, 'tenancy');
            $tenant->update(['logo_path' => "{$path}/{$filename}"]);
        } catch (\Exception $e) {
            dd("Upload failed:", $e->getMessage());
        }

        $this->successMessage = "Tenant $this->store_name created!";
    }

    public function render()
    {
        return view('livewire.tenant-registration');
    }
}
