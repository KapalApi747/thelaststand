<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Tenant;
use App\Models\TenantProfile;
use App\Models\User;
use Database\Seeders\TenantOrderDataSeeder;
use Database\Seeders\TenantPermissionSeeder;
use Database\Seeders\TenantProductReviewSeeder;
use Database\Seeders\TenantProductSeeder;
use Database\Seeders\TenantRoleSeeder;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\Permission\Models\Role;

#[Layout('central-layout')]
class TenantRegistration extends Component
{
    use WithFileUploads;

    public $tenant;
    public $store_name;
    public $domain;
    public $email;
    public $password;
    public $logo;

    public $phone;
    public $address;
    public $city;
    public $state;
    public $zip;
    public $country;
    public $business_description;

    public $success = false;
    public $storeUrl;

    public function tenantRegistrationFunction() {

        $this->validate([
            'store_name' => 'required|string|max:255',
            'domain' => 'required|string|max:255|unique:domains,domain',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|dimensions:width<=200,height<=200|max:2048',

            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'business_description' => 'nullable|string|max:255',
        ]);

        // Step 1: Create the tenant and attach the domain
        $tenant = Tenant::create([
            'id' => $this->domain,
            'store_name' => $this->store_name,
            'plan' => 'free',
        ]);

        $tenant->domains()->create([
            'domain' => $this->domain . '.thelaststand.local'
        ]);

        // Step 2: Migrate the tenant database
        $tenant->run(function () {

            $permissionSeeder = new TenantPermissionSeeder();
            $roleSeeder = new TenantRoleSeeder();

            Category::create(['name' => 'Category 1']);
            Category::create(['name' => 'Category 2']);
            Category::create(['name' => 'Category 3']);
            Category::create(['name' => 'Category 4']);

            $productSeeder = new TenantProductSeeder();
            $productReviewSeeder = new TenantProductReviewSeeder();
            $orderSeeder = new TenantOrderDataSeeder();
            $reviewSeeder = new TenantProductReviewSeeder();

            TenantProfile::create([
                'tenant_id' => tenant()->id,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
                'city' => $this->city,
                'state' => $this->state,
                'zip' => $this->zip,
                'country' => $this->country,
                'vat_id' => null,
                'business_description' => $this->business_description,
                'store_status' => 'active',
            ]);

            $permissionSeeder->run();
            $roleSeeder->run();
            $productSeeder->run();
            $productReviewSeeder->run();
            $orderSeeder->run();
            $reviewSeeder->run();
        });

        // Step 3: Create a default user for the tenant
        $tenant->run(function () {
            $user = User::create([
                'name' => $this->store_name . ' Admin',
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);

            $adminRole = Role::where('name', 'admin')->first();
            $user->assignRole($adminRole);
        });

        // Step 4: Upload logo if present
        $tenantId = $tenant->id;
        $path = "tenant{$tenantId}/assets/img";
        $file = $this->logo;

        if ($file) {
            try {
                $filename = 'store_logo.png';
                $this->logo->storeAs($path, $filename, 'tenancy');
                $tenant->update(['logo_path' => "{$path}/{$filename}"]);
            } catch (\Exception $e) {
                dd("Upload failed:", $e->getMessage());
            }
        }

        $this->success = true;
        $this->storeUrl = "http://" . $this->domain . ".thelaststand.local:8000/";
    }

    public function render()
    {
        return view('livewire.tenant-registration');
    }
}
