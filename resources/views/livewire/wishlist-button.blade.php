<div>
    <button wire:click="toggleWishlist" 
            class="p-2 rounded-full transition {{ $isInWishlist ? 'bg-orange-100 text-orange-600' : 'bg-gray-100 text-gray-600 hover:bg-orange-100 hover:text-orange-600' }}"
            title="{{ $isInWishlist ? 'Remove from wishlist' : 'Add to wishlist' }}">
        <svg class="w-5 h-5 {{ $isInWishlist ? 'fill-current' : '' }}" fill="{{ $isInWishlist ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
        </svg>
    </button>
</div>
