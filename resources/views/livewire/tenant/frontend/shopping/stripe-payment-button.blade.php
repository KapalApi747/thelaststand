<div class="mt-6">
    <button wire:click="checkoutStripe"
            wire:loading.attr="disabled"
            wire:target="checkoutStripe"
            class="cursor-pointer transition-colors duration-300 inline-flex items-center justify-center w-full
            sm:w-auto px-6 py-3 bg-gradient-to-r from-violet-200 to-pink-300 hover:from-violet-300 hover:to-pink-400
            text-black font-semibold text-sm rounded-xl shadow-md hover:shadow-lg focus:outline-none focus:ring-2
            focus:ring-pink-400 focus:ring-offset-2">

        <!-- Normal state -->
        <span wire:loading.remove wire:target="checkoutStripe" class="inline-flex items-center">
            Pay with
            <img src="https://cdn.worldvectorlogo.com/logos/stripe-4.svg" alt="Stripe logo" class="h-5 w-auto ml-2">
        </span>

        <!-- Loading state -->
        <span wire:loading wire:target="checkoutStripe" class="inline-flex items-center">
            <svg class="animate-spin h-5 w-5 mr-2 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>
            Processing...
        </span>
    </button>
</div>
