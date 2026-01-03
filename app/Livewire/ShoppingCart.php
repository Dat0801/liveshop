<?php

namespace App\Livewire;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\On;

class ShoppingCart extends Component
{
    public $cart;
    public $isOpen = false;

    public function mount()
    {
        $this->loadCart();
    }

    #[On('add-to-cart')]
    public function addToCart($productId, $variants = [], $quantity = 1, $price = null)
    {
        $product = Product::find($productId);

        if (!$product) {
            return;
        }

        $cart = $this->getOrCreateCart();

        // Check if item already exists in cart
        $existingItem = $cart->items()
            ->where('product_id', $productId)
            ->where('variants', json_encode($variants))
            ->first();

        if ($existingItem) {
            $existingItem->update([
                'quantity' => $existingItem->quantity + $quantity,
            ]);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'variants' => $variants,
                'quantity' => $quantity,
                'price' => $price ?? $product->getCurrentPrice(),
            ]);
        }

        $this->loadCart();
        $this->dispatch('cart-updated');
        $this->isOpen = true;
    }

    public function updateQuantity($itemId, $quantity)
    {
        if ($quantity < 1) {
            return;
        }

        $item = CartItem::find($itemId);
        if ($item && $item->cart_id === $this->cart->id) {
            $item->update(['quantity' => $quantity]);
            $this->loadCart();
            $this->dispatch('cart-updated');
        }
    }

    public function removeItem($itemId)
    {
        $item = CartItem::find($itemId);
        if ($item && $item->cart_id === $this->cart->id) {
            $item->delete();
            $this->loadCart();
            $this->dispatch('cart-updated');
        }
    }

    public function clearCart()
    {
        if ($this->cart) {
            $this->cart->items()->delete();
            $this->loadCart();
            $this->dispatch('cart-updated');
        }
    }

    public function toggleCart()
    {
        $this->isOpen = !$this->isOpen;
    }

    protected function getOrCreateCart()
    {
        $sessionId = session()->getId();

        $cart = Cart::where('session_id', $sessionId)->first();

        if (!$cart) {
            $cart = Cart::create([
                'session_id' => $sessionId,
                'user_id' => auth()->id(),
            ]);
        }

        return $cart;
    }

    protected function loadCart()
    {
        $sessionId = session()->getId();
        $this->cart = Cart::with(['items.product'])
            ->where('session_id', $sessionId)
            ->first();
    }

    public function render()
    {
        return view('livewire.shopping-cart');
    }
}
