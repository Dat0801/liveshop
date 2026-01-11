<?php

namespace App\Livewire\User;

use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

#[Title('Order History')]
class OrderHistory extends Component
{
    public int $perPage = 10;
    public int $currentPage = 1;

    #[\Livewire\Attributes\Computed]
    public function orders()
    {
        return Auth::user()
            ->orders()
            ->with('items')
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage, page: $this->currentPage);
    }

    public function showOrderDetails($orderId)
    {
        return redirect()->route('order.detail', $orderId);
    }

    public function render()
    {
        return view('livewire.user.order-history', [
            'orders' => $this->orders,
        ]);
    }
}
