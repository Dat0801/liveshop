<div>
    @if(session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    @if(session()->has('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- Review Summary -->
    @php
        $averageRating = $product->getAverageRating();
        $reviewsCount = $product->getReviewsCount();
        $fullStars = floor($averageRating);
        $hasHalfStar = ($averageRating - $fullStars) >= 0.5;
    @endphp

    <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Overall Rating -->
        <div class="text-center">
            <div class="text-5xl font-bold text-gray-900 mb-2">{{ number_format($averageRating, 1) }}</div>
            <div class="flex justify-center gap-1 mb-2">
                @for($i = 0; $i < 5; $i++)
                    @if($i < $fullStars)
                        <svg class="w-6 h-6 text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    @elseif($i == $fullStars && $hasHalfStar)
                        <svg class="w-6 h-6 text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                            <defs>
                                <linearGradient id="half-summary">
                                    <stop offset="50%" stop-color="currentColor"/>
                                    <stop offset="50%" stop-color="#D1D5DB" stop-opacity="1"/>
                                </linearGradient>
                            </defs>
                            <path fill="url(#half-summary)" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    @else
                        <svg class="w-6 h-6 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    @endif
                @endfor
            </div>
            <p class="text-gray-600 text-sm">{{ $reviewsCount }} {{ $reviewsCount == 1 ? 'Review' : 'Reviews' }}</p>
        </div>

        <!-- Rating Distribution -->
        <div class="md:col-span-2">
            <h4 class="font-semibold text-gray-900 mb-4">Rating Distribution</h4>
            @for($rating = 5; $rating >= 1; $rating--)
                @php
                    $count = $ratingDistribution[$rating] ?? 0;
                    $percentage = $reviewsCount > 0 ? ($count / $reviewsCount) * 100 : 0;
                @endphp
                <div class="flex items-center gap-3 mb-2">
                    <div class="flex items-center gap-1 w-20">
                        <span class="text-sm text-gray-700">{{ $rating }}</span>
                        <svg class="w-4 h-4 text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </div>
                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                        <div class="bg-orange-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                    </div>
                    <span class="text-sm text-gray-600 w-12 text-right">{{ $count }}</span>
                </div>
            @endfor
        </div>
    </div>

    <!-- Write Review Button -->
    @if($canReview && !$showReviewForm)
        <div class="mb-6">
            <button wire:click="openReviewForm" class="px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-lg transition">
                Write a Review
            </button>
        </div>
    @endif

    <!-- Review Form -->
    @if($showReviewForm)
        <div class="mb-8 p-6 bg-gray-50 rounded-lg border border-gray-200">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Write a Review</h3>
            
            <form wire:submit="submitReview">
                <!-- Rating -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                    <div class="flex gap-2">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" wire:click="$set('rating', {{ $i }})" class="focus:outline-none">
                                <svg class="w-8 h-8 {{ $rating >= $i ? 'text-orange-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            </button>
                        @endfor
                    </div>
                    @error('rating') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Comment -->
                <div class="mb-4">
                    <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">Your Review</label>
                    <textarea wire:model="comment" id="comment" rows="5" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" placeholder="Share your experience with this product..."></textarea>
                    @error('comment') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                @if(!auth()->check())
                    <!-- Name and Email for guests -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Your Name</label>
                            <input type="text" wire:model="name" id="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                            @error('name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Your Email</label>
                            <input type="email" wire:model="email" id="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                            @error('email') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                @endif

                <!-- Submit Buttons -->
                <div class="flex gap-3">
                    <button type="submit" class="px-6 py-2 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-lg transition">
                        Submit Review
                    </button>
                    <button type="button" wire:click="closeReviewForm" class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-900 font-semibold rounded-lg transition">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    @endif

    <!-- Reviews List -->
    @if($reviews->count() > 0)
        <div class="space-y-6">
            @foreach($reviews as $review)
                <div class="border-b border-gray-200 pb-6 last:border-b-0">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <h4 class="font-semibold text-gray-900">{{ $review->name ?? $review->user->name ?? 'Anonymous' }}</h4>
                                @if($review->is_verified_purchase)
                                    <span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs font-semibold rounded">Verified Purchase</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="flex gap-1">
                                    @for($i = 0; $i < 5; $i++)
                                        <svg class="w-4 h-4 {{ $i < $review->rating ? 'text-orange-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                </div>
                                <span class="text-sm text-gray-500">{{ $review->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                    @if($review->comment)
                        <p class="text-gray-700 mt-2">{{ $review->comment }}</p>
                    @endif
                </div>
            @endforeach

            <!-- Pagination -->
            <div class="mt-6">
                {{ $reviews->links() }}
            </div>
        </div>
    @else
        <div class="text-center py-12">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
            <p class="text-gray-600 text-lg mb-4">No reviews yet</p>
            <p class="text-gray-500">Be the first to review this product!</p>
        </div>
    @endif
</div>
