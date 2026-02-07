<?php

namespace App\Livewire;

use App\Models\Wishlist;
use Livewire\Component;
use Livewire\WithPagination;

class WishlistPage extends Component
{
    use WithPagination;

    public function removeFromWishlist($wishlistId)
    {
        $wishlist = Wishlist::where('id', $wishlistId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $wishlist->delete();

        session()->flash('message', 'Removed from wishlist');
        $this->resetPage();
    }

    public function render()
    {
        $wishlists = Wishlist::where('user_id', auth()->id())
            ->with('product.category')
            ->latest()
            ->paginate(12);

        return view('livewire.wishlist-page', [
            'wishlists' => $wishlists,
        ]);
    }
}
