<?php

namespace App\Livewire;

use App\Services\CartService;
use Livewire\Component;
use Livewire\Attributes\On;

class CartPage extends Component
{
    public function render(CartService $cartService)
    {
        return view('livewire.cart-page', [
            'cartItems' => $cartService->getItems(),
            'total' => $cartService->total(),
        ]);
    }

    public function increment($rowId, CartService $cartService)
    {
        $items = $cartService->getItems();
        $item = $items->firstWhere('id', $rowId);
        
        if ($item) {
            $cartService->update($rowId, $item->quantity + 1);
            $this->dispatch('cart-updated');
        }
    }

    public function decrement($rowId, CartService $cartService)
    {
        $items = $cartService->getItems();
        $item = $items->firstWhere('id', $rowId);

        if ($item) {
            if ($item->quantity > 1) {
                $cartService->update($rowId, $item->quantity - 1);
            } else {
                $cartService->remove($rowId);
            }
            $this->dispatch('cart-updated');
        }
    }

    public function remove($rowId, CartService $cartService)
    {
        $cartService->remove($rowId);
        $this->dispatch('cart-updated');
    }

    #[On('cart-updated')] 
    public function refreshCart()
    {
        // This method is just to trigger a re-render when the event is heard
    }
}
