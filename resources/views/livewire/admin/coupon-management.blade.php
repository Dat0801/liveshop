<div>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Coupon Management</h2>
        <button wire:click="openCreateModal" class="btn btn-primary">
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add Coupon
        </button>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search coupons..." class="input">
            <select wire:model.live="statusFilter" class="input">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
    </div>

    <!-- Coupons Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left py-3 px-4">Code</th>
                        <th class="text-center py-3 px-4">Type</th>
                        <th class="text-center py-3 px-4">Value</th>
                        <th class="text-center py-3 px-4">Usage</th>
                        <th class="text-center py-3 px-4">Valid Until</th>
                        <th class="text-center py-3 px-4">Status</th>
                        <th class="text-center py-3 px-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($coupons as $coupon)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4">
                                <span class="font-mono font-semibold text-primary-600">{{ $coupon->code }}</span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="px-2 py-1 rounded text-xs {{ $coupon->type === 'percentage' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                    {{ ucfirst($coupon->type) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center font-semibold">
                                {{ $coupon->type === 'percentage' ? $coupon->value . '%' : '$' . number_format($coupon->value, 2) }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                {{ $coupon->usage_count }} / {{ $coupon->usage_limit ?? '∞' }}
                            </td>
                            <td class="py-3 px-4 text-center text-sm">
                                {{ $coupon->valid_until ? $coupon->valid_until->format('M d, Y') : 'No expiry' }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                <button wire:click="toggleStatus({{ $coupon->id }})" 
                                        class="px-3 py-1 rounded-full text-sm font-medium transition-colors
                                            {{ $coupon->is_active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                    {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <button wire:click="openEditModal({{ $coupon->id }})" class="text-blue-600 hover:text-blue-800 mr-3">
                                    <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button wire:click="confirmDelete({{ $coupon->id }})" class="text-red-600 hover:text-red-800">
                                    <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-gray-500">No coupons found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t">
            {{ $coupons->links() }}
        </div>
    </div>

    <!-- Coupon Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: @entangle('showModal') }">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black opacity-50" @click="show = false"></div>

                <div class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold">{{ $editMode ? 'Edit Coupon' : 'Add Coupon' }}</h3>
                            <button wire:click="closeModal" class="text-gray-500 hover:text-gray-700">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <form wire:submit.prevent="save">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Coupon Code *</label>
                                    <div class="flex gap-2">
                                        <input type="text" wire:model="code" class="input flex-1" placeholder="SUMMER2026">
                                        <button type="button" wire:click="generateCode" class="btn">Generate</button>
                                    </div>
                                    @error('code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Discount Type *</label>
                                        <select wire:model="type" class="input">
                                            <option value="percentage">Percentage</option>
                                            <option value="fixed">Fixed Amount</option>
                                        </select>
                                        @error('type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Value *</label>
                                        <input type="number" step="0.01" wire:model="value" class="input">
                                        @error('value') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Min Purchase</label>
                                        <input type="number" step="0.01" wire:model="min_purchase" class="input" placeholder="0.00">
                                        @error('min_purchase') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Max Discount</label>
                                        <input type="number" step="0.01" wire:model="max_discount" class="input" placeholder="No limit">
                                        @error('max_discount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Usage Limit</label>
                                        <input type="number" wire:model="usage_limit" class="input" placeholder="Unlimited">
                                        @error('usage_limit') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Per User Limit</label>
                                        <input type="number" wire:model="per_user_limit" class="input" placeholder="Unlimited">
                                        @error('per_user_limit') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Valid From</label>
                                        <input type="datetime-local" wire:model="valid_from" class="input">
                                        @error('valid_from') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Valid Until</label>
                                        <input type="datetime-local" wire:model="valid_until" class="input">
                                        @error('valid_until') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" wire:model="is_active" class="mr-2">
                                        <span>Active</span>
                                    </label>
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
                        <h3 class="text-lg font-bold mb-4">Delete Coupon</h3>
                        <p class="text-gray-700 mb-6">Are you sure you want to delete this coupon?</p>
                        <div class="flex justify-end gap-3">
                            <button type="button" wire:click="closeDeleteModal" class="btn">Cancel</button>
                            <button type="button" wire:click="deleteCoupon" class="btn btn-danger">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
