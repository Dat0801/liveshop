<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">My Wishlist</h1>
            <p class="text-gray-600">Save your favorite products for later</p>
        </div>

        @if(session()->has('message'))
            <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('message') }}
            </div>
        @endif

        @if($wishlists->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($wishlists as $wishlist)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition group">
                        <a href="{{ route('products.show', $wishlist->product->slug) }}" class="block">
                            <div class="relative bg-gray-200 h-48 flex items-center justify-center overflow-hidden">
                                @if($wishlist->product->images && count($wishlist->product->images) > 0)
                                    <img src="{{ $wishlist->product->images[0] }}" alt="{{ $wishlist->product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="text-gray-500">No Image</div>
                                @endif
                                
                                @if($wishlist->product->hasDiscount())
                                    <span class="absolute top-2 left-2 px-2 py-1 bg-red-500 text-white text-xs font-semibold rounded">
                                        -{{ $wishlist->product->getDiscountPercentage() }}%
                                    </span>
                                @endif
                            </div>
                        </a>
                        
                        <div class="p-4">
                            <a href="{{ route('products.show', $wishlist->product->slug) }}" class="block mb-2">
                                <h3 class="font-semibold text-gray-900 text-sm hover:text-orange-500 transition line-clamp-2">
                                    {{ $wishlist->product->name }}
                                </h3>
                            </a>
                            
                            <p class="text-gray-600 text-xs mb-3">{{ $wishlist->product->category->name }}</p>
                            
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <span class="text-lg font-bold text-orange-500">
                                        ${{ number_format($wishlist->product->getCurrentPrice(), 2) }}
                                    </span>
                                    @if($wishlist->product->hasDiscount())
                                        <span class="text-sm text-gray-400 line-through ml-2">
                                            ${{ number_format($wishlist->product->base_price, 2) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="flex gap-2">
                                <a href="{{ route('products.show', $wishlist->product->slug) }}" 
                                   class="flex-1 px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold rounded-lg transition text-center">
                                    View Product
                                </a>
                                <button wire:click="removeFromWishlist({{ $wishlist->id }})" 
                                        wire:confirm="Remove this item from your wishlist?"
                                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $wishlists->links() }}
            </div>
        @else
            <div class="text-center py-16 bg-white rounded-lg shadow-md">
                <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Your wishlist is empty</h3>
                <p class="text-gray-600 mb-6">Start adding products you love to your wishlist!</p>
                <a href="{{ route('products.index') }}" class="inline-block px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-lg transition">
                    Browse Products
                </a>
            </div>
        @endif
    </div>
</div>
