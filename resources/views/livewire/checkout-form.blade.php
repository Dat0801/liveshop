<div>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold mb-8">Checkout</h1>

        @if (session()->has('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        <form wire:submit.prevent="placeOrder">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <h2 class="text-xl font-semibold mb-4">Customer Information</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                                <input type="text" wire:model.live.debounce.300ms="full_name" class="input">
                                @error('full_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                                <input type="email" wire:model.live.debounce.300ms="email" class="input">
                                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Phone *</label>
                                <input type="tel" wire:model.live.debounce.300ms="phone" class="input">
                                @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Shipping Address *</label>
                                <textarea wire:model.live.debounce.300ms="shipping_address" rows="3" class="input"></textarea>
                                @error('shipping_address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md p-6 sticky top-20">
                        <h2 class="text-xl font-semibold mb-4">Cart Summary</h2>

                        @if($items && $items->isNotEmpty())
                            <div class="space-y-3 mb-4 max-h-64 overflow-y-auto">
                                @foreach($items as $item)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">
                                            {{ $item->product->name }} × {{ $item->quantity }}
                                        </span>
                                        <span class="font-semibold">${{ number_format($item->subtotal, 2) }}</span>
                                    </div>
                                @endforeach
                            </div>

                            <div class="border-t pt-4 space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Subtotal</span>
                                    <span class="font-semibold">${{ number_format($subtotal, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Tax (10%)</span>
                                    <span class="font-semibold">${{ number_format($tax, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Shipping</span>
                                    <span class="font-semibold">
                                        @if($shipping == 0)
                                            <span class="text-green-600">FREE</span>
                                        @else
                                            ${{ number_format($shipping, 2) }}
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <div class="border-t mt-4 pt-4">
                                <div class="flex justify-between text-lg font-bold">
                                    <span>Total</span>
                                    <span class="text-primary-600">${{ number_format($total, 2) }}</span>
                                </div>
                            </div>

                            <button
                                type="submit"
                                class="btn btn-primary w-full mt-6"
                                wire:target="placeOrder"
                                wire:loading.attr="disabled"
                                wire:loading.class="opacity-50 cursor-not-allowed"
                            >
                                <span>Place Order</span>
                                <span wire:loading wire:target="placeOrder" x-transition>
                                    <svg class="inline-block w-5 h-5 animate-spin mr-2" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                    </svg>
                                    Processing...
                                </span>
                            </button>

                            <p class="text-xs text-gray-500 text-center mt-4">
                                By placing your order, you agree to our terms and conditions.
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
