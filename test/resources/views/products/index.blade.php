@extends('layouts.app')

@section('content')
<div class="page-header">
    <div class="header-content">
        <h1>Products List</h1>
        <p class="subtitle">Manage your inventory efficiently</p>
    </div>
    <a href="{{ route('products.create') }}" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="margin-right: 8px; margin-bottom: -2px;">
            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
        </svg>
        New Product
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td>#{{ $product->id }}</td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            @if($product->image)
                                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" style="width: 48px; height: 48px; object-fit: cover; border-radius: 6px;">
                            @else
                                <div style="width: 48px; height: 48px; background: #f1f5f9; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #cbd5e1;">
                                    <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                </div>
                            @endif
                            <div>
                                <div class="product-name">{{ $product->name }}</div>
                                <div class="product-desc">{{ Str::limit($product->description, 50) }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="price">${{ number_format($product->price, 2) }}</td>
                    <td>
                        @if($product->stock > 10)
                            <span class="badge badge-success">{{ $product->stock }} in stock</span>
                        @elseif($product->stock > 0)
                            <span class="badge badge-warning">{{ $product->stock }} low stock</span>
                        @else
                            <span class="badge badge-danger">Out of stock</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline">Edit</a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="delete-form" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center empty-state">
                        <div class="empty-icon">📦</div>
                        <h3>No products found</h3>
                        <p>Get started by creating a new product.</p>
                        <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm mt-3">Create Product</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
