<div>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Order Management</h2>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <input type="text" 
                       wire:model.live.debounce.300ms="search"
                       placeholder="Search orders..." 
                       class="input">
            </div>
            <div>
                <select wire:model.live="statusFilter" class="input">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="processing">Processing</option>
                    <option value="shipped">Shipped</option>
                    <option value="delivered">Delivered</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left py-3 px-4">Order #</th>
                        <th class="text-left py-3 px-4">Customer</th>
                        <th class="text-center py-3 px-4">Total</th>
                        <th class="text-center py-3 px-4">Status</th>
                        <th class="text-center py-3 px-4">Date</th>
                        <th class="text-center py-3 px-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4">
                                <span class="font-semibold text-primary-600">{{ $order->order_number }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <div>
                                    <p class="font-semibold">{{ $order->billing_name }}</p>
                                    <p class="text-sm text-gray-600">{{ $order->billing_email }}</p>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-center font-semibold">
                                ${{ number_format($order->total, 2) }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                <select 
                                    wire:change="updateOrderStatus({{ $order->id }}, $event.target.value)"
                                    class="px-2 py-1 rounded-full text-sm border-0 focus:ring-2 focus:ring-primary-500
                                        {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $order->status === 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $order->status === 'shipped' ? 'bg-purple-100 text-purple-800' : '' }}
                                        {{ $order->status === 'delivered' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                    <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </td>
                            <td class="py-3 px-4 text-center text-sm text-gray-600">
                                {{ $order->created_at->format('M d, Y') }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                <button wire:click="viewOrder({{ $order->id }})" class="text-blue-600 hover:text-blue-800">
                                    <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-500">No orders found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 border-t">
            {{ $orders->links() }}
        </div>
    </div>

    <!-- Order Details Modal -->
    @if($showDetailsModal && $selectedOrder)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: @entangle('showDetailsModal') }">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black opacity-50" @click="show = false"></div>

                <div class="relative bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-2xl font-bold">Order Details</h3>
                            <button wire:click="closeModal" class="text-gray-500 hover:text-gray-700">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Order Info -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-semibold mb-3">Order Information</h4>
                                <p class="text-sm mb-2"><span class="font-semibold">Order #:</span> {{ $selectedOrder->order_number }}</p>
                                <p class="text-sm mb-2"><span class="font-semibold">Date:</span> {{ $selectedOrder->created_at->format('M d, Y H:i') }}</p>
                                <p class="text-sm mb-2">
                                    <span class="font-semibold">Status:</span> 
                                    <span class="px-2 py-1 rounded-full text-xs
                                        {{ $selectedOrder->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $selectedOrder->status === 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $selectedOrder->status === 'shipped' ? 'bg-purple-100 text-purple-800' : '' }}
                                        {{ $selectedOrder->status === 'delivered' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $selectedOrder->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ ucfirst($selectedOrder->status) }}
                                    </span>
                                </p>
                            </div>

                            <!-- Billing Info -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-semibold mb-3">Billing Information</h4>
                                <p class="text-sm mb-1">{{ $selectedOrder->billing_name }}</p>
                                <p class="text-sm mb-1">{{ $selectedOrder->billing_email }}</p>
                                <p class="text-sm mb-1">{{ $selectedOrder->billing_phone }}</p>
                                <p class="text-sm mb-1">{{ $selectedOrder->billing_address }}</p>
                                <p class="text-sm">{{ $selectedOrder->billing_city }}, {{ $selectedOrder->billing_state }} {{ $selectedOrder->billing_zip }}</p>
                                <p class="text-sm">{{ $selectedOrder->billing_country }}</p>
                            </div>

                            <!-- Shipping Info -->
                            <div class="bg-gray-50 p-4 rounded-lg md:col-span-2">
                                <h4 class="font-semibold mb-3">Shipping Information</h4>
                                <p class="text-sm mb-1">{{ $selectedOrder->shipping_name }}</p>
                                <p class="text-sm mb-1">{{ $selectedOrder->shipping_phone }}</p>
                                <p class="text-sm mb-1">{{ $selectedOrder->shipping_address }}</p>
                                <p class="text-sm">{{ $selectedOrder->shipping_city }}, {{ $selectedOrder->shipping_state }} {{ $selectedOrder->shipping_zip }}</p>
                                <p class="text-sm">{{ $selectedOrder->shipping_country }}</p>
                            </div>
                        </div>

                        <!-- Order Items -->
                        <div class="mb-6">
                            <h4 class="font-semibold mb-3">Order Items</h4>
                            <table class="min-w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="text-left py-2 px-4">Product</th>
                                        <th class="text-center py-2 px-4">Quantity</th>
                                        <th class="text-center py-2 px-4">Price</th>
                                        <th class="text-right py-2 px-4">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($selectedOrder->items as $item)
                                        <tr class="border-b">
                                            <td class="py-2 px-4">
                                                <p class="font-semibold">{{ $item->product_name }}</p>
                                                <p class="text-sm text-gray-600">SKU: {{ $item->product_sku }}</p>
                                                @if($item->variants && count($item->variants) > 0)
                                                    <p class="text-sm text-gray-500">
                                                        @foreach($item->variants as $type => $value)
                                                            {{ ucfirst($type) }}: {{ $value }}
                                                            @if(!$loop->last), @endif
                                                        @endforeach
                                                    </p>
                                                @endif
                                            </td>
                                            <td class="py-2 px-4 text-center">{{ $item->quantity }}</td>
                                            <td class="py-2 px-4 text-center">${{ number_format($item->price, 2) }}</td>
                                            <td class="py-2 px-4 text-right font-semibold">${{ number_format($item->subtotal, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="3" class="py-2 px-4 text-right">Subtotal:</td>
                                        <td class="py-2 px-4 text-right">${{ number_format($selectedOrder->subtotal, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="py-2 px-4 text-right">Tax:</td>
                                        <td class="py-2 px-4 text-right">${{ number_format($selectedOrder->tax, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="py-2 px-4 text-right">Shipping:</td>
                                        <td class="py-2 px-4 text-right">${{ number_format($selectedOrder->shipping, 2) }}</td>
                                    </tr>
                                    <tr class="font-bold">
                                        <td colspan="3" class="py-2 px-4 text-right text-lg">Total:</td>
                                        <td class="py-2 px-4 text-right text-lg text-primary-600">${{ number_format($selectedOrder->total, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        @if($selectedOrder->notes)
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-semibold mb-2">Order Notes</h4>
                                <p class="text-sm">{{ $selectedOrder->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
