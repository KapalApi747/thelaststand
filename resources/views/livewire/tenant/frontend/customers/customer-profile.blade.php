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

        <h3 class="text-lg font-semibold mb-3 text-gray-900">Linked Payment Accounts</h3>
        @if(count($paymentAccounts) > 0)
            <ul class="list-disc pl-5 text-gray-900">
                @foreach($paymentAccounts as $account)
                    <li>
                        <strong>{{ ucfirst($account['provider']) }}</strong> - ID: {{ $account['provider_customer_id'] }}
                    </li>
                @endforeach
            </ul>
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
