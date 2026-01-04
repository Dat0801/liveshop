<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\Validate;

class Checkout extends Component
{
    public $items;

    #[Validate('required|string|max:255')]
    public $billing_name = '';

    #[Validate('required|email|max:255')]
    public $billing_email = '';

    #[Validate('required|string|max:20')]
    public $billing_phone = '';

    #[Validate('required|string')]
    public $billing_address = '';

    #[Validate('required|string|max:100')]
    public $billing_city = '';

    #[Validate('required|string|max:100')]
    public $billing_state = '';

    #[Validate('required|string|max:20')]
    public $billing_zip = '';

    #[Validate('required|string|max:100')]
    public $billing_country = '';

    public $same_as_billing = true;

    #[Validate('required_if:same_as_billing,false|nullable|string|max:255')]
    public $shipping_name = '';

    #[Validate('required_if:same_as_billing,false|nullable|string|max:20')]
    public $shipping_phone = '';

    #[Validate('required_if:same_as_billing,false|nullable|string')]
    public $shipping_address = '';

    #[Validate('required_if:same_as_billing,false|nullable|string|max:100')]
    public $shipping_city = '';

    #[Validate('required_if:same_as_billing,false|nullable|string|max:100')]
    public $shipping_state = '';

    #[Validate('required_if:same_as_billing,false|nullable|string|max:20')]
    public $shipping_zip = '';

    #[Validate('required_if:same_as_billing,false|nullable|string|max:100')]
    public $shipping_country = '';

    public $notes = '';

    public $subtotal = 0;
    public $tax = 0;
    public $shipping = 0;
    public $total = 0;

    public function mount(CartService $cartService)
    {
        $this->loadCart($cartService);
        $this->calculateTotals();
    }

    protected function loadCart(CartService $cartService)
    {
        $this->items = $cartService->getItems();
        if ($this->items->isEmpty()) {
            return redirect()->route('products.index')
                ->with('error', 'Your cart is empty!');
        }
    }

    protected function calculateTotals()
    {
        if (!$this->items || $this->items->isEmpty()) {
            return;
        }

        $this->subtotal = $this->items->sum('subtotal');
        $this->tax = $this->subtotal * 0.1; // 10% tax
        $this->shipping = $this->subtotal > 100 ? 0 : 10; // Free shipping over $100
        $this->total = $this->subtotal + $this->tax + $this->shipping;
    }

    public function updatedSameAsBilling($value)
    {
        if ($value) {
            $this->shipping_name = '';
            $this->shipping_phone = '';
            $this->shipping_address = '';
            $this->shipping_city = '';
            $this->shipping_state = '';
            $this->shipping_zip = '';
            $this->shipping_country = '';
        }
    }

    public function placeOrder(CartService $cartService)
    {
        $this->validate();

        if (!$this->items || $this->items->isEmpty()) {
            session()->flash('error', 'Your cart is empty!');
            return;
        }

        // Create order
        $order = Order::create([
            'user_id' => auth()->id(),
            'order_number' => 'ORD-' . strtoupper(Str::random(10)),
            'status' => 'pending',
            'subtotal' => $this->subtotal,
            'tax' => $this->tax,
            'shipping' => $this->shipping,
            'total' => $this->total,
            'billing_name' => $this->billing_name,
            'billing_email' => $this->billing_email,
            'billing_phone' => $this->billing_phone,
            'billing_address' => $this->billing_address,
            'billing_city' => $this->billing_city,
            'billing_state' => $this->billing_state,
            'billing_zip' => $this->billing_zip,
            'billing_country' => $this->billing_country,
            'shipping_name' => $this->same_as_billing ? $this->billing_name : $this->shipping_name,
            'shipping_phone' => $this->same_as_billing ? $this->billing_phone : $this->shipping_phone,
            'shipping_address' => $this->same_as_billing ? $this->billing_address : $this->shipping_address,
            'shipping_city' => $this->same_as_billing ? $this->billing_city : $this->shipping_city,
            'shipping_state' => $this->same_as_billing ? $this->billing_state : $this->shipping_state,
            'shipping_zip' => $this->same_as_billing ? $this->billing_zip : $this->shipping_zip,
            'shipping_country' => $this->same_as_billing ? $this->billing_country : $this->shipping_country,
            'notes' => $this->notes,
        ]);

        // Create order items
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

            // Update product stock
            $item->product->decrement('stock_quantity', $item->quantity);
        }

        // Clear cart (session)
        $cartService->clear();

        return redirect()->route('order.success', $order->id)
            ->with('success', 'Order placed successfully!');
    }

    public function render()
    {
        return view('livewire.checkout');
    }
}
