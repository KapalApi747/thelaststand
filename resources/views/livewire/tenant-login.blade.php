<div class="bg-blue-100 py-16 px-4 sm:px-6 lg:px-8 min-h-screen">
    <div class="max-w-2xl mx-auto bg-blue-50 p-10 rounded-xl shadow-lg border border-blue-200">
        <form wire:submit.prevent="loginTenant" class="p-6 rounded shadow">
            @csrf
            <h1 class="text-xl mb-6 text-blue-900 font-semibold">Tenant Login</h1>

            <div class="mb-4">
                <label class="block text-sm font-medium text-blue-800">Email</label>
                <input wire:model="email" type="email" class="w-full text-blue-800 border border-blue-300 px-3 py-2 rounded bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none" />
                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-blue-800">Password</label>
                <input wire:model="password" type="password" class="w-full text-blue-800 border border-blue-300 px-3 py-2 rounded bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none" />
                @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="inline-flex items-center text-blue-800">
                    <input wire:model="remember" type="checkbox" class="mr-2 rounded border-blue-300 focus:ring-blue-300">
                    Remember Me
                </label>
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors duration-300 ease-in-out cursor-pointer">
                Login
            </button>
        </form>
    </div>
</div>
