<label class="flex items-center space-x-2 text-sm text-gray-800" style="margin-left: {{ $level * 1.25 }}rem;">
    <input
        type="checkbox"
        wire:model.live="selectedCategories"
        value="{{ $category->id }}"
        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
    >
    <span>{{ $category->name }}</span>
</label>

@if ($category->children->isNotEmpty())
    @foreach ($category->children as $child)
        @include('partials.categories.category-checkbox', ['category' => $child, 'level' => $level + 1])
    @endforeach
@endif
