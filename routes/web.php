<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\SalesReportController;
use Illuminate\Support\Facades\Route;

// =========================================================================
// PUBLIC ROUTES
// =========================================================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [\App\Http\Controllers\ProductController::class, 'index'])->name('products.index');
Route::get('/category/{slug}', function ($slug) { return view('products.category'); })->name('products.category');
Route::get('/product/{slug}', function () { return view('products.show'); })->name('products.show');
Route::get('/search', function () { return view('products.index'); })->name('products.search');
Route::get('/wishlist', function () { return view('home'); })->name('wishlist.index');

// =========================================================================
// CART ROUTES
// =========================================================================
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/update/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/clear', [CartController::class, 'clear'])->name('cart.clear');
});

// =========================================================================
// PROTECTED CLIENT ROUTES (Checkout & History User)
// =========================================================================
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});

// =========================================================================
// AUTH & OTP ROUTES (Guest Middleware)
// =========================================================================
require __DIR__.'/auth.php';

Route::middleware('guest')->group(function () {
    Route::get('/verify-otp', [OtpController::class, 'show'])->name('otp.show');
    Route::post('/verify-otp', [OtpController::class, 'verify'])->name('otp.verify');
    Route::post('/verify-otp/resend', [OtpController::class, 'resend'])->name('otp.resend');
});

// =========================================================================
// ADMIN ROUTES (BLOK TUNGGAL - ANTI BENTROK)
// =========================================================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'admin'])->group(function () {
    
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Product CRUD & Stock Report
    Route::get('/produk', [ProductController::class, 'index'])->name('product.index');
    Route::get('/produk/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('/produk', [ProductController::class, 'store'])->name('product.store');
    Route::get('/produk/export-pdf', [ProductController::class, 'exportPdf'])->name('product.export.pdf');
    Route::get('/pesanan/export-pdf', [AdminOrderController::class, 'exportPdf'])->name('order.export.pdf');
    Route::get('/produk/{id}/edit', [ProductController::class, 'edit'])->name('product.edit');
    Route::put('/produk/{id}', [ProductController::class, 'update'])->name('product.update');
    Route::delete('/produk/{id}', [ProductController::class, 'destroy'])->name('product.destroy');
    Route::get('/laporan/stok', [ProductController::class, 'stockReport'])->name('report.stock');
    

    // Category CRUD
    Route::get('/category', [CategoryController::class, 'index'])->name('category.index');
    Route::get('/category/create', [CategoryController::class, 'create'])->name('category.create');
    Route::post('/category', [CategoryController::class, 'store'])->name('category.store');
    Route::get('/category/{id}/edit', [CategoryController::class, 'edit'])->name('category.edit');
    Route::put('/category/{id}', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('/category/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');

    // Order & Payment
    Route::get('/pesanan', [AdminOrderController::class, 'index'])->name('order.index');
    Route::patch('/pesanan/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('order.update.status');
    Route::get('/pesanan/{id}', function ($id) { return view('admin.order.show', ['orderId' => $id]); })->name('order.show');
    Route::get('/pembayaran/export-pdf', [AdminOrderController::class, 'exportPaymentPdf'])->name('payment.export.pdf');
    Route::get('/pembayaran/export-csv', [AdminOrderController::class, 'exportPaymentCsv'])->name('payment.export.csv');
    Route::get('/pembayaran', [AdminOrderController::class, 'payment'])->name('payment.index');
    Route::patch('/pembayaran/{id}/status', [AdminOrderController::class, 'updatePaymentStatus'])->name('payment.update.status');


    // Laporan Penjualan — pakai SalesReportController
    Route::get('/laporan/penjualan', [SalesReportController::class, 'index'])->name('report.sales');
    Route::get('/laporan/penjualan/export', [SalesReportController::class, 'export'])->name('report.sales.export');

    // Pelanggan
    Route::get('/pelanggan/export-pdf', [CustomerController::class, 'exportPdf'])->name('customer.export.pdf');
    Route::get('/pelanggan/export-csv', [CustomerController::class, 'exportCsv'])->name('customer.export.csv');
    Route::get('/pelanggan', [CustomerController::class, 'index'])->name('customer.index');
    Route::get('/customers/{id}/orders', [CustomerController::class, 'getCustomerOrders']);

    // Kasir POS
    Route::get('/kasir/transaksi', function () { return view('admin.cashier.pos'); })->name('kasir.pos');
    Route::get('/kasir/produk', [ProductController::class, 'posProducts'])->name('kasir.products');
    Route::post('/kasir/checkout', [AdminOrderController::class, 'posCheckout'])->name('kasir.checkout');
    Route::get('/orders/{id}/invoice', [AdminOrderController::class, 'invoice'])->name('orders.invoice');
    Route::get('/orders/{id}/invoice/pdf', [AdminOrderController::class, 'invoicePdf'])->name('orders.invoice.pdf');
});