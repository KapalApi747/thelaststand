<div class="max-w-6xl mx-auto mt-6 p-6 bg-white shadow rounded">
    <div class="flex justify-between items-center mb-12">
        <h2 class="text-3xl font-bold text-gray-900">My Profile</h2>
        <a
            href="{{ route('shop.shop-products') }}"
            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition-colors duration-300"
        >
            Back To Shop
        </a>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="save" class="space-y-6">
        @csrf
        <div>
            <label class="block font-semibold mb-1 text-gray-800">Name</label>
            <input type="text" wire:model.defer="name" class="w-full border border-gray-300 px-3 py-2 rounded text-gray-900 bg-white" />
            @error('name') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-semibold mb-1 text-gray-800">Email</label>
            <input type="email" wire:model.defer="email" class="w-full border border-gray-300 px-3 py-2 rounded text-gray-900 bg-white" />
            @error('email') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-semibold mb-1 text-gray-800">Phone</label>
            <input type="text" wire:model.defer="phone" class="w-full border border-gray-300 px-3 py-2 rounded text-gray-900 bg-white" />
            @error('phone') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <hr class="my-12 border-gray-300" />

        <h3 class="text-lg font-semibold mb-3 text-gray-900">Addresses</h3>

        <a href="{{ route('customer-addresses') }}" class="text-blue-600 hover:underline transition-colors duration-300">
            Manage your addresses
        </a>

        <hr class="my-12 border-gray-300" />

        <h3 class="text-lg font-semibold mb-4 text-gray-900">Linked Payment Accounts</h3>

        @if ($paymentAccounts)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ($paymentAccounts as $account)
                    <div class="border rounded-lg p-4 bg-white shadow-sm flex items-center gap-4">
                        {{-- Icon or provider logo --}}
                        <div class="flex-shrink-0">
                            @if ($account->provider === 'stripe')
                                <img src="https://cdn.jsdelivr.net/npm/simple-icons@v11/icons/stripe.svg" alt="Stripe" class="h-6 w-auto" />
                            @else
                                <span class="inline-block w-6 h-6 bg-gray-300 rounded-full"></span>
                            @endif
                        </div>

                        <div>
                            <div class="text-sm font-medium text-gray-900">
                                {{ ucfirst($account->provider) }}
                            </div>
                            <div class="text-xs text-gray-500">
                                Linked on {{ $account->created_at->format('M d, Y') }}
                            </div>
                        </div>

                        {{-- Optionally show ID or unlink button --}}
                        <div class="ml-auto text-xs text-gray-400">
                            ID: {{ Str::limit($account->provider_customer_id, 8, '...') }}
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-700">No linked payment accounts.</p>
        @endif

        <div class="mt-12 pb-12 flex justify-center">
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition-colors duration-300 cursor-pointer">
                Save Changes
            </button>
        </div>
    </form>
</div>
