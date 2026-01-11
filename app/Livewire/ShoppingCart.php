<?php

namespace App\Livewire;

use App\Models\Product;
use App\Services\CartService;
use Livewire\Component;
use Livewire\Attributes\On;

class ShoppingCart extends Component
{
    public $items;
    public $isOpen = false;

    public function mount(CartService $cartService)
    {
        $this->loadCart($cartService);
    }

    #[On('add-to-cart')]
    public function addToCart($productId, $variants = [], $quantity = 1, $price = null, CartService $cartService)
    {
        $product = Product::find($productId);

        if (!$product) {
            return;
        }

        $cartService->add($productId, $quantity, $variants);

        $this->loadCart($cartService);
        $this->dispatch('cart-updated');
        $this->isOpen = true;
    }

    #[On('open-cart')]
    public function openCart(CartService $cartService)
    {
        $this->loadCart($cartService);
        $this->isOpen = true;
    }

    #[On('cart-updated')]
    public function refreshCart(CartService $cartService)
    {
        $this->loadCart($cartService);
    }

    public function updateQuantity($rowId, $quantity, CartService $cartService)
    {
        if ($quantity < 1) {
            return;
        }

        $cartService->update($rowId, $quantity);
        $this->loadCart($cartService);
        $this->dispatch('cart-updated');
    }

    public function removeItem($rowId, CartService $cartService)
    {
        $cartService->remove($rowId);
        $this->loadCart($cartService);
        $this->dispatch('cart-updated');
    }

    public function clearCart(CartService $cartService)
    {
        $cartService->clear();
        $this->loadCart($cartService);
        $this->dispatch('cart-updated');
        $this->isOpen = false; // Close cart after clearing
    }

    public function toggleCart()
    {
        $this->isOpen = !$this->isOpen;
    }

    protected function loadCart(CartService $cartService)
    {
        $this->items = $cartService->getItems();
    }

    public function render()
    {
        return view('livewire.shopping-cart');
    }
}
