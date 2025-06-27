<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>404 - Not Found</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-900 text-gray-100 flex items-center justify-center min-h-screen">

<div class="text-center px-6">
    <h1 class="text-6xl font-bold text-indigo-500">404</h1>
    <p class="mt-4 text-xl">Oh no! The page you're looking for doesn't exist!</p>
    @if(isset($exception) && $exception->getMessage())
        <p class="mt-2 text-sm text-gray-400 italic">{{ $exception->getMessage() }}</p>
    @endif
    <a href="{{ route('shop.shop-products') }}" class="mt-6 inline-block bg-indigo-600 hover:bg-indigo-400 text-white py-2 px-4 rounded transition-colors duration-300">
        Back to Shop
    </a>
</div>

</body>
</html>
