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
<body class="bg-gradient-to-br from-blue-50 via-white to-blue-100 min-h-screen flex flex-col">

@include('components.shop.navbar')

@if(session('message'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative text-center" role="alert">
        <span class="block sm:inline">{{ session('message') }}</span>
    </div>
@endif

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

{{-- Cookie Banner --}}
<x-cookies.cookie-banner />

</body>
</html>
