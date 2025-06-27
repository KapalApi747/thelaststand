<div class="py-16 px-4 sm:px-6 lg:px-8 min-h-screen">

    @if (session()->has('message'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded">
            {{ session('message') }}
        </div>
    @endif

    <div class="max-w-2xl mx-auto bg-blue-50 p-10 rounded-xl shadow-lg border border-blue-200">
        <div class="p-6 rounded shadow">
            <h2 class="text-xl mb-6 text-blue-900 font-semibold text-center">Customer Registration</h2>

            <div class="mb-4">
                <label class="block text-sm font-medium text-blue-800">Name</label>
                <input type="text" wire:model.defer="name"
                       class="w-full text-blue-800 border border-blue-300 px-3 py-2 rounded bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none"
                />
                @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-blue-800">Email</label>
                <input type="email" wire:model.defer="email"
                       class="w-full text-blue-800 border border-blue-300 px-3 py-2 rounded bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none"
                />
                @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-blue-800">Phone</label>
                <input type="text" wire:model.defer="phone"
                       class="w-full text-blue-800 border border-blue-300 px-3 py-2 rounded bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none"
                />
                @error('phone') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-blue-800">Password</label>
                <input type="password" wire:model.defer="password"
                       class="w-full text-blue-800 border border-blue-300 px-3 py-2 rounded bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none"
                />
                @error('password') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-12">
                <label class="block text-sm font-medium text-blue-800">Confirm Password</label>
                <input type="password" wire:model.defer="password_confirmation"
                       class="w-full text-blue-800 border border-blue-300 px-3 py-2 rounded bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none"
                />
            </div>

            <div class="flex justify-between items-center">
                <div>
                    <a
                        href="{{ route('login') }}"
                        class="bg-blue-200 text-blue-800 px-4 py-2 rounded hover:bg-blue-600 hover:text-white transition-colors duration-300 ease-in-out cursor-pointer"
                    >
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                </div>
                <div>
                    <button wire:click="register"
                            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors duration-300 ease-in-out cursor-pointer">
                        Register
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
