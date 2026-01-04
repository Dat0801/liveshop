<div>
    <!-- Cart Sidebar -->
    <div 
        x-data="{ open: @entangle('isOpen') }"
        @toggle-cart.window="open = !open"
        @cart-updated.window="$wire.$refresh()"
        class="relative z-50">
        
        <!-- Overlay -->
        <div 
            x-show="open"
            x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="open = false"
            class="fixed inset-0 bg-black bg-opacity-50"
            style="display: none;">
        </div>

        <!-- Sidebar -->
        <div 
            x-show="open"
            x-transition:enter="transform transition ease-in-out duration-300"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in-out duration-300"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="fixed right-0 top-0 h-full w-full max-w-md bg-white shadow-xl flex flex-col"
            style="display: none;">
            
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b">
                <h2 class="text-2xl font-bold">Shopping Cart</h2>
                <button @click="open = false" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Cart Items -->
            <div class="flex-1 overflow-y-auto p-6">
                @if($items && $items->isNotEmpty())
                    <div class="space-y-4">
                        @foreach($items as $item)
                            <div class="flex space-x-4 border-b pb-4">
                                <!-- Product Image -->
                                <div class="w-20 h-20 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                                    @if($item->product->images && count($item->product->images) > 0)
                                        <img src="{{ $item->product->images[0] }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <!-- Product Info -->
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900">{{ $item->product->name }}</h3>
                                    
                                    @if($item->variants && count($item->variants) > 0)
                                        <p class="text-sm text-gray-500">
                                            @foreach($item->variants as $type => $value)
                                                <span>{{ ucfirst($type) }}: {{ $value }}</span>
                                                @if(!$loop->last), @endif
                                            @endforeach
                                        </p>
                                    @endif

                                    <p class="text-primary-600 font-semibold mt-1">
                                        ${{ number_format($item->price, 2) }}
                                    </p>

                                    <!-- Quantity Controls -->
                                    <div class="flex items-center space-x-2 mt-2">
                                        <button 
                                            wire:click="updateQuantity('{{ $item->id }}', {{ $item->quantity - 1 }})"
                                            class="w-6 h-6 rounded border border-gray-300 hover:bg-gray-100 flex items-center justify-center">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                            </svg>
                                        </button>
                                        <span class="w-8 text-center">{{ $item->quantity }}</span>
                                        <button 
                                            wire:click="updateQuantity('{{ $item->id }}', {{ $item->quantity + 1 }})"
                                            class="w-6 h-6 rounded border border-gray-300 hover:bg-gray-100 flex items-center justify-center">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                        </button>
                                        <button 
                                            wire:click="removeItem('{{ $item->id }}')"
                                            class="ml-auto text-red-500 hover:text-red-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Subtotal -->
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900">
                                        ${{ number_format($item->subtotal, 2) }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Your cart is empty</h3>
                        <p class="text-gray-500">Add some products to get started!</p>
                    </div>
                @endif
            </div>

            <!-- Footer -->
            @if($items && $items->isNotEmpty())
                <div class="border-t p-6 bg-gray-50">
                    <!-- Subtotal -->
                    <div class="flex justify-between mb-4">
                        <span class="text-lg font-semibold">Subtotal</span>
                        <span class="text-lg font-bold text-primary-600">
                            ${{ number_format($items->sum('subtotal'), 2) }}
                        </span>
                    </div>

                    <p class="text-sm text-gray-500 mb-4">Shipping and taxes calculated at checkout</p>

                    <!-- Buttons -->
                    <div class="space-y-3">
                        <a href="{{ route('checkout') }}" class="btn btn-primary w-full block text-center">
                            Proceed to Checkout
                        </a>
                        <button @click="open = false" class="btn btn-secondary w-full">
                            Continue Shopping
                        </button>
                        <button wire:click="clearCart" class="text-sm text-red-600 hover:text-red-800 w-full">
                            Clear Cart
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
