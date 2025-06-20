<div class="space-y-6 p-6">
    <div class="flex items-center justify-between">
        <h3 class="h3 font-bold mb-4">Shipping Methods</h3>
        <div>
            <a href="{{ route('tenant-dashboard.shipping-method-form') }}" class="btn btn-primary">Create New</a>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <div class="p-6 bg-white border border-gray-200 rounded">
        <table class="w-full table-auto border-collapse">
            <thead>
            <tr class="bg-gray-100 text-left">
                <th class="p-2">Code</th>
                <th class="p-2">Label</th>
                <th class="p-2">Cost</th>
                <th class="p-2">Carriers</th>
                <th class="p-2 text-center">Enabled</th>
                <th class="p-2 text-center">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($shippingMethods as $method)
                <tr class="border-b">
                    <td class="p-2">{{ $method->code }}</td>
                    <td class="p-2">{{ $method->label }}</td>
                    <td class="p-2">€{{ number_format($method->cost, 2) }}</td>
                    <td class="p-2">{{ implode(', ', $method->carriers ?? []) }}</td>
                    <td class="p-2 text-center">
                        <button wire:click="toggleStatus({{ $method->id }})"
                                class="px-2 py-1 rounded {{ $method->enabled ? 'bg-green-200' : 'bg-red-200' }}">
                            {{ $method->enabled ? 'Enabled' : 'Disabled' }}
                        </button>
                    </td>
                    <td class="p-2 space-x-2 text-center">
                        <a href="{{ route('tenant-dashboard.shipping-method-edit', ['shippingMethod' => $method->id]) }}"
                           class="text-blue-600 hover:text-blue-800 transition-colors duration-300">
                            <i class="fa fa-edit"></i>
                        </a>
                        <button
                            x-data
                            @click.prevent="if (confirm('Delete this shipping method?')) $wire.delete({{ $method->id }})"
                            class="text-red-600 hover:text-red-800 transition-colors duration-300"
                        >
                            <i class="fa fa-trash"></i>
                        </button>

                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="p-2 text-gray-500">No shipping methods found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
