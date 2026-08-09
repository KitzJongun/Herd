@extends('layouts.app')

@section('content')
<div class="page-header">
    <div class="header-content">
        <h1>Order Details</h1>
        <p class="subtitle">#ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
    </div>
    <a href="{{ route('orders.index') }}" class="btn btn-outline">&larr; Back to Orders</a>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; align-items: start;">
    
    <div class="card">
        <div style="padding: 1.5rem; border-bottom: 1px solid var(--border);">
            <h3 style="font-size: 1.1rem;">Purchased Items</h3>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>
                            @if($item->product)
                                <a href="{{ route('store.show', $item->product) }}" style="text-decoration: none; color: var(--primary); font-weight: 500;">
                                    {{ $item->product->name }}
                                </a>
                            @else
                                <span style="color: var(--text-muted);">Product Deleted</span>
                            @endif
                        </td>
                        <td>${{ number_format($item->price, 2) }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td class="price">${{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <div class="card" style="padding: 1.5rem;">
            <h3 style="font-size: 1.1rem; margin-bottom: 1rem; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem;">Customer Info</h3>
            <div style="margin-bottom: 0.5rem;">
                <strong>Name:</strong> <br>
                {{ $order->customer_name }}
            </div>
            <div>
                <strong>Address:</strong> <br>
                {{ $order->customer_address }}
            </div>
        </div>

        <div class="card" style="padding: 1.5rem;">
            <h3 style="font-size: 1.1rem; margin-bottom: 1rem; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem;">Order Summary</h3>
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                <span>Payment Method:</span>
                <strong>{{ $order->payment_method }}</strong>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                <span>Date:</span>
                <strong>{{ $order->created_at->format('M d, Y') }}</strong>
            </div>
            <div style="display: flex; justify-content: space-between; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border); font-size: 1.25rem;">
                <span>Total:</span>
                <strong class="price" style="color: var(--primary);">${{ number_format($order->total_amount, 2) }}</strong>
            </div>
        </div>

        <div class="card" style="padding: 1.5rem;">
            <h3 style="font-size: 1.1rem; margin-bottom: 1rem; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem;">Update Status</h3>
            <form action="{{ route('orders.updateStatus', $order) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <select name="status" class="form-control">
                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">Update Status</button>
            </form>
        </div>
    </div>

</div>
@endsection
