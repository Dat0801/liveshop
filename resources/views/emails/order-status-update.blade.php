@component('emails.layout')
    <h2 style="color: #1f2937; margin-top: 0;">Order Status Update</h2>
    
    <p>Hi {{ $order->billing_name }},</p>
    
    <p>Your order <strong>#{{ $order->order_number }}</strong> status has been updated.</p>
    
    <div class="order-info">
        <div style="text-align: center; padding: 20px;">
            <div style="font-size: 14px; color: #6b7280; margin-bottom: 10px;">Previous Status</div>
            <div style="font-size: 18px; font-weight: 600; color: #9ca3af; text-transform: capitalize; margin-bottom: 20px;">
                {{ str_replace('_', ' ', $oldStatus) }}
            </div>
            <div style="font-size: 14px; color: #6b7280; margin-bottom: 10px;">New Status</div>
            <div style="font-size: 24px; font-weight: bold; color: #f97316; text-transform: capitalize;">
                {{ str_replace('_', ' ', $newStatus) }}
            </div>
        </div>
    </div>
    
    @if($newStatus === 'processing')
        <p>Your order is now being processed. We'll notify you once it's shipped.</p>
    @elseif($newStatus === 'shipped')
        <p>Great news! Your order has been shipped. You'll receive tracking information shortly.</p>
    @elseif($newStatus === 'delivered')
        <p>Your order has been delivered! We hope you enjoy your purchase.</p>
    @elseif($newStatus === 'cancelled')
        <p>We're sorry to inform you that your order has been cancelled. If you have any questions, please contact our support team.</p>
    @endif
    
    <div style="text-align: center; margin-top: 30px;">
        <a href="{{ route('order.detail', $order->id) }}" class="button">View Order Details</a>
    </div>
    
    <p style="margin-top: 30px; color: #6b7280; font-size: 14px;">
        If you have any questions about your order, please don't hesitate to contact us.
    </p>
@endcomponent
