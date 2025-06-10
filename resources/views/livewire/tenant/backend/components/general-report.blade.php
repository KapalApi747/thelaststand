<!-- General Report -->
<div class="grid grid-cols-4 gap-6 xl:grid-cols-1">


    <!-- card -->
    <div class="report-card">
        <div class="card">
            <div class="card-body flex flex-col">

                <!-- top -->
                <div class="flex flex-row justify-between items-center">
                    <div class="h6 text-indigo-700 fad fa-shopping-cart"></div>
                    <span class="rounded-full text-white badge {{ $itemsSoldChevron === 'up' ? 'bg-teal-400' : 'bg-red-400' }} text-xs">
                        {{ abs($itemsSoldChange) }}%
                        <i class="fal fa-chevron-{{ $itemsSoldChevron }} ml-1"></i>
                    </span>
                </div>
                <!-- end top -->

                <!-- bottom -->
                <div class="mt-8">
                    <h1 class="h5">
                        {{ $itemsSold }}
                    </h1>
                    <p>Total item sales this past month</p>
                </div>
                <!-- end bottom -->

            </div>
        </div>
        <div class="footer bg-white p-1 mx-4 border border-t-0 rounded rounded-t-none"></div>
    </div>
    <!-- end card -->

    <!-- card -->
    <div class="report-card">
        <div class="card">
            <div class="card-body flex flex-col">

                <!-- top -->
                <div class="flex flex-row justify-between items-center">
                    <div class="h6 text-red-700 fad fa-store"></div>
                    <span class="rounded-full text-white badge {{ $newOrdersChevron === 'up' ? 'bg-teal-400' : 'bg-red-400' }} text-xs">
                        {{ abs($newOrdersChange) }}%
                        <i class="fal fa-chevron-{{ $newOrdersChevron }} ml-1"></i>
                    </span>
                </div>
                <!-- end top -->

                <!-- bottom -->
                <div class="mt-8">
                    <h1 class="h5">
                        {{ $newOrders }}
                    </h1>
                    <p>New orders this past month</p>
                </div>
                <!-- end bottom -->

            </div>
        </div>
        <div class="footer bg-white p-1 mx-4 border border-t-0 rounded rounded-t-none"></div>
    </div>
    <!-- end card -->


    <!-- card -->
    <div class="report-card">
        <div class="card">
            <div class="card-body flex flex-col">

                <!-- top -->
                <div class="flex flex-row justify-between items-center">
                    <div class="h6 text-yellow-600 fad fa-sitemap"></div>
                    <span class="rounded-full text-white badge {{ $totalProductsChevron === 'up' ? 'bg-teal-400' : 'bg-red-400' }} text-xs">
                        {{ abs($totalProductsChange) }}%
                        <i class="fal fa-chevron-{{ $totalProductsChevron }} ml-1"></i>
                    </span>
                </div>
                <!-- end top -->

                <!-- bottom -->
                <div class="mt-8">
                    <h1 class="h5">
                        {{ $totalProducts }}
                    </h1>
                    <p>Total products</p>
                </div>
                <!-- end bottom -->

            </div>
        </div>
        <div class="footer bg-white p-1 mx-4 border border-t-0 rounded rounded-t-none"></div>
    </div>
    <!-- end card -->


    <!-- card -->
    <div class="report-card">
        <div class="card">
            <div class="card-body flex flex-col">

                <!-- top -->
                <div class="flex flex-row justify-between items-center">
                    <div class="h6 text-green-700 fad fa-users"></div>
                    <span class="rounded-full text-white badge {{ $newCustomersChevron === 'up' ? 'bg-teal-400' : 'bg-red-400' }} text-xs">
                        {{ abs($newCustomersChange) }}%
                        <i class="fal fa-chevron-{{ $newCustomersChevron }} ml-1"></i>
                    </span>
                </div>
                <!-- end top -->

                <!-- bottom -->
                <div class="mt-8">
                    <h1 class="h5">
                        {{ $newCustomers }}
                    </h1>
                    <p>New customers this month</p>
                </div>
                <!-- end bottom -->

            </div>
        </div>
        <div class="footer bg-white p-1 mx-4 border border-t-0 rounded rounded-t-none"></div>
    </div>
    <!-- end card -->


</div>
<!-- End General Report -->
