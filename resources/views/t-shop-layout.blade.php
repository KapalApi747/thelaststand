<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Welcome To The Last Stand!</title>

    <!-- Free Font Awesome (solid, regular, brands) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <link rel="stylesheet" href="https://kit-pro.fontawesome.com/releases/v5.12.1/css/pro.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance

</head>
<body class="bg-white min-h-screen flex flex-col">

@include('components.shop.navbar')

<div class="container mx-auto">
    {{ $slot }}
</div>

<div class="flex-grow"></div>


<footer class="text-center text-gray-500 py-6 text-sm border-t border-gray-800 flex flex-col">
    <div class="mb-6 flex justify-center">
        <div class="mr-4">
            <a
                href="{{ route('page-show', ['slug' => 'privacy-policy']) }}"
                class="text-gray-500 hover:text-gray-700 transition-colors duration-300 ease-in-out"
            >
                Privacy Policy
            </a>
        </div>
        <div class="mr-4">
            <a
                href="{{ route('page-show', ['slug' => 'terms-of-service']) }}"
                class="text-gray-500 hover:text-gray-700 transition-colors duration-300 ease-in-out"
            >
                Terms Of Service
            </a>
        </div>
        <div>
            <a
                href="{{ route('page-show', ['slug' => 'cookies-policy']) }}"
                class="text-gray-500 hover:text-gray-700 transition-colors duration-300 ease-in-out"
            >
                Cookies Policy
            </a>
        </div>

    </div>
    <div>
        &copy; {{ date('Y') }} {{ (tenant()->store_name) }}. All rights reserved.
    </div>
</footer>

@fluxScripts
<script>
    window.addEventListener('updated_and_refresh', () => {
        setTimeout(() => {
            window.location.reload();
        }, 2000);
    });
</script>

{{-- Cookie Banner --}}
<x-cookies.cookie-banner />

</body>
</html>
