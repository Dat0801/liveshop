<?php

namespace App\Livewire\Admin;

use App\Models\StockAdjustment;
use App\Models\Product;
use App\Models\ProductVariant;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Carbon\Carbon;

#[Layout('components.layouts.admin')]
class StockAdjustments extends Component
{
    use WithPagination;

    public $showModal = false;
    public $product_id = '';
    public $product_variant_id = '';
    public $quantity_change = 0;
    public $type = 'addition';
    public $reason = '';
    public $notes = '';

    public $search = '';
    public $typeFilter = '';
    public $reasonFilter = '';
    public $dateFilter = '30';

    protected function rules()
    {
        return [
            'product_id' => 'required|exists:products,id',
            'product_variant_id' => 'nullable|exists:product_variants,id',
            'quantity_change' => 'required|integer',
            'type' => 'required|in:addition,subtraction,return',
            'reason' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ];
    }

    public function clearFilters()
    {
        $this->reset(['search', 'typeFilter', 'reasonFilter', 'dateFilter']);
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
        $this->type = 'addition';
    }

    public function getStatistics()
    {
        $days = intval($this->dateFilter);
        $startDate = Carbon::now()->subDays($days);
        
        // Total adjustments in period
        $totalAdjustments = StockAdjustment::where('created_at', '>=', $startDate)->count();
        
        // Total additions
        $totalAdditions = StockAdjustment::where('created_at', '>=', $startDate)
            ->where('quantity_change', '>', 0)
            ->count();
        
        // Total subtractions
        $totalSubtractions = StockAdjustment::where('created_at', '>=', $startDate)
            ->where('quantity_change', '<', 0)
            ->count();
        
        // Calculate percentage changes from previous period
        $prevStartDate = Carbon::now()->subDays($days * 2);
        $prevEndDate = Carbon::now()->subDays($days);
        
        $prevTotalAdjustments = StockAdjustment::whereBetween('created_at', [$prevStartDate, $prevEndDate])->count();
        $prevTotalAdditions = StockAdjustment::whereBetween('created_at', [$prevStartDate, $prevEndDate])
            ->where('quantity_change', '>', 0)->count();
        $prevTotalSubtractions = StockAdjustment::whereBetween('created_at', [$prevStartDate, $prevEndDate])
            ->where('quantity_change', '<', 0)->count();
        
        $adjustmentsChange = $prevTotalAdjustments > 0 
            ? round((($totalAdjustments - $prevTotalAdjustments) / $prevTotalAdjustments) * 100) 
            : 0;
        $additionsChange = $prevTotalAdditions > 0 
            ? round((($totalAdditions - $prevTotalAdditions) / $prevTotalAdditions) * 100) 
            : 0;
        $subtractionsChange = $prevTotalSubtractions > 0 
            ? round((($totalSubtractions - $prevTotalSubtractions) / $prevTotalSubtractions) * 100) 
            : 0;
        
        return [
            'total_adjustments' => $totalAdjustments,
            'total_additions' => $totalAdditions,
            'total_subtractions' => $totalSubtractions,
            'adjustments_change' => $adjustmentsChange,
            'additions_change' => $additionsChange,
            'subtractions_change' => $subtractionsChange,
        ];
    }

    public function render()
    {
        $query = StockAdjustment::with(['product', 'variant', 'admin'])
            ->select('stock_adjustments.*');

        // Date filter
        if ($this->dateFilter) {
            $days = intval($this->dateFilter);
            $query->where('created_at', '>=', Carbon::now()->subDays($days));
        }

        // Search filter
        if ($this->search) {
            $query->whereHas('product', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('sku', 'like', '%' . $this->search . '%');
            });
        }

        // Type filter
        if ($this->typeFilter) {
            if ($this->typeFilter === 'addition') {
                $query->where('quantity_change', '>', 0);
            } elseif ($this->typeFilter === 'subtraction') {
                $query->where('quantity_change', '<', 0);
            } elseif ($this->typeFilter === 'return') {
                $query->where('type', 'return');
            }
        }

        // Reason filter
        if ($this->reasonFilter) {
            $query->where('reason', 'like', '%' . $this->reasonFilter . '%');
        }

        $adjustments = $query->orderByDesc('created_at')->paginate(10);
        $products = Product::all();
        $statistics = $this->getStatistics();

        return view('livewire.admin.stock-adjustments', [
            'adjustments' => $adjustments,
            'products' => $products,
            'statistics' => $statistics,
            'header' => 'Stock Adjustments',
        ]);
    }
}
