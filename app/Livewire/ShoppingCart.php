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
    protected CartService $cartService;

    public function mount(CartService $cartService)
    {
        $this->cartService = $cartService;
        $this->loadCart();
    }

    #[On('add-to-cart')]
    public function addToCart($productId, $variants = [], $quantity = 1, $price = null)
    {
        $product = Product::find($productId);

        if (!$product) {
            return;
        }

        $this->cartService->add($productId, $quantity, $variants);

        $this->loadCart();
        $this->dispatch('cart-updated');
        $this->isOpen = true;
    }

    public function updateQuantity($rowId, $quantity)
    {
        if ($quantity < 1) {
            return;
        }

        $this->cartService->update($rowId, $quantity);
        $this->loadCart();
        $this->dispatch('cart-updated');
    }

    public function removeItem($rowId)
    {
        $this->cartService->remove($rowId);
        $this->loadCart();
        $this->dispatch('cart-updated');
    }

    public function clearCart()
    {
        $this->cartService->clear();
        $this->loadCart();
        $this->dispatch('cart-updated');
    }

    public function toggleCart()
    {
        $this->isOpen = !$this->isOpen;
    }

    protected function loadCart()
    {
        $this->items = $this->cartService->getItems();
    }

    public function render()
    {
        return view('livewire.shopping-cart');
    }
}
