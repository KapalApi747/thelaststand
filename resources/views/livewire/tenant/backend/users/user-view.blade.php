<div class="p-6 space-y-6">

    @php
        $profile_picture = 'tenant' . tenant()->id . '/' . $user->profile_picture_path;
    @endphp

    <div class="flex justify-between items-center">
        <h3 class="h3 font-bold mb-4">{{ $user->name }}</h3>
        <div>
            <a
                class="btn btn-primary"
                href="{{ route('tenant-dashboard.user-edit', $user) }}">Edit</a>
        </div>
    </div>

    <div class="max-w-md p-6 bg-white rounded shadow">

        <div>
            <p class="text-black"><strong>Slug:</strong> {{ $user->slug }}</p>
            <p class="text-black"><strong>Email:</strong> {{ $user->email }}</p>
            <p class="text-black"><strong>Active:</strong> {{ $user->is_active ? 'Yes' : 'No' }}</p>
        </div>

        <div class="mt-4">
            <strong>Roles:</strong>
            <ul class="list-disc ml-5">
                @foreach ($user->roles as $role)
                    <li>{{ $role->name }}</li>
                @endforeach
            </ul>
        </div>

        <div class="mt-4">
            <strong>Profile picture:</strong>
            <div class="w-40 h-40 overflow-hidden rounded-full mt-6">
                <img src="{{ file_exists(public_path($profile_picture)) ? asset($profile_picture) : 'https://placehold.co/40x40' }}" class="w-full h-full object-cover">
            </div>
        </div>

    </div>
</div>
