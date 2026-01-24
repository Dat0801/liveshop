<div>
    <!-- Breadcrumb -->
    <div class="mb-6">
        <nav class="text-sm breadcrumb">
            <a href="#" class="text-gray-600 hover:text-gray-900">Admin</a>
            <span class="text-gray-400 mx-2">›</span>
            <span class="text-gray-900 font-medium">Orders Management</span>
        </nav>
    </div>

    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Orders Management</h2>
            <p class="text-gray-600 text-sm mt-1">Review and process customer orders across all channels.</p>
        </div>
        <div class="flex gap-3">
            <button class="flex items-center gap-2 px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m4-3H8m7-9H5a2 2 0 00-2 2v12a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2z" />
                </svg>
                Export CSV
            </button>
            <button class="flex items-center gap-2 px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create Order
            </button>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex flex-col md:flex-row gap-4 items-end justify-between">
            <!-- Search Box -->
            <div class="flex-1">
                <input type="text" 
                       wire:model.live.debounce.300ms="search"
                       placeholder="Search orders, customers, IDs..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
            </div>

            <!-- Filter Dropdowns -->
            <div class="flex gap-3">
                <select wire:model.live="statusFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="processing">Processing</option>
                    <option value="shipped">Shipped</option>
                    <option value="delivered">Delivered</option>
                    <option value="cancelled">Cancelled</option>
                </select>

                <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    <option>All Payments</option>
                </select>

                <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    <option>Last 30 Days</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Table Info -->
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <p class="text-sm text-gray-600">Showing <span class="font-semibold">{{ count($orders) }}</span> of <span class="font-semibold">{{ $orders->total() }}</span> orders</p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left py-4 px-6 font-semibold text-gray-700 text-sm uppercase tracking-wide">Order ID</th>
                        <th class="text-left py-4 px-6 font-semibold text-gray-700 text-sm uppercase tracking-wide">Customer</th>
                        <th class="text-left py-4 px-6 font-semibold text-gray-700 text-sm uppercase tracking-wide">Date</th>
                        <th class="text-left py-4 px-6 font-semibold text-gray-700 text-sm uppercase tracking-wide">Total</th>
                        <th class="text-left py-4 px-6 font-semibold text-gray-700 text-sm uppercase tracking-wide">Payment</th>
                        <th class="text-left py-4 px-6 font-semibold text-gray-700 text-sm uppercase tracking-wide">Status</th>
                        <th class="text-center py-4 px-6 font-semibold text-gray-700 text-sm uppercase tracking-wide">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="py-4 px-6">
                                <span class="font-semibold text-orange-600 text-sm">#{{ $order->order_number }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <div>
                                    <p class="font-medium text-gray-900 text-sm">{{ $order->billing_name }}</p>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-sm text-gray-700">
                                {{ $order->created_at->format('M d, Y') }}
                            </td>
                            <td class="py-4 px-6 font-semibold text-gray-900">
                                ${{ number_format($order->total, 2) }}
                            </td>
                            <td class="py-4 px-6 text-sm">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4z" />
                                        <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6z" clip-rule="evenodd" />
                                    </svg>
                                    Visa
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $order->status === 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $order->status === 'shipped' ? 'bg-purple-100 text-purple-800' : '' }}
                                    {{ $order->status === 'delivered' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <button wire:click="viewOrder({{ $order->id }})" class="inline-flex items-center px-3 py-1 text-orange-600 hover:text-orange-700 font-medium text-sm">
                                    View Details
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-gray-500">No orders found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Section -->
        <div class="px-6 py-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    Showing page with results
                </div>
                <div class="flex gap-2 items-center">
                    {{ $orders->links() }}
                </div>
            </div>
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
