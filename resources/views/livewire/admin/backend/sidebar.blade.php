<!-- start sidebar -->
<div id="sideBar" class="relative flex flex-col flex-wrap bg-white border-r border-gray-300 p-6 flex-none w-64 md:-ml-64 md:fixed md:top-0 md:z-30 md:h-screen md:shadow-xl animated faster">


    <!-- sidebar content -->
    <div class="flex flex-col">

        <!-- sidebar toggle -->
        <div class="text-right hidden md:block mb-4">
            <button id="sideBarHideBtn">
                <i class="fad fa-times-circle"></i>
            </button>
        </div>
        <!-- end sidebar toggle -->

        <p class="uppercase text-xs text-gray-600 mb-4 tracking-wider">Homes</p>

        <!-- link -->
        <a
            href="{{ route('dashboard.tenant-profiles') }}"
            class="mb-3 font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500 flex justify-between items-center">
            <i class="fad fa-chart-pie text-xs"></i>
            <div>Dashboard</div>
        </a>
        <!-- end link -->

        <!-- link -->
        <a
            href="{{ route('dashboard.payouts') }}"
            class="mb-3 font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500 flex justify-between items-center">
            <i class="fad fa-chart-pie text-xs"></i>
            <div>Payouts</div>
        </a>
        <!-- end link -->

    </div>
    <!-- end sidebar content -->

</div>
<!-- end sidbar -->
