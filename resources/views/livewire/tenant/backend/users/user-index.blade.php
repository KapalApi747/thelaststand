<div class="space-y-6 p-6">

    <h3 class="h3 font-semibold mb-4">All Users</h3>

    @if (session()->has('message'))
        <div class="p-2 bg-green-200 text-green-800 rounded">
            {{ session('message') }}
        </div>
    @endif

    <div class="bg-white p-6 rounded shadow mt-6">

        <div class="flex flex-wrap gap-4 mb-6">
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Search users (use user's name, email) ..."
                class="border border-gray-300 rounded px-3 py-2 w-full md:w-1/3"
            />

            <select wire:model.live="role" class="border border-gray-300 rounded px-3 py-2">
                <option value="">All Roles</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>

            <select wire:model.live="is_active" class="border border-gray-300 rounded px-3 py-2">
                <option value="">All Statuses</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>

            <select wire:model.live="pagination" class="border border-gray-300 rounded px-3 py-2">
                <option value="10">10 per page</option>
                <option value="20">20 per page</option>
                <option value="50">50 per page</option>
            </select>

            <select wire:model.live="sortField" class="border border-gray-300 rounded px-3 py-2">
                <option value="name">Name</option>
                <option value="created_at">Date Added</option>
            </select>

            <select wire:model.live="sortDirection" class="border border-gray-300 rounded px-3 py-2">
                <option value="asc">Ascending</option>
                <option value="desc">Descending</option>
            </select>

        </div>

        @if ($users->isEmpty())
            <p class="text-gray-600">The users list is currently empty. Please adjust your search criteria.</p>
        @else
            <table class="w-full border-collapse border">
                <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2">Profile Picture</th>
                    <th class="border p-2">Name</th>
                    <th class="border p-2">Email</th>
                    <th class="border p-2">Roles</th>
                    <th class="border p-2">Status</th>
                    <th class="border p-2">Joined At</th>
                    <th class="border p-2">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td class="border p-2 flex justify-center items-center">
                            @php
                                $picturePath = 'tenant' . tenant()->id . '/' . $user->profile_picture_path;
                            @endphp

                            <img src="{{ $user->profile_picture_path && file_exists(public_path('tenancy/assets/' . $picturePath)) ? asset($picturePath) : 'https://placehold.co/10x10' }}" alt="Profile Picture" class="w-10 h-10 rounded-full">
                        </td>
                        <td class="border p-2">{{ $user->name }}</td>
                        <td class="border p-2">{{ $user->email }}</td>
                        <td class="border p-2">{{ $user->roles->pluck('name')->implode(', ') }}</td>
                        <td class="border p-2 text-center">{{ $user->is_active ? 'Active' : 'Inactive' }}</td>
                        <td class="border p-2 text-center">{{ $user->created_at->format('Y-m-d') }}</td>
                        <td class="border p-2">
                            <div class="flex justify-center items-center">
                                <div class="mr-2">
                                    <a href="{{ route('tenant-dashboard.user-view', $user) }}"
                                       class="text-blue-500 hover:text-blue-700 transition-colors duration-300">

                                        <i class="far fa-eye"></i>
                                    </a>
                                </div>
                                <div class="mr-2">
                                    <a href="{{ route('tenant-dashboard.user-edit', $user) }}"
                                       class="text-yellow-500 hover:text-yellow-700 transition-colors duration-300">
                                        <i class="far fa-edit"></i>
                                    </a>
                                </div>
                                <div>
                                    @if ($user->is_active)
                                        <button
                                            type="button"
                                            wire:click="activeToggle({{ $user->id }})"
                                            class="text-red-600 hover:text-red-800 transition-colors duration-300">
                                            <i class="far fa-ban"></i>
                                        </button>
                                    @else
                                        <button
                                            type="button"
                                            wire:click="activeToggle({{ $user->id }})"
                                            class="text-green-600 hover:text-green-800 transition-colors duration-300">
                                            <i class="far fa-check"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
    <div class="mt-6">
        {{ $users->links() }}
    </div>
</div>
