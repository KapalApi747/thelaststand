<!-- start numbers -->
<div class="grid grid-cols-5 gap-6 xl:grid-cols-2">

    <!-- card -->
    <div class="card mt-6">
        <div class="card-body flex items-center">

            <div class="px-3 py-2 rounded bg-indigo-600 text-white mr-3">
                <i class="fad fa-wallet"></i>
            </div>

            <div class="flex flex-col">
                <h1 class="font-semibold"><span>{{ $totalSales }}</span> Sales</h1>
                <p class="text-xs"><span>{{ $totalPayments }}</span> payments</p>
            </div>

        </div>
    </div>
    <!-- end card -->

    <!-- card -->
    <div class="card mt-6">
        <div class="card-body flex items-center">

            <div class="px-3 py-2 rounded bg-green-600 text-white mr-3">
                <i class="fad fa-shopping-cart"></i>
            </div>

            <div class="flex flex-col">
                <h1 class="font-semibold"><span>{{ $totalOrders }}</span> Orders</h1>
                <p class="text-xs"><span>{{ $totalItems }}</span> items</p>
            </div>

        </div>
    </div>
    <!-- end card -->

    <!-- card -->
    <div class="card mt-6 xl:mt-1">
        <div class="card-body flex items-center">

            <div class="px-3 py-2 rounded bg-yellow-600 text-white mr-3">
                <i class="fad fa-blog"></i>
            </div>

            <div class="flex flex-col">
                <h1 class="font-semibold"><span>0</span> posts</h1>
                <p class="text-xs"><span>0</span> active</p>
            </div>

        </div>
    </div>
    <!-- end card -->

    <!-- card -->
    <div class="card mt-6 xl:mt-1">
        <div class="card-body flex items-center">

            <div class="px-3 py-2 rounded bg-red-600 text-white mr-3">
                <i class="fad fa-comments"></i>
            </div>

            <div class="flex flex-col">
                <h1 class="font-semibold"><span>{{ $totalReviews }}</span> reviews</h1>
                <p class="text-xs"><span>{{ $totalComments }}</span> comments</p>
            </div>

        </div>
    </div>
    <!-- end card -->

    <!-- card -->
    <div class="card mt-6 xl:mt-1 xl:col-span-2">
        <div class="card-body flex items-center">

            <div class="px-3 py-2 rounded bg-pink-600 text-white mr-3">
                <i class="fad fa-user"></i>
            </div>

            <div class="flex flex-col">
                <h1 class="font-semibold"><span>{{ $totalCustomers }}</span> members</h1>
                <p class="text-xs"><span>{{ $totalInactiveCustomers }}</span> inactive</p>
            </div>

        </div>
    </div>
    <!-- end card -->

</div>
<!-- end numbers -->
