@extends('layouts.app')

@section('content')
<div class="storefront-header">
    <h1>Our Collection</h1>
    <p class="subtitle">Discover our premium minimalist products</p>
</div>

<div class="product-grid">
    @forelse($products as $product)
        <div class="product-card">
            <a href="{{ route('store.show', $product) }}" style="text-decoration: none; color: inherit; display: block;">
                <div class="product-card-img">
                    @if($product->image)
                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}">
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    @endif
                </div>
            </a>
            <div class="product-card-body">
                <div class="product-price-row">
                    <span class="product-card-price">${{ number_format($product->price, 2) }}</span>
                    <!-- Fake old price for design -->
                    <span class="product-card-old-price">${{ number_format($product->price * 1.2, 2) }}</span>
                </div>
                <div class="product-rating">
                    <!-- Star icons -->
                    <span style="color: #ff9017;">★★★★☆</span>
                    <span style="color: #ff9017;">4.5</span>
                    <span class="product-rating-count">(120)</span>
                </div>
                <a href="{{ route('store.show', $product) }}" style="text-decoration: none; color: inherit;">
                    <h3 class="product-card-title">{{ $product->name }}</h3>
                </a>
                <div class="product-card-footer">
                    <form action="{{ route('cart.add', $product) }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" class="btn btn-outline btn-sm" {{ $product->stock < 1 ? 'disabled' : '' }}>
                            Add to cart
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="empty-state" style="grid-column: 1 / -1; text-align: center;">
            <div class="empty-icon">🛍️</div>
            <h3>No products available</h3>
            <p>Check back later for new arrivals.</p>
        </div>
    @endforelse
</div>
@endsection
