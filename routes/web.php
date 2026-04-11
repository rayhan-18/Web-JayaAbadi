<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

// ========================
// PUBLIC ROUTES (STATIS)
// ========================
Route::get('/', [HomeController::class, 'index'])->name('home');

// Halaman produk statis (langsung ke view)
Route::get('/products', function () {
    return view('products.index');
})->name('products.index');

Route::get('/category/{slug}', function ($slug) {
    return view('products.category');
})->name('products.category');

Route::get('/product/{slug}', function () {
    return view('products.show');
})->name('products.show');

Route::get('/search', function () {
    return view('products.index');
})->name('products.search');

// ========================
// CART ROUTES (session based)
// ========================
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/clear', [CartController::class, 'clear'])->name('cart.clear');
});

// ========================
// AUTH ROUTES (Breeze)
// ========================
require __DIR__.'/auth.php';

// ========================
// PROTECTED ROUTES (login required)
// ========================
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});