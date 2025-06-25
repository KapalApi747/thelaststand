<div class="space-y-6 p-6">

    <h3 class="h3 font-bold mb-4">Editing Page: <span class="text-gray-700">{{ $page->title }}</span></h3>

    @if (session()->has('message'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4">{{ session('message') }}</div>
    @endif

    <div>
        <label class="block font-medium">Title</label>
        <input type="text" wire:model.live="title" class="input w-full">
    </div>

    <div>
        <label class="block font-medium">Slug</label>
        <input type="text" wire:model.live="slug" class="input w-full">
    </div>

    <div>
        <label class="block font-medium">Content</label>
        <textarea wire:model.live="content_html" class="input w-full h-40"></textarea>
    </div>

    <div>
        <label class="inline-flex items-center">
            <input type="checkbox" wire:model="is_active" class="form-checkbox">
            <span class="ml-2">Active</span>
        </label>
    </div>

    <div class="pt-4">
        <button wire:click="save" class="btn">Save Changes</button>
    </div>
</div>
