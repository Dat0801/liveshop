@component('emails.layout')
    <h2 style="color: #1f2937; margin-top: 0;">Your Order Has Been Delivered! ✅</h2>
    
    <p>Hi {{ $order->billing_name }},</p>
    
    <p>We're excited to let you know that your order <strong>#{{ $order->order_number }}</strong> has been successfully delivered!</p>
    
    <div class="order-info">
        <h3>Delivery Details</h3>
        <p style="margin: 0;">
            <strong>Delivered To:</strong><br>
            {{ $order->shipping_name }}<br>
            {{ $order->shipping_address }}<br>
            {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}<br>
            {{ $order->shipping_country }}
        </p>
    </div>
    
    <p>We hope you're happy with your purchase! If you have any questions or concerns, please don't hesitate to reach out to us.</p>
    
    <div style="text-align: center; margin-top: 30px;">
        <a href="{{ route('order.detail', $order->id) }}" class="button">View Order Details</a>
    </div>
    
    <div style="background-color: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; margin-top: 30px; border-radius: 4px;">
        <p style="margin: 0; color: #92400e;">
            <strong>💡 Tip:</strong> We'd love to hear about your experience! Consider leaving a review for the products you purchased.
        </p>
    </div>
    
    <p style="margin-top: 30px; color: #6b7280; font-size: 14px;">
        Thank you for shopping with LiveShop!
    </p>
@endcomponent
