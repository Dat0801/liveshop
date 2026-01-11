<div>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Stock Adjustments</h2>
        <button wire:click="openCreateModal" class="btn btn-primary">
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Record Adjustment
        </button>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search products..." class="input">
            <select wire:model.live="typeFilter" class="input">
                <option value="">All Types</option>
                <option value="receive">Receive</option>
                <option value="adjust">Adjust</option>
                <option value="damage">Damage</option>
                <option value="return">Return</option>
                <option value="sale">Sale</option>
            </select>
            <select wire:model.live="productFilter" class="input">
                <option value="">All Products</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Adjustments Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left py-3 px-4">Product</th>
                        <th class="text-center py-3 px-4">Type</th>
                        <th class="text-center py-3 px-4">Change</th>
                        <th class="text-center py-3 px-4">Before</th>
                        <th class="text-center py-3 px-4">After</th>
                        <th class="text-left py-3 px-4">Reason</th>
                        <th class="text-left py-3 px-4">Admin</th>
                        <th class="text-center py-3 px-4">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($adjustments as $adj)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4">
                                <p class="font-semibold">{{ $adj->product->name }}</p>
                                <p class="text-sm text-gray-600">{{ $adj->product->sku }}</p>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="px-2 py-1 rounded text-xs font-semibold
                                    {{ $adj->type === 'receive' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $adj->type === 'adjust' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $adj->type === 'damage' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $adj->type === 'return' ? 'bg-purple-100 text-purple-800' : '' }}
                                    {{ $adj->type === 'sale' ? 'bg-orange-100 text-orange-800' : '' }}">
                                    {{ ucfirst($adj->type) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center font-semibold {{ $adj->quantity_change > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $adj->quantity_change > 0 ? '+' : '' }}{{ $adj->quantity_change }}
                            </td>
                            <td class="py-3 px-4 text-center">{{ $adj->quantity_before }}</td>
                            <td class="py-3 px-4 text-center font-semibold">{{ $adj->quantity_after }}</td>
                            <td class="py-3 px-4 text-sm">{{ Str::limit($adj->reason, 50) }}</td>
                            <td class="py-3 px-4 text-sm">{{ $adj->admin->name ?? 'System' }}</td>
                            <td class="py-3 px-4 text-center text-sm text-gray-600">
                                {{ $adj->created_at->format('M d, Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-8 text-gray-500">No adjustments found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t">
            {{ $adjustments->links() }}
        </div>
    </div>

    <!-- Adjustment Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: @entangle('showModal') }">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black opacity-50" @click="show = false"></div>

                <div class="relative bg-white rounded-lg shadow-xl max-w-xl w-full">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold">Record Stock Adjustment</h3>
                            <button wire:click="closeModal" class="text-gray-500 hover:text-gray-700">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <form wire:submit.prevent="save">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Product *</label>
                                    <select wire:model="product_id" class="input">
                                        <option value="">Select Product</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->sku }})</option>
                                        @endforeach
                                    </select>
                                    @error('product_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Adjustment Type *</label>
                                    <select wire:model="type" class="input">
                                        <option value="adjust">Adjust</option>
                                        <option value="receive">Receive Stock</option>
                                        <option value="damage">Damage/Loss</option>
                                        <option value="return">Customer Return</option>
                                        <option value="sale">Sale</option>
                                    </select>
                                    @error('type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Quantity Change *</label>
                                    <input type="number" wire:model="quantity_change" class="input" placeholder="Positive or negative number">
                                    @error('quantity_change') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Reason *</label>
                                    <input type="text" wire:model="reason" class="input" placeholder="E.g., Supplier delivery, inventory count">
                                    @error('reason') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                                    <textarea wire:model="notes" rows="3" class="input" placeholder="Additional details..."></textarea>
                                    @error('notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end gap-3">
                                <button type="button" wire:click="closeModal" class="btn">Cancel</button>
                                <button type="submit" class="btn btn-primary">Record Adjustment</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
