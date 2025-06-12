<div class="mx-auto p-12 bg-black shadow">
    <div class="flex justify-between items-center mb-12">
        <h2 class="text-3xl font-bold">My Profile</h2>
        <a
            href=" {{ route('shop.shop-products') }}"
            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"
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
            <label class="block font-semibold mb-1">Name</label>
            <input type="text" wire:model.defer="name" class="w-full border px-3 py-2 rounded" />
            @error('name') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-semibold mb-1">Email</label>
            <input type="email" wire:model.defer="email" class="w-full border px-3 py-2 rounded" />
            @error('email') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-semibold mb-1">Phone</label>
            <input type="text" wire:model.defer="phone" class="w-full border px-3 py-2 rounded" />
            @error('phone') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <hr class="my-12" />

        <h3 class="text-lg font-semibold mb-3">Addresses</h3>

        <a href="{{ route('shop.customer-addresses') }}" class="text-blue-500 hover:underline">
            Manage your addresses
        </a>


        <hr class="my-12" />

        <h3 class="text-lg font-semibold mb-3">Linked Payment Accounts</h3>
        @if(count($paymentAccounts) > 0)
            <ul class="list-disc pl-5">
                @foreach($paymentAccounts as $account)
                    <li>
                        <strong>{{ ucfirst($account['provider']) }}</strong> - ID: {{ $account['provider_customer_id'] }}
                    </li>
                @endforeach
            </ul>
        @else
            <p>No linked payment accounts.</p>
        @endif

        <div class="mt-12 pb-12 flex justify-center">
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                Save Changes
            </button>
        </div>
    </form>
</div>
