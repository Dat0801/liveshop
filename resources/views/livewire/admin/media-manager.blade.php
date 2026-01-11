<div>
    @if (session()->has('message'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('message') }}
        </div>
    @endif

    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold">Media Management</h2>
            <p class="text-gray-600 mt-1">Product: {{ $product->name }}</p>
        </div>
        <a href="{{ route('admin.products') }}" class="btn bg-gray-500 hover:bg-gray-600 text-white">
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Products
        </a>
    </div>

    <!-- Upload Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-semibold mb-4">Upload New Images</h3>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Select Images (Max 2MB each)
                </label>
                <input type="file" wire:model="newImages" multiple accept="image/*" class="input">
                @error('newImages.*') 
                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                @enderror
            </div>

            @if (count($newImages) > 0)
                <div class="flex flex-wrap gap-4">
                    @foreach($newImages as $image)
                        <div class="relative">
                            <img src="{{ $image->temporaryUrl() }}" class="w-32 h-32 object-cover rounded-lg border-2 border-gray-300">
                            <span class="absolute top-0 right-0 bg-green-500 text-white text-xs px-2 py-1 rounded-bl">New</span>
                        </div>
                    @endforeach
                </div>
                <button wire:click="uploadImages" class="btn btn-primary">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    Upload Images
                </button>
            @endif
        </div>
    </div>

    <!-- Current Images -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold mb-4">
            Current Images 
            @if(count($images) > 0)
                <span class="text-sm font-normal text-gray-600">({{ count($images) }} {{ count($images) == 1 ? 'image' : 'images' }})</span>
            @endif
        </h3>

        @if(count($images) > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                @foreach($images as $index => $image)
                    <div class="relative group">
                        <div class="relative rounded-lg overflow-hidden border-2 {{ $index === 0 ? 'border-blue-500' : 'border-gray-300' }} hover:border-blue-400 transition-colors">
                            <img src="{{ Storage::url($image) }}" 
                                 alt="Product Image {{ $index + 1 }}" 
                                 class="w-full h-48 object-cover">
                            
                            <!-- Cover Badge -->
                            @if($index === 0)
                                <div class="absolute top-2 left-2 bg-blue-500 text-white text-xs font-semibold px-2 py-1 rounded">
                                    <svg class="w-4 h-4 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    Cover
                                </div>
                            @endif

                            <!-- Action Buttons Overlay -->
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all duration-200 flex items-center justify-center opacity-0 group-hover:opacity-100">
                                <div class="flex gap-2">
                                    @if($index !== 0)
                                        <button wire:click="setCoverImage({{ $index }})" 
                                                class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-full transition-colors"
                                                title="Set as Cover">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        </button>
                                    @endif
                                    
                                    <button wire:click="deleteImage({{ $index }})" 
                                            wire:confirm="Are you sure you want to delete this image?"
                                            class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-full transition-colors"
                                            title="Delete Image">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Image Info -->
                        <div class="mt-2 text-xs text-gray-600 text-center">
                            Image {{ $index + 1 }}
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                    <div class="text-sm text-blue-800">
                        <p class="font-semibold mb-1">Tips:</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li>The first image is your cover image (shown with a star badge)</li>
                            <li>Hover over images to set as cover or delete</li>
                            <li>Recommended image size: 800x800px</li>
                            <li>Supported formats: JPG, PNG, WEBP</li>
                        </ul>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No images yet</h3>
                <p class="mt-2 text-sm text-gray-500">Upload some images to get started</p>
            </div>
        @endif
    </div>
</div>
