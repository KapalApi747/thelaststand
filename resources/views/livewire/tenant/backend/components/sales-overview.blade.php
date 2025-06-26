<!-- Sales Overview -->
<div class="card mt-6">

    <!-- header -->
    <div class="card-header flex flex-row justify-between">
        <h1 class="h6">Sales Overview</h1>

        {{--<div class="flex flex-row justify-center items-center">

            <a href="">
                <i class="fad fa-chevron-double-down mr-6"></i>
            </a>

            <a href="">
                <i class="fad fa-ellipsis-v"></i>
            </a>

        </div>--}}

    </div>
    <!-- end header -->

    <!-- body -->
    <div class="card-body grid grid-cols-2 gap-6 lg:grid-cols-1">

        <div class="p-8">
            <h1 class="h2">{{ number_format($salesThisMonth) }}</h1>
            <p class="text-black font-medium">Sales this month</p>

            <div class="mt-20 mb-2 flex items-center">
                <div
                    class="py-1 px-3 rounded mr-3"
                    @class([
                        'bg-green-200 text-green-900' => $salesGrowth > 0,
                        'bg-red-200 text-red-900' => $salesGrowth < 0,
                        'bg-gray-200 text-gray-600' => $salesGrowth === 0 || $salesGrowth === null,
                    ])
                >
                    <i
                        @class([
                            'fa fa-caret-up' => $salesGrowth > 0,
                            'fa fa-caret-down' => $salesGrowth < 0,
                            'fa fa-minus' => $salesGrowth === 0 || $salesGrowth === null,
                        ])
                    ></i>
                </div>
                <p class="text-black">
            <span
                @class([
                    'text-green-400' => $salesGrowth > 0,
                    'text-red-400' => $salesGrowth < 0,
                    'text-gray-400' => $salesGrowth === 0 || $salesGrowth === null,
                ])
            >
                {{ $salesGrowth !== null ? abs($salesGrowth) . '%' : 'N/A' }}
            </span>
                    <span
                @class([
                    'text-green-400' => $salesGrowth > 0,
                    'text-red-400' => $salesGrowth < 0,
                    'text-gray-400' => $salesGrowth === 0 || $salesGrowth === null,
                ])
            >
                {{ $salesGrowth > 0 ? 'more sales' : ($salesGrowth < 0 ? 'fewer sales' : 'sales') }}
            </span> in comparison to last month so far.
                </p>
            </div>

            <div class="flex items-center mb-2">
                <div
                    class="py-1 px-3 rounded mr-3"
                    @class([
                        'bg-green-200 text-green-900' => $avgRevenue > 0,
                        'bg-red-200 text-red-900' => $avgRevenue < 0,
                        'bg-gray-200 text-gray-600' => $avgRevenue === 0 || $avgRevenue === null,
                    ])
                >
                    <i
                        @class([
                            'fa fa-caret-up' => $avgRevenue > 0,
                            'fa fa-caret-down' => $avgRevenue < 0,
                            'fa fa-minus' => $avgRevenue === 0 || $avgRevenue === null,
                        ])
                    ></i>
                </div>
                <p class="text-black">
            <span
                @class([
                    'text-green-400' => $avgRevenue > 0,
                    'text-red-400' => $avgRevenue < 0,
                    'text-gray-400' => $avgRevenue === 0 || $avgRevenue === null,
                ])
            >
                {{ $avgRevenue !== null ? abs($avgRevenue) . '%' : 'N/A' }}
            </span>
                    <span
                @class([
                    'text-green-400' => $avgRevenue > 0,
                    'text-red-400' => $avgRevenue < 0,
                    'text-gray-400' => $avgRevenue === 0 || $avgRevenue === null,
                ])
            >
                {{ $avgRevenue > 0 ? 'more revenue' : ($avgRevenue < 0 ? 'less revenue' : 'revenue') }}
            </span> per sale in comparison to last month so far.
                </p>
            </div>

            <div class="mb-2 flex items-center">
                <div
                    class="py-1 px-3 rounded mr-3"
                    @class([
                        'bg-green-200 text-green-900' => $revenueGrowth > 0,
                        'bg-red-200 text-red-900' => $revenueGrowth < 0,
                        'bg-gray-200 text-gray-600' => $revenueGrowth === 0 || $revenueGrowth === null,
                    ])
                >
                    <i
                        @class([
                            'fa fa-caret-up' => $revenueGrowth > 0,
                            'fa fa-caret-down' => $revenueGrowth < 0,
                            'fa fa-minus' => $revenueGrowth === 0 || $revenueGrowth === null,
                        ])
                    ></i>
                </div>
                <p class="text-black">
            <span
                @class([
                    'text-green-400' => $revenueGrowth > 0,
                    'text-red-400' => $revenueGrowth < 0,
                    'text-gray-400' => $revenueGrowth === 0 || $revenueGrowth === null,
                ])
            >
                {{ $revenueGrowth !== null ? abs($revenueGrowth) . '%' : 'N/A' }}
            </span>
                    <span
                @class([
                    'text-green-400' => $revenueGrowth > 0,
                    'text-red-400' => $revenueGrowth < 0,
                    'text-gray-400' => $revenueGrowth === 0 || $revenueGrowth === null,
                ])
            >
                {{ $revenueGrowth > 0 ? 'more total revenue' : ($revenueGrowth < 0 ? 'less total revenue' : 'total revenue') }}
            </span> in comparison to last month so far.
                </p>
            </div>

            <a href="{{ route('tenant-dashboard.shop-statistics') }}" class="btn-shadow mt-6">view details</a>
        </div>



        <div class="">
            <div id="sealsOverview"></div>
        </div>

    </div>
    <!-- end body -->
    <script>
        window.salesChartLabels = @json($salesChartLabels);
        window.salesChartData = @json($salesChartData);
    </script>
</div>
<!-- end Sales Overview -->
