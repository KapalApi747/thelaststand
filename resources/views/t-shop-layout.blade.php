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
<body class="bg-white">
<div class="mx-auto">
    @include('components.shop.navbar')
    {{ $slot }}
</div>

@fluxScripts
</body>
</html>
