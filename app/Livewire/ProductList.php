<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductList extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';
    public $sortBy = 'latest';
    public $minPrice = 0;
    public $maxPrice = 10000;
    public $perPage = 12;

    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => ''],
        'sortBy' => ['except' => 'latest'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function updatingSortBy()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'category', 'sortBy', 'minPrice', 'maxPrice']);
        $this->resetPage();
    }

    public function render()
    {
        $query = Product::query()
            ->with(['category', 'variants'])
            ->active()
            ->inStock();

        // Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%')
                    ->orWhere('sku', 'like', '%' . $this->search . '%');
            });
        }

        // Category filter
        if ($this->category) {
            $query->where('category_id', $this->category);
        }

        // Price filter
        $query->whereBetween('base_price', [$this->minPrice, $this->maxPrice]);

        // Sorting
        match ($this->sortBy) {
            'price_low' => $query->orderBy('base_price', 'asc'),
            'price_high' => $query->orderBy('base_price', 'desc'),
            'name' => $query->orderBy('name', 'asc'),
            'popular' => $query->orderBy('is_featured', 'desc'),
            default => $query->latest(),
        };

        $products = $query->paginate($this->perPage);
        $categories = Category::active()->withCount('activeProducts')->get();

        return view('livewire.product-list', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }
}
