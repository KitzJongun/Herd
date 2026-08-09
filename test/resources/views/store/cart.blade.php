@extends('layouts.app')

@section('content')
<div class="page-header">
    <div class="header-content">
        <h1>Shopping Cart</h1>
        <p class="subtitle">Review your items before checkout</p>
    </div>
    <a href="{{ route('store.index') }}" class="btn btn-outline">Continue Shopping</a>
</div>

@if(count($cart) > 0)
<div class="cart-layout" style="display: grid; grid-template-columns: 1fr 350px; gap: 2rem; align-items: start;">
    <div class="card">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cart as $id => $details)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                @if($details['image'])
                                    <img src="{{ Storage::url($details['image']) }}" alt="{{ $details['name'] }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                @else
                                    <div style="width: 60px; height: 60px; background: #f1f5f9; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #cbd5e1;">
                                        <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    </div>
                                @endif
                                <span class="product-name" style="font-size: 1rem;">{{ $details['name'] }}</span>
                            </div>
                        </td>
                        <td class="price">${{ number_format($details['price'], 2) }}</td>
                        <td>
                            <span class="badge badge-primary" style="font-size: 0.9rem; padding: 0.4rem 0.8rem;">{{ $details['quantity'] }}</span>
                        </td>
                        <td class="price" style="color: var(--primary);">${{ number_format($details['price'] * $details['quantity'], 2) }}</td>
                        <td>
                            <form action="{{ route('cart.remove') }}" method="POST" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="id" value="{{ $id }}">
                                <button type="submit" class="btn btn-sm btn-danger" title="Remove Item">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                      <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                      <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card" style="padding: 1.5rem;">
        <h3 style="margin-bottom: 1.5rem; border-bottom: 1px solid var(--border); padding-bottom: 1rem;">Order Summary</h3>
        <div style="display: flex; justify-content: space-between; margin-bottom: 1rem; color: var(--text-muted);">
            <span>Subtotal</span>
            <span class="price">${{ number_format($total, 2) }}</span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 1.5rem; color: var(--text-muted);">
            <span>Shipping</span>
            <span style="color: var(--success); font-weight: 500;">Free</span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 2rem; border-top: 1px solid var(--border); padding-top: 1rem; font-size: 1.25rem; font-weight: 700;">
            <span>Total</span>
            <span class="price" style="color: var(--primary);">${{ number_format($total, 2) }}</span>
        </div>
        <a href="{{ route('cart.checkout') }}" class="btn btn-primary btn-lg" style="width: 100%; text-align: center;">Proceed to Checkout</a>
    </div>
</div>
@else
<div class="card empty-state" style="text-align: center;">
    <div class="empty-icon" style="font-size: 4rem;">🛒</div>
    <h3 style="font-size: 1.5rem;">Your cart is empty</h3>
    <p style="margin-bottom: 2rem;">Looks like you haven't added any products yet.</p>
    <a href="{{ route('store.index') }}" class="btn btn-primary btn-lg">Start Shopping</a>
</div>
@endif

<style>
@media (max-width: 768px) {
    .cart-layout {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection
