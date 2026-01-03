<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class OrderManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $showDetailsModal = false;
    public $selectedOrder;

    public function viewOrder($id)
    {
        $this->selectedOrder = Order::with(['items.product', 'user'])->findOrFail($id);
        $this->showDetailsModal = true;
    }

    public function updateOrderStatus($orderId, $status)
    {
        $order = Order::findOrFail($orderId);
        $order->update(['status' => $status]);
        
        session()->flash('message', 'Order status updated successfully!');
        
        if ($this->selectedOrder && $this->selectedOrder->id === $orderId) {
            $this->selectedOrder = Order::with(['items.product', 'user'])->findOrFail($orderId);
        }
    }

    public function closeModal()
    {
        $this->showDetailsModal = false;
        $this->selectedOrder = null;
    }

    public function render()
    {
        $query = Order::with(['user', 'items']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('order_number', 'like', '%' . $this->search . '%')
                    ->orWhere('billing_name', 'like', '%' . $this->search . '%')
                    ->orWhere('billing_email', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $orders = $query->latest()->paginate(20);

        return view('livewire.admin.order-management', [
            'orders' => $orders,
        ])->layout('components.layouts.admin', [
            'header' => 'Orders',
        ]);
    }
}
