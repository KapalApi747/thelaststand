<div class="max-w-3xl mx-auto p-6 bg-black rounded shadow">
    <h1 class="font-semibold mb-8">Shipping Options</h1>

    <div class="space-y-4">
        @foreach ($shippingOptions as $key => $option)
            <label class="flex items-center space-x-3">
                <input
                    type="radio"
                    wire:model.live="shippingMethod"
                    name="shippingMethod"
                    value="{{ $key }}"
                    class="form-radio"
                />
                <span>{{ $option['label'] }} ({{ number_format($option['cost'], 2) }} €)</span>
            </label>

            @if ($shippingMethod === $key && !empty($option['carriers']))
                <div class="ml-6 mt-1">
                    <label for="carrier" class="block text-sm font-medium text-white mb-3">Select Carrier:</label>
                    <select
                        id="carrier"
                        wire:model.live="carrier"
                        class="form-select border rounded text-white px-2 py-1"
                    >
                        <option value="" class="bg-gray-600">-- Choose a carrier --</option>
                        @foreach ($option['carriers'] as $carrier)
                            <option value="{{ $carrier }}" class="bg-gray-600">{{ $carrier }}</option>
                        @endforeach
                    </select>
                    @error('carrier') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
            @endif
        @endforeach
    </div>

    @error('shippingMethod')
    <div class="text-red-600 mt-2">{{ $message }}</div>
    @enderror

    <button wire:click="confirmShipping" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
        Continue to Payment
    </button>
</div>
