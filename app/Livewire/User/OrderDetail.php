<?php

namespace App\Livewire\User;

use App\Models\Order;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

#[Title('Order Details')]
class OrderDetail extends Component
{
    public ?Order $order = null;

    public function mount(Order $order)
    {
        // Ensure user can only view their own orders
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        $this->order = $order->load('items.product');
    }

    public function render()
    {
        return view('livewire.user.order-detail', [
            'order' => $this->order,
        ]);
    }
}
