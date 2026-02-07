@component('emails.layout')
    <h2 style="color: #1f2937; margin-top: 0;">Your Order Has Been Shipped! 🚀</h2>
    
    <p>Hi {{ $order->billing_name }},</p>
    
    <p>Great news! Your order <strong>#{{ $order->order_number }}</strong> has been shipped and is on its way to you.</p>
    
    @if($trackingNumber)
        <div class="order-info">
            <h3>Tracking Information</h3>
            <p style="margin: 0;">
                <strong>Tracking Number:</strong> {{ $trackingNumber }}<br>
                You can track your package using the tracking number above.
            </p>
        </div>
    @endif
    
    <div class="order-info">
        <h3>Shipping Details</h3>
        <p style="margin: 0;">
            <strong>Shipping Method:</strong> {{ $order->shippingMethod->name ?? 'Standard Shipping' }}<br>
            <strong>Estimated Delivery:</strong> {{ $order->shippingMethod->processing_days_max ?? 5 }} business days<br>
            <strong>Shipping To:</strong><br>
            {{ $order->shipping_name }}<br>
            {{ $order->shipping_address }}<br>
            {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}<br>
            {{ $order->shipping_country }}
        </p>
    </div>
    
    <div style="text-align: center; margin-top: 30px;">
        <a href="{{ route('order.detail', $order->id) }}" class="button">Track Your Order</a>
    </div>
    
    <p style="margin-top: 30px; color: #6b7280; font-size: 14px;">
        We'll notify you once your order has been delivered. If you have any questions, please contact our support team.
    </p>
@endcomponent
