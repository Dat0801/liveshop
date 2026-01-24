<div class="bg-[#f8f4ed] min-h-screen">
    <div class="max-w-6xl mx-auto px-4 lg:px-0 py-10">
        <div class="flex items-center text-sm text-gray-600 gap-2 mb-6">
            <a href="/" class="font-semibold text-orange-600 hover:text-orange-700">Home</a>
            <span class="text-gray-400">/</span>
            <span class="font-semibold text-gray-900">Shopping Cart</span>
        </div>

        <h1 class="text-3xl md:text-4xl font-extrabold text-[#4d2a1a] mb-1">Your Shopping Cart</h1>
        <p class="text-gray-600 mb-8">Review your items before checkout ({{ $cartItems->count() }} {{ \Illuminate\Support\Str::plural('item', $cartItems->count()) }})</p>

        @if (session()->has('message'))
            <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-800 shadow-sm">
                {{ session('message') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800 shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        @if($cartItems->isEmpty())
            <div class="bg-white rounded-2xl shadow-sm border border-orange-100 p-8 text-center">
                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-orange-50 text-orange-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M3 3h2l1 2m0 0h13l-1.68 8.39a2 2 0 01-1.97 1.61H8.61a2 2 0 01-1.98-1.71L5.28 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M16 16a2 2 0 104 0 2 2 0 00-4 0zM6 16a2 2 0 104 0 2 2 0 00-4 0z"/></svg>
                </div>
                <p class="text-gray-700 text-lg font-semibold mb-2">Your cart is empty</p>
                <p class="text-gray-500 mb-6">Add products to enjoy fast checkout and exclusive perks.</p>
                <a href="/" class="inline-flex items-center justify-center rounded-full bg-orange-500 px-6 py-3 text-sm font-semibold text-white shadow hover:bg-orange-600 transition">
                    Continue Shopping
                </a>
            </div>
        @else
            <div class="grid md:grid-cols-[2fr_1fr] gap-6">
                <div class="space-y-4">
                    <div class="bg-white rounded-2xl shadow-sm border border-orange-100 overflow-hidden">
                        <div class="hidden md:grid grid-cols-12 px-6 py-3 text-xs font-semibold uppercase tracking-[0.12em] text-gray-500 border-b border-orange-100">
                            <span class="col-span-6">Product</span>
                            <span class="col-span-2 text-center">Unit Price</span>
                            <span class="col-span-2 text-center">Quantity</span>
                            <span class="col-span-2 text-right">Total</span>
                        </div>

                        @foreach($cartItems as $item)
                            <div class="grid grid-cols-1 md:grid-cols-12 items-center gap-4 px-4 md:px-6 py-5 border-b last:border-b-0 border-orange-50">
                                <div class="md:col-span-6 flex items-start gap-3">
                                    @if(!empty($item->product->images) && isset($item->product->images[0]))
                                        <img src="{{ $item->product->images[0] }}" alt="{{ $item->product->name }}" class="h-20 w-20 rounded-xl object-cover border border-orange-100">
                                    @else
                                        <div class="h-20 w-20 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400 border border-orange-100">No Img</div>
                                    @endif
                                    <div class="space-y-1">
                                        <p class="text-sm text-gray-500 uppercase tracking-wide">{{ $item->product->brand ?? 'Product' }}</p>
                                        <h3 class="text-lg font-semibold text-gray-900 leading-snug">{{ $item->product->name }}</h3>
                                        @if(!empty($item->variants))
                                            <div class="text-sm text-gray-600 flex flex-wrap gap-2">
                                                @foreach($item->variants as $type => $value)
                                                    <span class="rounded-full bg-orange-50 px-3 py-1 text-orange-700 font-medium border border-orange-100">{{ ucfirst($type) }}: {{ $value }}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                        <button wire:click="remove('{{ $item->id }}')" class="mt-1 inline-flex items-center gap-1 text-xs font-semibold text-red-500 hover:text-red-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 7h12m-9 0V5a1 1 0 011-1h2a1 1 0 011 1v2m3 0v12a1 1 0 01-1 1H8a1 1 0 01-1-1V7z"/></svg>
                                            Remove
                                        </button>
                                    </div>
                                </div>

                                <div class="md:col-span-2 w-full md:text-center text-gray-900 font-semibold">${{ number_format($item->price, 2) }}</div>

                                <div class="md:col-span-2">
                                    <div class="flex items-center md:justify-center w-full max-w-[120px] rounded-full border border-orange-100 bg-orange-50 px-2 py-2">
                                        <button wire:click="decrement('{{ $item->id }}')" class="h-7 w-7 flex items-center justify-center rounded-full bg-white text-gray-700 shadow-sm hover:shadow transition" aria-label="Decrease quantity">
                                            <span class="text-lg leading-none">−</span>
                                        </button>
                                        <span class="mx-3 w-8 text-center text-sm font-semibold text-gray-900">{{ $item->quantity }}</span>
                                        <button wire:click="increment('{{ $item->id }}')" class="h-7 w-7 flex items-center justify-center rounded-full bg-white text-gray-700 shadow-sm hover:shadow transition" aria-label="Increase quantity">
                                            <span class="text-lg leading-none">+</span>
                                        </button>
                                    </div>
                                </div>

                                <div class="md:col-span-2 w-full md:text-right text-lg font-bold text-gray-900">${{ number_format($item->subtotal, 2) }}</div>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <a href="/" class="inline-flex items-center gap-2 text-sm font-semibold text-orange-600 hover:text-orange-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M10 19l-7-7 7-7m11 7H3"/></svg>
                            Continue Shopping
                        </a>
                        <button wire:click="clearCart" class="text-sm font-semibold text-gray-700 hover:text-red-500">Clear Shopping Cart</button>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="bg-white rounded-2xl shadow-sm border border-orange-100 p-6 sticky top-28">
                        <h2 class="text-xl font-extrabold text-gray-900 mb-4">Order Summary</h2>

                        <div class="space-y-3 text-sm text-gray-700">
                            <div class="flex items-center justify-between">
                                <span>Subtotal</span>
                                <span class="font-semibold text-gray-900">${{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Shipping (Standard)</span>
                                <span class="font-semibold text-green-600">{{ $shipping <= 0 ? 'FREE' : '$'.number_format($shipping, 2) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Estimated Tax ({{ number_format($taxRate * 100, 0) }}%)</span>
                                <span class="font-semibold text-gray-900">${{ number_format($tax, 2) }}</span>
                            </div>
                        </div>

                        <div class="mt-4 border-t border-orange-100 pt-4 flex items-center justify-between">
                            <span class="text-lg font-extrabold text-gray-900">Total</span>
                            <span class="text-2xl font-black text-orange-500">${{ number_format($grandTotal, 2) }}</span>
                        </div>

                        <div class="mt-4 flex items-center gap-2">
                            <label for="coupon" class="sr-only">Coupon Code</label>
                            <input id="coupon" type="text" placeholder="Code" class="flex-1 rounded-lg border border-orange-100 bg-orange-50 px-3 py-2 text-sm focus:border-orange-300 focus:ring-2 focus:ring-orange-200">
                            <button type="button" class="rounded-lg bg-orange-500 px-4 py-2 text-sm font-semibold text-white hover:bg-orange-600 transition">Apply</button>
                        </div>

                        <a href="{{ route('checkout') }}" class="mt-6 w-full inline-flex items-center justify-center gap-2 rounded-xl bg-orange-500 px-4 py-3 text-sm font-bold text-white shadow hover:bg-orange-600 transition">
                            Proceed to Checkout
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 12h14m-6-6l6 6-6 6"/></svg>
                        </a>

                        <div class="mt-4 space-y-2 text-xs text-gray-700">
                            <div class="flex items-center gap-2"><span class="text-orange-500">▣</span> Secure SSL Encrypted Checkout</div>
                            <div class="flex items-center gap-2"><span class="text-orange-500">▣</span> Free shipping on all orders over $150</div>
                            <div class="flex items-center gap-2"><span class="text-orange-500">▣</span> 30-Day Hassle-Free Returns</div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-orange-200 bg-gradient-to-br from-orange-400 to-orange-600 p-6 text-white shadow-sm">
                        <h3 class="text-xl font-extrabold mb-2">Upgrade to Premium</h3>
                        <p class="text-sm text-orange-50 mb-4">Get extra 10% off your first order</p>
                        <button class="w-full rounded-xl bg-white/90 text-orange-700 font-bold py-2 hover:bg-white transition">Upgrade Now</button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
