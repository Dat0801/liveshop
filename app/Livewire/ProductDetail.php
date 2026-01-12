<?php

namespace App\Livewire;

use App\Models\Product;
use App\Services\CartService;
use Livewire\Component;

class ProductDetail extends Component
{
    public Product $product;
    public $selectedVariants = [];
    public $quantity = 1;
    public $currentPrice;

    public function mount(Product $product)
    {
        $this->product = $product->load(['category', 'variants']);
        $this->currentPrice = $product->getCurrentPrice();
    }

    public function updatedSelectedVariants()
    {
        $this->calculatePrice();
    }

    public function calculatePrice()
    {
        $basePrice = $this->product->getCurrentPrice();
        $adjustment = 0;

        foreach ($this->selectedVariants as $type => $value) {
            $variant = $this->product->variants
                ->where('type', $type)
                ->where('value', $value)
                ->first();

            if ($variant) {
                $adjustment += $variant->price_adjustment;
            }
        }

        $this->currentPrice = $basePrice + $adjustment;
    }

    public function incrementQuantity()
    {
        $this->quantity++;
    }

    public function decrementQuantity()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function updatedQuantity($value)
    {
        if ($value < 1) {
            $this->quantity = 1;
        }
    }

    public function addToCart(CartService $cartService)
    {
        try {
            $cartService->add(
                $this->product->id, 
                $this->quantity, 
                $this->selectedVariants
            );

            // Notify UI to open the drawer and refresh cart counters
            $this->dispatch('open-cart');
            $this->dispatch('cart-updated');
            
            session()->flash('message', 'Product added to cart successfully!');
        } catch (\DomainException $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.product-detail');
    }
}
