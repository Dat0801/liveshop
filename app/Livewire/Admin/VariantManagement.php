<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Models\ProductVariant;
use Livewire\Component;
use Livewire\WithPagination;

class VariantManagement extends Component
{
    use WithPagination;

    public Product $product;

    public $showModal = false;
    public $showDeleteModal = false;
    public $editMode = false;
    public $variantId;
    public $variantToDelete;

    public $type = '';
    public $value = '';
    public $price_adjustment = 0;
    public $stock_quantity = 0;
    public $sku = '';

    protected function rules()
    {
        $rules = [
            'type' => 'required|string|max:100',
            'value' => 'required|string|max:100',
            'price_adjustment' => 'nullable|numeric',
            'stock_quantity' => 'required|integer|min:0',
            'sku' => 'required|string|unique:product_variants,sku',
        ];

        if ($this->editMode && $this->variantId) {
            $rules['sku'] = 'required|string|unique:product_variants,sku,' . $this->variantId;
        }

        return $rules;
    }

    public function mount(Product $product)
    {
        $this->product = $product;
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $variant = ProductVariant::where('product_id', $this->product->id)->findOrFail($id);
        $this->variantId = $variant->id;
        $this->type = $variant->type;
        $this->value = $variant->value;
        $this->price_adjustment = $variant->price_adjustment ?? 0;
        $this->stock_quantity = $variant->stock_quantity;
        $this->sku = $variant->sku;

        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'product_id' => $this->product->id,
            'type' => $this->type,
            'value' => $this->value,
            'price_adjustment' => $this->price_adjustment,
            'stock_quantity' => $this->stock_quantity,
            'sku' => $this->sku,
        ];

        if ($this->editMode) {
            $variant = ProductVariant::findOrFail($this->variantId);
            $variant->update($data);
            session()->flash('message', 'Variant updated successfully!');
        } else {
            ProductVariant::create($data);
            session()->flash('message', 'Variant created successfully!');
        }

        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $this->variantToDelete = $id;
        $this->showDeleteModal = true;
    }

    public function deleteVariant()
    {
        if ($this->variantToDelete) {
            ProductVariant::where('product_id', $this->product->id)->findOrFail($this->variantToDelete)->delete();
            session()->flash('message', 'Variant deleted successfully!');
            $this->showDeleteModal = false;
            $this->variantToDelete = null;
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->variantToDelete = null;
    }

    protected function resetForm()
    {
        $this->reset([
            'variantId',
            'type',
            'value',
            'price_adjustment',
            'stock_quantity',
            'sku',
        ]);
        $this->stock_quantity = 0;
        $this->price_adjustment = 0;
    }

    public function render()
    {
        $variants = ProductVariant::where('product_id', $this->product->id)
            ->orderBy('type')
            ->orderBy('value')
            ->paginate(20);

        return view('livewire.admin.variant-management', [
            'variants' => $variants,
        ])->layout('components.layouts.admin', [
            'header' => 'Variants: ' . $this->product->name,
        ]);
    }
}
