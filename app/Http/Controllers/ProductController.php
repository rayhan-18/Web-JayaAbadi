<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;  // <-- WAJIB ADA INI

class ProductController extends Controller
{
public function index(Request $request)
    {
        $query = Product::with('category')->where('is_active', true);

        // Filter kategori (jika ada parameter category_id)
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter rentang harga
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price') && $request->max_price < 50000000) {
            $query->where('price', '<=', $request->max_price);
        }

        $products = $query->paginate(12)->appends($request->query());
        return view('products.index', compact('products'));
    }

    public function show($slug)
    {
        $product = Product::with('category')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
        return view('products.show', compact('product'));
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $products = Product::where('category_id', $category->id)
            ->where('is_active', true)
            ->paginate(12);
        return view('products.category', compact('category', 'products'));
    }

    public function search(Request $request)  // <-- PASTIKAN REQUEST
    {
        $query = $request->input('q');
        $products = Product::where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->where('is_active', true)
            ->paginate(12);
        return view('products.index', compact('products', 'query'));
    }
}