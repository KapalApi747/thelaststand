<label class="inline-flex items-center space-x-2 ml-{{ $level * 4 }}">
    <input
        type="checkbox"
        value="{{ $category->id }}"
        wire:model="categoryIds"
        class="form-checkbox"
    >
    <span>{{ $category->name }}</span>
</label>

@if ($category->children && $category->children->isNotEmpty())
    @foreach ($category->children as $child)
        @include('partials.categories.category-checkbox-2', ['category' => $child, 'level' => $level + 1])
    @endforeach
@endif
