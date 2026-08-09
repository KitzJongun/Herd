@extends('layouts.app')

@section('content')
<div class="page-header">
    <div class="header-content">
        <h1>Checkout</h1>
        <p class="subtitle">Complete your purchase</p>
    </div>
    <a href="{{ route('cart.index') }}" class="btn btn-outline">Back to Cart</a>
</div>

<div class="cart-layout" style="display: grid; grid-template-columns: 1fr 350px; gap: 2rem; align-items: start;">
    <div class="card form-card" style="max-width: none; margin: 0;">
        <h3 style="margin-bottom: 1.5rem; font-size: 1.25rem;">Shipping Information</h3>
        <form action="{{ route('cart.process') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="customer_name" class="form-label">Full Name</label>
                <input type="text" id="customer_name" name="customer_name" class="form-control @error('customer_name') is-invalid @enderror" value="{{ old('customer_name') }}" required placeholder="John Doe">
                @error('customer_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="customer_address" class="form-label">Complete Delivery Address</label>
                <textarea id="customer_address" name="customer_address" rows="3" class="form-control @error('customer_address') is-invalid @enderror" required placeholder="123 Main Street, City, Country">{{ old('customer_address') }}</textarea>
                @error('customer_address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <h3 style="margin: 2rem 0 1.5rem; font-size: 1.25rem; border-top: 1px solid var(--border); padding-top: 2rem;">Payment Method</h3>
            
            <div class="form-group">
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <label style="display: flex; align-items: center; gap: 1rem; padding: 1rem; border: 1px solid var(--border); border-radius: 8px; cursor: pointer; transition: all 0.2s;">
                        <input type="radio" name="payment_method" value="Bank Transfer" required checked style="width: 1.2rem; height: 1.2rem; accent-color: var(--primary);">
                        <div>
                            <div style="font-weight: 600; color: var(--text-main);">Bank Transfer</div>
                            <div style="font-size: 0.85rem; color: var(--text-muted);">Direct transfer to our bank account</div>
                        </div>
                    </label>
                    
                    <label style="display: flex; align-items: center; gap: 1rem; padding: 1rem; border: 1px solid var(--border); border-radius: 8px; cursor: pointer; transition: all 0.2s;">
                        <input type="radio" name="payment_method" value="Credit Card" required style="width: 1.2rem; height: 1.2rem; accent-color: var(--primary);">
                        <div>
                            <div style="font-weight: 600; color: var(--text-main);">Credit Card</div>
                            <div style="font-size: 0.85rem; color: var(--text-muted);">Pay securely using Visa or Mastercard</div>
                        </div>
                    </label>
                </div>
                @error('payment_method')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-actions" style="margin-top: 2rem;">
                <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">Place Order Securely</button>
            </div>
        </form>
    </div>

    <div class="card" style="padding: 1.5rem; background: #FAFAFA;">
        <h3 style="margin-bottom: 1.5rem; border-bottom: 1px solid var(--border); padding-bottom: 1rem;">Order Summary</h3>
        
        <div style="margin-bottom: 1.5rem; max-height: 300px; overflow-y: auto;">
            @foreach($cart as $details)
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <span style="font-size: 0.85rem; background: var(--border); padding: 0.2rem 0.5rem; border-radius: 4px; font-weight: 600;">{{ $details['quantity'] }}x</span>
                        <span style="font-size: 0.95rem; font-weight: 500;">{{ Str::limit($details['name'], 20) }}</span>
                    </div>
                    <span class="price" style="font-size: 0.95rem;">${{ number_format($details['price'] * $details['quantity'], 2) }}</span>
                </div>
            @endforeach
        </div>

        <div style="display: flex; justify-content: space-between; margin-bottom: 1rem; color: var(--text-muted);">
            <span>Subtotal</span>
            <span class="price">${{ number_format($total, 2) }}</span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 1.5rem; color: var(--text-muted);">
            <span>Shipping</span>
            <span style="color: var(--success); font-weight: 500;">Free</span>
        </div>
        <div style="display: flex; justify-content: space-between; border-top: 1px solid var(--border); padding-top: 1rem; font-size: 1.25rem; font-weight: 700;">
            <span>Total</span>
            <span class="price" style="color: var(--primary);">${{ number_format($total, 2) }}</span>
        </div>
    </div>
</div>

<style>
@media (max-width: 768px) {
    .cart-layout {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection
