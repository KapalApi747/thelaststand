{{-- resources/views/components/cookie-banner.blade.php --}}
<div
    x-data="{ visible: !localStorage.getItem('cookieAccepted') }"
    x-show="visible"
    x-cloak
    class="fixed bottom-4 left-4 right-4 max-w-xl mx-auto bg-gray-100 text-gray-700 p-4 rounded-lg shadow-lg z-50"
>
    <div class="flex items-start gap-3">
        <div class="flex-1 text-sm">
            <p class="font-semibold mb-1">We use cookies</p>
            <p class="text-gray-800">
                We use essential cookies to make our site work. You cannot opt out of these.
            </p>
            <label class="mt-3 inline-flex items-center space-x-2">
                <input type="checkbox" checked disabled class="form-checkbox text-red-600" />
                <span class="text-sm">Essential cookies (required)</span>
            </label>
        </div>
        <button
            @click="localStorage.setItem('cookieAccepted', true); visible = false"
            class="ml-4 mt-2 sm:mt-0 bg-blue-200 hover:bg-blue-300 text-black px-4 py-2 rounded cursor-pointer transition-colors duration-300 ease-in-out"
        >
            Accept
        </button>
    </div>
</div>
