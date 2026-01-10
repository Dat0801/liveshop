<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold mb-8">Checkout</h1>

        @if (session()->has('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        <form wire:submit.prevent="placeOrder">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Checkout Form -->
                <div class="lg:col-span-2">
                    <!-- Billing Information -->
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <h2 class="text-xl font-semibold mb-4">Billing Information</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                                <input type="text" wire:model.blur="billing_name" class="input">
                                @error('billing_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                                <input type="email" wire:model.blur="billing_email" class="input">
                                @error('billing_email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Phone *</label>
                                <input type="tel" wire:model.blur="billing_phone" class="input">
                                @error('billing_phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Address *</label>
                                <textarea wire:model.blur="billing_address" rows="2" class="input"></textarea>
                                @error('billing_address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">City *</label>
                                <input type="text" wire:model.blur="billing_city" class="input">
                                @error('billing_city') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">State *</label>
                                <input type="text" wire:model.blur="billing_state" class="input">
                                @error('billing_state') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">ZIP Code *</label>
                                <input type="text" wire:model.blur="billing_zip" class="input">
                                @error('billing_zip') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Country *</label>
                                <input type="text" wire:model.blur="billing_country" class="input">
                                @error('billing_country') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Information -->
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xl font-semibold">Shipping Information</h2>
                            <label class="flex items-center">
                                <input type="checkbox" wire:model.live="same_as_billing" class="mr-2">
                                <span class="text-sm">Same as billing</span>
                            </label>
                        </div>

                        @if(!$same_as_billing)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                                    <input type="text" wire:model.blur="shipping_name" class="input">
                                    @error('shipping_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone *</label>
                                    <input type="tel" wire:model.blur="shipping_phone" class="input">
                                    @error('shipping_phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Address *</label>
                                    <textarea wire:model.blur="shipping_address" rows="2" class="input"></textarea>
                                    @error('shipping_address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">City *</label>
                                    <input type="text" wire:model.blur="shipping_city" class="input">
                                    @error('shipping_city') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">State *</label>
                                    <input type="text" wire:model.blur="shipping_state" class="input">
                                    @error('shipping_state') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">ZIP Code *</label>
                                    <input type="text" wire:model.blur="shipping_zip" class="input">
                                    @error('shipping_zip') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Country *</label>
                                    <input type="text" wire:model.blur="shipping_country" class="input">
                                    @error('shipping_country') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Order Notes -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold mb-4">Order Notes (Optional)</h2>
                        <textarea wire:model="notes" rows="4" placeholder="Any special instructions for your order..." class="input"></textarea>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md p-6 sticky top-20">
                        <h2 class="text-xl font-semibold mb-4">Order Summary</h2>

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
                                @if($subtotal < 100 && $shipping > 0)
                                    <p class="text-xs text-gray-500">Add ${{ number_format(100 - $subtotal, 2) }} more for free shipping!</p>
                                @endif
                            </div>

                            <div class="border-t mt-4 pt-4">
                                <div class="flex justify-between text-lg font-bold">
                                    <span>Total</span>
                                    <span class="text-primary-600">${{ number_format($total, 2) }}</span>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-full mt-6">
                                Place Order
                            </button>

                            <p class="text-xs text-gray-500 text-center mt-4">
                                By placing your order, you agree to our terms and conditions
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
