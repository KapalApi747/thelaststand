<div class="space-y-6 p-6">

    <h3 class="h3 font-bold mb-4">All Pages</h3>

    <div class="grid grid-cols-3 md:grid-cols-1 gap-6 mb-6">
        <div class="bg-white p-4 shadow rounded">
            <p class="text-gray-500">Total Pages</p>
            <p class="text-2xl font-semibold">{{ $pagesCount }}</p>
        </div>
    </div>

    <div class="bg-white p-4 shadow rounded">

        <div class="mb-6">
            @if (session()->has('message'))
                <div class="p-2 bg-green-200 text-green-800 rounded">
                    {{ session('message') }}
                </div>
            @endif
        </div>

        <div class="flex flex-wrap gap-4 mb-6">
            <div class="flex items-center justify-between">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    class="border border-gray-300 rounded px-3 py-2 w-full md:w-1/3"
                    placeholder="Search pages by title..."
                >
                {{--<a href="{{ route('tenant.pages.create') }}" class="btn btn-success">+ New Page</a>--}}
            </div>
        </div>

        <table class="min-w-full text-left">
            <thead class="bg-gray-200">
            <tr class="border">
                <th class="p-2">Title</th>
                <th class="p-2">Slug</th>
                <th class="p-2 text-center">Active</th>
                <th class="p-2 text-center">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($pages as $page)
                <tr class="border">
                    <td class="p-2">{{ $page->title }}</td>
                    <td class="p-2">{{ $page->slug }}</td>
                    <td class="p-2 text-center">
                        <span class="{{ $page->is_active ? 'text-green-500' : 'text-red-500' }}">
                            {{ $page->is_active ? 'Yes' : 'No' }}
                        </span>
                    </td>
                    <td class="p-2">
                        <div class="flex justify-center items-center">
                            <a href="{{ route('tenant-dashboard.page-edit', $page) }}"
                               class="text-yellow-500 hover:text-yellow-700 transition-colors duration-300 mr-3"
                            >
                                <i class="far fa-edit"></i>
                            </a>
                            <button wire:click="delete({{ $page->id }})">
                                <i class="fa-solid fa-trash text-red-600 hover:text-red-800 transition-colors duration-300"></i>
                            </button>
                        </div>
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
</div>
