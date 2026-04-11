<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = auth()->user()->wishlists()->with('product')->latest()->get();
        return view('wishlist.index', compact('wishlists'));
    }

    public function add(Product $product)
    {
        auth()->user()->wishlists()->firstOrCreate(['product_id' => $product->id]);
        return back()->with('success', 'Produk ditambahkan ke wishlist');
    }

    public function remove(Product $product)
    {
        auth()->user()->wishlists()->where('product_id', $product->id)->delete();
        return back()->with('success', 'Produk dihapus dari wishlist');
    }
}