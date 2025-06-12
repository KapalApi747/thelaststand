<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Welcome To The Last Stand!</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance

</head>
<body class="bg-gray-800">
<div class="container mx-auto">
    @include('components.shop.navbar')
    {{ $slot }}
</div>

@fluxScripts
</body>
</html>
