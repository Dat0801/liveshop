<?php

namespace App\Livewire;

use App\Services\CartService;
use Livewire\Component;
use Livewire\Attributes\On;

class CartPage extends Component
{
    public function render(CartService $cartService)
    {
        $items = $cartService->getItems();
        $subtotal = $items->sum('subtotal');
        $shipping = 0;
        $taxRate = 0.08;
        $tax = round($subtotal * $taxRate, 2);

        return view('livewire.cart-page', [
            'cartItems' => $items,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'tax' => $tax,
            'taxRate' => $taxRate,
            'grandTotal' => $subtotal + $shipping + $tax,
        ]);
    }

    public function increment($rowId, CartService $cartService)
    {
        $items = $cartService->getItems();
        $item = $items->firstWhere('id', $rowId);
        
        if ($item) {
            try {
                $cartService->update($rowId, $item->quantity + 1);
                $this->dispatch('cart-updated');
            } catch (\DomainException $e) {
                session()->flash('error', $e->getMessage());
            }
        }
    }

    public function decrement($rowId, CartService $cartService)
    {
        $items = $cartService->getItems();
        $item = $items->firstWhere('id', $rowId);

        if ($item) {
            if ($item->quantity > 1) {
                try {
                    $cartService->update($rowId, $item->quantity - 1);
                } catch (\DomainException $e) {
                    session()->flash('error', $e->getMessage());
                    return;
                }
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

    public function clearCart(CartService $cartService)
    {
        $cartService->clear();
        $this->dispatch('cart-updated');
    }

    #[On('cart-updated')] 
    public function refreshCart()
    {
        // This method is just to trigger a re-render when the event is heard
    }
}
