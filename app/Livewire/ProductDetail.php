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

    public function addToCart(CartService $cartService)
    {
        $cartService->add(
            $this->product->id, 
            $this->quantity, 
            $this->selectedVariants
        );

        $this->dispatch('cartUpdated');
        
        session()->flash('message', 'Product added to cart successfully!');
    }

    public function render()
    {
        return view('livewire.product-detail');
    }
}
