<div class="max-w-xl mx-auto p-6 bg-white shadow rounded mt-10 text-center">
    <h1 class="text-2xl font-bold mb-4">Livewire Test Page 1</h1>
    <p>Welcome, {{ $user->name }}!</p>

    <div class="mt-6 space-x-4">
        <a href="{{ route('tenant.test2') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Go to Page 2</a>
        <a href="{{ route('tenant.dashboard') }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">Back to Dashboard</a>
    </div>
</div>
