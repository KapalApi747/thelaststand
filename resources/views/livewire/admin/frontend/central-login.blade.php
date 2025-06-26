<div class="bg-gray-950 py-16 px-4 sm:px-6 lg:px-8 min-h-screen">
    <div class="max-w-2xl mx-auto bg-gray-900 p-10 rounded-xl shadow-lg border border-gray-800">
        <form wire:submit.prevent="loginCentral" class="p-6 rounded shadow">
            <h1 class="text-xl mb-4">Admin Login</h1>

            <div class="mb-4">
                <label class="block text-sm font-medium">Email</label>
                <input wire:model.live="email" type="email" class="w-full border px-3 py-2 rounded" />
                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Password</label>
                <input wire:model.live="password" type="password" class="w-full border px-3 py-2 rounded" />
                @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{--<div class="mb-4">
                <label class="inline-flex items-center">
                    <input wire:model="remember" type="checkbox" class="mr-2">
                    Remember Me
                </label>
            </div>--}}

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors duration-300 ease-in-out cursor-pointer">Login</button>
        </form>
    </div>
</div>
