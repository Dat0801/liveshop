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

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Product Images -->
            <div>
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-4">
                    @if($product->images && count($product->images) > 0)
                        <img src="{{ $product->images[0] }}" alt="{{ $product->name }}" class="w-full h-96 object-cover">
                    @else
                        <div class="w-full h-96 flex items-center justify-center bg-gray-300">
                            <svg class="w-32 h-32 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Thumbnail Images -->
                @if($product->images && count($product->images) > 1)
                    <div class="grid grid-cols-4 gap-2">
                        @foreach($product->images as $image)
                            <div class="bg-white rounded-lg shadow-md overflow-hidden cursor-pointer hover:ring-2 hover:ring-primary-500">
                                <img src="{{ $image }}" alt="{{ $product->name }}" class="w-full h-20 object-cover">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Product Details -->
            <div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <!-- Breadcrumb -->
                    <div class="text-sm text-gray-500 mb-4">
                        <a href="{{ route('products.index') }}" class="hover:text-primary-600">Products</a>
                        <span class="mx-2">/</span>
                        <span>{{ $product->category->name }}</span>
                    </div>

                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
                    
                    <p class="text-gray-600 mb-4">SKU: {{ $product->sku }}</p>

                    <!-- Price -->
                    <div class="mb-6">
                        <div class="flex items-baseline space-x-3">
                            <span class="text-4xl font-bold text-primary-600">
                                ${{ number_format($currentPrice, 2) }}
                            </span>
                            @if($product->hasDiscount())
                                <span class="text-xl text-gray-500 line-through">
                                    ${{ number_format($product->base_price, 2) }}
                                </span>
                                <span class="bg-red-500 text-white text-sm font-bold px-2 py-1 rounded">
                                    Save {{ $product->getDiscountPercentage() }}%
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Stock Status -->
                    @if($product->stock_quantity > 0)
                        <p class="text-green-600 font-semibold mb-6">
                            ✓ In Stock ({{ $product->stock_quantity }} available)
                        </p>
                    @else
                        <p class="text-red-600 font-semibold mb-6">
                            ✗ Out of Stock
                        </p>
                    @endif

                    <!-- Description -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">Description</h3>
                        <p class="text-gray-700">{{ $product->short_description }}</p>
                    </div>

                    <!-- Variants -->
                    @php
                        $variantTypes = $product->variants->groupBy('type');
                    @endphp

                    @if($variantTypes->isNotEmpty())
                        <div class="mb-6">
                            @foreach($variantTypes as $type => $variants)
                                <div class="mb-4">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2 capitalize">
                                        {{ $type }}
                                    </label>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($variants as $variant)
                                            <button 
                                                wire:click="$set('selectedVariants.{{ $type }}', '{{ $variant->value }}')"
                                                class="px-4 py-2 border rounded-lg transition-colors
                                                    {{ ($selectedVariants[$type] ?? '') === $variant->value 
                                                        ? 'border-primary-600 bg-primary-50 text-primary-600' 
                                                        : 'border-gray-300 hover:border-primary-400' }}">
                                                {{ $variant->value }}
                                                @if($variant->price_adjustment != 0)
                                                    <span class="text-xs">
                                                        ({{ $variant->price_adjustment > 0 ? '+' : '' }}${{ number_format($variant->price_adjustment, 2) }})
                                                    </span>
                                                @endif
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Quantity -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Quantity</label>
                        <div class="flex items-center space-x-3">
                            <button 
                                wire:click="decrementQuantity"
                                class="w-10 h-10 rounded-lg border border-gray-300 hover:bg-gray-100 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                </svg>
                            </button>
                            <input 
                                type="number" 
                                wire:model="quantity"
                                min="1"
                                class="w-20 text-center border border-gray-300 rounded-lg py-2">
                            <button 
                                wire:click="incrementQuantity"
                                class="w-10 h-10 rounded-lg border border-gray-300 hover:bg-gray-100 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Add to Cart Button -->
                    <button 
                        wire:click="addToCart"
                        @if($product->stock_quantity <= 0) disabled @endif
                        class="btn btn-primary w-full mb-4 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Add to Cart
                    </button>

                    <!-- Additional Info -->
                    <div class="border-t pt-4">
                        <div class="flex items-center text-sm text-gray-600 mb-2">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                            </svg>
                            Free shipping on orders over $100
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            30-day return policy
                        </div>
                    </div>
                </div>

                <!-- Full Description -->
                @if($product->description)
                    <div class="bg-white rounded-lg shadow-md p-6 mt-6">
                        <h3 class="text-xl font-semibold mb-4">Product Details</h3>
                        <div class="text-gray-700 prose max-w-none">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
