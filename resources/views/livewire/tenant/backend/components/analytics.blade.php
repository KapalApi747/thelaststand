<!-- strat Analytics -->
<div class="mt-6 grid grid-cols-2 gap-6 xl:grid-cols-1">

    <!-- update section -->
    <div class="card bg-teal-400 border-teal-400 shadow-md text-white">
        <div class="card-body flex flex-row">

            <!-- image -->
            <div class="w-40 h-40 overflow-hidden rounded-full">
                @php
                    $picturePath = 'tenant' . tenant()->id . '/' . auth()->user()->profile_picture_path;
                @endphp

                <img
                    src="{{ auth()->user()->profile_picture_path && file_exists(public_path('tenancy/assets/' . $picturePath)) ? asset($picturePath) : 'https://placehold.co/160x160' }}"
                    class="w-full h-full object-cover"
                    alt="User Picture">
            </div>
            <!-- end image -->

            <!-- info -->
            <div class="py-2 ml-10">
                <h1 class="h6">Good Job, {{ auth()->user()->name }}!</h1>
                <p class="text-white text-xs">You've finished all of your tasks for this week.</p>

                <ul class="mt-4">
                    <li class="text-sm font-light"><i class="fad fa-check-double mr-2 mb-2"></i> Finish Dashboard Design</li>
                    <li class="text-sm font-light"><i class="fad fa-check-double mr-2 mb-2"></i> Fix Issue #74</li>
                    <li class="text-sm font-light"><i class="fad fa-check-double mr-2"></i> Publish version 1.0.6</li>
                </ul>
            </div>
            <!-- end info -->

        </div>
    </div>
    <!-- end update section -->

    <!-- carts -->
    <div class="flex flex-col">

        <!-- alert -->
        <div class="alert alert-dark mb-6">
            Welcome, {{ auth()->user()->name }}!
        </div>
        <!-- end alert -->

        <!-- charts -->
        <div class="grid grid-cols-2 gap-6 h-full">

            <div class="card">
                <div class="py-3 px-4 flex flex-row justify-between">
                    <h1 class="h6">
                        <span>{{ $totalOrders }}</span>
                        <p>Total Orders</p>
                    </h1>

                    <div class="bg-teal-200 text-teal-700 border-teal-300 border w-10 h-10 rounded-full flex justify-center items-center">
                        <i class="fad fa-eye"></i>
                    </div>
                </div>
                <div class="analytics_1"></div>
            </div>

            <div class="card">
                <div class="py-3 px-4 flex flex-row justify-between">
                    <h1 class="h6">
                        <span>{{ $totalCustomers }}</span>
                        <p>Unique Customers</p>
                    </h1>

                    <div class="bg-indigo-200 text-indigo-700 border-indigo-300 border w-10 h-10 rounded-full flex justify-center items-center">
                        <i class="fad fa-users-crown"></i>
                    </div>
                </div>
                <div class="analytics_1"></div>
            </div>

        </div>
        <!-- charts    -->

    </div>
    <!-- end charts -->

    <script>
        window.analyticsChartData = @json($orderChartData);
        window.analyticsCustomerChartData = @json($customerChartData);
    </script>
</div>
<!-- end Analytics -->
