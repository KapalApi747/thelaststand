<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>THE LAST STAND</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-900 text-gray-100 font-sans leading-relaxed min-h-screen flex flex-col">

@include('components.central.navbar')

@include('components.central.hero')

@include('components.central.features')

@include('components.central.call-to-action')

<!-- Spacer to push footer down -->
<div class="flex-grow"></div>

<!-- Footer -->
<footer class="text-center text-gray-500 py-6 text-sm border-t border-gray-800">
    &copy; {{ date('Y') }} THE LAST STAND. All rights reserved.
</footer>

<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

{{-- Cookie Banner --}}
<x-cookies.cookie-banner />

</body>
</html>
