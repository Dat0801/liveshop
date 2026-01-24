<?php

namespace App\Livewire\Admin;

use App\Models\Coupon;
use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Order;

class CouponManagement extends Component
{
    use WithPagination;

    public $showModal = false;
    public $showDeleteModal = false;
    public $editMode = false;
    public $couponId;
    public $couponToDelete;

    public $code = '';
    public $type = 'percentage';
    public $value = 0;
    public $min_purchase = null;
    public $max_discount = null;
    public $usage_limit = null;
    public $per_user_limit = null;
    public $valid_from = '';
    public $valid_until = '';
    public $is_active = true;
    public $applicable_categories = [];
    public $applicable_products = [];

    public $search = '';
    public $statusFilter = '';
    public $tab = 'all'; // all|active|scheduled|expired

    // Top stats
    public $stats = [
        'active' => 0,
        'total_usage' => 0,
        'revenue_saved' => 0.0,
    ];

    protected function rules()
    {
        $rules = [
            'code' => 'required|string|max:50|unique:coupons,code',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'per_user_limit' => 'nullable|integer|min:1',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            'is_active' => 'boolean',
            'applicable_categories' => 'nullable|array',
            'applicable_products' => 'nullable|array',
        ];

        if ($this->editMode && $this->couponId) {
            $rules['code'] = 'required|string|max:50|unique:coupons,code,' . $this->couponId;
        }

        return $rules;
    }

    public function setTab(string $tab)
    {
        $this->tab = $tab;
        $this->resetPage();
    }

    public function generateCode()
    {
        $this->code = strtoupper(Str::random(8));
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $coupon = Coupon::findOrFail($id);
        $this->couponId = $coupon->id;
        $this->code = $coupon->code;
        $this->type = $coupon->type;
        $this->value = $coupon->value;
        $this->min_purchase = $coupon->min_purchase;
        $this->max_discount = $coupon->max_discount;
        $this->usage_limit = $coupon->usage_limit;
        $this->per_user_limit = $coupon->per_user_limit;
        $this->valid_from = $coupon->valid_from ? $coupon->valid_from->format('Y-m-d\TH:i') : '';
        $this->valid_until = $coupon->valid_until ? $coupon->valid_until->format('Y-m-d\TH:i') : '';
        $this->is_active = $coupon->is_active;
        $this->applicable_categories = $coupon->applicable_categories ?? [];
        $this->applicable_products = $coupon->applicable_products ?? [];

        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'code' => strtoupper($this->code),
            'type' => $this->type,
            'value' => $this->value,
            'min_purchase' => $this->min_purchase,
            'max_discount' => $this->max_discount,
            'usage_limit' => $this->usage_limit,
            'per_user_limit' => $this->per_user_limit,
            'valid_from' => $this->valid_from ?: null,
            'valid_until' => $this->valid_until ?: null,
            'is_active' => $this->is_active,
            'applicable_categories' => $this->applicable_categories ?: null,
            'applicable_products' => $this->applicable_products ?: null,
        ];

        if ($this->editMode) {
            $coupon = Coupon::findOrFail($this->couponId);
            $coupon->update($data);
            session()->flash('message', 'Coupon updated successfully!');
        } else {
            Coupon::create($data);
            session()->flash('message', 'Coupon created successfully!');
        }

        $this->closeModal();
    }

    public function exportCsv()
    {
        $rows = Coupon::query()->orderBy('created_at', 'desc')->get([
            'code', 'type', 'value', 'usage_count', 'usage_limit', 'valid_from', 'valid_until', 'is_active'
        ]);

        $csv = [];
        $csv[] = implode(',', ['code','type','value','usage_count','usage_limit','valid_from','valid_until','is_active']);
        foreach ($rows as $r) {
            $csv[] = implode(',', [
                $r->code,
                $r->type,
                (string) $r->value,
                (string) ($r->usage_count ?? 0),
                (string) ($r->usage_limit ?? ''),
                $r->valid_from ? $r->valid_from->toDateString() : '',
                $r->valid_until ? $r->valid_until->toDateString() : '',
                $r->is_active ? '1' : '0',
            ]);
        }

        $path = 'exports/coupons-' . now()->format('Ymd-His') . '.csv';
        Storage::disk('local')->put($path, implode("\n", $csv));
        session()->flash('message', 'CSV exported to storage/app/' . $path);
    }

    public function confirmDelete($id)
    {
        $this->couponToDelete = $id;
        $this->showDeleteModal = true;
    }

    public function deleteCoupon()
    {
        if ($this->couponToDelete) {
            Coupon::findOrFail($this->couponToDelete)->delete();
            session()->flash('message', 'Coupon deleted successfully!');
            $this->showDeleteModal = false;
            $this->couponToDelete = null;
        }
    }

    public function toggleStatus($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->update(['is_active' => !$coupon->is_active]);
        session()->flash('message', 'Coupon status updated!');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->couponToDelete = null;
    }

    protected function resetForm()
    {
        $this->reset([
            'couponId',
            'code',
            'type',
            'value',
            'min_purchase',
            'max_discount',
            'usage_limit',
            'per_user_limit',
            'valid_from',
            'valid_until',
            'is_active',
            'applicable_categories',
            'applicable_products',
        ]);
        $this->type = 'percentage';
        $this->value = 0;
        $this->is_active = true;
    }

    public function render()
    {
        $query = Coupon::query();

        if ($this->search) {
            $query->where('code', 'like', '%' . $this->search . '%');
        }

        if ($this->statusFilter === 'active') {
            $query->where('is_active', true);
        } elseif ($this->statusFilter === 'inactive') {
            $query->where('is_active', false);
        }

        // Tab filter logic
        if ($this->tab === 'active') {
            $query->valid();
        } elseif ($this->tab === 'scheduled') {
            $query->where('is_active', true)->whereNotNull('valid_from')->where('valid_from', '>', now());
        } elseif ($this->tab === 'expired') {
            $query->where(function ($q) {
                $q->whereNotNull('valid_until')->where('valid_until', '<', now())
                  ->orWhere(function ($qq) {
                      $qq->whereNotNull('usage_limit')->whereColumn('usage_count', '>=', 'usage_limit');
                  });
            });
        }

        $coupons = $query->latest()->paginate(15);
        $categories = Category::all();
        $products = Product::all();

        // Stats
        $this->stats['active'] = Coupon::valid()->count();
        $this->stats['total_usage'] = (int) Coupon::sum('usage_count');
        $this->stats['revenue_saved'] = (float) Order::whereNotNull('coupon_code')->sum('discount_amount');

        return view('livewire.admin.coupon-management', [
            'coupons' => $coupons,
            'categories' => $categories,
            'products' => $products,
            'stats' => $this->stats,
        ])->layout('components.layouts.admin', [
            'header' => 'Coupons',
        ]);
    }
}
