<div class="max-w-md mx-auto mt-10">
    <form wire:submit.prevent="loginTenant" class="bg-white p-6 rounded shadow">
        <h1 class="text-xl mb-4">Tenant Login</h1>

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

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Login</button>
    </form>
</div>
