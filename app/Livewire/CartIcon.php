<?php

namespace App\Livewire;

use App\Services\CartService;
use Livewire\Component;
use Livewire\Attributes\On;

class CartIcon extends Component
{
    public $cartCount = 0;

    public function mount(CartService $cartService)
    {
        $this->cartCount = $cartService->count();
    }

    #[On('cartUpdated')]
    public function updateCartCount(CartService $cartService)
    {
        $this->cartCount = $cartService->count();
    }

    public function render()
    {
        return view('livewire.cart-icon');
    }
}
