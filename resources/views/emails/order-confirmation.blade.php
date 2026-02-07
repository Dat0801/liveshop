@component('emails.layout')
    <h2 style="color: #1f2937; margin-top: 0;">Thank You for Your Order!</h2>
    
    <p>Hi {{ $order->billing_name }},</p>
    
    <p>We're excited to confirm that we've received your order <strong>#{{ $order->order_number }}</strong>. Your order is being processed and you'll receive another email when it ships.</p>
    
    <div class="order-info">
        <h3>Order Summary</h3>
        
        <div style="margin-bottom: 15px;">
            <strong>Order Number:</strong> {{ $order->order_number }}<br>
            <strong>Order Date:</strong> {{ $order->created_at->format('F d, Y h:i A') }}<br>
            <strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}<br>
            <strong>Payment Status:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
        </div>
        
        <h4 style="margin-top: 20px; margin-bottom: 10px;">Items Ordered:</h4>
        @foreach($order->items as $item)
            <div class="order-item">
                <div style="display: flex; justify-content: space-between;">
                    <div>
                        <strong>{{ $item->product_name }}</strong><br>
                        <small style="color: #6b7280;">SKU: {{ $item->product_sku }}</small>
                        @if($item->variants)
                            <br><small style="color: #6b7280;">
                                @foreach($item->variants as $type => $value)
                                    {{ ucfirst($type) }}: {{ $value }}@if(!$loop->last), @endif
                                @endforeach
                            </small>
                        @endif
                    </div>
                    <div style="text-align: right;">
                        <strong>${{ number_format($item->price, 2) }}</strong><br>
                        <small>Qty: {{ $item->quantity }}</small>
                    </div>
                </div>
            </div>
        @endforeach
        
        <div style="margin-top: 20px;">
            <div class="total-row">
                <span>Subtotal:</span>
                <span>${{ number_format($order->subtotal, 2) }}</span>
            </div>
            @if($order->discount_amount > 0)
                <div class="total-row">
                    <span>Discount ({{ $order->coupon_code }}):</span>
                    <span>-${{ number_format($order->discount_amount, 2) }}</span>
                </div>
            @endif
            <div class="total-row">
                <span>Tax:</span>
                <span>${{ number_format($order->tax, 2) }}</span>
            </div>
            <div class="total-row">
                <span>Shipping:</span>
                <span>${{ number_format($order->shipping, 2) }}</span>
            </div>
            <div class="total-row final">
                <span>Total:</span>
                <span>${{ number_format($order->total, 2) }}</span>
            </div>
        </div>
    </div>
    
    <div class="order-info">
        <h3>Shipping Address</h3>
        <p style="margin: 0;">
            {{ $order->shipping_name }}<br>
            {{ $order->shipping_address }}<br>
            {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}<br>
            {{ $order->shipping_country }}<br>
            Phone: {{ $order->shipping_phone }}
        </p>
    </div>
    
    <div style="text-align: center; margin-top: 30px;">
        <a href="{{ route('order.detail', $order->id) }}" class="button">View Order Details</a>
    </div>
    
    <p style="margin-top: 30px; color: #6b7280; font-size: 14px;">
        If you have any questions about your order, please don't hesitate to contact us.
    </p>
@endcomponent
