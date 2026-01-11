<div>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Manage Variants</h2>
        <div class="flex gap-3">
            <a href="{{ route('admin.products') }}" class="btn">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Products
            </a>
            <button wire:click="openCreateModal" class="btn btn-primary">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Variant
            </button>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left py-3 px-4">Type</th>
                        <th class="text-left py-3 px-4">Value</th>
                        <th class="text-center py-3 px-4">SKU</th>
                        <th class="text-center py-3 px-4">Price Adj.</th>
                        <th class="text-center py-3 px-4">Stock</th>
                        <th class="text-center py-3 px-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($variants as $variant)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4">{{ ucfirst($variant->type) }}</td>
                            <td class="py-3 px-4">{{ $variant->value }}</td>
                            <td class="py-3 px-4 text-center">{{ $variant->sku }}</td>
                            <td class="py-3 px-4 text-center">${{ number_format($variant->price_adjustment ?? 0, 2) }}</td>
                            <td class="py-3 px-4 text-center">{{ $variant->stock_quantity }}</td>
                            <td class="py-3 px-4 text-center">
                                <button wire:click="openEditModal({{ $variant->id }})" class="text-blue-600 hover:text-blue-800 mr-3">
                                    <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button wire:click="confirmDelete({{ $variant->id }})" class="text-red-600 hover:text-red-800">
                                    <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-500">No variants found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t">
            {{ $variants->links() }}
        </div>
    </div>

    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: @entangle('showModal') }">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black opacity-50" @click="show = false"></div>

                <div class="relative bg-white rounded-lg shadow-xl max-w-xl w-full">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold">{{ $editMode ? 'Edit Variant' : 'Add Variant' }}</h3>
                            <button wire:click="closeModal" class="text-gray-500 hover:text-gray-700">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <form wire:submit.prevent="save">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Type *</label>
                                    <input type="text" wire:model="type" class="input">
                                    @error('type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Value *</label>
                                    <input type="text" wire:model="value" class="input">
                                    @error('value') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">SKU *</label>
                                        <input type="text" wire:model="sku" class="input">
                                        @error('sku') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Price Adjustment</label>
                                        <input type="number" step="0.01" wire:model="price_adjustment" class="input">
                                        @error('price_adjustment') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Stock Quantity *</label>
                                        <input type="number" wire:model="stock_quantity" class="input">
                                        @error('stock_quantity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="mt-6 flex justify-end gap-3">
                                <button type="button" wire:click="closeModal" class="btn">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: @entangle('showDeleteModal') }">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black opacity-50" @click="show = false"></div>
                <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full">
                    <div class="p-6">
                        <h3 class="text-lg font-bold mb-4">Delete Variant</h3>
                        <p class="text-gray-700 mb-6">Are you sure you want to delete this variant?</p>
                        <div class="flex justify-end gap-3">
                            <button type="button" wire:click="closeDeleteModal" class="btn">Cancel</button>
                            <button type="button" wire:click="deleteVariant" class="btn btn-danger">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
