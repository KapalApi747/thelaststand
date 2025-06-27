<option value="{{ $category->id }}">
    {!! str_repeat('&nbsp;&nbsp;&nbsp;', $level) !!}{{ $category->name }}
</option>

@if ($category->children)
    @foreach ($category->children as $child)
        @include('partials.categories.category-option', ['category' => $child, 'level' => $level + 1])
    @endforeach
@endif
