<div>
    @if (session()->has('message'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('message') }}
        </div>
    @endif

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
            <div>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search by code..." class="input">
            </div>
            <div>
                <select wire:model.live="statusFilter" class="input">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Coupons Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left py-3 px-4">Code</th>
                        <th class="text-left py-3 px-4">Type</th>
                        <th class="text-left py-3 px-4">Value</th>
                        <th class="text-center py-3 px-4">Usage</th>
                        <th class="text-center py-3 px-4">Valid Period</th>
                        <th class="text-center py-3 px-4">Status</th>
                        <th class="text-center py-3 px-4">Actions</th>
                    </tr>
                </thead>
@forelse ($coupons as $coupon)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4">
                                <span class="font-mono font-semibold">{{ $coupon->code }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-1 text-xs rounded {{ $coupon->type === 'percentage' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $coupon->type === 'percentage' ? 'Percentage' : 'Fixed Amount' }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <div>
                                    <span class="font-semibold">
                                        {{ $coupon->type === 'percentage' ? $coupon->value . '%' : '$' . number_format($coupon->value, 2) }}
                                    </span>
                                    @if($coupon->max_discount)
                                        <p class="text-xs text-gray-500">Max: ${{ number_format($coupon->max_discount, 2) }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="px-2 py-1 rounded-full text-sm 
                                    {{ ($coupon->usage_limit && $coupon->usage_count >= $coupon->usage_limit) ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $coupon->usage_count ?? 0 }}
                                    @if($coupon->usage_limit)
                                        / {{ $coupon->usage_limit }}
                                    @else
                                        / ∞
                                    @endif
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center text-sm text-gray-600">
                                @if($coupon->valid_from)
                                    {{ $coupon->valid_from->format('M d, Y') }}
                                @else
                                    —
                                @endif
                                <br>
                                @if($coupon->valid_until)
                                    {{ $coupon->valid_until->format('M d, Y') }}
                                @else
                                    No expiry
                                @endif
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
                            <td colspan="7" class="text-center py-8 text-gray-500">
                                No coupons found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t">
            {{ $coupons->links() }}
        </div>
    </div>

    <!-- Create/Edit Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: @entangle('showModal') }">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black opacity-50" @click="show = false"></div>

                <div class="relative bg-white rounded-lg shadow-xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
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
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Code -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Coupon Code *</label>
                                    <div class="flex gap-2">
                                        <input wire:model="code" type="text" class="input flex-1" required>
                                        <button type="button" wire:click="generateCode" class="btn bg-gray-500 hover:bg-gray-600 text-white">
                                            Generate
                                        </button>
                                    </div>
                                    @error('code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <!-- Type -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Discount Type *</label>
                                    <select wire:model="type" class="input" required>
                                        <option value="percentage">Percentage</option>
                                        <option value="fixed">Fixed Amount</option>
                                    </select>
                                    @error('type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <!-- Value -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Discount Value {{ $type === 'percentage' ? '(%)' : '($)' }} *
                                    </label>
                                    <input wire:model="value" type="number" step="0.01" min="0" class="input" required>
                                    @error('value') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <!-- Min Purchase -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Min Purchase ($)</label>
                                    <input wire:model="min_purchase" type="number" step="0.01" min="0" class="input">
                                    @error('min_purchase') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <!-- Max Discount -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Max Discount ($)</label>
                                    <input wire:model="max_discount" type="number" step="0.01" min="0" class="input">
                                    @error('max_discount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <!-- Usage Limit -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Total Usage Limit</label>
                                    <input wire:model="usage_limit" type="number" min="1" class="input">
                                    @error('usage_limit') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <!-- Per User Limit -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Per User Limit</label>
                                    <input wire:model="per_user_limit" type="number" min="1" class="input">
                                    @error('per_user_limit') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <!-- Valid From -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Valid From</label>
                                    <input wire:model="valid_from" type="datetime-local" class="input">
                                    @error('valid_from') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <!-- Valid Until -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Valid Until</label>
                                    <input wire:model="valid_until" type="datetime-local" class="input">
                                    @error('valid_until') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <!-- Applicable Categories -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Applicable Categories (Optional)</label>
                                    <select wire:model="applicable_categories" multiple class="input" size="4">
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">Hold Ctrl/Cmd to select multiple. Leave empty for all categories.</p>
                                    @error('applicable_categories') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <!-- Applicable Products -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Applicable Products (Optional)</label>
                                    <select wire:model="applicable_products" multiple class="input" size="4">
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">Hold Ctrl/Cmd to select multiple. Leave empty for all products.</p>
                                    @error('applicable_products') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <!-- Is Active -->
                                <div class="md:col-span-2">
                                    <label class="flex items-center">
                                        <input wire:model="is_active" type="checkbox" class="rounded">
                                        <span class="ml-2 text-sm font-medium text-gray-700">Active</span>
                                    </label>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end gap-3">
                                <button type="button" wire:click="closeModal" class="btn bg-gray-300 hover:bg-gray-400 text-gray-800">
                                    Cancel
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    {{ $editMode ? 'Update' : 'Create' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: @entangle('showDeleteModal') }">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black opacity-50" @click="show = false"></div>

                <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold">Delete Coupon</h3>
                            <button wire:click="closeDeleteModal" class="text-gray-500 hover:text-gray-700">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Are you sure you want to delete this coupon? This action cannot be undone.
                            </p>
                        </div>
                        <div class="mt-6 flex justify-end gap-3">
                            <button wire:click="closeDeleteModal" class="btn bg-gray-300 hover:bg-gray-400 text-gray-800">
                                Cancel
                            </button>
                            <button wire:click="deleteCoupon" class="btn bg-red-500 hover:bg-red-600 text-white">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
