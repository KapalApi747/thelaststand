<div class="p-6 space-y-4">
    <h1 class="text-2xl font-bold">Tenant Profiles</h1>

    <table class="min-w-full border rounded shadow">
        <thead>
        <tr class="bg-gray-100 text-left text-black">
            <th class="p-2">Store Name</th>
            <th class="p-2">Email</th>
            <th class="p-2">Phone</th>
            <th class="p-2">Domain</th>
            <th class="p-2">Status</th>
            <th class="p-2">Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($tenants as $tenant)
            <tr class="border-t">
                <td class="p-2">{{ $tenant->store_name ?? '-' }}</td>
                <td class="p-2">{{ $tenant->profile->email ?? '-' }}</td>
                <td class="p-2">{{ $tenant->profile->phone ?? '-' }}</td>
                <td class="p-2">{{ $tenant->domains()->first()->domain ?? '-' }}</td>
                <td class="p-2">
                        <span class="inline-block px-2 py-1 text-sm rounded {{ $tenant->profile->store_status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ ucfirst($tenant->profile->store_status) }}
                        </span>
                </td>
                <td class="p-2">
                    <a href="{{ route('dashboard.admin.tenant-profiles.edit', $tenant) }}" class="text-blue-600 hover:underline">View</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $tenants->links() }}
</div>
