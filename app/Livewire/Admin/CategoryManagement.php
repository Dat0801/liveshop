<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryManagement extends Component
{
    use WithPagination;

    public $showModal = false;
    public $showDeleteModal = false;
    public $editMode = false;
    public $categoryId;
    public $categoryToDelete;

    public $name = '';
    public $slug = '';
    public $description = '';
    public $image = null;
    public $is_active = true;

    public $search = '';
    public $statusFilter = 'all';

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'is_active' => 'boolean',
        ];

        if ($this->editMode && $this->categoryId) {
            $rules['slug'] = 'required|string|max:255|unique:categories,slug,' . $this->categoryId;
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
        $cat = Category::findOrFail($id);
        $this->categoryId = $cat->id;
        $this->name = $cat->name;
        $this->slug = $cat->slug;
        $this->description = $cat->description ?? '';
        $this->image = $cat->image ?? null;
        $this->is_active = (bool) $cat->is_active;

        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'image' => $this->image,
            'is_active' => $this->is_active,
        ];

        if ($this->editMode) {
            $cat = Category::findOrFail($this->categoryId);
            $cat->update($data);
            session()->flash('message', 'Category updated successfully!');
        } else {
            Category::create($data);
            session()->flash('message', 'Category created successfully!');
        }

        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $this->categoryToDelete = $id;
        $this->showDeleteModal = true;
    }

    public function deleteCategory()
    {
        if ($this->categoryToDelete) {
            Category::findOrFail($this->categoryToDelete)->delete();
            session()->flash('message', 'Category deleted successfully!');
            $this->showDeleteModal = false;
            $this->categoryToDelete = null;
        }
    }

    public function toggleStatus($id)
    {
        $cat = Category::findOrFail($id);
        $cat->update(['is_active' => !$cat->is_active]);
        session()->flash('message', 'Category status updated!');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->categoryToDelete = null;
    }

    protected function resetForm()
    {
        $this->reset([
            'categoryId',
            'name',
            'slug',
            'description',
            'image',
            'is_active',
        ]);
        $this->is_active = true;
    }

    public function render()
    {
        $query = Category::withCount('products');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('slug', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->statusFilter === 'active') {
            $query->where('is_active', true);
        } elseif ($this->statusFilter === 'inactive') {
            $query->where('is_active', false);
        }

        $categories = $query->latest()->paginate(10);

        // Calculate statistics
        $totalCategories = Category::count();
        $activeCategories = Category::where('is_active', true)->count();
        $totalItemsLinked = Category::withCount('products')
            ->get()
            ->sum('products_count');

        return view('livewire.admin.category-management', [
            'categories' => $categories,
            'totalCategories' => $totalCategories,
            'activeCategories' => $activeCategories,
            'totalItemsLinked' => $totalItemsLinked,
        ])->layout('components.layouts.admin', [
            'header' => 'Categories',
        ]);
    }
}
