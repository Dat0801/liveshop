<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Shopping Cart</h1>

    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    @if($cartItems->isEmpty())
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <p class="text-gray-500 text-lg mb-4">Your cart is empty.</p>
            <a href="/" class="inline-block bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                Continue Shopping
            </a>
        </div>
    @else
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Cart Items List -->
            <div class="w-full lg:w-3/4">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="text-left py-4 px-6 font-semibold text-gray-600">Product</th>
                                <th class="text-center py-4 px-6 font-semibold text-gray-600">Quantity</th>
                                <th class="text-right py-4 px-6 font-semibold text-gray-600">Total</th>
                                <th class="py-4 px-6"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($cartItems as $item)
                                <tr>
                                    <td class="py-4 px-6">
                                        <div class="flex items-center">
                                            @if(!empty($item->product->images) && isset($item->product->images[0]))
                                                <img src="{{ $item->product->images[0] }}" alt="{{ $item->product->name }}" class="w-16 h-16 object-cover rounded mr-4">
                                            @else
                                                <div class="w-16 h-16 bg-gray-200 rounded mr-4 flex items-center justify-center text-gray-400">
                                                    No Img
                                                </div>
                                            @endif
                                            <div>
                                                <h3 class="font-semibold text-gray-800">{{ $item->product->name }}</h3>
                                                @if(!empty($item->variants))
                                                    <div class="text-sm text-gray-500 mt-1">
                                                        @foreach($item->variants as $type => $value)
                                                            <span class="mr-2">{{ ucfirst($type) }}: {{ $value }}</span>
                                                        @endforeach
                                                    </div>
                                                @endif
                                                <p class="text-gray-500 text-sm mt-1">${{ number_format($item->price, 2) }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex items-center justify-center">
                                            <button wire:click="decrement('{{ $item->id }}')" class="text-gray-500 hover:text-blue-600 focus:outline-none p-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                            </button>
                                            <span class="mx-3 text-gray-700 font-medium w-8 text-center">{{ $item->quantity }}</span>
                                            <button wire:click="increment('{{ $item->id }}')" class="text-gray-500 hover:text-blue-600 focus:outline-none p-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <span class="font-semibold text-gray-800">${{ number_format($item->subtotal, 2) }}</span>
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <button wire:click="remove('{{ $item->id }}')" class="text-red-500 hover:text-red-700 transition" title="Remove item">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="w-full lg:w-1/4">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold mb-4 text-gray-800">Order Summary</h2>
                    <div class="flex justify-between mb-2 text-gray-600">
                        <span>Subtotal</span>
                        <span>${{ number_format($total, 2) }}</span>
                    </div>
                    <div class="flex justify-between mb-4 text-gray-600">
                        <span>Tax (Estimate)</span>
                        <span>$0.00</span>
                    </div>
                    <div class="border-t pt-4 flex justify-between font-bold text-lg text-gray-900 mb-6">
                        <span>Total</span>
                        <span>${{ number_format($total, 2) }}</span>
                    </div>
                    <button class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-300 shadow-md">
                        Proceed to Checkout
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
