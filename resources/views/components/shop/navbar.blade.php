@php
    use Illuminate\Support\Facades\Auth;

    $store_logo_url = 'tenant' . tenant()->id . '/assets/img/store_logo.png';

    $tenantUser = Auth::guard('web')->user();
    $customerUser = Auth::guard('customer')->user();

    $linkedCustomer = null;
    if ($tenantUser && $tenantUser->relationLoaded('customers')) {
        $linkedCustomer = $tenantUser->customers->first();
    } elseif ($tenantUser) {
        $linkedCustomer = $tenantUser->customers()->first();
    }
@endphp

<nav class="bg-gray-100 px-8 py-4 flex justify-between items-center">
    <div class="flex items-center gap-4">
        @if (file_exists(public_path('tenancy/assets/' . $store_logo_url)))
            <div class="w-10 h-10 rounded-full overflow-hidden">
                <img
                    src="{{ file_exists(public_path('tenancy/assets/' . $store_logo_url)) ? asset($store_logo_url) : 'https://placehold.co/40x40' }}"
                    alt="store_logo"
                    class="w-full h-full object-cover">
            </div>
        @endif
        <div>
            <h1 class="text-black font-bold text-3xl">{{tenant()->store_name}}</h1>
        </div>
    </div>
    <div class="flex items-center">
        <div class="me-4">
            <a
                class="bg-teal-600 text-white py-2 px-4 rounded hover:bg-teal-700 transition-colors duration-300"
                href="{{ route('shop.shop-cart') }}"
            >
                <i class="fa-solid fa-cart-shopping"></i>
            </a>
        </div>

        @if ($customerUser || $linkedCustomer)
            @php
                $displayName = $customerUser
                    ? $customerUser->name
                    : ($linkedCustomer ? $linkedCustomer->name : ($tenantUser ? $tenantUser->name : 'User'));
            @endphp

            <div x-data="{ open: false }" class="relative">
                <button
                    @click="open = !open"
                    @keydown.escape.window="open = false"
                    @click.outside="open = false"
                    type="button"
                    class="flex items-center py-2 px-4 text-green-500 font-medium focus:outline-none cursor-pointer"
                    aria-haspopup="true"
                    :aria-expanded="open"
                >
                    Hello, {{ $displayName }}!
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
                    @if ($customerUser || $linkedCustomer)
                        <a href="{{ route('shop.customer-orders') }}" class="block px-4 py-2 hover:bg-gray-100">My Orders</a>
                        <a href="{{ route('shop.customer-addresses') }}" class="block px-4 py-2 hover:bg-gray-100">My Addresses</a>
                        <a href="{{ route('shop.customer-profile') }}" class="block px-4 py-2 hover:bg-gray-100">My Profile</a>
                        <a href="{{ route('shop.customer-settings') }}" class="block px-4 py-2 hover:bg-gray-100">Settings</a>
                    @endif

                    @if ($tenantUser)
                        <a href="{{ route('tenant-dashboard.index') }}" class="block px-4 py-2 hover:bg-gray-100">Tenant Dashboard</a>
                    @endif

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
                href="{{ route('shop.login') }}"
            >
                Login
            </a>
        @endif

    </div>
</nav>
