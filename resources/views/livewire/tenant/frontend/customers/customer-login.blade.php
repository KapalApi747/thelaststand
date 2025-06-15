<div class="max-w-md mx-auto mt-10 bg-red-900">

    @if (session()->has('message'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="loginCustomer" class="p-6 rounded shadow">
        <h1 class="text-xl mb-4">Customer Login</h1>

        <div class="mb-4">
            <label class="block text-sm font-medium">Email</label>
            <input wire:model="email" type="email" class="w-full border px-3 py-2 rounded" />
            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium">Password</label>
            <input wire:model="password" type="password" class="w-full border px-3 py-2 rounded" />
            @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="inline-flex items-center">
                <input wire:model="remember" type="checkbox" class="mr-2">
                Remember Me
            </label>
        </div>

        <div class="mb-4">
            <p>Not a member yet? <a href="{{ route('shop.customer-register') }}" class="text-blue-500">Register</a></p>
        </div>

        <button type="submit" class="bg-black text-white px-4 py-2 rounded hover:bg-gray-700">Login</button>
    </form>
</div>
