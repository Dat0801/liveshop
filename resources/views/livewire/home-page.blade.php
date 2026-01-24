<div class="bg-white">
    <!-- Hero Banner -->
    <div class="relative bg-gradient-to-r from-gray-900 to-gray-800 text-white overflow-hidden">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=1200&h=400&fit=crop" 
                 alt="Summer Tech & Style Festival" 
                 class="w-full h-full object-cover opacity-40">
        </div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-32">
            <div class="max-w-2xl">
                <span class="inline-block bg-orange-500 text-white text-xs font-bold px-3 py-1 rounded mb-4">TRENDING NOW</span>
                <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold mb-4">Summer Tech & Style Festival</h1>
                <p class="text-lg mb-8 text-gray-200">Get up to 40% off on the latest seasonal arrivals. Join our live unboxing at 6 PM.</p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-8 rounded transition duration-200">
                        Shop the Drop
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                    <button class="inline-flex items-center justify-center bg-white text-gray-900 hover:bg-gray-100 font-bold py-3 px-8 rounded transition duration-200">
                        Notify Me
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Categories -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h2 class="text-3xl font-bold mb-12">Top Categories</h2>
        
        <div class="grid grid-cols-4 sm:grid-cols-5 lg:grid-cols-8 gap-6">
            @forelse($categories as $category)
                <a href="{{ route('products.index') }}?category={{ $category->id }}" class="flex flex-col items-center group">
                    <div class="w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center group-hover:bg-orange-200 transition mb-3">
                        <svg class="w-10 h-10 text-orange-500" fill="currentColor" viewBox="0 0 24 24">
                            @switch($loop->index % 8)
                                @case(0)
                                    <path d="M13.5 6H3V5h10.5v1zm0 2H3v1h10.5v-1zm0 2H3v1h10.5v-1zM17 9h-4v1h4V9zm0 2h-4v1h4v-1zm0-4h-4v1h4V7zm4.5-2H21v10.5h1V5z"/>
                                @break
                                @case(1)
                                    <path d="M7.5 2C4.46 2 2 4.46 2 7.5S4.46 13 7.5 13 13 10.54 13 7.5 10.54 2 7.5 2zm0 11C5.57 13 4 11.43 4 9.5S5.57 6 7.5 6 11 7.57 11 9.5 9.43 13 7.5 13z"/>
                                @break
                                @case(2)
                                    <path d="M21 3H3c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h18c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H3V5h18v14zm-5.04-6.71l-2.75 3.54-2.96-3.83c-.375-.48-.998-.48-1.373 0L6 16.5h12l-3.54-4.71c-.375-.48-.998-.48-1.373 0z"/>
                                @break
                                @case(3)
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z"/>
                                @break
                                @case(4)
                                    <path d="M17.5 7H15V5.5c0-.83-.67-1.5-1.5-1.5h-5c-.83 0-1.5.67-1.5 1.5V7H6.5c-.83 0-1.5.67-1.5 1.5v8c0 .83.67 1.5 1.5 1.5h11c.83 0 1.5-.67 1.5-1.5v-8c0-.83-.67-1.5-1.5-1.5zM9 5.5h6V7H9v-1.5zm8.5 12h-11v-8h11v8z"/>
                                @break
                                @case(5)
                                    <path d="M11.99 5V1h-1v4H8.01V1H7v4c-1.1 0-1.89.9-1.89 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2h-.01V1h-1v4h-3.99zm9 15H7V10h13.99v10z"/>
                                @break
                                @case(6)
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                                @break
                                @default
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            @endswitch
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-center text-gray-700 group-hover:text-orange-500">{{ $category->name }}</p>
                </a>
            @empty
                <div class="col-span-full text-center py-8">
                    <p class="text-gray-500">No categories available</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Live Now Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold">Live Now</h2>
            <span class="inline-block bg-red-200 text-red-700 text-xs font-bold px-3 py-1 rounded">24 ACTIVE</span>
            <a href="#" class="text-orange-500 font-semibold hover:text-orange-600">See All Streams →</a>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @for($i = 0; $i < 4; $i++)
                <div class="relative rounded-lg overflow-hidden bg-gray-200 h-80 group cursor-pointer">
                    <div class="w-full h-full bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center">
                        <svg class="w-20 h-20 text-white opacity-50" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                    </div>
                    
                    <div class="absolute top-4 left-4 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded flex items-center">
                        <span class="inline-block w-2 h-2 bg-white rounded-full mr-2"></span>
                        LIVE
                    </div>
                    
                    <div class="absolute top-4 right-4 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M15 8c0 3.31-2.69 6-6 6s-6-2.69-6-6 2.69-6 6-6 6 2.69 6 6zm4 0c0 5.25-4.25 9.5-9.5 9.5S.5 13.25.5 8 4.75-1.5 10-1.5s9.5 4.25 9.5 9.5z"/>
                        </svg>
                        @switch($i)
                            @case(0)1.2k
                            @break
                            @case(1)850
                            @break
                            @case(2)2.4k
                            @break
                            @default5.1k
                        @endswitch
                    </div>
                    
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-4">
                        <p class="text-white font-semibold text-sm line-clamp-2">
                            @switch($i)
                                @case(0)Tech Unboxing: The Ultra S24 Pro
                                @break
                                @case(1)Summer Essentials: Wardrobe Refresh
                                @break
                                @case(2)Live Kitchen: Master the Perfect Steak
                                @break
                                @default Pro Gaming Gear Review
                            @endswitch
                        </p>
                        <p class="text-gray-300 text-xs">
                            @switch($i)
                                @case(0)Alex TechLab
                                @break
                                @case(1)Sarah Styles
                                @break
                                @case(2)Chef Marcus
                                @break
                                @default GameCore
                            @endswitch
                        </p>
                    </div>
                </div>
            @endfor
        </div>
    </div>

    <!-- Flash Sales Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 bg-orange-50 rounded-lg">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold">FLASH SALES</h2>
            <p class="text-gray-600">Don't blink or you'll miss these exclusive deals!</p>
            <div class="flex gap-2">
                <span class="inline-flex items-center justify-center w-12 h-12 bg-orange-500 text-white font-bold rounded-full">04</span>
                <span class="inline-flex items-center justify-center w-12 h-12 bg-orange-500 text-white font-bold rounded-full">22</span>
                <span class="inline-flex items-center justify-center w-12 h-12 bg-orange-500 text-white font-bold rounded-full">15</span>
            </div>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($flashSaleProducts as $product)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="relative aspect-square bg-gray-200 overflow-hidden">
                        @if($product->images && count($product->images) > 0)
                            <img src="{{ $product->images[0] }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-300">
                                <svg class="w-20 h-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                        
                        @if($product->hasDiscount())
                            <span class="absolute top-3 left-3 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">
                                -{{ $product->getDiscountPercentage() }}%
                            </span>
                        @endif
                    </div>
                    
                    <div class="p-4">
                        <h3 class="text-sm font-semibold text-gray-900 mb-2 line-clamp-2">{{ $product->name }}</h3>
                        
                        <div class="flex items-baseline gap-2 mb-3">
                            @if($product->hasDiscount())
                                <span class="text-xl font-bold text-orange-600">${{ number_format($product->discount_price, 2) }}</span>
                                <span class="text-sm text-gray-500 line-through">${{ number_format($product->base_price, 2) }}</span>
                            @else
                                <span class="text-xl font-bold text-orange-600">${{ number_format($product->base_price, 2) }}</span>
                            @endif
                        </div>
                        
                        <p class="text-xs text-gray-600 mb-3">
                            @if($product->stock_quantity > 0)
                                {{ $product->stock_quantity }}% SOLD
                            @else
                                OUT OF STOCK
                            @endif
                        </p>
                        
                        <a href="{{ route('products.show', $product->slug) }}" class="block w-full text-center bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 rounded transition">
                            View Deal
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-8">
                    <p class="text-gray-500">No flash sale products available</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Recommended For You Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold">Recommended for You</h2>
            <div class="flex gap-2">
                <button class="p-2 hover:bg-gray-100 rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <button class="p-2 hover:bg-gray-100 rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
            @forelse($recommendedProducts as $product)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="relative aspect-square bg-gray-200 overflow-hidden">
                        @if($product->images && count($product->images) > 0)
                            <img src="{{ $product->images[0] }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-300">
                                <svg class="w-20 h-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                    </div>
                    
                    <div class="p-4">
                        <p class="text-xs text-gray-500 mb-1">{{ $product->category->name ?? 'Category' }}</p>
                        <h3 class="text-sm font-semibold text-gray-900 mb-2 line-clamp-2">{{ $product->name }}</h3>
                        
                        <div class="flex items-center gap-1 mb-3">
                            @for($i = 0; $i < 5; $i++)
                                <svg class="w-4 h-4 {{ $i < 4 ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            @endfor
                            <span class="text-xs text-gray-600 ml-1">({{ rand(10, 100) }})</span>
                        </div>
                        
                        <p class="text-lg font-bold text-gray-900">${{ number_format($product->base_price, 2) }}</p>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-8">
                    <p class="text-gray-500">No recommended products available</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Newsletter Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="bg-gradient-to-r from-orange-50 to-orange-100 rounded-lg p-12 text-center">
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Newsletter</h3>
            <p class="text-gray-700 mb-6">Get the latest on new streams and drops</p>
            <div class="flex max-w-md mx-auto gap-2">
                <input type="email" placeholder="Email" class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                <button class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-8 rounded-lg transition">
                    Join
                </button>
            </div>
        </div>
    </div>
</div>
