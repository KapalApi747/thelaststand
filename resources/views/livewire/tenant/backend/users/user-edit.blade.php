<div class="p-6 space-y-4">

    @if (session()->has('message'))
        <div class="text-green-600 font-semibold">{{ session('message') }}</div>
    @endif

    <form wire:submit.prevent="updateUser" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
            <label for="name" class="block font-medium mb-1">Name</label>
            <input type="text" id="name" wire:model.defer="name" class="w-full border px-3 py-2 rounded" />
            @error('name') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="email" class="block font-medium mb-1">Email</label>
            <input type="email" id="email" wire:model.defer="email" class="w-full border px-3 py-2 rounded" />
            @error('email') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="block font-medium mb-1">Password</label>
            <input type="password" id="password" wire:model.defer="password" class="w-full border px-3 py-2 rounded" />
            @error('password') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="block font-medium mb-1">Confirm Password</label>
            <input type="password" id="password_confirmation" wire:model.defer="password_confirmation" class="w-full border px-3 py-2 rounded" />
            @error('password_confirmation') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="roles">Roles</label>
            <select wire:model="roles" multiple class="form-select">
                @foreach($existingRoles as $role)
                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                @endforeach
            </select>
            @error('roles') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label>Status</label>
            <select wire:model.defer="is_active" class="w-full border p-2 rounded">
                <option value="0">Inactive</option>
                <option value="1">Active</option>
            </select>
            @error('is_active') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label>Current Profile Picture</label>
            @if ($existingProfilePicture)
                <img src="{{ asset('tenant' . tenant()->id . '/' . $user->profile_picture_path }}" class="h-20 w-20 rounded-full mt-2">
            @endif
        </div>

        @if ($profile_picture)
            <div>
                <label>New Profile Picture</label>
                <img src="{{ $profile_picture->temporaryUrl() }}" class="h-20 w-20 rounded-full mt-2">
            </div>
        @endif



        <div class="mb-4">
            <label>Profile Picture</label>
            <input type="file" wire:model="profile_picture" accept="image/*" class="mt-1 block w-full">

            @error('profile_picture') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
            Update User
        </button>
    </form>
</div>
