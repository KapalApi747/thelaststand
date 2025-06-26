<div class="ml-{{ $depth * 4 }} border-l pl-4 mb-2">
    <div class="flex justify-between items-center">
        <div>
            <strong>{{ $category->name }}</strong>
            <span class="text-sm text-gray-500">({{ $category->slug }})</span>
        </div>
        <div class="flex space-x-2">
            <div>
                <a href="{{ route('tenant-dashboard.category-edit', $category->slug) }}"
                   class="text-yellow-500 hover:text-yellow-700 transition-colors duration-300"
                >
                    <i class="fas fa-edit"></i>
                </a>
            </div>
            <div>
                <button
                    x-data
                    @click="if (confirm('Are you sure you want to delete this category?')) { $dispatch('category-delete-requested', { id: {{ $category->id }} }) }"
                    class="text-red-600 hover:text-red-800 transition-colors duration-300"
                >
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    </div>

    @if ($category->children->isNotEmpty())
        @foreach ($category->children as $child)
            @include('livewire.tenant.backend.categories._category-list', [
                'category' => $child,
                'depth' => $depth + 1
            ])
        @endforeach
    @endif
</div>
