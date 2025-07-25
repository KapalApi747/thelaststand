<div class="space-y-6 p-6">

    <h3 class="h3 font-semibold mb-4">Editing: <span class="text-gray-700">{{ $user->name }}</span></h3>

    @if (session()->has('message'))
        <div class="text-green-600 font-semibold">{{ session('message') }}</div>
    @endif

    <div class="p-6 bg-white rounded shadow">
        <form wire:submit.prevent="updateUser" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="name" class="block font-semibold mb-1">Name</label>
                <input type="text" id="name" wire:model.live="name" class="w-full border px-3 py-2 rounded"/>
                @error('name') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="block font-semibold mb-1">Email</label>
                <input type="email" id="email" wire:model.live="email" class="w-full border px-3 py-2 rounded"/>
                @error('email') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block font-semibold mb-1">New Password (optional)</label>
                <input type="password" id="password" wire:model.defer="password"
                       class="w-full border px-3 py-2 rounded"/>
                @error('password') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="block font-semibold mb-1">Confirm Password (optional)</label>
                <input type="password" id="password_confirmation" wire:model.defer="password_confirmation"
                       class="w-full border px-3 py-2 rounded"/>
                @error('password_confirmation') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4 flex flex-col max-w-sm">
                <label class="block font-semibold mb-2">Roles</label>

                <div class="space-y-2">
                    @foreach($allRoles as $role)
                        <label class="inline-flex items-center space-x-2">
                            <input
                                type="checkbox"
                                value="{{ $role->name }}"
                                wire:model="userRoles"
                                class="form-checkbox"
                            >
                            <span>{{ $role->name }}</span>
                        </label>
                    @endforeach
                </div>

                @error('roles') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4 max-w-sm">
                <label for="is_active" class="block font-semibold mb-1">Status</label>
                <select wire:model.live="is_active" class="w-full border p-2 rounded">
                    <option value="0">Inactive</option>
                    <option value="1">Active</option>
                </select>
                @error('is_active') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>

            @if ($existingProfilePicture)
                <div class="mb-4">
                    <label class="block font-semibold mb-1">Current Profile Picture</label>
                    <div class="w-20 h-20 overflow-hidden rounded-full mt-4">
                        <img src="{{ asset('tenant' . tenant()->id . '/' . $user->profile_picture_path) }}"
                             class="w-full h-full object-cover">
                    </div>

                </div>
            @endif

            @if ($profile_picture)
                <div>
                    <label class="block font-semibold mb-1">New Profile Picture</label>
                    <div class="w-20 h-20 overflow-hidden rounded-full mt-4">
                        <img src="{{ $profile_picture->temporaryUrl() }}" class="w-full h-full object-cover">
                    </div>
                </div>
            @endif

            <div class="my-6">
                <label for="profile_picture" class="block font-semibold mb-1">Profile Picture</label>
                <input
                    type="file"
                    wire:model="profile_picture"
                    accept="image/*"
                    class="mt-1 block w-full"
                >
                @error('profile_picture')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror

                {{-- Spinner only visible while uploading --}}
                <div wire:loading wire:target="profile_picture" class="mt-2 text-gray-500 flex items-center gap-2">
                    <svg class="animate-spin h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                              d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 100 16v-4l-3 3 3 3v-4a8 8 0 01-8-8z"></path>
                    </svg>
                    <span>Uploading...</span>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                Update User
            </button>
        </form>
    </div>
</div>
