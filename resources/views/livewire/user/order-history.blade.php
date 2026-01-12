<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4">
        <div class="bg-white rounded-lg shadow-md p-8 mb-8">
            <a href="{{ route('profile') }}" class="text-blue-600 hover:text-blue-700 font-semibold mb-6 inline-block">
                ← Back
            </a>

            <h1 class="text-3xl font-bold text-gray-900 mb-8">Order History</h1>

            @if ($orders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b-2 border-gray-300">
                                <th class="pb-4 font-semibold text-gray-700">Order Number</th>
                                <th class="pb-4 font-semibold text-gray-700">Order Date</th>
                                <th class="pb-4 font-semibold text-gray-700">Status</th>
                                <th class="pb-4 font-semibold text-gray-700">Total</th>
                                <th class="pb-4 font-semibold text-gray-700">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr class="border-b border-gray-200 hover:bg-gray-50">
                                    <td class="py-4">#{{ $order->order_number }}</td>
                                    <td class="py-4">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="py-4">
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
                                        <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="py-4 font-semibold text-gray-900">${{ number_format($order->total, 2) }}</td>
                                    <td class="py-4">
                                        <a 
                                            href="{{ route('order.detail', $order->id) }}"
                                            class="text-blue-600 hover:text-blue-700 font-semibold text-sm"
                                        >
                                            Details
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($orders->hasPages())
                    <div class="mt-8 flex justify-center">
                        {{ $orders->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-16">
                    <p class="text-gray-600 text-lg mb-6">You have not placed any order</p>
                    <a 
                        href="{{ route('products.index') }}"
                        class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition duration-200"
                    >
                        Start Shopping
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
