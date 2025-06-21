<div class="p-6 space-y-6">

    <div class="flex justify-between">
        <h3 class="h3 font-bold mb-4">All Roles</h3>
        <div>
            <a href="{{ route('tenant-dashboard.role-creation') }}"
               class="btn btn-primary">
                New Role
            </a>
        </div>
    </div>


    <div class="flex justify-between items-center mb-4">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search roles..."
               class="border rounded px-3 py-2 w-1/3"
        />
    </div>

    @if (session()->has('message'))
        <div class="p-2 mb-4 bg-green-100 text-green-700 rounded">{{ session('message') }}</div>
    @endif
    @if (session()->has('error'))
        <div class="p-2 mb-4 bg-red-100 text-red-700 rounded">{{ session('error') }}</div>
    @endif

    <div class="max-w-3xl">
        <table class="min-w-full bg-white border rounded shadow-sm">
            <thead>
            <tr>
                <th class="px-4 py-2 border-b">Name</th>
                <th class="px-4 py-2 border-b">Permissions Count</th>
                <th class="px-4 py-2 border-b">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($roles as $role)
                <tr class="hover:bg-gray-50">
                    <td class="border p-2 text-center">{{ $role->name }}</td>
                    <td class="border p-2 text-center">{{ $role->permissions()->count() }}</td>
                    <td class="border p-2">
                        <div class="flex justify-evenly">
                            <div>
                                <a href="{{ route('tenant-dashboard.role-edit', $role) }}"
                                   class="text-yellow-500 hover:text-yellow-700 transition-colors duration-300">
                                    <i class="far fa-edit"></i>
                                </a>
                            </div>
                            <div>
                                <button wire:click="deleteRole({{ $role->id }})"
                                        onclick="confirm('Are you sure you want to delete this role?') || event.stopImmediatePropagation()"
                                        class="text-red-600 hover:text-red-400 transition-colors duration-300">
                                    <i class="far fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center py-4 text-gray-500">No roles found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">
        {{ $roles->links() }}
    </div>
</div>
