<div class="p-6">
    <div class="flex justify-between">
        <div>
            <h1 class="text-2xl font-bold mb-4">{{ $user->name }}</h1>
            <p class="text-black"><strong>Slug:</strong> {{ $user->slug }}</p>
            <p class="text-black"><strong>Email:</strong> {{ $user->email }}</p>
            <p class="text-black"><strong>Active:</strong> {{ $user->is_active ? 'Yes' : 'No' }}</p>
        </div>
        <div>
            <a
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                href="{{ route('tenant-dashboard.user-edit', $user) }}">Edit</a>
        </div>
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
        <div>
            <img src="{{ asset('tenant' . tenant()->id . '/' . $user->profile_picture_path) }}" class="h-48 w-48 rounded">
        </div>
    </div>
</div>
