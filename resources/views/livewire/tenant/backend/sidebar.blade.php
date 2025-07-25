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
            href="{{ route('tenant-dashboard.index') }}"
            class="mb-3 font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500 flex justify-between items-center">
            <i class="fad fa-chart-pie text-xs"></i>
            <div>Dashboard</div>
        </a>
        <!-- end link -->

        <!-- link -->
        @role('admin')
        <div x-data="{ open: false }">
            <button @click="open = !open" type="button"
                    class="w-full mb-3 font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500 flex justify-between items-center">
                <i class="fas fa-users text-xs"></i>
                <div class="flex items-center">
                    Users
                    <svg class="ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </button>

            <!-- Dropdown panel -->
            <div x-show="open" @click.outside="open = false"
                 class="absolute z-10 w-full origin-bottom-right bg-white border border-gray-200 divide-y divide-gray-100 rounded-md shadow-lg focus:outline-none">
                <div class="py-1">

                    <a href="{{ route('tenant-dashboard.user-index') }}"
                       class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-teal-600 transition ease-in-out duration-500 flex justify-between items-center">
                        <i class="fas fa-person-walking"></i>
                        <div>
                            All Users
                        </div>
                    </a>

                    <a href="{{ route('tenant-dashboard.user-register') }}"
                       class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-teal-600 transition ease-in-out duration-500 flex justify-between items-center">
                        <i class="fas fa-user-plus"></i>
                        <div>
                            Register New User
                        </div>
                    </a>

                </div>
            </div>
        </div>
        @endrole
        <!-- end link -->

        <!-- link -->
        @role('admin')
        <div x-data="{ open: false }">
            <button @click="open = !open" type="button"
                    class="w-full mb-3 font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500 flex justify-between items-center">
                <i class="fas fa-user text-xs"></i>
                <div class="flex items-center">
                    Customers
                    <svg class="ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </button>

            <!-- Dropdown panel -->
            <div x-show="open" @click.outside="open = false"
                 class="absolute z-10 w-full origin-bottom-right bg-white border border-gray-200 divide-y divide-gray-100 rounded-md shadow-lg focus:outline-none">
                <div class="py-1">

                    <a href="{{ route('tenant-dashboard.customer-index') }}"
                       class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-teal-600 transition ease-in-out duration-500 flex justify-between items-center">
                        <i class="fas fa-person-walking"></i>
                        <div>
                            All Customers
                        </div>
                    </a>

                    <a href="{{ route('tenant-dashboard.customer-creation') }}"
                       class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-teal-600 transition ease-in-out duration-500 flex justify-between items-center">
                        <i class="fas fa-user-plus"></i>
                        <div>
                            Create New Customer
                        </div>
                    </a>

                </div>
            </div>
        </div>
        @endrole
        <!-- end link -->

        <!-- link -->
        @role('admin')
        <div x-data="{ open: false }">
            <button @click="open = !open" type="button"
                    class="w-full mb-3 font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500 flex justify-between items-center">
                <i class="fas fa-user-tie text-xs"></i>
                <div class="flex items-center">
                    Roles
                    <svg class="ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </button>

            <!-- Dropdown panel -->
            <div x-show="open" @click.outside="open = false"
                 class="absolute z-10 w-full origin-bottom-right bg-white border border-gray-200 divide-y divide-gray-100 rounded-md shadow-lg focus:outline-none">
                <div class="py-1">

                    <a href="{{ route('tenant-dashboard.role-index') }}"
                       class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-teal-600 transition ease-in-out duration-500 flex justify-between items-center">
                        <i class="fas fa-person-walking"></i>
                        <div>
                            All Roles
                        </div>
                    </a>

                    <a href="{{ route('tenant-dashboard.role-creation') }}"
                       class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-teal-600 transition ease-in-out duration-500 flex justify-between items-center">
                        <i class="fas fa-user-plus"></i>
                        <div>
                            Create New Role
                        </div>
                    </a>

                </div>
            </div>
        </div>
        @endrole
        <!-- end link -->

        <!-- link -->
        @can('manage products')
            <div x-data="{ open: false }">
                <button @click="open = !open" type="button"
                        class="w-full mb-3 font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500 flex justify-between items-center">
                    <i class="fa-solid fa-box text-xs"></i>
                    <div class="flex items-center">
                        Products
                        <svg class="ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </button>

                <!-- Dropdown panel -->
                <div x-show="open" @click.outside="open = false"
                     class="absolute z-10 w-full origin-bottom-right bg-white border border-gray-200 divide-y divide-gray-100 rounded-md shadow-lg focus:outline-none">
                    <div class="py-1">

                        <a href="{{ route('tenant-dashboard.product-management') }}"
                           class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-teal-600 transition ease-in-out duration-500 flex justify-between items-center">
                            <i class="fa-solid fa-boxes"></i>
                            <div>
                                All Products
                            </div>
                        </a>

                        <a href="{{ route('tenant-dashboard.product-creation') }}"
                           class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-teal-600 transition ease-in-out duration-500 flex justify-between items-center">
                            <i class="fa-solid fa-box"></i>
                            <div>
                                Create New Product
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        <!-- end link -->

        <!-- link -->
            <div x-data="{ open: false }">
                <button @click="open = !open" type="button"
                        class="w-full mb-3 font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500 flex justify-between items-center">
                    <i class="fa-solid fa-list text-xs"></i>
                    <div class="flex items-center">
                        Categories
                        <svg class="ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </button>

                <!-- Dropdown panel -->
                <div x-show="open" @click.outside="open = false"
                     class="absolute z-10 w-full origin-bottom-right bg-white border border-gray-200 divide-y divide-gray-100 rounded-md shadow-lg focus:outline-none">
                    <div class="py-1">

                        <a href="{{ route('tenant-dashboard.category-management') }}"
                           class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-teal-600 transition ease-in-out duration-500 flex justify-between items-center">
                            <i class="fas fa-person-walking"></i>
                            <div>
                                All Categories
                            </div>
                        </a>

                    </div>
                </div>
            </div>
        @endcan
        <!-- end link -->

        <!-- link -->
        @can('manage orders')
        <div x-data="{ open: false }">
            <button @click="open = !open" type="button"
                    class="w-full mb-3 font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500 flex justify-between items-center">
                <i class="fas fa-receipt text-xs"></i>
                <div class="flex items-center">
                    Orders
                    <svg class="ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </button>

            <!-- Dropdown panel -->
            <div x-show="open" @click.outside="open = false"
                 class="absolute z-10 w-full origin-bottom-right bg-white border border-gray-200 divide-y divide-gray-100 rounded-md shadow-lg focus:outline-none">
                <div class="py-1">

                    <a href="{{ route('tenant-dashboard.order-index') }}"
                       class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-teal-600 transition ease-in-out duration-500 flex justify-between items-center">
                        <i class="fas fa-clipboard-list text-xs"></i>
                        <div>
                            All Orders
                        </div>
                    </a>

                </div>
            </div>
        </div>
        @endcan
        <!-- end link -->

        <!-- link -->
        @role('admin')
        <a
            href="{{ route('tenant-dashboard.tenant-payouts') }}"
            class="mb-3 font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500 flex justify-between items-center">
            <i class="fas fa-hand-holding-usd text-xs"></i>
            <div>Payouts</div>
        </a>
        @endrole
        <!-- end link -->

        <!-- link -->
        @role('admin')
        <div x-data="{ open: false }">
            <button @click="open = !open" type="button"
                    class="w-full mb-3 font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500 flex justify-between items-center">
                <i class="fas fa-file text-xs"></i>
                <div class="flex items-center">
                    Pages
                    <svg class="ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </button>

            <!-- Dropdown panel -->
            <div x-show="open" @click.outside="open = false"
                 class="absolute z-10 w-full origin-bottom-right bg-white border border-gray-200 divide-y divide-gray-100 rounded-md shadow-lg focus:outline-none">
                <div class="py-1">

                    <a href="{{ route('tenant-dashboard.page-index') }}"
                       class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-teal-600 transition ease-in-out duration-500 flex justify-between items-center">
                        <i class="fas fa-file text-xs"></i>
                        <div>
                            All Pages
                        </div>
                    </a>

                    {{--<a
                        href="{{ route('tenant-dashboard.shipping-method-form') }}"
                        class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-teal-600 transition ease-in-out duration-500 flex justify-between items-center">
                        <i class="fas fa-file text-xs"></i>
                        <div>
                            Create New Page
                        </div>
                    </a>--}}

                </div>
            </div>
        </div>
        @endrole
        <!-- end link -->

        <!-- link -->
        @role('admin')
        <div x-data="{ open: false }">
            <button @click="open = !open" type="button"
                    class="w-full mb-3 font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500 flex justify-between items-center">
                <i class="fas fa-shipping-fast text-xs"></i>
                <div class="flex items-center">
                    Shipping Methods
                    <svg class="ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </button>

            <!-- Dropdown panel -->
            <div x-show="open" @click.outside="open = false"
                 class="absolute z-10 w-full origin-bottom-right bg-white border border-gray-200 divide-y divide-gray-100 rounded-md shadow-lg focus:outline-none">
                <div class="py-1">

                    <a href="{{ route('tenant-dashboard.shipping-method-index') }}"
                       class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-teal-600 transition ease-in-out duration-500 flex justify-between items-center">
                        <i class="fas fa-shipping-fast text-xs"></i>
                        <div>
                            All Methods
                        </div>
                    </a>

                    <a
                        href="{{ route('tenant-dashboard.shipping-method-form') }}"
                        class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-teal-600 transition ease-in-out duration-500 flex justify-between items-center">
                        <i class="fas fa-shipping-fast text-xs"></i>
                        <div>
                            Create New
                        </div>
                    </a>

                </div>
            </div>
        </div>
        @endrole
        <!-- end link -->

        <!-- link -->
        @role(['admin', 'analyst'])
        <a
            href="{{ route('tenant-dashboard.shop-statistics') }}"
            class="mb-3 font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500 flex justify-between items-center">
            <i class="fad fa-chart-line text-xs"></i>
            <div>Shop Statistics</div>
        </a>
        @endrole
        <!-- end link -->

        <!-- link -->
        @role('admin')
        <a
            href="{{ route('tenant-dashboard.store-settings') }}"
            class="mb-3 font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500 flex justify-between items-center">
            <i class="fad fa-shopping-cart text-xs"></i>
            <div>Store Settings</div>
        </a>
        @endrole
        <!-- end link -->

        {{--<p class="uppercase text-xs text-gray-600 mb-4 mt-4 tracking-wider">apps</p>

        <!-- link -->
        <a href="./email.html" class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
            <i class="fad fa-envelope-open-text text-xs mr-2"></i>
            email
        </a>
        <!-- end link -->

        <!-- link -->
        <a href="#" class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
            <i class="fad fa-comments text-xs mr-2"></i>
            chat
        </a>
        <!-- end link -->

        <!-- link -->
        <a href="#" class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
            <i class="fad fa-shield-check text-xs mr-2"></i>
            todo
        </a>
        <!-- end link -->

        <!-- link -->
        <a href="#" class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
            <i class="fad fa-calendar-edit text-xs mr-2"></i>
            calendar
        </a>
        <!-- end link -->

        <!-- link -->
        <a href="#" class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
            <i class="fad fa-file-invoice-dollar text-xs mr-2"></i>
            invoice
        </a>
        <!-- end link -->

        <!-- link -->
        <a href="#" class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
            <i class="fad fa-folder-open text-xs mr-2"></i>
            file manager
        </a>
        <!-- end link -->--}}


        {{--<p class="uppercase text-xs text-gray-600 mb-4 mt-4 tracking-wider">UI Elements</p>

        <!-- link -->
        <a href="./typography.html" class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
            <i class="fad fa-text text-xs mr-2"></i>
            typography
        </a>
        <!-- end link -->

        <!-- link -->
        <a href="./alert.html" class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
            <i class="fad fa-whistle text-xs mr-2"></i>
            alerts
        </a>
        <!-- end link -->


        <!-- link -->
        <a href="./buttons.html" class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
            <i class="fad fa-cricket text-xs mr-2"></i>
            buttons
        </a>
        <!-- end link -->

        <!-- link -->
        <a href="#" class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
            <i class="fad fa-box-open text-xs mr-2"></i>
            Content
        </a>
        <!-- end link -->

        <!-- link -->
        <a href="#" class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
            <i class="fad fa-swatchbook text-xs mr-2"></i>
            colors
        </a>
        <!-- end link -->

        <!-- link -->
        <a href="#" class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
            <i class="fad fa-atom-alt text-xs mr-2"></i>
            icons
        </a>
        <!-- end link -->

        <!-- link -->
        <a href="#" class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
            <i class="fad fa-club text-xs mr-2"></i>
            card
        </a>
        <!-- end link -->

        <!-- link -->
        <a href="#" class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
            <i class="fad fa-cheese-swiss text-xs mr-2"></i>
            Widgets
        </a>
        <!-- end link -->

        <!-- link -->
        <a href="#" class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
            <i class="fad fa-computer-classic text-xs mr-2"></i>
            Components
        </a>
        <!-- end link -->--}}



    </div>
    <!-- end sidebar content -->

</div>
<!-- end sidbar -->
