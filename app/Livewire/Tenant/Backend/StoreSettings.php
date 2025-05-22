<?php
/*
namespace App\Livewire\Tenant\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Stancl\Tenancy\Database\Models\Tenant;

#[Layout('t-dashboard-layout')]
class StoreSettings extends Component
{
    use WithFileUploads;

    public $storeName;
    public $pendingStoreName;
    public $storeNameApproved;

    public $logo;
    public $currentLogoUrl;
    public $pendingLogoExists = false;

    public function mount(Request $request) {
        $tenantId = tenant()->id;
        $tenant = Tenant::find($tenantId);

        $this->storeName = $tenant->store_name;
        $this->pendingStoreName = $tenant->pending_store_name;
        $this->storeNameApproved = $tenant->store_name_approved;

        $logoPath = public_path("tenancy/assets/{$tenantId}/assets/img/store_logo.png");

        $this->currentLogoUrl = file_exists($logoPath)
            ? asset("{$tenantId}/assets/img/store_logo.png")
            : null;

        // Detect if a pending logo exists

        $pendingLogoPath = public_path("tenancy/assets/{$tenantId}/assets/img/pending/new_logo.png");
        $this->pendingLogoExists = file_exists($pendingLogoPath);

        Log::info('🧭 Route check', [
            'upload_route_exists' => route('livewire.upload-file', [], false),
        ]);
    }

    public function submitStoreChanges(Request $request)
    {
        $this->validate([
            'pendingStoreName' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $tenantId = tenant()->id;
        $tenant = Tenant::find($tenantId);

        // Save pending store name
        $tenant->pending_store_name = $this->pendingStoreName;
        $tenant->store_name_approved = false;

        if ($this->logo) {
            $destinationPath = public_path("tenancy/assets/{$tenantId}/assets/img/pending");

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $this->logo->storeAs(
                "tenancy/assets/{$tenantId}/assets/img/pending",
                'new_logo.png',
                'tenancy'
            );
        }

        $tenant->save();

        session()->flash('message', 'Changes submitted for admin approval.');
    }

    public function render()
    {
        return view('livewire.tenant.backend.store-settings');
    }
}*/


namespace App\Livewire\Tenant\Backend;

use App\Models\Tenant;
use App\Models\TenantProfile;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('t-dashboard-layout')]
class StoreSettings extends Component
{
    use WithFileUploads;

    public $profile;
    public $storeName;
    public $logo;
    public $currentLogoUrl;

    public function mount()
    {
        $tenantId = tenant()->id;
        $tenant = Tenant::find($tenantId);

        $this->profile = $tenant->profile ? $tenant->profile->toArray() :
            [
            'tenant_id' => $tenantId,
            'email' => '',
            'phone' => '',
            'address' => '',
            'city' => '',
            'state' => '',
            'zip' => '',
            'country' => '',
            'vat_id' => '',
            'business_description' => '',
            'store_status' => 'active',
        ];

        $this->storeName = $tenant->store_name;

        $logoPath = public_path("tenancy/assets/tenant{$tenantId}/assets/img/store_logo.png");
        $this->currentLogoUrl = file_exists($logoPath)
            ? asset("tenant{$tenantId}/assets/img/store_logo.png")
            : null;
    }

    protected function rules()
    {
        return [
            'profile.email' => 'nullable|email',
            'profile.phone' => 'nullable|string|max:20',
            'profile.address' => 'nullable|string',
            'profile.city' => 'nullable|string|max:100',
            'profile.state' => 'nullable|string|max:100',
            'profile.zip' => 'nullable|string|max:20',
            'profile.country' => 'nullable|string|max:100',
            'profile.vat_id' => 'nullable|string|max:100',
            'profile.business_description' => 'nullable|string|max:1000',
            'profile.store_status' => 'required|in:active,inactive',

            'storeName' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function submitProfileChanges()
    {
        $this->validate();

        TenantProfile::updateOrCreate(['tenant_id' => tenant()->id], $this->profile);

        session()->flash('message', 'Profile updated successfully.');

        $this->dispatch('updated_and_refresh');
    }

    public function submitStoreChanges()
    {
        $this->validate();

        $tenantId = tenant()->id;
        $tenant = Tenant::find($tenantId);

        // Update store name immediately
        $tenant->store_name = $this->storeName;

        if ($this->logo) {
            $destinationPath = public_path("tenancy/assets/tenant{$tenantId}/assets/img");

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            // Save the uploaded logo as store_logo.png
            $this->logo->storeAs(
                "assets/img",
                'store_logo.png',
                'tenancy'
            );

            // Refresh currentLogoUrl to show updated logo
            $this->currentLogoUrl = asset("tenant{$tenantId}/assets/img/store_logo.png");

            $tenant->logo_path = "tenant{$tenantId}/assets/img/store_logo.png";
        }

        $tenant->save();

        session()->flash('message', 'Store settings updated successfully.');

        $this->dispatch('updated_and_refresh');
    }

    public function render()
    {
        return view('livewire.tenant.backend.store-settings');
    }
}

