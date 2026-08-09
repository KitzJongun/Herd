@extends('layouts.app')

@section('content')
<div class="page-header">
    <div class="header-content">
        <h1>Orders Management</h1>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td><strong style="color: var(--primary);">#ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</strong></td>
                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                    <td>{{ $order->customer_name }}</td>
                    <td class="price">${{ number_format($order->total_amount, 2) }}</td>
                    <td>
                        @if($order->status == 'completed')
                            <span class="badge badge-success">Completed</span>
                        @elseif($order->status == 'shipped')
                            <span class="badge badge-primary">Shipped</span>
                        @else
                            <span class="badge badge-warning">Pending</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('orders.show', $order) }}" class="btn btn-outline btn-sm">View Details</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 3rem;">
                        <p style="color: var(--text-muted);">No orders found.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
