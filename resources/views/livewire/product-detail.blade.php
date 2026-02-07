<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if (session()->has('message'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded" 
                 x-data="{ show: true }" 
                 x-show="show" 
                 x-init="setTimeout(() => show = false, 3000)">
                {{ session('message') }}
            </div>
        @endif

        <!-- Breadcrumb -->
        <div class="text-sm text-gray-600 mb-6">
            <a href="{{ route('products.index') }}" class="hover:text-primary-600">Home</a>
            <span class="mx-2">/</span>
            <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" class="hover:text-primary-600">{{ $product->category->name }}</a>
            <span class="mx-2">/</span>
            <span class="text-gray-900 font-semibold">{{ $product->name }}</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
            <!-- Product Images -->
            <div>
                <!-- Main Image -->
                <div class="bg-white rounded-lg overflow-hidden mb-4" x-data="{ mainImage: '{{ $product->images ? $product->images[0] : '' }}' }">
                    @if($product->images && count($product->images) > 0)
                        <img :src="mainImage" alt="{{ $product->name }}" class="w-full h-auto object-cover">
                    @else
                        <div class="w-full h-96 flex items-center justify-center bg-gray-200">
                            <svg class="w-32 h-32 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Thumbnail Images -->
                @if($product->images && count($product->images) > 1)
                    <div class="grid grid-cols-4 gap-3">
                        @foreach($product->images as $image)
                            <div class="bg-white rounded-lg overflow-hidden cursor-pointer border-2 border-transparent hover:border-orange-500 transition" 
                                 x-on:click="mainImage = '{{ $image }}'">
                                <img src="{{ $image }}" alt="{{ $product->name }}" class="w-full h-24 object-cover">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Product Details -->
            <div>
                <!-- TOP RATED Badge -->
                <div class="flex items-center gap-2 mb-3">
                    <span class="text-orange-500 font-bold text-sm">TOP RATED</span>
                </div>

                <!-- Title -->
                <h1 class="text-3xl font-bold text-gray-900 mb-3">{{ $product->name }}</h1>

                <!-- Rating -->
                @php
                    $averageRating = $product->getAverageRating();
                    $reviewsCount = $product->getReviewsCount();
                    $fullStars = floor($averageRating);
                    $hasHalfStar = ($averageRating - $fullStars) >= 0.5;
                @endphp
                <div class="flex items-center gap-2 mb-4">
                    <div class="flex gap-1">
                        @for($i = 0; $i < 5; $i++)
                            @if($i < $fullStars)
                                <svg class="w-5 h-5 text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @elseif($i == $fullStars && $hasHalfStar)
                                <svg class="w-5 h-5 text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                                    <defs>
                                        <linearGradient id="half-{{ $i }}">
                                            <stop offset="50%" stop-color="currentColor"/>
                                            <stop offset="50%" stop-color="#D1D5DB" stop-opacity="1"/>
                                        </linearGradient>
                                    </defs>
                                    <path fill="url(#half-{{ $i }})" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endif
                        @endfor
                    </div>
                    <span class="text-gray-700 text-sm">
                        @if($reviewsCount > 0)
                            {{ number_format($averageRating, 1) }} ({{ number_format($reviewsCount) }} {{ $reviewsCount == 1 ? 'Review' : 'Reviews' }})
                        @else
                            No reviews yet
                        @endif
                    </span>
                </div>

                <!-- Price -->
                <div class="mb-6">
                    <div class="flex items-baseline gap-3">
                        <span class="text-4xl font-bold text-orange-500">
                            ${{ number_format($currentPrice, 2) }}
                        </span>
                        @if($product->hasDiscount())
                            <span class="text-lg text-gray-400 line-through">
                                ${{ number_format($product->base_price, 2) }}
                            </span>
                        @endif
                    </div>
                    <p class="text-gray-600 text-sm mt-1">Inclusive of all taxes</p>
                </div>

                <!-- Color Variants -->
                @php
                    $colorVariants = $product->variants->where('type', 'color')->groupBy('value');
                @endphp
                @if($colorVariants->isNotEmpty())
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-3 capitalize">
                            COLOR: <span class="text-gray-500">{{ $selectedVariants['color'] ?? 'Midnight Black' }}</span>
                        </label>
                        <div class="flex gap-3">
                            @foreach($colorVariants as $value => $variants)
                                <button 
                                    wire:click="$set('selectedVariants.color', '{{ $value }}')"
                                    class="w-10 h-10 rounded-full border-2 transition
                                        {{ ($selectedVariants['color'] ?? '') === $value 
                                            ? 'border-gray-800' 
                                            : 'border-gray-300' }}"
                                    style="background-color: 
                                        @if(strtolower($value) === 'midnight black') #000
                                        @elseif(strtolower($value) === 'orange') #FF9500
                                        @elseif(strtolower($value) === 'light grey' || strtolower($value) === 'light gray') #D3D3D3
                                        @else #999
                                        @endif"
                                    title="{{ $value }}">
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Model Variants -->
                @php
                    $modelVariants = $product->variants->where('type', 'model')->groupBy('value');
                @endphp
                @if($modelVariants->isNotEmpty())
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-3 capitalize">
                            MODEL
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($modelVariants as $value => $variants)
                                <button 
                                    wire:click="$set('selectedVariants.model', '{{ $value }}')"
                                    class="p-4 border-2 rounded-lg transition text-center
                                        {{ ($selectedVariants['model'] ?? '') === $value 
                                            ? 'border-orange-500 bg-orange-50' 
                                            : 'border-gray-300 hover:border-orange-300' }}">
                                    <div class="font-semibold text-gray-900">{{ $value }}</div>
                                    @if($variants->first()->price_adjustment)
                                        <div class="text-sm text-gray-600">
                                            {{ $variants->first()->price_adjustment > 0 ? '+' : '' }}${{ number_format($variants->first()->price_adjustment, 2) }}
                                        </div>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Other Variants -->
                @php
                    $otherVariants = $product->variants->whereNotIn('type', ['color', 'model'])->groupBy('type');
                @endphp
                @if($otherVariants->isNotEmpty())
                    <div class="mb-6">
                        @foreach($otherVariants as $type => $variants)
                            <div class="mb-4">
                                <label class="block text-sm font-semibold text-gray-700 mb-2 capitalize">
                                    {{ $type }}
                                </label>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($variants as $variant)
                                        <button 
                                            wire:click="$set('selectedVariants.{{ $type }}', '{{ $variant->value }}')"
                                            class="px-4 py-2 border-2 rounded-lg transition
                                                {{ ($selectedVariants[$type] ?? '') === $variant->value 
                                                    ? 'border-orange-500 bg-orange-50 text-orange-600' 
                                                    : 'border-gray-300 hover:border-orange-300' }}">
                                            {{ $variant->value }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Wishlist Button -->
                <div class="mb-4">
                    @livewire('wishlist-button', ['product' => $product])
                </div>

                <!-- Buttons -->
                <div class="grid grid-cols-1 gap-3 mb-6">
                    <button 
                        wire:click="addToCart"
                        @if($product->stock_quantity <= 0) disabled @endif
                        class="w-full bg-orange-500 hover:bg-orange-600 disabled:bg-gray-400 text-white font-bold py-3 rounded-lg transition">
                        BUY NOW
                    </button>
                    <button 
                        wire:click="addToCart"
                        @if($product->stock_quantity <= 0) disabled @endif
                        class="w-full border-2 border-gray-800 text-gray-800 hover:bg-gray-50 disabled:opacity-50 font-bold py-3 rounded-lg transition flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        ADD TO CART
                    </button>
                </div>

                <!-- Stock Status -->
                @if($product->stock_quantity > 0)
                    <p class="text-green-600 font-semibold mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        In Stock ({{ $product->stock_quantity }} available)
                    </p>
                @else
                    <p class="text-red-600 font-semibold mb-4">Out of Stock</p>
                @endif

                <!-- Additional Info -->
                <div class="border-t pt-4 space-y-2">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-orange-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                            <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z" />
                        </svg>
                        <span class="text-sm text-gray-700">Free Shipping on orders over $50</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-orange-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 110-2H5a1 1 0 110-2h1V3a1 1 0 01-1-1zm0 0a2 2 0 110 4 2 2 0 010-4zm9-2a1 1 0 011 1v1h1a1 1 0 110 2h-1v1a1 1 0 110-2h-1V3a1 1 0 01-1-1zm0 0a2 2 0 110 4 2 2 0 010-4z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm text-gray-700">2-Year Manufacturer Warranty</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Description & Reviews Tabs -->
        <div class="bg-white rounded-lg shadow-md mb-12" x-data="{ activeTab: 'description' }">
            <div class="flex border-b">
                <button @click="activeTab = 'description'" :class="activeTab === 'description' ? 'border-b-2 border-orange-500 text-gray-900' : 'text-gray-500 hover:text-gray-700'" class="flex-1 py-4 px-6 font-semibold transition">
                    DESCRIPTION
                </button>
                <button @click="activeTab = 'specifications'" :class="activeTab === 'specifications' ? 'border-b-2 border-orange-500 text-gray-900' : 'text-gray-500 hover:text-gray-700'" class="flex-1 py-4 px-6 font-semibold transition">
                    SPECIFICATIONS
                </button>
                <button @click="activeTab = 'reviews'" :class="activeTab === 'reviews' ? 'border-b-2 border-orange-500 text-gray-900' : 'text-gray-500 hover:text-gray-700'" class="flex-1 py-4 px-6 font-semibold transition">
                    REVIEWS ({{ $product->getReviewsCount() }})
                </button>
                <button @click="activeTab = 'shipping'" :class="activeTab === 'shipping' ? 'border-b-2 border-orange-500 text-gray-900' : 'text-gray-500 hover:text-gray-700'" class="flex-1 py-4 px-6 font-semibold transition">
                    SHIPPING & RETURNS
                </button>
            </div>

            <!-- Description Tab -->
            <div x-show="activeTab === 'description'" class="p-6">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Product Description</h3>
                
                @if($product->description)
                    <div class="text-gray-700 mb-4 prose max-w-none">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                @else
                    <p class="text-gray-500">No description available.</p>
                @endif
                
                @if($product->short_description)
                    <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                        <p class="text-gray-700">{{ $product->short_description }}</p>
                    </div>
                @endif
            </div>

            <!-- Specifications Tab -->
            <div x-show="activeTab === 'specifications'" class="p-6">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Product Specifications</h3>
                <ul class="space-y-2">
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-orange-500 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 10 10.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-gray-700">SKU: {{ $product->sku }}</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-orange-500 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 10 10.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-gray-700">Category: {{ $product->category->name }}</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-orange-500 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 10 10.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-gray-700">Stock: {{ $product->stock_quantity }} units</span>
                    </li>
                </ul>
            </div>

            <!-- Reviews Tab -->
            <div x-show="activeTab === 'reviews'" class="p-6">
                @livewire('product-reviews', ['product' => $product])
            </div>

            <!-- Shipping & Returns Tab -->
            <div x-show="activeTab === 'shipping'" class="p-6">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Shipping & Returns</h3>
                <div class="space-y-4">
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Shipping Information</h4>
                        <ul class="space-y-2 text-gray-700">
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-orange-500 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                                    <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z" />
                                </svg>
                                <span>Free Shipping on orders over $50</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-orange-500 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                </svg>
                                <span>Standard shipping: 3-5 business days</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-orange-500 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                </svg>
                                <span>Express shipping: 1-2 business days (additional fee)</span>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Return Policy</h4>
                        <ul class="space-y-2 text-gray-700">
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-orange-500 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span>30-day return policy</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-orange-500 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span>Items must be in original condition</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-orange-500 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span>Free return shipping for defective items</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customers Also Bought -->
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Customers Also Bought</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Product Card 1 -->
                <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition">
                    <div class="bg-gray-200 h-48 flex items-center justify-center">
                        <div class="text-gray-500">Product Image</div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 text-sm mb-2">Hard Shell Carrying Case</h3>
                        <p class="text-gray-600 text-xs mb-3">Accessories</p>
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold text-orange-500">$29.00</span>
                            <button class="text-gray-500 hover:text-orange-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Product Card 2 -->
                <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition">
                    <div class="bg-gray-200 h-48 flex items-center justify-center">
                        <div class="text-gray-500">Product Image</div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 text-sm mb-2">Minimalist Headphone Stand</h3>
                        <p class="text-gray-600 text-xs mb-3">Furniture</p>
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold text-orange-500">$45.00</span>
                            <button class="text-gray-500 hover:text-orange-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Product Card 3 -->
                <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition">
                    <div class="bg-gray-200 h-48 flex items-center justify-center">
                        <div class="text-gray-500">Product Image</div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 text-sm mb-2">SoundPulse Buds Pro</h3>
                        <p class="text-gray-600 text-xs mb-3">Audio</p>
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold text-orange-500">$159.00</span>
                            <button class="text-gray-500 hover:text-orange-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Product Card 4 -->
                <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition">
                    <div class="bg-gray-200 h-48 flex items-center justify-center">
                        <div class="text-gray-500">Product Image</div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 text-sm mb-2">Premium Braided USB-C Cable</h3>
                        <p class="text-gray-600 text-xs mb-3">Accessories</p>
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold text-orange-500">$19.00</span>
                            <button class="text-gray-500 hover:text-orange-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
