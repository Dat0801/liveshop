<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Wishlist;
use Livewire\Component;

class WishlistButton extends Component
{
    public Product $product;

    public $isInWishlist = false;

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->checkWishlistStatus();
    }

    protected function checkWishlistStatus(): void
    {
        if (! auth()->check()) {
            $this->isInWishlist = false;

            return;
        }

        $this->isInWishlist = Wishlist::where('user_id', auth()->id())
            ->where('product_id', $this->product->id)
            ->exists();
    }

    public function toggleWishlist()
    {
        if (! auth()->check()) {
            session()->flash('error', 'Please login to add items to your wishlist.');

            return redirect()->route('login');
        }

        $wishlist = Wishlist::where('user_id', auth()->id())
            ->where('product_id', $this->product->id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            $this->isInWishlist = false;
            session()->flash('message', 'Removed from wishlist');
        } else {
            Wishlist::create([
                'user_id' => auth()->id(),
                'product_id' => $this->product->id,
            ]);
            $this->isInWishlist = true;
            session()->flash('message', 'Added to wishlist');
        }

        $this->dispatch('wishlist-updated');
    }

    public function render()
    {
        return view('livewire.wishlist-button');
    }
}
