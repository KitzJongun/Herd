<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\AdminAuthController;

Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');

Route::get('/', [PublicController::class, 'index'])->name('public.index');
Route::post('/tickets', [PublicController::class, 'store'])->name('public.store');
Route::get('/track', [PublicController::class, 'track'])->name('public.track');

// Admin Routes
Route::prefix('admin')->group(function () {
    // Protected Admin Routes
    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('admin.tickets.show');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    });
});
