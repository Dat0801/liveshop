<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Filters and Search -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" 
                           wire:model.live.debounce.300ms="search"
                           placeholder="Search products..." 
                           class="input">
                </div>

                <!-- Category Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <select wire:model.live="category" class="input">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }} ({{ $cat->active_products_count }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Sort -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                    <select wire:model.live="sortBy" class="input">
                        <option value="latest">Latest</option>
                        <option value="price_low">Price: Low to High</option>
                        <option value="price_high">Price: High to Low</option>
                        <option value="name">Name: A to Z</option>
                        <option value="popular">Popular</option>
                    </select>
                </div>
            </div>

            <!-- Price Range -->
            <div class="mt-4 flex items-center space-x-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Price Range: ${{ $minPrice }} - ${{ $maxPrice }}</label>
                    <div class="flex items-center space-x-4">
                        <input type="range" 
                               wire:model.live.debounce.500ms="minPrice" 
                               min="0" 
                               max="10000" 
                               step="10"
                               class="flex-1">
                        <input type="range" 
                               wire:model.live.debounce.500ms="maxPrice" 
                               min="0" 
                               max="10000" 
                               step="10"
                               class="flex-1">
                    </div>
                </div>
                <button wire:click="clearFilters" class="btn btn-secondary mt-6">
                    Clear Filters
                </button>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($products as $product)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <!-- Product Image -->
                    <a href="{{ route('products.show', $product->slug) }}" class="block relative">
                        <div class="aspect-w-1 aspect-h-1 bg-gray-200">
                            @if($product->images && count($product->images) > 0)
                                <img src="{{ $product->images[0] }}" alt="{{ $product->name }}" class="w-full h-64 object-cover">
                            @else
                                <div class="w-full h-64 flex items-center justify-center bg-gray-300">
                                    <svg class="w-20 h-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        
                        @if($product->hasDiscount())
                            <span class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">
                                -{{ $product->getDiscountPercentage() }}%
                            </span>
                        @endif
                        
                        @if($product->is_featured)
                            <span class="absolute top-2 left-2 bg-yellow-500 text-white text-xs font-bold px-2 py-1 rounded">
                                Featured
                            </span>
                        @endif
                    </a>

                    <!-- Product Info -->
                    <div class="p-4">
                        <p class="text-xs text-gray-500 mb-1">{{ $product->category->name }}</p>
                        <a href="{{ route('products.show', $product->slug) }}" class="block">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2 hover:text-primary-600 line-clamp-2">
                                {{ $product->name }}
                            </h3>
                        </a>
                        
                        <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                            {{ $product->short_description }}
                        </p>

                        <!-- Price -->
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                @if($product->hasDiscount())
                                    <span class="text-xl font-bold text-primary-600">
                                        ${{ number_format($product->discount_price, 2) }}
                                    </span>
                                    <span class="text-sm text-gray-500 line-through ml-2">
                                        ${{ number_format($product->base_price, 2) }}
                                    </span>
                                @else
                                    <span class="text-xl font-bold text-primary-600">
                                        ${{ number_format($product->base_price, 2) }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Stock Status -->
                        @if($product->stock_quantity > 0)
                            <p class="text-xs text-green-600 mb-3">In Stock ({{ $product->stock_quantity }})</p>
                        @else
                            <p class="text-xs text-red-600 mb-3">Out of Stock</p>
                        @endif

                        <!-- Add to Cart Button -->
                        <a href="{{ route('products.show', $product->slug) }}" class="btn btn-primary w-full text-center">
                            View Details
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">No products found</h3>
                    <p class="text-gray-500">Try adjusting your filters or search terms</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $products->links() }}
        </div>
    </div>
</div>
