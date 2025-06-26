<div class="space-y-6 p-6">

    <h3 class="h3 font-bold mb-4">All Categories</h3>

    @if (session()->has('message'))
        <div class="p-2 bg-green-200 text-green-800 rounded">
            {{ session('message') }}
        </div>
    @endif

    <livewire:tenant.backend.categories.category-creation />

    <div class="bg-white p-4 rounded shadow mt-6">
        <h2 class="text-xl font-semibold mb-4">My Categories</h2>

        @if ($categories->isEmpty())
            <p class="text-gray-600">You haven't added any categories yet.</p>
        @else
            <div class="flex flex-col">
                @foreach ($categories as $category)
                    @include('livewire.tenant.backend.categories._category-list', [
                        'category' => $category,
                        'depth' => 0
                    ])
                @endforeach
            </div>
        @endif
    </div>

</div>
