<div class="space-y-6 p-6">

    @if (session()->has('message'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <div class="flex items-center justify-between">
        <input type="text" wire:model.live.debounce.300ms="search" class="input" placeholder="Search pages...">

        {{--<a href="{{ route('tenant.pages.create') }}" class="btn btn-success">+ New Page</a>--}}
    </div>

    <table class="table-auto w-full text-left mt-4">
        <thead>
        <tr class="border-b border-gray-700">
            <th class="p-2">Title</th>
            <th class="p-2">Slug</th>
            <th class="p-2">Active</th>
            <th class="p-2 text-right">Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($pages as $page)
            <tr>
                <td class="p-2 border">{{ $page->title }}</td>
                <td class="p-2 border">{{ $page->slug }}</td>
                <td class="p-2 border">
                        <span class="{{ $page->is_active ? 'text-green-500' : 'text-red-500' }}">
                            {{ $page->is_active ? 'Yes' : 'No' }}
                        </span>
                </td>
                <td class="p-2 border">
                    <a href="{{ route('tenant-dashboard.page-edit', $page) }}" class="btn">Edit</a>
                    <button wire:click="delete({{ $page->id }})" class="btn btn-sm btn-danger">Delete</button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="p-4 text-center text-gray-400">No pages found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div>
        {{ $pages->links() }}
    </div>
</div>
