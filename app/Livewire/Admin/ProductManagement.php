<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class ProductManagement extends Component
{
    use WithPagination, WithFileUploads;

    public $showModal = false;
    public $showDeleteModal = false;
    public $editMode = false;
    public $productId;
    public $productToDelete;

    public $name = '';
    public $slug = '';
    public $category_id = '';
    public $description = '';
    public $short_description = '';
    public $base_price = '';
    public $discount_price = '';
    public $sku = '';
    public $stock_quantity = 0;
    public $is_active = true;
    public $is_featured = false;
    public $images = [];

    public $search = '';
    public $categoryFilter = '';
    public $statusFilter = '';
    public $showTrashed = false;

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'base_price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:base_price',
            'sku' => 'required|string|unique:products,sku',
            'stock_quantity' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];

        if ($this->editMode && $this->productId) {
            $rules['slug'] = 'required|string|max:255|unique:products,slug,' . $this->productId;
            $rules['sku'] = 'required|string|unique:products,sku,' . $this->productId;
        }

        return $rules;
    }

    public function updatedName($value)
    {
        $this->slug = Str::slug($value);
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $product = Product::findOrFail($id);
        $this->productId = $product->id;
        $this->name = $product->name;
        $this->slug = $product->slug;
        $this->category_id = $product->category_id;
        $this->description = $product->description;
        $this->short_description = $product->short_description;
        $this->base_price = $product->base_price;
        $this->discount_price = $product->discount_price;
        $this->sku = $product->sku;
        $this->stock_quantity = $product->stock_quantity;
        $this->is_active = $product->is_active;
        $this->is_featured = $product->is_featured;

        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'slug' => $this->slug,
            'category_id' => $this->category_id,
            'description' => $this->description,
            'short_description' => $this->short_description,
            'base_price' => $this->base_price,
            'discount_price' => $this->discount_price,
            'sku' => $this->sku,
            'stock_quantity' => $this->stock_quantity,
            'is_active' => $this->is_active,
            'is_featured' => $this->is_featured,
        ];

        if ($this->editMode) {
            $product = Product::findOrFail($this->productId);
            $product->update($data);
            session()->flash('message', 'Product updated successfully!');
        } else {
            Product::create($data);
            session()->flash('message', 'Product created successfully!');
        }

        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $this->productToDelete = $id;
        $this->showDeleteModal = true;
    }

    public function deleteProduct()
    {
        if ($this->productToDelete) {
            Product::findOrFail($this->productToDelete)->delete();
            session()->flash('message', 'Product deleted successfully!');
            $this->showDeleteModal = false;
            $this->productToDelete = null;
        }
    }

    public function restoreProduct($id)
    {
        Product::withTrashed()->findOrFail($id)->restore();
        session()->flash('message', 'Product restored successfully!');
    }

    public function forceDeleteProduct($id)
    {
        Product::withTrashed()->findOrFail($id)->forceDelete();
        session()->flash('message', 'Product permanently deleted!');
    }

    public function toggleStatus($id)
    {
        $product = Product::findOrFail($id);
        $product->update(['is_active' => !$product->is_active]);
        session()->flash('message', 'Product status updated!');
    }

    public function toggleFeatured($id)
    {
        $product = Product::findOrFail($id);
        $product->update(['is_featured' => !$product->is_featured]);
        session()->flash('message', 'Featured status updated!');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->productToDelete = null;
    }

    protected function resetForm()
    {
        $this->reset([
            'productId',
            'name',
            'slug',
            'category_id',
            'description',
            'short_description',
            'base_price',
            'discount_price',
            'sku',
            'stock_quantity',
            'is_active',
            'is_featured',
            'images',
        ]);
    }

    public function render()
    {
        $query = Product::with('category');

        // Show trashed products if filter is active
        if ($this->showTrashed) {
            $query->onlyTrashed();
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('sku', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->categoryFilter) {
            $query->where('category_id', $this->categoryFilter);
        }

        if ($this->statusFilter !== '') {
            if ($this->statusFilter === 'active') {
                $query->where('is_active', true);
            } elseif ($this->statusFilter === 'inactive') {
                $query->where('is_active', false);
            } elseif ($this->statusFilter === 'draft') {
                $query->where('is_active', false);
            } elseif ($this->statusFilter === 'low_stock') {
                $query->where('stock_quantity', '>', 0)->where('stock_quantity', '<=', 10);
            } elseif ($this->statusFilter === 'out_of_stock') {
                $query->where('stock_quantity', 0);
            }
        }

        $products = $query->latest()->paginate(10);
        $categories = Category::all();

        // Calculate statistics
        $totalProducts = Product::count();
        $lowStockItems = Product::where('stock_quantity', '>', 0)
                                ->where('stock_quantity', '<=', 10)
                                ->count();
        $outOfStockItems = Product::where('stock_quantity', 0)->count();
        
        // Calculate percentage changes (mock data - you can implement real tracking)
        $productGrowth = 2.4; // Example growth percentage
        $stockDecline = 3; // Example decline percentage

        return view('livewire.admin.product-management', [
            'products' => $products,
            'categories' => $categories,
            'totalProducts' => $totalProducts,
            'lowStockItems' => $lowStockItems,
            'outOfStockItems' => $outOfStockItems,
            'productGrowth' => $productGrowth,
            'stockDecline' => $stockDecline,
        ])->layout('components.layouts.admin', [
            'header' => 'Products',
        ]);
    }
}