<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <div class="bg-white rounded-lg shadow-md p-8 mb-8">
            <a href="{{ route('profile.orders') }}" class="text-blue-600 hover:text-blue-700 font-semibold mb-6 inline-block">
                ← Back to Orders
            </a>

            <!-- Order Header -->
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Order #{{ $order->order_number }}</h1>
                    <p class="text-gray-600">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div class="text-right">
                    @php
                        $statusColors = [
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'processing' => 'bg-blue-100 text-blue-800',
                            'shipped' => 'bg-purple-100 text-purple-800',
                            'delivered' => 'bg-green-100 text-green-800',
                            'cancelled' => 'bg-red-100 text-red-800',
                        ];
                        $statusLabels = [
                            'pending' => 'Pending',
                            'processing' => 'Processing',
                            'shipped' => 'Shipped',
                            'delivered' => 'Delivered',
                            'cancelled' => 'Cancelled',
                        ];
                    @endphp
                    <span class="inline-block px-4 py-2 rounded-full text-sm font-semibold {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                    </span>
                </div>
            </div>

            <!-- Order Items -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Items</h2>
                <div class="border rounded-lg overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Product</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-900">Quantity</th>
                                <th class="px-6 py-3 text-right text-sm font-semibold text-gray-900">Price</th>
                                <th class="px-6 py-3 text-right text-sm font-semibold text-gray-900">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr class="border-t border-gray-200 hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $item->product_name }}</p>
                                            @if($item->variants)
                                                <p class="text-sm text-gray-600">
                                                    @foreach($item->variants as $key => $value)
                                                        {{ ucfirst($key) }}: {{ $value }}{{ !$loop->last ? ', ' : '' }}
                                                    @endforeach
                                                </p>
                                            @endif
                                            <p class="text-xs text-gray-500">SKU: {{ $item->product_sku }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center text-gray-900">{{ $item->quantity }}</td>
                                    <td class="px-6 py-4 text-right text-gray-900">${{ number_format($item->price, 2) }}</td>
                                    <td class="px-6 py-4 text-right font-semibold text-gray-900">${{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Pricing Summary -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-semibold">${{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tax</span>
                            <span class="font-semibold">${{ number_format($order->tax, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Shipping</span>
                            <span class="font-semibold">${{ number_format($order->shipping, 2) }}</span>
                        </div>
                        @if($order->discount_amount > 0)
                            <div class="flex justify-between text-green-600">
                                <span>Discount</span>
                                <span class="font-semibold">-${{ number_format($order->discount_amount, 2) }}</span>
                            </div>
                        @endif
                        <div class="border-t border-gray-200 pt-3 flex justify-between">
                            <span class="font-semibold text-gray-900">Total</span>
                            <span class="text-lg font-bold text-blue-600">${{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Shipping Information -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Delivery Information</h2>
                    <div class="mb-6 pb-6 border-b">
                        <p class="text-sm text-gray-600 font-semibold mb-2">Shipping Address</p>
                        <p class="font-semibold text-gray-900">{{ $order->shipping_name }}</p>
                        <p class="text-gray-700">{{ $order->shipping_address }}</p>
                        <p class="text-gray-700">{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}</p>
                        <p class="text-gray-700">{{ $order->shipping_country }}</p>
                        <p class="text-gray-600 text-sm mt-2">📞 {{ $order->shipping_phone }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 font-semibold mb-2">Billing Address</p>
                        <p class="font-semibold text-gray-900">{{ $order->billing_name }}</p>
                        <p class="text-gray-700">{{ $order->billing_address }}</p>
                        <p class="text-gray-700">{{ $order->billing_city }}, {{ $order->billing_state }} {{ $order->billing_zip }}</p>
                        <p class="text-gray-700">{{ $order->billing_country }}</p>
                        <p class="text-gray-600 text-sm mt-2">📞 {{ $order->billing_phone }}</p>
                    </div>
                </div>
            </div>

            <!-- Payment Method & Notes -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8 pt-8 border-t">
                <div>
                    <h3 class="font-semibold text-gray-900 mb-2">Payment Method</h3>
                    <p class="text-gray-700 capitalize">{{ $order->payment_method ?? 'Not specified' }}</p>
                </div>
                @if($order->notes)
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-2">Order Notes</h3>
                        <p class="text-gray-700">{{ $order->notes }}</p>
                    </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 pt-8 border-t flex gap-3">
                <a href="{{ route('profile.orders') }}" class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-900 font-semibold rounded-lg transition">
                    Back to Orders
                </a>
                @if($order->status !== 'cancelled')
                    <button class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                        Track Order
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
