<!-- start quick Info -->
<div class="w-full my-6">

{{--<div class="grid grid-cols-3 gap-6 mt-6 xl:grid-cols-1">--}}

    {{--<!-- Browser Stats -->
    <div class="card">
        <div class="card-header">Browser Stats</div>

        <!-- brawser -->
        <div class="p-6 flex flex-row justify-between items-center text-gray-600 border-b">
            <div class="flex items-center">
                <i class="fab fa-chrome mr-4"></i>
                <h1>google chrome</h1>
            </div>
            <div>
                <span class="num-2"></span>%
            </div>
        </div>
        <!-- end brawser -->

        <!-- brawser -->
        <div class="p-6 flex flex-row justify-between items-center text-gray-600 border-b">
            <div class="flex items-center">
                <i class="fab fa-firefox mr-4"></i>
                <h1>firefox</h1>
            </div>
            <div>
                <span class="num-2"></span>%
            </div>
        </div>
        <!-- end brawser -->

        <!-- brawser -->
        <div class="p-6 flex flex-row justify-between items-center text-gray-600 border-b">
            <div class="flex items-center">
                <i class="fab fa-internet-explorer mr-4"></i>
                <h1>internet explorer</h1>
            </div>
            <div>
                <span class="num-2"></span>%
            </div>
        </div>
        <!-- end brawser -->

        <!-- brawser -->
        <div class="p-6 flex flex-row justify-between items-center text-gray-600 border-b-0">
            <div class="flex items-center">
                <i class="fab fa-safari mr-4"></i>
                <h1>safari</h1>
            </div>
            <div>
                <span class="num-2"></span>%
            </div>
        </div>
        <!-- end brawser -->

    </div>
    <!-- end Browser Stats -->--}}

    <!-- Start Recent Sales -->
    <div class="card col-span-2 xl:col-span-1">
        <div class="card-header">Most Popular Products</div>

        <table class="table-auto w-full text-left">
            <thead>
            <tr>
                <th class="px-4 py-2 border-r"></th>
                <th class="px-4 py-2 border-r">Product</th>
                <th class="px-4 py-2 border-r">Price</th>
                <th class="px-4 py-2">Total Sold</th>
            </tr>
            </thead>
            <tbody class="text-gray-600">
            @foreach($popularProducts as $product)
                <tr>
                    <td class="border border-l-0 px-4 py-2">
                        <div class="flex items-center justify-center">
                            <img src="{{ asset('tenant' . tenant()->id . '/' . $product->images[0]->path) }}" alt="{{ $product->name }}" class="w-12 h-12 object-cover rounded">
                        </div>
                    </td>
                    <td class="border border-l-0 px-4 py-2">
                        @role('admin')
                        <a
                            href="{{ route('tenant-dashboard.product-view', $product) }}"
                            class="text-teal-600 hover:text-teal-400 transition-colors duration-300 ease-in-out"
                        >
                            {{ $product->name }}
                        </a>
                        @else
                            {{ $product->name }}
                        @endrole
                    </td>
                    <td class="border border-l-0 px-4 py-2">${{ number_format($product->price, 2) }}</td>
                    <td class="border border-l-0 border-r-0 px-4 py-2">{{ $product->total_sold }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <!-- End Recent Sales -->


</div>
<!-- end quick Info -->
