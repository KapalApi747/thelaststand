<div class="space-y-3 mt-4 ms-6 border-l border-gray-700 ps-4">
    <h4 class="text-sm font-semibold text-gray-300">Replies</h4>

    @foreach ($replies as $reply)
        <div
            class="text-sm text-gray-200 mb-2 border-l border-gray-700 pl-3">
            <p class="mb-1">
                <strong>{{ $reply->customer?->name ?? 'Deleted User' }}</strong>
                <span class="text-xs text-gray-500 ml-2">
                    {{ $reply->created_at->diffForHumans() }}
                </span>
            </p>
            <p class="text-gray-400">{{ $reply->body }}</p>
        </div>
    @endforeach

    @auth('customer')
        <form wire:submit.prevent="submit" class="mt-3 space-y-2">
            <textarea wire:model.defer="body"
                      class="w-full rounded bg-gray-900 text-white border-gray-700 focus:ring focus:ring-indigo-400"
                      rows="2" placeholder="Write a reply..."></textarea>
            @error('body') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror

            <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded text-sm cursor-pointer">
                Reply
            </button>
        </form>

        @if (session()->has('message'))
            <p class="text-green-400 text-sm mt-2">{{ session('message') }}</p>
        @endif
    @endauth
</div>
