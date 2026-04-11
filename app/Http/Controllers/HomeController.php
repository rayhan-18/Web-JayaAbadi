<?php

namespace App\Http\Controllers;

use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil 4 produk untuk ditampilkan di bagian "Produk Terpopuler"
        // Sementara ini ambil produk terbaru, nanti bisa diubah sesuai logika popularitas
        $popularProducts = Product::with('category')->latest()->take(4)->get();

        return view('home', compact('popularProducts'));
    }
}