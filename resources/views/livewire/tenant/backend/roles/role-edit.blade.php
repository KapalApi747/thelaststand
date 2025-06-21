<div class="p-6 space-y-6">

    <h3 class="h3 font-bold mb-6">Edit Role: <span class="text-gray-700">{{ $role->name }}</span></h3>

    @if (session()->has('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    <div class="p-6 bg-white rounded shadow">
        <form wire:submit.prevent="save" class="space-y-6">
            @csrf
            <div class="max-w-md">
                <label for="name" class="text-2xl text-semibold mb-2">Role Name</label>
                <input
                    type="text"
                    id="name"
                    wire:model.defer="name"
                    class="w-full rounded border p-2"
                />
                @error('name') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div>
                <h2 class="text-2xl text-semibold mb-2">Permissions</h2>
                <div class="max-w-md flex flex-col space-y-2 border rounded p-2">
                    @foreach ($allPermissions as $permission)
                        <label>
                            <input type="checkbox" wire:model="permissions" value="{{ $permission->name }}">
                            {{ $permission->name }}
                        </label>
                    @endforeach
                </div>

                @error('permissions.*') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="flex space-x-4 align-center">
                <div>
                    <button type="submit" class="btn btn-primary">Update Role</button>
                </div>
                <div>
                    <a href="{{ route('tenant-dashboard.role-index') }}" class="btn-gray">
                        Cancel
                    </a>
                </div>
            </div>

        </form>
    </div>
</div>
