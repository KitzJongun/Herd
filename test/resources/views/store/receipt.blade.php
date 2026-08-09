@extends('layouts.app')

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <div class="card" style="padding: 3rem; text-align: center; border-top: 8px solid var(--success);">
        <div style="width: 80px; height: 80px; background: rgba(16, 185, 129, 0.1); color: var(--success); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 40px; height: 40px;">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        
        <h1 style="font-size: 2rem; margin-bottom: 0.5rem; color: var(--text-main);">Payment Successful!</h1>
        <p style="color: var(--text-muted); margin-bottom: 2.5rem;">Thank you for your purchase. Your order has been placed.</p>

        <div style="text-align: left; background: #f8fafc; padding: 1.5rem; border-radius: 12px; margin-bottom: 2.5rem; border: 1px dashed var(--border);">
            <h3 style="font-size: 1.1rem; margin-bottom: 1rem; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem;">Receipt Details</h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                <div>
                    <span style="display: block; font-size: 0.85rem; color: var(--text-muted);">Order ID</span>
                    <span style="font-weight: 600;">#ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div>
                    <span style="display: block; font-size: 0.85rem; color: var(--text-muted);">Date</span>
                    <span style="font-weight: 600;">{{ $order->created_at->format('M d, Y h:i A') }}</span>
                </div>
                <div>
                    <span style="display: block; font-size: 0.85rem; color: var(--text-muted);">Customer Name</span>
                    <span style="font-weight: 600;">{{ $order->customer_name }}</span>
                </div>
                <div>
                    <span style="display: block; font-size: 0.85rem; color: var(--text-muted);">Payment Method</span>
                    <span style="font-weight: 600;">{{ $order->payment_method }}</span>
                </div>
            </div>

            <div style="border-top: 1px solid var(--border); padding-top: 1rem;">
                <h4 style="font-size: 0.95rem; margin-bottom: 1rem;">Items</h4>
                @foreach($order->items as $item)
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 0.95rem;">
                        <span>{{ $item->quantity }}x {{ $item->product ? $item->product->name : 'Unknown Product' }}</span>
                        <span class="price">${{ number_format($item->price * $item->quantity, 2) }}</span>
                    </div>
                @endforeach
                
                <div style="display: flex; justify-content: space-between; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border); font-size: 1.25rem; font-weight: 700;">
                    <span>Total Paid</span>
                    <span class="price" style="color: var(--primary);">${{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 1rem; justify-content: center;">
            <a href="{{ route('store.index') }}" class="btn btn-primary">Back to Store</a>
            <button onclick="window.print()" class="btn btn-outline">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="margin-right: 6px;">
                  <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
                  <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"/>
                </svg>
                Print Receipt
            </button>
        </div>
    </div>
</div>
@endsection
