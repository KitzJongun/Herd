<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\CartController;

use App\Http\Controllers\OrderController;

Route::get('/', [StoreController::class, 'index'])->name('store.index');
Route::get('/product/{product}', [StoreController::class, 'show'])->name('store.show');

// Cart Routes
Route::get('cart', [CartController::class, 'index'])->name('cart.index');
Route::post('cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::delete('cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::get('checkout', [CartController::class, 'checkout'])->name('cart.checkout');
Route::post('checkout', [CartController::class, 'processCheckout'])->name('cart.process');
Route::get('receipt/{order}', [CartController::class, 'receipt'])->name('cart.receipt');

// Admin Routes
Route::resource('products', ProductController::class);
Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
Route::put('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
