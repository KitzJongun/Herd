@extends('layouts.app')

@section('content')
<div class="page-header">
    <a href="{{ route('store.index') }}" class="btn btn-outline btn-sm">
        &larr; Back to Store
    </a>
</div>

<div class="card" style="padding: 2rem;">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: start;">
        <div class="product-image-container" style="background: #f8f9fa; border-radius: var(--radius); padding: 2rem; display: flex; align-items: center; justify-content: center; min-height: 400px; border: 1px solid var(--border);">
            @if($product->image)
                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" style="max-width: 100%; max-height: 400px; object-fit: contain;">
            @else
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 100px; height: 100px; color: #ced4da;">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            @endif
        </div>

        <div>
            <div style="display: flex; gap: 10px; margin-bottom: 1rem;">
                <span class="badge badge-success">In Stock</span>
                <span class="badge" style="background: #e9ecef; color: var(--text-muted);">Free Shipping</span>
            </div>
            
            <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem; color: var(--text-main);">{{ $product->name }}</h1>
            
            <div class="product-rating" style="margin-bottom: 1.5rem; font-size: 1rem;">
                <span style="color: #ff9017;">★★★★☆</span>
                <span style="color: #ff9017; font-weight: 600;">4.8</span>
                <span class="product-rating-count">(342 reviews)</span>
                <span style="margin: 0 10px; color: var(--border);">|</span>
                <span style="color: var(--text-muted);">1k+ sold</span>
            </div>

            <div style="background: #f8f9fa; padding: 1.5rem; border-radius: var(--radius-sm); margin-bottom: 2rem; border: 1px solid var(--border);">
                <div style="font-size: 0.9rem; color: var(--text-muted); text-decoration: line-through; margin-bottom: 0.25rem;">
                    ${{ number_format($product->price * 1.2, 2) }}
                </div>
                <div style="font-size: 2.5rem; font-weight: 700; color: var(--danger); line-height: 1;">
                    ${{ number_format($product->price, 2) }}
                </div>
            </div>

            <div style="margin-bottom: 2rem;">
                <h4 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 0.5rem;">Description</h4>
                <p style="color: var(--text-muted); line-height: 1.6; white-space: pre-line;">
                    {{ $product->description ?: 'No description available for this product.' }}
                </p>
            </div>

            <div style="border-top: 1px solid var(--border); padding-top: 2rem;">
                <form action="{{ route('cart.add', $product) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; font-size: 1.1rem; padding: 1rem;" {{ $product->stock < 1 ? 'disabled' : '' }}>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16" style="margin-right: 8px;">
                          <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l.84 4.479 9.144-.459L13.89 4H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                        </svg>
                        Add to Cart
                    </button>
                </form>
                @if($product->stock <= 5)
                    <p style="text-align: center; color: var(--danger); font-size: 0.9rem; margin-top: 0.75rem;">Hurry! Only {{ $product->stock }} left in stock.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
