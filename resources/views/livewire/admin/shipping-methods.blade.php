<div>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Shipping Methods</h2>
        <button wire:click="openCreateModal" class="btn btn-primary">
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add Method
        </button>
    </div>

    <!-- Search -->
    <div class="mb-6">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search shipping methods..." class="input w-full">
    </div>

    <!-- Methods Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left py-3 px-4">Name</th>
                        <th class="text-center py-3 px-4">Code</th>
                        <th class="text-center py-3 px-4">Type</th>
                        <th class="text-center py-3 px-4">Rate</th>
                        <th class="text-center py-3 px-4">Days</th>
                        <th class="text-center py-3 px-4">Status</th>
                        <th class="text-center py-3 px-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($methods as $method)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4">
                                <p class="font-semibold">{{ $method->name }}</p>
                                <p class="text-sm text-gray-600">{{ Str::limit($method->description, 50) }}</p>
                            </td>
                            <td class="py-3 px-4 text-center font-mono">{{ $method->code }}</td>
                            <td class="py-3 px-4 text-center">
                                <span class="px-2 py-1 rounded text-xs font-semibold bg-gray-100 text-gray-800">
                                    {{ ucfirst(str_replace('-', ' ', $method->type)) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center font-semibold">
                                {{ $method->type === 'percentage' ? $method->rate . '%' : '$' . number_format($method->rate, 2) }}
                            </td>
                            <td class="py-3 px-4 text-center text-sm">
                                {{ $method->processing_days_min }}-{{ $method->processing_days_max }} days
                            </td>
                            <td class="py-3 px-4 text-center">
                                <button wire:click="toggleStatus({{ $method->id }})" 
                                        class="px-3 py-1 rounded-full text-sm font-medium transition-colors
                                            {{ $method->is_active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                    {{ $method->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <button wire:click="openEditModal({{ $method->id }})" class="text-blue-600 hover:text-blue-800 mr-3">
                                    <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button wire:click="confirmDelete({{ $method->id }})" class="text-red-600 hover:text-red-800">
                                    <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-gray-500">No shipping methods found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t">
            {{ $methods->links() }}
        </div>
    </div>

    <!-- Shipping Method Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: @entangle('showModal') }">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black opacity-50" @click="show = false"></div>

                <div class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold">{{ $editMode ? 'Edit Shipping Method' : 'Add Shipping Method' }}</h3>
                            <button wire:click="closeModal" class="text-gray-500 hover:text-gray-700">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <form wire:submit.prevent="save">
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Name *</label>
                                        <input type="text" wire:model="name" class="input">
                                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Code *</label>
                                        <input type="text" wire:model="code" class="input" placeholder="e.g., STANDARD">
                                        @error('code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                    <textarea wire:model="description" rows="2" class="input"></textarea>
                                </div>

                                <div class="grid grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Type *</label>
                                        <select wire:model="type" class="input">
                                            <option value="flat">Flat Rate</option>
                                            <option value="weight-based">Weight-Based</option>
                                            <option value="amount-based">Amount-Based %</option>
                                        </select>
                                        @error('type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Rate *</label>
                                        <input type="number" step="0.01" wire:model="rate" class="input">
                                        @error('rate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Order Priority</label>
                                        <input type="number" wire:model="display_order" class="input">
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Min Order</label>
                                        <input type="number" step="0.01" wire:model="min_order" class="input" placeholder="0.00">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Max Order</label>
                                        <input type="number" step="0.01" wire:model="max_order" class="input" placeholder="No limit">
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Min Weight (g)</label>
                                        <input type="number" wire:model="min_weight" class="input" placeholder="0">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Max Weight (g)</label>
                                        <input type="number" wire:model="max_weight" class="input" placeholder="No limit">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Processing Days</label>
                                        <div class="flex gap-2">
                                            <input type="number" wire:model="processing_days_min" class="input" placeholder="Min">
                                            <input type="number" wire:model="processing_days_max" class="input" placeholder="Max">
                                        </div>
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
                        <h3 class="text-lg font-bold mb-4">Delete Shipping Method</h3>
                        <p class="text-gray-700 mb-6">Are you sure? This cannot be undone.</p>
                        <div class="flex justify-end gap-3">
                            <button type="button" wire:click="closeDeleteModal" class="btn">Cancel</button>
                            <button type="button" wire:click="deleteMethod" class="btn btn-danger">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
