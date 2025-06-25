<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Access Denied</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-900 text-gray-100 font-sans leading-relaxed h-screen flex items-center justify-center">
<div class="max-w-5xl mx-auto bg-gray-900 p-10 rounded-xl shadow-lg border border-gray-800 text-center">
    <h1 class="text-6xl font-bold text-white">403</h1>
    <p class="text-xl mt-4 font-semibold">Error: Access Denied</p>
    <p class="mt-2 text-gray-400">Access to this page is restricted.</p>

    <a href="{{ route('home') }}" class="mt-6 inline-block px-4 py-2 bg-indigo-500 text-white rounded hover:bg-indigo-700 transition-colors duration-300">
        Go Home
    </a>
</div>
</body>
</html>
