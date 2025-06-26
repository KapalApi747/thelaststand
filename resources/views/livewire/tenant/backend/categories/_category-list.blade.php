<div class="ml-{{ $depth * 4 }} border-l pl-4 mb-2">
    <h3 class="font-semibold text-gray-800">{{ $category->name }}</h3>
    <p class="text-sm text-gray-600">Slug: {{ $category->slug }}</p>

    @if ($category->children->isNotEmpty())
        @foreach ($category->children as $child)
            @include('livewire.tenant.backend.categories._category-list', [
                'category' => $child,
                'depth' => $depth + 1
            ])
        @endforeach
    @endif
</div>
