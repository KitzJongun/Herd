@extends('layouts.app')

@section('content')
<div class="page-header">
    <div class="header-content">
        <h1>Edit Product</h1>
        <p class="subtitle">Update details for #{{ $product->id }} - {{ $product->name }}</p>
    </div>
    <a href="{{ route('products.index') }}" class="btn btn-outline">Back to List</a>
</div>

<div class="card form-card">
    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="name" class="form-label">Product Name</label>
            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="image" class="form-label">Product Image</label>
            @if($product->image)
                <div style="margin-bottom: 10px;">
                    <img src="{{ Storage::url($product->image) }}" alt="Current Image" style="max-height: 100px; border-radius: 6px;">
                </div>
            @endif
            <input type="file" id="image" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
            @error('image')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" name="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-row">
            <div class="form-group half">
                <label for="price" class="form-label">Price ($)</label>
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" id="price" name="price" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $product->price) }}" required>
                </div>
                @error('price')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group half">
                <label for="stock" class="form-label">Stock</label>
                <input type="number" id="stock" name="stock" min="0" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', $product->stock) }}" required>
                @error('stock')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary btn-lg">Update Product</button>
            <a href="{{ route('products.index') }}" class="btn btn-outline btn-lg">Cancel</a>
        </div>
    </form>
</div>
@endsection
