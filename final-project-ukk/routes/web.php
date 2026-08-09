<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\UserController;

// Public Routes
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::post('/ticket/submit', [PublicController::class, 'store'])->name('ticket.store');
Route::get('/lacak', [PublicController::class, 'track'])->name('lacak');
Route::post('/lacak', [PublicController::class, 'check'])->name('lacak.check');

// Admin Auth Routes
// Fallback nama 'login' yang dicari middleware bawaan Laravel
Route::get('/login', [AuthController::class, 'login'])->name('login');

Route::prefix('admin')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('admin.login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');
});

// Admin Panel Routes
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/export', [DashboardController::class, 'export'])->name('admin.export');
    
    Route::get('/tickets', [TicketController::class, 'index'])->name('admin.tickets.index');
    Route::get('/tickets/export', [TicketController::class, 'export'])->name('admin.tickets.export');
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('admin.tickets.show');
    Route::post('/tickets/{ticket}/reply', [TicketController::class, 'reply'])->name('admin.tickets.reply');
    Route::put('/tickets/{ticket}/status', [TicketController::class, 'updateStatus'])->name('admin.tickets.updateStatus');
    Route::put('/tickets/{ticket}/ai-suggest', [TicketController::class, 'acceptAiSuggest'])->name('admin.tickets.acceptAiSuggest');

    Route::resource('/users', UserController::class)->names('admin.users');
});
