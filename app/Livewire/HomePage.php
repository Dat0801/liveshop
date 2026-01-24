<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;

class HomePage extends Component
{
    public function render()
    {
        $categories = Category::active()->get();
        
        // Get featured/popular products for different sections
        $flashSaleProducts = Product::active()
            ->inStock()
            ->where('is_featured', true)
            ->take(4)
            ->get();
        
        $recommendedProducts = Product::active()
            ->inStock()
            ->take(5)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.home-page', [
            'categories' => $categories,
            'flashSaleProducts' => $flashSaleProducts,
            'recommendedProducts' => $recommendedProducts,
        ]);
    }
}
