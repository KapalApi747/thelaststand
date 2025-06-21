<div class="p-6 space-y-6">

    <h3 class="h3 font-bold mb-6">Create New Role</h3>

    @if (session()->has('message'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="submit">
        @csrf
        <div class="mb-4">
            <label for="name" class="block font-medium mb-1">Role Name</label>
            <input type="text" id="name" wire:model.defer="name"
                   class="w-full border rounded px-3 py-2 @error('name') border-red-500 @enderror" />
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-2">Permissions</label>
            <div class="max-h-48 overflow-auto border rounded p-2">
                @foreach ($allPermissions as $permission)
                    <label class="flex items-center space-x-2 mb-1">
                        <input type="checkbox" wire:model="permissions" value="{{ $permission->name }}" />
                        <span>{{ $permission->name }}</span>
                    </label>
                @endforeach
            </div>
            @error('permissions') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="flex space-x-4 align-center">
            <div>
                <button type="submit" class="btn btn-primary">Create Role</button>
            </div>
            <div>
                <a href="{{ route('tenant-dashboard.role-index') }}" class="btn-gray">
                    Cancel
                </a>
            </div>
        </div>
    </form>
</div>
