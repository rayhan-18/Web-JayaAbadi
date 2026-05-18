<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Auth\OtpController;
use Illuminate\Support\Facades\Route;

// ========================
// PUBLIC ROUTES
// ========================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', function () { return view('products.index'); })->name('products.index');
Route::get('/category/{slug}', function ($slug) { return view('products.category'); })->name('products.category');
Route::get('/product/{slug}', function () { return view('products.show'); })->name('products.show');
Route::get('/search', function () { return view('products.index'); })->name('products.search');
Route::get('/wishlist', function () { return view('home'); })->name('wishlist.index');

// ========================
// CART ROUTES
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
// PROTECTED ROUTES
// ========================
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});

// ========================
// ADMIN ROUTES
// ========================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/dashboard', function () { return view('admin.dashboard.index'); })->name('dashboard');
    Route::get('/produk', function () { return view('admin.product.index'); })->name('product.index');
    Route::get('/kategori', function () { return view('admin.category.index'); })->name('category.index');
    Route::get('/pesanan', function () { return view('admin.order.index'); })->name('order.index');
    Route::get('/pesanan/{id}', function ($id) { return view('admin.order.show', ['orderId' => $id]); })->name('order.show');
});


//otp route email

Route::middleware('guest')->group(function () {
    Route::get('/verify-otp', [OtpController::class, 'show'])->name('otp.show');
    Route::post('/verify-otp', [OtpController::class, 'verify'])->name('otp.verify');
    Route::post('/verify-otp/resend', [OtpController::class, 'resend'])->name('otp.resend');
});