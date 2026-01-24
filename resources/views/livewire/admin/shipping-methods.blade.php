<div>
    <!-- Header -->
    <div class="mb-6">
        <div class="flex justify-between items-start mb-4">
            <div>
                <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
                    <a href="#" class="hover:text-orange-500">Dashboard</a>
                    <span>/</span>
                    <a href="#" class="hover:text-orange-500">Settings</a>
                    <span>/</span>
                    <span class="text-gray-900">Shipping Methods</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">Shipping Methods</h1>
                <p class="text-gray-600 mt-1">Configure and manage your store's shipping carriers and pricing rules.</p>
            </div>
            <button wire:click="openCreateModal" class="bg-orange-500 hover:bg-orange-600 text-white px-5 py-2.5 rounded-lg font-medium flex items-center gap-2 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Shipping Method
            </button>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-lg p-5 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Carriers</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $totalCarriers }}</p>
                    </div>
                    <div class="bg-orange-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-5 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Default Method</p>
                        <p class="text-lg font-bold text-gray-900">{{ $defaultMethod ? $defaultMethod->name : 'N/A' }}</p>
                    </div>
                    <div class="bg-orange-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-5 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Last Sync</p>
                        <p class="text-lg font-bold text-gray-900">2 mins ago</p>
                    </div>
                    <div class="bg-orange-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="flex gap-3 mb-6">
            <select wire:model.live="statusFilter" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white">
                <option value="all">All Statuses</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
            <select wire:model.live="regionFilter" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white">
                <option value="all">All Regions</option>
                <option value="domestic">Domestic</option>
                <option value="international">International</option>
            </select>
        </div>
    </div>

    <!-- Shipping Methods Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left py-3.5 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Method Name</th>
                        <th class="text-left py-3.5 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Base Cost</th>
                        <th class="text-left py-3.5 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Est. Delivery</th>
                        <th class="text-left py-3.5 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="text-center py-3.5 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($methods as $method)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-3">
                                    @php
                                        $iconClass = match($method->icon ?? 'truck') {
                                            'truck' => 'M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0',
                                            'rocket' => 'M13 10V3L4 14h7v7l9-11h-7z',
                                            'globe' => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                                            'lightning' => 'M13 10V3L4 14h7v7l9-11h-7z',
                                            default => 'M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0',
                                        };
                                        $iconBg = match($method->icon ?? 'truck') {
                                            'truck' => 'bg-blue-100',
                                            'rocket' => 'bg-orange-100',
                                            'globe' => 'bg-yellow-100',
                                            'lightning' => 'bg-orange-100',
                                            default => 'bg-blue-100',
                                        };
                                        $iconColor = match($method->icon ?? 'truck') {
                                            'truck' => 'text-blue-600',
                                            'rocket' => 'text-orange-600',
                                            'globe' => 'text-yellow-600',
                                            'lightning' => 'text-orange-600',
                                            default => 'text-blue-600',
                                        };
                                    @endphp
                                    <div class="{{ $iconBg }} p-2.5 rounded-lg">
                                        <svg class="w-5 h-5 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconClass }}" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $method->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $method->description }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <span class="text-gray-900 font-semibold">${{ number_format($method->rate, 2) }}</span>
                            </td>
                            <td class="py-4 px-4">
                                <span class="text-gray-700">
                                    @if($method->delivery_text)
                                        {{ $method->delivery_text }}
                                    @else
                                        {{ $method->processing_days_min }} - {{ $method->processing_days_max }} Business Days
                                    @endif
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" wire:click="toggleStatus({{ $method->id }})" {{ $method->is_active ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-500"></div>
                                </label>
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex items-center justify-center gap-2">
                                    <button wire:click="openEditModal({{ $method->id }})" class="p-2 text-gray-600 hover:text-orange-600 hover:bg-orange-50 rounded-lg transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </button>
                                    <button wire:click="confirmDelete({{ $method->id }})" class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                <p class="mt-3 text-gray-500 text-sm">No shipping methods found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        <div class="px-4 py-3 border-t border-gray-200 flex items-center justify-between">
            <p class="text-sm text-gray-600">
                Showing {{ $methods->firstItem() ?? 0 }}-{{ $methods->lastItem() ?? 0 }} of {{ $methods->total() }} results
            </p>
            <div class="flex gap-2">
                {{ $methods->links() }}
            </div>
        </div>
    </div>

    <!-- Help Section -->
    <div class="mt-6 bg-orange-50 border border-orange-200 rounded-lg p-6 flex items-start gap-4">
        <div class="bg-orange-100 p-3 rounded-lg">
            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
            </svg>
        </div>
        <div>
            <h3 class="font-bold text-gray-900 mb-1">Need help with shipping zones?</h3>
            <p class="text-sm text-gray-700 mb-3">You can set up custom pricing rules based on customer location, order weight, or total purchase value in the advanced settings of each carrier.</p>
            <a href="#" class="text-orange-600 hover:text-orange-700 font-medium text-sm inline-flex items-center gap-1">
                View Documentation
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </a>
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

                                <div class="grid grid-cols-4 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Icon</label>
                                        <select wire:model="icon" class="input">
                                            <option value="truck">Truck</option>
                                            <option value="rocket">Rocket</option>
                                            <option value="globe">Globe</option>
                                            <option value="lightning">Lightning</option>
                                        </select>
                                    </div>
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
                                        <input type="number" step="0.01" wire:model.number="rate" class="input">
                                        @error('rate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Order Priority</label>
                                        <input type="number" wire:model.number="display_order" class="input">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Delivery Text</label>
                                    <input type="text" wire:model="delivery_text" class="input" placeholder="e.g., 3-5 Business Days or Same Day (Order by 12 PM)">
                                    <p class="text-xs text-gray-500 mt-1">Custom delivery time display (optional)</p>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Min Order</label>
                                        <input type="number" step="0.01" wire:model.number="min_order" class="input" placeholder="0.00">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Max Order</label>
                                        <input type="number" step="0.01" wire:model.number="max_order" class="input" placeholder="No limit">
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Min Weight (g)</label>
                                        <input type="number" wire:model.number="min_weight" class="input" placeholder="0">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Max Weight (g)</label>
                                        <input type="number" wire:model.number="max_weight" class="input" placeholder="No limit">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Processing Days</label>
                                        <div class="flex gap-2">
                                            <input type="number" wire:model.number="processing_days_min" class="input" placeholder="Min">
                                            <input type="number" wire:model.number="processing_days_max" class="input" placeholder="Max">
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
