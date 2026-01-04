<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CheckoutForm extends Component
{
    public $items;

    #[Validate('required|string|max:255')]
    public $full_name = '';

    #[Validate('required|email|max:255')]
    public $email = '';

    #[Validate('required|string|max:20')]
    public $phone = '';

    #[Validate('required|string')]
    public $shipping_address = '';

    public $subtotal = 0.0;
    public $tax = 0.0;
    public $shipping = 0.0;
    public $total = 0.0;

    public $isValid = false;

    public function mount(CartService $cartService)
    {
        $this->loadCart($cartService);
        $this->calculateTotals();
        $this->recomputeValidity();
    }

    protected function loadCart(CartService $cartService): void
    {
        $this->items = $cartService->getItems();
        if (!$this->items || $this->items->isEmpty()) {
            redirect()->route('products.index')->with('error', 'Your cart is empty!');
        }
    }

    protected function calculateTotals(): void
    {
        if (!$this->items || $this->items->isEmpty()) {
            $this->subtotal = $this->tax = $this->shipping = $this->total = 0.0;
            return;
        }

        $this->subtotal = $this->items->sum('subtotal');
        $this->tax = round($this->subtotal * 0.1, 2);
        $this->shipping = $this->subtotal > 100 ? 0.0 : 10.0;
        $this->total = round($this->subtotal + $this->tax + $this->shipping, 2);
    }

    public function updated($property): void
    {
        $this->validateOnly($property);
        $this->recomputeValidity();
    }

    protected function recomputeValidity(): void
    {
        try {
            $this->validate();
            $this->isValid = true;
        } catch (\Throwable $e) {
            $this->isValid = false;
        }
    }

    public function placeOrder(CartService $cartService)
    {
        $this->validate();

        if (!$this->items || $this->items->isEmpty()) {
            session()->flash('error', 'Your cart is empty!');
            return;
        }

        $order = DB::transaction(function () use ($cartService) {
            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => 'ORD-' . strtoupper(Str::random(10)),
                'status' => 'pending',
                'subtotal' => $this->subtotal,
                'tax' => $this->tax,
                'shipping' => $this->shipping,
                'total' => $this->total,
                'billing_name' => $this->full_name,
                'billing_email' => $this->email,
                'billing_phone' => $this->phone,
                'billing_address' => $this->shipping_address,
                'billing_city' => '',
                'billing_state' => '',
                'billing_zip' => '',
                'billing_country' => '',
                'shipping_name' => $this->full_name,
                'shipping_phone' => $this->phone,
                'shipping_address' => $this->shipping_address,
                'shipping_city' => '',
                'shipping_state' => '',
                'shipping_zip' => '',
                'shipping_country' => '',
            ]);

            foreach ($this->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'product_sku' => $item->product->sku,
                    'variants' => $item->variants,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'subtotal' => $item->subtotal,
                ]);

                $item->product->decrement('stock_quantity', $item->quantity);
            }

            $cartService->clear();

            return $order;
        });

        return redirect()->route('order.success', $order->id)
            ->with('success', 'Order placed successfully!');
    }

    public function render()
    {
        return view('livewire.checkout-form');
    }
}
