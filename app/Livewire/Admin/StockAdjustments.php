<?php

namespace App\Livewire\Admin;

use App\Models\StockAdjustment;
use App\Models\Product;
use App\Models\ProductVariant;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class StockAdjustments extends Component
{
    use WithPagination;

    public $showModal = false;
    public $product_id = '';
    public $product_variant_id = '';
    public $quantity_change = 0;
    public $type = 'adjust';
    public $reason = '';
    public $notes = '';

    public $search = '';
    public $typeFilter = '';
    public $productFilter = '';

    protected function rules()
    {
        return [
            'product_id' => 'required|exists:products,id',
            'product_variant_id' => 'nullable|exists:product_variants,id',
            'quantity_change' => 'required|integer',
            'type' => 'required|in:receive,adjust,damage,return,sale',
            'reason' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ];
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $product = Product::findOrFail($this->product_id);
        $quantityBefore = $product->stock_quantity;
        $quantityAfter = $quantityBefore + $this->quantity_change;

        if ($quantityAfter < 0) {
            $this->addError('quantity_change', 'Stock cannot go below 0');
            return;
        }

        // Create adjustment record
        StockAdjustment::create([
            'product_id' => $this->product_id,
            'product_variant_id' => $this->product_variant_id ?: null,
            'admin_id' => Auth::id(),
            'quantity_change' => $this->quantity_change,
            'quantity_before' => $quantityBefore,
            'quantity_after' => $quantityAfter,
            'type' => $this->type,
            'reason' => $this->reason,
            'notes' => $this->notes,
        ]);

        // Update product stock
        $product->update(['stock_quantity' => $quantityAfter]);

        // Update variant stock if applicable
        if ($this->product_variant_id) {
            $variant = ProductVariant::findOrFail($this->product_variant_id);
            $variant->update(['stock_quantity' => $variant->stock_quantity + $this->quantity_change]);
        }

        session()->flash('message', 'Stock adjustment recorded successfully!');
        $this->closeModal();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    protected function resetForm()
    {
        $this->reset([
            'product_id',
            'product_variant_id',
            'quantity_change',
            'type',
            'reason',
            'notes',
        ]);
        $this->type = 'adjust';
    }

    public function render()
    {
        $query = StockAdjustment::with(['product', 'variant', 'admin']);

        if ($this->search) {
            $query->whereHas('product', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('sku', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->typeFilter) {
            $query->where('type', $this->typeFilter);
        }

        if ($this->productFilter) {
            $query->where('product_id', $this->productFilter);
        }

        $adjustments = $query->orderByDesc('created_at')->paginate(20);
        $products = Product::all();

        return view('livewire.admin.stock-adjustments', [
            'adjustments' => $adjustments,
            'products' => $products,
        ])->layout('components.layouts.admin', [
            'header' => 'Stock Adjustments',
        ]);
    }
}
