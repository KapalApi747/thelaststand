<div class="p-6">
    <h3 class="h3 font-bold mb-4">All Tenants</h3>

    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
            {{ session('message') }}
        </div>
    @endif

    <table class="min-w-full border rounded shadow bg-white">
        <thead>
        <tr class="bg-gray-100 text-center text-black">
            <th class="p-2">Store Name</th>
            <th class="p-2">Email</th>
            <th class="p-2">Phone</th>
            <th class="p-2">Domain</th>
            <th class="p-2 text-center">Status</th>
            <th class="p-2 text-center">Actions</th>
        </tr>
        </thead>
        <tbody>
        @if ($tenants->count() > 0)
            @foreach ($tenants as $tenant)
                <tr class="text-center {{ $tenant->profile->store_status === 'active' ? 'bg-green-100' : 'bg-red-100' }}">
                    <td class="p-2">{{ $tenant->store_name ?? '-' }}</td>
                    <td class="p-2">{{ $tenant->profile->email ?? '-' }}</td>
                    <td class="p-2">{{ $tenant->profile->phone ?? '-' }}</td>
                    <td class="p-2">{{ $tenant->domains()->first()->domain ?? '-' }}</td>
                    <td class="p-2">
                        <div class="flex items-center justify-center space-x-2">
                        <span class="inline-block px-2 py-1 text-sm rounded
                            {{ $tenant->profile->store_status === 'active'
                                ? 'bg-green-100 text-green-700'
                                : 'bg-yellow-100 text-yellow-700' }}">
                            {{ ucfirst($tenant->profile->store_status) }}
                        </span>
                            <button
                                wire:click="toggleStatus('{{ $tenant->id }}')"
                                class="text-sm underline text-gray-500 hover:text-gray-800 transition-colors duration-300">
                                {{ $tenant->profile->store_status === 'active' ? 'Deactivate' : 'Activate' }}
                            </button>
                        </div>
                    </td>
                    <td class="p-2 border">
                        <div class="flex justify-evenly">
                            <div>
                                <a href="{{ route('dashboard.tenant-profiles.view', $tenant) }}"
                                   class="text-blue-600 hover:text-blue-400 transition-colors duration-300"><i
                                        class="fa-solid fa-eye"></i></a>
                            </div>
                            <div>
                                <a href="{{ route('dashboard.tenant-profiles.edit', $tenant) }}"
                                   class="text-yellow-600 hover:underline">Edit</a>
                            </div>
                            <div>
                                <button
                                    wire:click="deleteTenant('{{ $tenant->id }}')"
                                    onclick="return confirm('Are you sure you want to delete this tenant? This will drop their database and remove all data.')"
                                    class="text-red-600 hover:underline">
                                    Delete
                                </button>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        @else
            <tr class="text-center">
                <td colspan="6" class="p-2">No tenants found.</td>
            </tr>
        @endif
        </tbody>
    </table>
    {{ $tenants->links() }}
</div>
