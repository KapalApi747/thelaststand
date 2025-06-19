<div class="max-w-2xl mx-auto mt-6 p-6 bg-white rounded-2xl shadow-sm space-y-6">
    <h1 class="text-2xl font-bold text-gray-800">Shipping Options</h1>

    <div class="space-y-6">
        @foreach ($shippingOptions as $key => $option)
            <div class="border border-gray-200 rounded-xl p-4 hover:shadow-sm transition-shadow">
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input
                        type="radio"
                        wire:model.live="shippingMethod"
                        name="shippingMethod"
                        value="{{ $key }}"
                        class="form-radio text-blue-600 focus:ring-blue-500"
                    />
                    <span class="text-base font-medium text-gray-800">
                        {{ $option['label'] }}
                        <span class="text-sm text-gray-500">({{ number_format($option['cost'], 2) }} €)</span>
                    </span>
                </label>

                @if ($shippingMethod === $key && !empty($option['carriers']))
                    <div class="mt-4 pl-7">
                        <label for="carrier" class="block text-sm font-semibold text-gray-700 mb-2">Select Carrier:</label>
                        <select
                            id="carrier"
                            wire:model.live="carrier"
                            class="w-full border border-gray-300 rounded-md bg-white text-gray-900 text-sm px-3 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            <option value="">-- Choose a carrier --</option>
                            @foreach ($option['carriers'] as $carrier)
                                <option value="{{ $carrier }}">{{ $carrier }}</option>
                            @endforeach
                        </select>
                        @error('carrier')
                        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    @error('shippingMethod')
    <div class="text-red-600 text-sm">{{ $message }}</div>
    @enderror

    <div class="pt-4">
        <button
            wire:click="confirmShipping"
            class="w-full bg-blue-600 hover:bg-blue-700 transition-colors text-white font-semibold py-3 px-6 rounded-xl cursor-pointer"
        >
            Continue to Payment
        </button>
    </div>
</div>
