<div>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Product Management</h2>
        <div class="flex gap-3">
            <button wire:click="$toggle('showTrashed')" 
                    class="btn {{ $showTrashed ? 'bg-gray-700 text-white' : 'bg-gray-200 text-gray-700' }}">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                {{ $showTrashed ? 'Show Active' : 'Show Deleted' }}
            </button>
            <button wire:click="openCreateModal" class="btn btn-primary">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Product
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <input type="text" 
                       wire:model.live.debounce.300ms="search"
                       placeholder="Search products..." 
                       class="input">
            </div>
            <div>
                <select wire:model.live="categoryFilter" class="input">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left py-3 px-4">Product</th>
                        <th class="text-left py-3 px-4">Category</th>
                        <th class="text-center py-3 px-4">Price</th>
                        <th class="text-center py-3 px-4">Stock</th>
                        <th class="text-center py-3 px-4">Status</th>
                        <th class="text-center py-3 px-4">Featured</th>
                        <th class="text-center py-3 px-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr class="border-b hover:bg-gray-50 {{ $showTrashed ? 'bg-red-50' : '' }}">
                            <td class="py-3 px-4">
                                <div>
                                    <p class="font-semibold {{ $showTrashed ? 'line-through text-gray-500' : '' }}">
                                        {{ $product->name }}
                                    </p>
                                    <p class="text-sm text-gray-600">SKU: {{ $product->sku }}</p>
                                </div>
                            </td>
                            <td class="py-3 px-4">{{ $product->category->name }}</td>
                            <td class="py-3 px-4 text-center">
                                ${{ number_format($product->getCurrentPrice(), 2) }}
                                @if($product->hasDiscount())
                                    <br><span class="text-xs text-gray-500 line-through">${{ number_format($product->base_price, 2) }}</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="px-2 py-1 rounded-full text-sm
                                    {{ $product->stock_quantity === 0 ? 'bg-red-100 text-red-800' : ($product->stock_quantity <= 10 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                    {{ $product->stock_quantity }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if(!$showTrashed)
                                    <button wire:click="toggleStatus({{ $product->id }})" 
                                            class="px-3 py-1 rounded-full text-sm font-medium transition-colors
                                                {{ $product->is_active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                @else
                                    <span class="px-2 py-1 rounded-full text-sm bg-gray-100 text-gray-600">
                                        Deleted
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if(!$showTrashed)
                                    <button wire:click="toggleFeatured({{ $product->id }})" 
                                            class="px-3 py-1 rounded-full text-sm font-medium transition-colors
                                                {{ $product->is_featured ? 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                        {{ $product->is_featured ? '⭐ Featured' : 'Not Featured' }}
                                    </button>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($showTrashed)
                                    <button wire:click="restoreProduct({{ $product->id }})" 
                                            class="text-green-600 hover:text-green-800 mr-3"
                                            title="Restore Product">
                                        <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                    </button>
                                    <button wire:click="confirmDelete({{ $product->id }})" 
                                            class="text-red-600 hover:text-red-800"
                                            title="Permanently Delete">
                                        <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                @else
                                    <button wire:click="openEditModal({{ $product->id }})" class="text-blue-600 hover:text-blue-800 mr-3">
                                        <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <a href="{{ route('admin.products.variants', ['product' => $product->id]) }}" class="text-purple-600 hover:text-purple-800 mr-3" title="Manage Variants">
                                        <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.products.media', ['product' => $product->id]) }}" class="text-indigo-600 hover:text-indigo-800 mr-3" title="Manage Media">
                                        <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </a>
                                    <button wire:click="confirmDelete({{ $product->id }})" 
                                            class="text-red-600 hover:text-red-800">
                                        <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-gray-500">
                                {{ $showTrashed ? 'No deleted products found' : 'No products found' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 border-t">
            {{ $products->links() }}
        </div>
    </div>

    <!-- Product Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: @entangle('showModal') }">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black opacity-50" @click="show = false"></div>

                <div class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold">{{ $editMode ? 'Edit Product' : 'Add Product' }}</h3>
                            <button wire:click="closeModal" class="text-gray-500 hover:text-gray-700">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <form wire:submit.prevent="save">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Product Name *</label>
                                    <input type="text" wire:model.blur="name" class="input">
                                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Slug *</label>
                                    <input type="text" wire:model="slug" class="input">
                                    @error('slug') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                                    <select wire:model="category_id" class="input">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Short Description</label>
                                    <textarea wire:model="short_description" rows="2" class="input"></textarea>
                                    @error('short_description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                    <textarea wire:model="description" rows="4" class="input"></textarea>
                                    @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Base Price *</label>
                                        <input type="number" step="0.01" wire:model="base_price" class="input">
                                        @error('base_price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Discount Price</label>
                                        <input type="number" step="0.01" wire:model="discount_price" class="input">
                                        @error('discount_price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">SKU *</label>
                                        <input type="text" wire:model="sku" class="input">
                                        @error('sku') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Stock Quantity *</label>
                                        <input type="number" wire:model="stock_quantity" class="input">
                                        @error('stock_quantity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="flex items-center space-x-6">
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="is_active" class="mr-2">
                                        <span class="text-sm">Active</span>
                                    </label>

                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="is_featured" class="mr-2">
                                        <span class="text-sm">Featured</span>
                                    </label>
                                </div>
                            </div>

                            <div class="flex justify-end space-x-3 mt-6">
                                <button type="button" wire:click="closeModal" class="btn btn-secondary">Cancel</button>
                                <button type="submit" class="btn btn-primary">{{ $editMode ? 'Update' : 'Create' }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
             x-data="{ show: @entangle('showDeleteModal') }"
             x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4"
                 @click.away="$wire.closeDeleteModal()"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-90"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-90">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full bg-red-100 flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            {{ $showTrashed ? 'Permanently Delete Product?' : 'Delete Product?' }}
                        </h3>
                    </div>
                </div>

                <p class="text-gray-600 mb-6">
                    @if($showTrashed)
                        This action cannot be undone. The product will be permanently removed from the database.
                    @else
                        This will move the product to trash. You can restore it later if needed.
                    @endif
                </p>

                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            wire:click="closeDeleteModal" 
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        Cancel
                    </button>
                    <button type="button" 
                            wire:click="{{ $showTrashed ? 'forceDeleteProduct(' . $productToDelete . ')' : 'deleteProduct()' }}" 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        {{ $showTrashed ? 'Delete Permanently' : 'Move to Trash' }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
