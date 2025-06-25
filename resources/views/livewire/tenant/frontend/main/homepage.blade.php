<div class="py-24 text-center">
    <section>
        <div class="max-w-4xl mx-auto px-6">
            <h1 class="text-4xl md:text-6xl font-extrabold text-gray-900 leading-tight mb-6">
                Welcome to {{ tenant()->store_name }}!
            </h1>
            <p class="text-lg md:text-xl text-gray-700 mb-8">
                We’re not just another store. We're the last stand for bold ideas, small creators, and daring shoppers.
                If you’re tired of the same old — this is where you belong.
            </p>
            <div class="flex justify-center space-x-4">
                <a href="{{ route('shop.shop-products') }}"
                   class="px-6 py-3 bg-blue-500 text-white hover:bg-blue-700 rounded-md text-lg font-semibold transition-colors duration-300">
                    Shop Now
                </a>
                <a href="{{ route('page-show', ['slug' => 'about-us']) }}"
                   class="px-6 py-3 border-2 border-blue-500 text-blue-700 hover:bg-blue-100 rounded-md text-lg font-semibold transition-colors duration-300">
                    Learn More
                </a>
            </div>
        </div>
    </section>

    <section class="py-20">
        <div class="max-w-6xl mx-auto px-6">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-900">Featured Products</h2>
            {{-- @livewire('tenant.shop.featured-products') --}}
            <p class="text-center text-gray-500">[Coming soon – your most-loved picks will appear here]</p>
        </div>
    </section>

    <section id="about" class="py-20">
        <div class="max-w-5xl mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold mb-6 text-gray-900">About {{ tenant()->store_name }}</h2>
            <p class="text-lg text-gray-700 mb-4">
                We’re not part of some faceless corporate machine. {{ tenant()->store_name }} is run by real people with
                real passion — for craft, for quality, and for you.
            </p>
            <p class="text-lg text-gray-700 mb-4">
                Everything we sell is curated with care and grit. No fluff. No nonsense. Just things we believe in.
            </p>
        </div>
    </section>

    <section class="py-20">
        <div class="max-w-4xl mx-auto text-center px-6">
            <h2 class="text-3xl md:text-4xl font-bold mb-6 text-gray-900">
                Join the Movement. Shop Differently.
            </h2>
            <p class="text-lg md:text-xl mb-8 text-gray-700">
                Be part of something that empowers independent stores. Make a stand — with us.
            </p>
            <a href="{{ route('shop.shop-products') }}"
               class="px-8 py-4 bg-blue-500 text-white rounded-md font-semibold hover:bg-blue-700 transition-colors duration-300">
                Browse Our Products
            </a>
        </div>
    </section>

</div>
