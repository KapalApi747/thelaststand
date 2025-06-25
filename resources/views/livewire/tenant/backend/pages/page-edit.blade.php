<div class="space-y-6 p-6">

    <h3 class="h3 font-bold mb-4">Editing Page: <span class="text-gray-700">{{ $page->title }}</span></h3>

    <form wire:submit.prevent="save" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium">Title</label>
            <input type="text" wire:model="page.title" class="w-full rounded border-gray-300" />
        </div>

        <div>
            <label class="block text-sm font-medium">Content</label>
            <textarea wire:model="page.content_html" rows="10" class="w-full rounded border-gray-300"></textarea>
        </div>

        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">
            Save Page
        </button>
    </form>
</div>
