<div class="bg-gray-50">
    <div class="border-b border-gray-200 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <a href="/" class="hover:text-orange-500">Home</a>
                <span>/</span>
                <a href="{{ route('products.index') }}" class="hover:text-orange-500">Products</a>
                <span>/</span>
                <span class="text-gray-900 font-semibold">Audio &amp; Headphones</span>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex flex-col lg:flex-row gap-8">
            <aside class="w-full lg:w-72 flex-shrink-0">
                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 lg:sticky lg:top-24">
                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <p class="text-xs font-semibold text-orange-500 uppercase tracking-wide">Filters</p>
                            <h3 class="text-lg font-bold text-gray-900">Refine results</h3>
                        </div>
                        <button wire:click="clearFilters" class="text-sm font-semibold text-orange-500 hover:text-orange-600">
                            Clear all
                        </button>
                    </div>

                    <div class="space-y-8">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Category</p>
                            <div class="space-y-2">
                                <label class="flex items-center justify-between px-3 py-2 rounded-xl border border-gray-200 hover:border-orange-400 transition">
                                    <div class="flex items-center gap-3">
                                        <input type="radio" value="" wire:model.live="category" class="rounded-full text-orange-500 border-gray-300 focus:ring-orange-500">
                                        <span class="text-sm font-medium text-gray-800">All categories</span>
                                    </div>
                                    <span class="text-xs text-gray-400">{{ $categories->sum('active_products_count') }}</span>
                                </label>
                                @foreach($categories as $cat)
                                    <label class="flex items-center justify-between px-3 py-2 rounded-xl border border-gray-200 hover:border-orange-400 transition">
                                        <div class="flex items-center gap-3">
                                            <input type="radio" value="{{ $cat->id }}" wire:model.live="category" class="rounded-full text-orange-500 border-gray-300 focus:ring-orange-500">
                                            <span class="text-sm font-medium text-gray-800">{{ $cat->name }}</span>
                                        </div>
                                        <span class="text-xs text-gray-400">{{ $cat->active_products_count }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Price range</p>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between text-sm text-gray-700">
                                    <span>${{ number_format((float) $minPrice, 0) }}</span>
                                    <span class="text-gray-400">to</span>
                                    <span>${{ number_format((float) $maxPrice, 0) }}</span>
                                </div>
                                <div class="space-y-3">
                                    <input type="range" 
                                           wire:model.live.debounce.500ms="minPrice" 
                                           min="0" 
                                           max="10000" 
                                           step="10"
                                           class="w-full accent-orange-500">
                                    <input type="range" 
                                           wire:model.live.debounce.500ms="maxPrice" 
                                           min="0" 
                                           max="10000" 
                                           step="10"
                                           class="w-full accent-orange-500">
                                </div>
                            </div>
                        </div>

                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Search</p>
                            <div class="relative">
                                <input type="text" 
                                       wire:model.live.debounce.300ms="search"
                                       placeholder="Search for products, brands and more..." 
                                       class="input pr-10">
                                <svg class="absolute right-3 top-3 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>

            <section class="flex-1 space-y-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Audio &amp; Headphones</h1>
                        <p class="text-sm text-gray-500">{{ $products->total() }} results found</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-gray-500">Sort by</span>
                        <select wire:model.live="sortBy" class="input w-48 bg-white border-gray-200 focus:ring-orange-500 focus:border-orange-500">
                            <option value="popular">Popularity</option>
                            <option value="latest">Latest</option>
                            <option value="price_low">Price: Low to High</option>
                            <option value="price_high">Price: High to Low</option>
                            <option value="name">Name: A to Z</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @forelse($products as $product)
                        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm hover:shadow-lg hover:-translate-y-1 transition transform">
                            <a href="{{ route('products.show', $product->slug) }}" class="block relative bg-gray-50">
                                <div class="aspect-[4/5] overflow-hidden flex items-center justify-center">
                                    @if($product->images && count($product->images) > 0)
                                        <img src="{{ $product->images[0] }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                            <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                @if($product->hasDiscount())
                                    <span class="absolute top-3 left-3 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                                        SALE
                                    </span>
                                @endif

                                @if($product->is_featured)
                                    <span class="absolute top-3 right-3 bg-orange-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                                        NEW
                                    </span>
                                @endif
                            </a>

                            <div class="p-4 space-y-3">
                                <div class="flex items-center justify-between text-xs text-gray-500">
                                    <span class="font-semibold text-gray-800">{{ $product->category->name }}</span>
                                    @if($product->stock_quantity > 0)
                                        <span class="text-green-600 font-semibold">In stock</span>
                                    @else
                                        <span class="text-red-600 font-semibold">Out of stock</span>
                                    @endif
                                </div>

                                <a href="{{ route('products.show', $product->slug) }}">
                                    <h3 class="text-base font-semibold text-gray-900 hover:text-orange-600 transition line-clamp-2">{{ $product->name }}</h3>
                                </a>

                                @if($product->short_description)
                                    <p class="text-sm text-gray-500 line-clamp-2">{{ $product->short_description }}</p>
                                @endif

                                <div class="flex items-center gap-3">
                                    @if($product->hasDiscount())
                                        <span class="text-lg font-bold text-orange-600">${{ number_format($product->discount_price, 2) }}</span>
                                        <span class="text-sm text-gray-400 line-through">${{ number_format($product->base_price, 2) }}</span>
                                    @else
                                        <span class="text-lg font-bold text-orange-600">${{ number_format($product->base_price, 2) }}</span>
                                    @endif
                                </div>

                                <a href="{{ route('products.show', $product->slug) }}" class="w-full inline-flex items-center justify-center bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2.5 rounded-xl transition">
                                    View details
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12 bg-white rounded-2xl border border-gray-200">
                            <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">No products found</h3>
                            <p class="text-gray-500">Try adjusting your filters or search terms</p>
                        </div>
                    @endforelse
                </div>

                <div class="pt-4 flex justify-center">
                    {{ $products->links() }}
                </div>
            </section>
        </div>
    </div>
</div>
