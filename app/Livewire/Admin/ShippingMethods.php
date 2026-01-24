<?php

namespace App\Livewire\Admin;

use App\Models\ShippingMethod;
use Livewire\Component;
use Livewire\WithPagination;

class ShippingMethods extends Component
{
    use WithPagination;

    public $showModal = false;
    public $showDeleteModal = false;
    public $editMode = false;
    public $methodId;
    public $methodToDelete;

    public $name = '';
    public $code = '';
    public $description = '';
    public $type = 'flat';
    public $rate = 0;
    public $min_order = null;
    public $max_order = null;
    public $min_weight = null;
    public $max_weight = null;
    public $processing_days_min = 1;
    public $processing_days_max = 5;
    public $is_active = true;
    public $display_order = 0;
    public $icon = 'truck';
    public $delivery_text = '';

    public $search = '';
    public $statusFilter = 'all';
    public $regionFilter = 'all';

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:shipping_methods,code',
            'description' => 'nullable|string',
            'type' => 'required|in:flat,weight-based,amount-based',
            'rate' => 'required|numeric|min:0',
            'min_order' => 'nullable|numeric|min:0',
            'max_order' => 'nullable|numeric|min:0|gte:min_order',
            'min_weight' => 'nullable|integer|min:0',
            'max_weight' => 'nullable|integer|min:0|gte:min_weight',
            'processing_days_min' => 'required|integer|min:0',
            'processing_days_max' => 'required|integer|min:0|gte:processing_days_min',
            'is_active' => 'boolean',
            'display_order' => 'integer',
            'icon' => 'nullable|string|max:50',
            'delivery_text' => 'nullable|string|max:255',
        ];

        if ($this->editMode && $this->methodId) {
            $rules['code'] = 'required|string|max:50|unique:shipping_methods,code,' . $this->methodId;
        }

        return $rules;
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $method = ShippingMethod::findOrFail($id);
        $this->methodId = $method->id;
        $this->name = $method->name;
        $this->code = $method->code;
        $this->description = $method->description;
        $this->type = $method->type;
        $this->rate = $method->rate;
        $this->min_order = $method->min_order;
        $this->max_order = $method->max_order;
        $this->min_weight = $method->min_weight;
        $this->max_weight = $method->max_weight;
        $this->processing_days_min = $method->processing_days_min;
        $this->processing_days_max = $method->processing_days_max;
        $this->is_active = $method->is_active;
        $this->display_order = $method->display_order;
        $this->icon = $method->icon ?? 'truck';
        $this->delivery_text = $method->delivery_text ?? '';

        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'code' => strtoupper($this->code),
            'description' => $this->description,
            'type' => $this->type,
            'rate' => $this->rate,
            'min_order' => $this->min_order,
            'max_order' => $this->max_order,
            'min_weight' => $this->min_weight,
            'max_weight' => $this->max_weight,
            'processing_days_min' => $this->processing_days_min,
            'processing_days_max' => $this->processing_days_max,
            'is_active' => $this->is_active,
            'display_order' => $this->display_order,
            'icon' => $this->icon,
            'delivery_text' => $this->delivery_text,
        ];

        if ($this->editMode) {
            ShippingMethod::findOrFail($this->methodId)->update($data);
            session()->flash('message', 'Shipping method updated successfully!');
        } else {
            ShippingMethod::create($data);
            session()->flash('message', 'Shipping method created successfully!');
        }

        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $this->methodToDelete = $id;
        $this->showDeleteModal = true;
    }

    public function deleteMethod()
    {
        if ($this->methodToDelete) {
            ShippingMethod::findOrFail($this->methodToDelete)->delete();
            session()->flash('message', 'Shipping method deleted successfully!');
            $this->showDeleteModal = false;
            $this->methodToDelete = null;
        }
    }

    public function toggleStatus($id)
    {
        $method = ShippingMethod::findOrFail($id);
        $method->update(['is_active' => !$method->is_active]);
        session()->flash('message', 'Shipping method status updated!');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->methodToDelete = null;
    }

    protected function resetForm()
    {
        $this->reset([
            'methodId',
            'name',
            'code',
            'description',
            'type',
            'rate',
            'min_order',
            'max_order',
            'min_weight',
            'max_weight',
            'processing_days_min',
            'processing_days_max',
            'is_active',
            'display_order',
            'icon',
            'delivery_text',
        ]);
        $this->type = 'flat';
        $this->processing_days_min = 1;
        $this->processing_days_max = 5;
        $this->is_active = true;
        $this->icon = 'truck';
    }

    public function render()
    {
        $query = ShippingMethod::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('code', 'like', '%' . $this->search . '%');
        }

        if ($this->statusFilter !== 'all') {
            $query->where('is_active', $this->statusFilter === 'active');
        }

        $methods = $query->orderBy('display_order')->paginate(4);
        $totalCarriers = ShippingMethod::count();
        $defaultMethod = ShippingMethod::where('is_active', true)
                            ->orderBy('display_order')
                            ->first();

        return view('livewire.admin.shipping-methods', [
            'methods' => $methods,
            'totalCarriers' => $totalCarriers,
            'defaultMethod' => $defaultMethod,
        ])->layout('components.layouts.admin', [
            'header' => 'Shipping Methods',
        ]);
    }
}
