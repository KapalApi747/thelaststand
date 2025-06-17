<!-- Navigation -->
<header class="px-6 py-4 flex flex-wrap justify-between items-center border-b border-gray-700 bg-gray-900">

    <a href="{{ route('home') }}">
        <h1 class="text-2xl font-bold text-white tracking-wide">THE LAST STAND</h1>
    </a>

    <nav class="flex items-center gap-6 text-sm text-gray-300 mt-2 sm:mt-0">
        <a href="#features" class="hover:text-indigo-400 transition-colors">Features</a>

        <a href="{{ route('tenant-register') }}" class="hover:text-indigo-400 transition-colors">Register</a>

        @auth('central')
            <a href="{{ route('dashboard.tenant-profiles') }}" class="hover:text-indigo-400 transition-colors">
                Dashboard
            </a>

            <form method="POST" action="{{ route('central-logout') }}">
                @csrf
                <button type="submit"
                        class="bg-gray-800 hover:bg-gray-700 px-4 py-2 rounded text-white hover:text-indigo-300 transition duration-300 ease-in-out cursor-pointer">
                    Logout
                </button>
            </form>
        @else
            <a href="{{ route('central-login') }}"
               class="bg-indigo-600 hover:bg-indigo-500 px-4 py-2 rounded text-white transition">
                Login
            </a>
        @endauth
    </nav>
</header>
