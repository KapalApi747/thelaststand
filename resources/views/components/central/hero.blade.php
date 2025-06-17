<div class="text-center">
    @auth('central')
        <p class="text-sm text-gray-400 mt-2">Welcome back, <span class="text-indigo-400 font-semibold">{{ auth('central')->user()->name }}!</span></p>
    @endauth
</div>

<!-- Hero -->
<section class="text-center py-24 px-6">
    <h2 class="text-4xl md:text-5xl font-extrabold mb-6">Build Your Empire with THE LAST STAND</h2>
    <p class="text-lg text-gray-400 max-w-2xl mx-auto mb-8">A powerful, multi-tenant e-commerce platform where your store becomes unstoppable. Launch, sell, and thrive — all in one place.</p>
    <a href="{{ route('tenant-register') }}" class="inline-block bg-indigo-600 hover:bg-indigo-500 text-white px-6 py-3 rounded-lg text-lg font-semibold transition">Get Started</a>
</section>
