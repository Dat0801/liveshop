<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    public $totalRevenue;
    public $totalOrders;
    public $totalProducts;
    public $totalCustomers;
    public $recentOrders;
    public $lowStockProducts;
    public $topSellingProducts;

    public function mount()
    {
        $this->loadStatistics();
    }

    protected function loadStatistics()
    {
        // Total revenue
        $this->totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total');

        // Total orders
        $this->totalOrders = Order::count();

        // Total products
        $this->totalProducts = Product::count();

        // Total customers
        $this->totalCustomers = User::count();

        // Recent orders
        $this->recentOrders = Order::with('user')
            ->latest()
            ->limit(10)
            ->get();

        // Low stock products
        $this->lowStockProducts = Product::where('stock_quantity', '<=', 10)
            ->where('is_active', true)
            ->orderBy('stock_quantity')
            ->limit(10)
            ->get();

        // Top selling products
        $this->topSellingProducts = Product::withCount(['orderItems as total_sold' => function ($query) {
            $query->select(DB::raw('SUM(quantity)'));
        }])
            ->orderBy('total_sold', 'desc')
            ->limit(10)
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.dashboard')
            ->layout('components.layouts.admin', [
                'header' => 'Dashboard',
            ]);
    }
}
