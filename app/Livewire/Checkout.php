<?php

namespace App\Livewire;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\Validate;
use DomainException;

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

    public $coupon_code = '';
    public $coupon_applied = false;
    public $coupon_error = '';

    public $selected_shipping_method_id = null;
    public $available_shipping_methods = [];

    public $saved_addresses = [];
    public $selected_billing_address_id = null;
    public $selected_shipping_address_id = null;
    public $save_billing_address = false;
    public $save_shipping_address = false;

    public $payment_method = 'cod';
    public $available_payment_methods = [
        'cod' => 'Cash on Delivery',
        'bank_transfer' => 'Bank Transfer',
        'credit_card' => 'Credit Card',
        'paypal' => 'PayPal',
    ];

    public $subtotal = 0;
    public $tax = 0;
    public $shipping = 0;
    public $discount = 0;
    public $total = 0;

    public function mount(CartService $cartService)
    {
        $this->loadCart($cartService);
        $this->loadShippingMethods();
        $this->loadSavedAddresses();
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
        
        // Calculate shipping based on selected method
        if ($this->selected_shipping_method_id) {
            $method = \App\Models\ShippingMethod::find($this->selected_shipping_method_id);
            if ($method && $method->isAvailable($this->subtotal, 0, $this->billing_country ?: null)) {
                $this->shipping = $method->calculateRate($this->subtotal, 0);
            } else {
                $this->shipping = 0;
            }
        } else {
            $this->shipping = 0;
        }
        
        $this->total = $this->subtotal + $this->tax + $this->shipping - $this->discount;
    }

    protected function loadShippingMethods()
    {
        $this->available_shipping_methods = \App\Models\ShippingMethod::active()
            ->get()
            ->filter(function ($method) {
                return $method->isAvailable($this->subtotal, 0, $this->billing_country ?: null);
            });

        // Auto-select first available method
        if ($this->available_shipping_methods->isNotEmpty() && !$this->selected_shipping_method_id) {
            $this->selected_shipping_method_id = $this->available_shipping_methods->first()->id;
        }
    }

    public function updatedSelectedShippingMethodId()
    {
        $this->calculateTotals();
    }

    public function updatedBillingCountry()
    {
        $this->loadShippingMethods();
        $this->calculateTotals();
    }

    protected function loadSavedAddresses()
    {
        $this->saved_addresses = Address::where('user_id', auth()->id())
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        // Auto-select default billing address
        $defaultAddress = $this->saved_addresses->firstWhere('is_default', true);
        if ($defaultAddress) {
            $this->selected_billing_address_id = $defaultAddress->id;
            $this->loadBillingAddress($defaultAddress->id);
        }
    }

    public function updatedSelectedBillingAddressId($value)
    {
        if ($value) {
            $this->loadBillingAddress($value);
        }
    }

    public function updatedSelectedShippingAddressId($value)
    {
        if ($value) {
            $this->loadShippingAddress($value);
        }
    }

    protected function loadBillingAddress($addressId)
    {
        $address = Address::find($addressId);
        if ($address && $address->user_id === auth()->id()) {
            $this->billing_name = $address->full_name;
            $this->billing_phone = $address->phone_number;
            $this->billing_address = $address->street_address;
            $this->billing_city = $address->city;
            $this->billing_state = $address->state;
            $this->billing_zip = $address->postal_code;
            $this->billing_country = $address->country;
            
            $this->loadShippingMethods();
            $this->calculateTotals();
        }
    }

    protected function loadShippingAddress($addressId)
    {
        $address = Address::find($addressId);
        if ($address && $address->user_id === auth()->id()) {
            $this->shipping_name = $address->full_name;
            $this->shipping_phone = $address->phone_number;
            $this->shipping_address = $address->street_address;
            $this->shipping_city = $address->city;
            $this->shipping_state = $address->state;
            $this->shipping_zip = $address->postal_code;
            $this->shipping_country = $address->country;
        }
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

    public function applyCoupon()
    {
        $this->coupon_error = '';
        $this->coupon_applied = false;
        $this->discount = 0;

        if (empty($this->coupon_code)) {
            $this->coupon_error = 'Please enter a coupon code.';
            return;
        }

        $coupon = \App\Models\Coupon::where('code', $this->coupon_code)->first();

        if (!$coupon) {
            $this->coupon_error = 'Invalid coupon code.';
            return;
        }

        if (!$coupon->isValid(auth()->id())) {
            $this->coupon_error = 'This coupon is not valid or has expired.';
            return;
        }

        if ($coupon->min_purchase && $this->subtotal < $coupon->min_purchase) {
            $this->coupon_error = 'Minimum purchase of $' . number_format($coupon->min_purchase, 2) . ' required.';
            return;
        }

        // Convert items to array format for isApplicable check
        $cartItems = $this->items->map(function ($item) {
            return ['product_id' => $item->product_id];
        })->toArray();

        if (!$coupon->isApplicable($cartItems)) {
            $this->coupon_error = 'This coupon is not applicable to items in your cart.';
            return;
        }

        $this->discount = $coupon->calculateDiscount($this->subtotal);
        $this->coupon_applied = true;
        $this->calculateTotals();

        session()->flash('message', 'Coupon applied successfully!');
    }

    public function removeCoupon()
    {
        $this->coupon_code = '';
        $this->coupon_applied = false;
        $this->coupon_error = '';
        $this->discount = 0;
        $this->calculateTotals();
    }

    public function placeOrder(CartService $cartService)
    {
        $this->validate();

        if (!$this->items || $this->items->isEmpty()) {
            session()->flash('error', 'Your cart is empty!');
            return;
        }

        if (!$this->selected_shipping_method_id) {
            session()->flash('error', 'Please select a shipping method.');
            return;
        }

        if (!$this->payment_method) {
            session()->flash('error', 'Please select a payment method.');
            return;
        }

        try {
            $order = DB::transaction(function () use ($cartService) {
                // Refresh cart items for latest stock/price
                $this->items = $cartService->getItems();
                $this->calculateTotals();

                // Lock and validate stock
                $lockedProducts = [];
                foreach ($this->items as $item) {
                    $product = Product::with('variants')->lockForUpdate()->find($item->product_id);

                    if (!$product) {
                        throw new DomainException('A product in your cart no longer exists.');
                    }

                    $available = $cartService->getAvailableStock($product, $item->variants ?? []);

                    if ($available < $item->quantity) {
                        throw new DomainException("Not enough stock for {$product->name}.");
                    }

                    $lockedProducts[$item->product_id] = $product;
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
                    'coupon_code' => $this->coupon_applied ? $this->coupon_code : null,
                    'discount_amount' => $this->discount,
                    'shipping_method_id' => $this->selected_shipping_method_id,
                    'payment_method' => $this->payment_method,
                    'payment_status' => $this->payment_method === 'cod' ? 'pending' : 'awaiting_payment',
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

                // Create order items and decrement stock
                foreach ($this->items as $item) {
                    $product = $lockedProducts[$item->product_id];

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item->product_id,
                        'product_name' => $product->name,
                        'product_sku' => $product->sku,
                        'variants' => $item->variants,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'subtotal' => $item->subtotal,
                    ]);

                    if ($product->stock_quantity < $item->quantity) {
                        throw new DomainException("Not enough stock for {$product->name}.");
                    }

                    $product->decrement('stock_quantity', $item->quantity);

                    foreach ($item->variants ?? [] as $type => $value) {
                        $variant = $product->variants
                            ->where('type', $type)
                            ->where('value', $value)
                            ->first();

                        if ($variant) {
                            if ($variant->stock_quantity < $item->quantity) {
                                throw new DomainException("Not enough stock for {$product->name} ({$type}: {$value}).");
                            }

                            $variant->decrement('stock_quantity', $item->quantity);
                        }
                    }
                }

                // Save billing address if requested
                if ($this->save_billing_address && !$this->selected_billing_address_id) {
                    Address::create([
                        'user_id' => auth()->id(),
                        'full_name' => $this->billing_name,
                        'phone_number' => $this->billing_phone,
                        'street_address' => $this->billing_address,
                        'city' => $this->billing_city,
                        'state' => $this->billing_state,
                        'postal_code' => $this->billing_zip,
                        'country' => $this->billing_country,
                        'is_default' => Address::where('user_id', auth()->id())->count() === 0,
                    ]);
                }

                // Save shipping address if requested
                if ($this->save_shipping_address && !$this->same_as_billing && !$this->selected_shipping_address_id) {
                    Address::create([
                        'user_id' => auth()->id(),
                        'full_name' => $this->shipping_name,
                        'phone_number' => $this->shipping_phone,
                        'street_address' => $this->shipping_address,
                        'city' => $this->shipping_city,
                        'state' => $this->shipping_state,
                        'postal_code' => $this->shipping_zip,
                        'country' => $this->shipping_country,
                        'is_default' => false,
                    ]);
                }

                // Increment coupon usage
                if ($this->coupon_applied && $this->coupon_code) {
                    $coupon = \App\Models\Coupon::where('code', $this->coupon_code)->first();
                    if ($coupon) {
                        $coupon->incrementUsage();
                    }
                }

                // Clear cart (session)
                $cartService->clear();

                return $order;
            });
        } catch (DomainException $e) {
            session()->flash('error', $e->getMessage());
            return;
        }

        return redirect()->route('order.success', $order->id)
            ->with('success', 'Order placed successfully!');
    }

    public function render()
    {
        return view('livewire.checkout');
    }
}
