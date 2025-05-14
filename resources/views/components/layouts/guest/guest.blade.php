<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tenant Login - THE LAST STAND</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles {{-- Livewire 3 style scripts --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-100 min-h-screen flex flex-col justify-center items-center">

<main class="w-full max-w-md p-8 bg-white rounded-lg shadow-lg">
    {{ $slot }}
</main>

@livewireScripts {{-- Livewire 3 runtime scripts --}}
</body>
</html>
