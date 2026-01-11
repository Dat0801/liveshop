<div>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Manage Product Media</h2>
        <div class="flex gap-3">
            <a href="{{ route('admin.products') }}" class="btn">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Products
            </a>
        </div>
    </div>

    <!-- Upload Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-semibold mb-4">Upload New Images</h3>
        <form wire:submit.prevent="uploadImages">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Images</label>
                    <input type="file" wire:model="newImages" multiple accept="image/*" class="input">
                    @error('newImages.*') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    <p class="text-sm text-gray-500 mt-1">Maximum file size: 2MB per image</p>
                </div>

                @if ($newImages)
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($newImages as $image)
                            <div class="border rounded-lg p-2">
                                <img src="{{ $image->temporaryUrl() }}" class="w-full h-32 object-cover rounded">
                            </div>
                        @endforeach
                    </div>
                @endif

                <div>
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                        <span wire:loading.remove>Upload Images</span>
                        <span wire:loading>Uploading...</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Current Images -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold mb-4">Current Images ({{ count($images) }})</h3>
        
        @if(count($images) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" x-data="{ dragging: null }">
                @foreach($images as $index => $imagePath)
                    <div class="border rounded-lg p-4 relative group" 
                         x-data="{ hover: false }"
                         @mouseenter="hover = true"
                         @mouseleave="hover = false">
                        
                        @if($index === 0)
                            <div class="absolute top-2 left-2 bg-primary-600 text-white px-2 py-1 rounded text-xs font-semibold z-10">
                                Cover Image
                            </div>
                        @endif

                        <div class="relative">
                            <img src="{{ Storage::url($imagePath) }}" 
                                 alt="Product Image {{ $index + 1 }}" 
                                 class="w-full h-48 object-cover rounded">
                            
                            <!-- Hover overlay with actions -->
                            <div x-show="hover" 
                                 x-transition
                                 class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center rounded">
                                <div class="flex gap-2">
                                    @if($index !== 0)
                                        <button wire:click="setCoverImage({{ $index }})" 
                                                class="bg-white text-gray-800 px-3 py-2 rounded hover:bg-gray-100"
                                                title="Set as Cover">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </button>
                                    @endif
                                    
                                    <button wire:click="deleteImage({{ $index }})" 
                                            onclick="return confirm('Are you sure you want to delete this image?')"
                                            class="bg-red-600 text-white px-3 py-2 rounded hover:bg-red-700"
                                            title="Delete Image">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mt-2 text-sm text-gray-600">
                            <p class="truncate">{{ basename($imagePath) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                <p class="text-sm text-blue-800">
                    <strong>Tip:</strong> The first image is used as the cover image. Click "Set as Cover" on any image to make it the cover.
                </p>
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <p class="text-gray-500 text-lg">No images uploaded yet</p>
                <p class="text-gray-400 text-sm mt-2">Upload images using the form above</p>
            </div>
        @endif
    </div>
</div>
