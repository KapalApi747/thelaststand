<div class="space-y-6 p-6 max-w-2xl">

    <h3 class="h3 font-bold mb-4">All Categories</h3>

    @if (session()->has('message'))
        <div class="alert alert-success alert-close">
            <button class="alert-btn-close">
                <i class="fad fa-times"></i>
            </button>
            <span>{{ session('message') }}</span>
        </div>
    @endif

    <livewire:tenant.backend.categories.category-creation />

    <div class="bg-white p-4 rounded shadow mt-6">
        <h2 class="text-xl font-semibold mb-4">My Categories</h2>

        @if ($categories->isEmpty())
            <p class="text-gray-600">You haven't added any categories yet.</p>
        @else
            <div class="flex flex-col">
                @foreach ($this->categories as $category)
                    @include('livewire.tenant.backend.categories._category-list', [
                        'category' => $category,
                        'depth' => 0
                    ])
                @endforeach
            </div>
        @endif
    </div>

</div>
