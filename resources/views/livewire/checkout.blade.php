<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Secure Checkout</h1>
            <p class="text-orange-600 text-sm mt-1">Fast, secure, and encrypted payment processing.</p>
        </div>

        @if (session()->has('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        <form wire:submit.prevent="placeOrder">
            <!-- Progress Bar (Full Width) -->
            <div class="mb-6">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-lg font-semibold text-gray-900">Step 2: Payment Details</h3>
                        <span class="text-sm text-gray-600">2 of 3</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-gradient-to-r from-orange-400 to-orange-500 h-2 rounded-full" style="width: 66.67%"></div>
                    </div>
                    <div class="mt-3">
                        <span class="text-xs text-orange-600 font-medium uppercase tracking-wide">Up Next: Order Review</span>
                    </div>
                </div>
            </div>

            <!-- Main Content (3 columns: Address, Delivery, Summary) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- 1. Shipping Address -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">1. Shipping Address</h2>
                    
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                            <input type="text" wire:model.blur="billing_name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent bg-gray-50 text-sm">
                            @error('billing_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                            <input type="text" wire:model.blur="billing_email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent bg-gray-50 text-sm">
                            @error('billing_email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Street Address</label>
                            <input type="text" wire:model.blur="billing_address" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent bg-gray-50 text-sm">
                            @error('billing_address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                            <input type="text" wire:model.blur="billing_city" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent bg-gray-50 text-sm">
                            @error('billing_city') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">State</label>
                                <input type="text" wire:model.blur="billing_state" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent bg-gray-50 text-sm">
                                @error('billing_state') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Zip Code</label>
                                <input type="text" wire:model.blur="billing_zip" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent bg-gray-50 text-sm">
                                @error('billing_zip') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Delivery Method -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">2. Delivery Method</h2>
                    
                    @if($available_shipping_methods && $available_shipping_methods->count() > 0)
                        <div class="space-y-2.5">
                            @foreach($available_shipping_methods as $method)
                                <label class="relative flex items-start p-3 border-2 rounded-lg cursor-pointer transition-all {{ $selected_shipping_method_id == $method->id ? 'border-orange-500 bg-orange-50' : 'border-gray-200 hover:border-gray-300' }}">
                                    <input type="radio" 
                                           wire:model.live="selected_shipping_method_id" 
                                           value="{{ $method->id }}" 
                                           class="absolute opacity-0">
                                    <div class="flex-1">
                                        <div class="font-semibold text-gray-900 text-sm">{{ $method->name }}</div>
                                        @if($method->processing_days_min && $method->processing_days_max)
                                            <div class="text-xs text-gray-500 mt-0.5">
                                                {{ $method->processing_days_min }}-{{ $method->processing_days_max }} days
                                            </div>
                                        @endif
                                    </div>
                                    <div class="text-right ml-2 flex-shrink-0">
                                        @php
                                            $rate = $method->calculateRate($subtotal, 0);
                                        @endphp
                                        @if($rate == 0)
                                            <span class="font-bold text-green-600 text-xs">FREE</span>
                                        @else
                                            <span class="font-semibold text-gray-900 text-sm">${{ number_format($rate, 2) }}</span>
                                        @endif
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6 bg-gray-50 rounded-lg">
                            <p class="text-gray-600 text-sm">No shipping methods available.</p>
                        </div>
                    @endif
                </div>

                <!-- 3. Order Summary -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Order Summary</h2>

                    @if($items && $items->isNotEmpty())
                        <div class="space-y-2.5 mb-4 max-h-64 overflow-y-auto">
                            @foreach($items as $item)
                                <div class="flex gap-2 pb-2.5 border-b last:border-b-0 last:pb-0">
                                    @if($item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}" 
                                             alt="{{ $item->product->name }}"
                                             class="w-12 h-12 object-cover rounded-lg bg-gray-100 flex-shrink-0">
                                    @else
                                        <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="2"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-semibold text-xs text-gray-900 truncate">{{ $item->product->name }}</h3>
                                        <p class="text-xs text-gray-500 mt-0.5">Qty: {{ $item->quantity }}</p>
                                        <p class="text-xs font-bold text-gray-900 mt-0.5">${{ number_format($item->subtotal, 2) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Coupon Section -->
                        <div class="mb-3 pb-3 border-b">
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">Promo code</label>
                            <div class="flex gap-1">
                                <input type="text" 
                                       wire:model="coupon_code" 
                                       placeholder=""
                                       class="flex-1 px-2 py-1.5 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent bg-gray-50 text-xs">
                                <button type="button"
                                        wire:click="applyCoupon"
                                        class="px-2.5 py-1.5 bg-gray-900 text-white text-xs font-medium rounded-md hover:bg-gray-800 transition-colors">
                                    Apply
                                </button>
                            </div>
                            @if($coupon_error)
                                <p class="text-red-500 text-xs mt-1">{{ $coupon_error }}</p>
                            @endif
                            @if($coupon_applied)
                                <div class="mt-1.5 flex items-center justify-between text-xs">
                                    <span class="text-green-600 font-medium">{{ $coupon_code }} applied</span>
                                    <button type="button"
                                            wire:click="removeCoupon"
                                            class="text-red-600 hover:text-red-800 text-xs">
                                        Remove
                                    </button>
                                </div>
                            @endif
                        </div>

                        <div class="space-y-1.5 text-xs mb-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-semibold text-gray-900">${{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Shipping</span>
                                <span class="font-semibold {{ $shipping == 0 ? 'text-green-600' : 'text-gray-900' }}">
                                    @if($shipping == 0)
                                        FREE
                                    @else
                                        ${{ number_format($shipping, 2) }}
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tax</span>
                                <span class="font-semibold text-gray-900">${{ number_format($tax, 2) }}</span>
                            </div>
                            @if($discount > 0)
                                <div class="flex justify-between text-green-600">
                                    <span>Discount</span>
                                    <span class="font-semibold">-${{ number_format($discount, 2) }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="border-t pt-2 mb-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-semibold text-gray-900">Total</span>
                                <span class="text-lg font-bold text-orange-600">${{ number_format($total, 2) }}</span>
                            </div>
                        </div>

                        <button type="submit" class="w-full px-4 py-2.5 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-semibold rounded-lg hover:from-orange-600 hover:to-orange-700 transition-all shadow-md flex items-center justify-center gap-2 text-sm">
                            Place Order
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M13 7l5 5m0 0l-5 5m5-5H6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>

                        <!-- SSL Badge -->
                        <div class="mt-2 flex items-center justify-center gap-1 text-xs text-gray-600 bg-gray-50 py-1.5 px-2 rounded-md">
                            <svg class="w-3 h-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="font-medium">SSL Secure</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- 3. Payment Option (Full Width) -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">3. Payment Option</h2>
                
                <!-- Payment Method Tabs -->
                <div class="grid grid-cols-3 gap-3 mb-6">
                    <button type="button" 
                            wire:click="$set('payment_method', 'card')"
                            class="flex flex-col items-center justify-center p-4 border-2 rounded-lg transition-all {{ $payment_method == 'card' ? 'border-orange-500 bg-orange-50' : 'border-gray-200 hover:border-gray-300' }}">
                        <svg class="w-6 h-6 mb-1 {{ $payment_method == 'card' ? 'text-orange-600' : 'text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <rect x="2" y="6" width="20" height="12" rx="2" stroke-width="2"/>
                            <path d="M2 10h20" stroke-width="2"/>
                        </svg>
                        <span class="text-sm font-medium {{ $payment_method == 'card' ? 'text-orange-600' : 'text-gray-700' }}">Card</span>
                    </button>
                    
                    <button type="button" 
                            wire:click="$set('payment_method', 'e_wallet')"
                            class="flex flex-col items-center justify-center p-4 border-2 rounded-lg transition-all {{ $payment_method == 'e_wallet' ? 'border-orange-500 bg-orange-50' : 'border-gray-200 hover:border-gray-300' }}">
                        <svg class="w-6 h-6 mb-1 {{ $payment_method == 'e_wallet' ? 'text-orange-600' : 'text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" stroke-width="2"/>
                        </svg>
                        <span class="text-sm font-medium {{ $payment_method == 'e_wallet' ? 'text-orange-600' : 'text-gray-700' }}">E-Wallet</span>
                    </button>
                    
                    <button type="button" 
                            wire:click="$set('payment_method', 'cod')"
                            class="flex flex-col items-center justify-center p-4 border-2 rounded-lg transition-all {{ $payment_method == 'cod' ? 'border-orange-500 bg-orange-50' : 'border-gray-200 hover:border-gray-300' }}">
                        <svg class="w-6 h-6 mb-1 {{ $payment_method == 'cod' ? 'text-orange-600' : 'text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2"/>
                        </svg>
                        <span class="text-sm font-medium {{ $payment_method == 'cod' ? 'text-orange-600' : 'text-gray-700' }}">COD</span>
                    </button>
                </div>

                <!-- Card Payment Form -->
                @if($payment_method == 'card')
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Card Number</label>
                            <div class="relative">
                                <input type="text" 
                                       placeholder="0000 0000 0000 0000"
                                       maxlength="19"
                                       class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent bg-gray-50">
                                <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" stroke-width="2"/>
                                </svg>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Expiry Date</label>
                                <input type="text" 
                                       placeholder="MM/YY"
                                       maxlength="5"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent bg-gray-50">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">CVC / CVV</label>
                                <input type="text" 
                                       placeholder="123"
                                       maxlength="3"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent bg-gray-50">
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <p class="text-xs text-gray-500 text-center mt-6">
                By placing your order, you agree to LiveShop's <a href="#" class="text-orange-600 hover:underline">Terms of Service</a> and <a href="#" class="text-orange-600 hover:underline">Privacy Policy</a>
            </p>
        </form>
    </div>
</div>
