<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('tenant' . tenant()->id . '/assets/img/store_logo.png') }}" type="image/x-icon">

    <!-- Free Font Awesome (solid, regular, brands) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <link rel="stylesheet" href="https://kit-pro.fontawesome.com/releases/v5.12.1/css/pro.min.css">
    {{--<link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">--}}
    @vite(['resources/css/style.css', 'resources/js/scripts.js'])
    <title>Welcome To The Last Stand!</title>
</head>
<body class="bg-gray-100">

<livewire:tenant.backend.navbar />

<!-- strat wrapper -->
<div class="h-screen flex flex-row flex-wrap">

    <livewire:tenant.backend.sidebar />

    <!-- strat content -->
    <div class="flex-1 overflow-y-auto bg-gray-100">
        <div class="px-4 sm:px-6 lg:px-8 py-6">
            {{ $slot }}
        </div>
    </div>
    <!-- end content -->

</div>
<!-- end wrapper -->

<!-- script -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
{{--<script src="{{ asset('js/scripts.js') }}"></script>--}}
<script>
    window.addEventListener('updated_and_refresh', () => {
        setTimeout(() => {
            window.location.reload();
        }, 2000);
    });
</script>
<!-- end script -->

</body>
</html>

