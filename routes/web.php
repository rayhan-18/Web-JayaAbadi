<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Admin\CategoryController;
use Illuminate\Support\Facades\Route;

// ========================
// ADMIN ROUTES (STATIS)
// ========================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard.index');
    })->name('dashboard');

    // Product CRUD
    Route::get('/produk', [App\Http\Controllers\Admin\ProductController::class, 'index'])->name('product.index');
    Route::get('/produk/create', [App\Http\Controllers\Admin\ProductController::class, 'create'])->name('product.create');
    Route::post('/produk', [App\Http\Controllers\Admin\ProductController::class, 'store'])->name('product.store');
    Route::get('/produk/{id}/edit', [App\Http\Controllers\Admin\ProductController::class, 'edit'])->name('product.edit');
    Route::put('/produk/{id}', [App\Http\Controllers\Admin\ProductController::class, 'update'])->name('product.update');
    Route::delete('/produk/{id}', [App\Http\Controllers\Admin\ProductController::class, 'destroy'])->name('product.destroy');

// CATEGORY
    Route::get('/category', [CategoryController::class, 'index'])->name('category.index');
    Route::get('/category/create', [CategoryController::class, 'create'])->name('category.create');
    Route::post('/category', [CategoryController::class, 'store'])->name('category.store');
    Route::get('/category/{id}/edit', [CategoryController::class, 'edit'])->name('category.edit');
    Route::put('/category/{id}', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('/category/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');

    // Pesanan & lainnya
    Route::get('/pesanan', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('order.index');
    Route::patch('/pesanan/{id}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('order.update.status');
    Route::get('/pembayaran', function () { return view('admin.payment.index'); })->name('payment.index');
    Route::get('/pelanggan', function () { return view('admin.customer.index'); })->name('customer.index');
    Route::get('/laporan/penjualan', function () { return view('admin.report.sales'); })->name('report.sales');
    Route::get('/laporan/stok', function () { return view('admin.report.stock'); })->name('report.stock');
    Route::get('/kasir/transaksi', function () { return view('admin.cashier.pos'); })->name('kasir.pos');
});

// ========================
// PUBLIC ROUTES (STATIS)
// PUBLIC ROUTES
// ========================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [\App\Http\Controllers\ProductController::class, 'index'])->name('products.index');
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
    Route::patch('/update/{cartItem}', [CartController::class, 'update'])->name('cart.update');
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

Route::get('/wishlist', function () {
    return view('home');
})->name('wishlist.index');
// ========================
// ADMIN ROUTES
// ========================
    Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/dashboard', function () { return view('admin.dashboard.index'); })->name('dashboard');
    Route::get('/produk', [\App\Http\Controllers\Admin\ProductController::class, 'index'])->name('product.index');    
    Route::get('/kategori', function () { return view('admin.category.index'); })->name('category.index');
    Route::get('/pesanan', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('order.index');
    Route::get('/pesanan/{id}', function ($id) { return view('admin.order.show', ['orderId' => $id]); })->name('order.show');
    Route::get('/pembayaran', [\App\Http\Controllers\Admin\OrderController::class, 'payment'])->name('payment.index');
    Route::patch('/pembayaran/{id}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updatePaymentStatus'])->name('payment.update.status');
    Route::get('/pelanggan', [\App\Http\Controllers\Admin\CustomerController::class, 'index'])->name('customer.index');
    Route::get('/customers/{id}/orders', [App\Http\Controllers\Admin\CustomerController::class, 'getCustomerOrders']);
    Route::get('/laporan/penjualan', [\App\Http\Controllers\Admin\OrderController::class, 'salesReport'])->name('report.sales');
    Route::get('/laporan/stok', [\App\Http\Controllers\Admin\ProductController::class, 'stockReport'])->name('report.stock');

    Route::get('/kasir/produk', [\App\Http\Controllers\Admin\ProductController::class, 'posProducts'])->name('kasir.products');
    Route::post('/kasir/checkout', [\App\Http\Controllers\Admin\OrderController::class, 'posCheckout'])->name('kasir.checkout');
});


//otp route email

Route::middleware('guest')->group(function () {
    Route::get('/verify-otp', [OtpController::class, 'show'])->name('otp.show');
    Route::post('/verify-otp', [OtpController::class, 'verify'])->name('otp.verify');
    Route::post('/verify-otp/resend', [OtpController::class, 'resend'])->name('otp.resend');
});

// Route::get('/whoami', function() {
//     return auth()->user();
// });
