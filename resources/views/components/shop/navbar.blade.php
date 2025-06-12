<nav class="bg-black px-12 py-4 rounded flex justify-between items-center">

    <div>
        <a
            class="bg-teal-600 text-white py-2 px-4 rounded hover:bg-teal-700 transition"
            href="{{ route('shop.shop-cart') }}"
        >
            Shopping Cart
        </a>
    </div>

    <div class="flex items-center">
        @auth('customer')
        <div x-data="{ open: false }" class="relative">

                <button
                    @click="open = !open"
                    @keydown.escape.window="open = false"
                    @click.outside="open = false"
                    type="button"
                    class="flex items-center py-2 px-4 text-green-300 font-medium focus:outline-none cursor-pointer"
                    aria-haspopup="true"
                    :aria-expanded="open"
                >
                    Hello, {{ auth('customer')->user()->name }}!
                    <svg class="w-4 h-4 ms-3 transform transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div
                    x-show="open"
                    x-transition
                    class="absolute right-0 mt-2 bg-white text-gray-800 rounded shadow-lg w-48 z-20"
                    @click.outside="open = false"
                >
                    <a href="{{ route('shop.customer-orders') }}" class="block px-4 py-2 hover:bg-gray-100">My Orders</a>
                    <a href="{{ route('shop.customer-addresses') }}" class="block px-4 py-2 hover:bg-gray-100">My Addresses</a>
                    <a href="{{ route('shop.customer-profile') }}" class="block px-4 py-2 hover:bg-gray-100">My Profile</a>
                    <a href="{{ route('shop.customer-settings') }}" class="block px-4 py-2 hover:bg-gray-100">Settings</a>
                    <form method="POST" action="{{ route('shop.customer-logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 hover:bg-red-100 text-red-600 cursor-pointer">
                            Logout
                        </button>
                    </form>
                </div>
        </div>
        @else
            <a
                class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-900 transition"
                href="{{ route('shop.customer-login') }}"
            >
                Login
            </a>
        @endauth
    </div>
</nav>
