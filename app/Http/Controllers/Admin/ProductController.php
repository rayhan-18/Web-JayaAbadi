<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
public function index(Request $request)
{
    $query = Product::with('category');

    // Search: nama produk saja (sku dihapus karena kolom tidak ada)
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where('name', 'like', "%{$search}%");
    }

    // Filter kategori
    if ($request->filled('category')) {
        $query->where('category_id', $request->category);
    }

    // Filter status — filled() sudah handle string kosong & null
    if ($request->filled('status')) {
        $query->where('is_active', (bool) $request->status);
    }

    $products = $query->latest()->paginate(10)->withQueryString();
    $categories = Category::orderBy('name')->get();

    return view('admin.product.index', compact('products', 'categories'));
}

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.product.create', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name'        => 'required|string|max:255',
                'description' => 'required|string',
                'price'       => 'required|numeric|min:0',
                'sale_price'  => 'nullable|numeric|min:0',
                'stock'       => 'required|integer|min:0',
                'category_id' => 'required|exists:categories,id',
                'image'       => 'nullable|image|max:2048',
                'is_active'   => 'nullable|boolean',
            ]);

            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('products', 'public');
            }

            Product::create([
                'name'        => $request->name,
                'slug'        => Str::slug($request->name) . '-' . Str::random(5),
                'description' => $request->description,
                'price'       => $request->price,
                'sale_price'  => $request->sale_price,
                'stock'       => $request->stock,
                'category_id' => $request->category_id,
                'image'       => $imagePath,
                'is_active'   => $request->input('is_active', 1),
            ]);

            return redirect()->route('admin.product.index')->with('success', 'Produk berhasil ditambahkan!');

        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function edit($id)
    {
        $product    = Product::findOrFail($id);
        $categories = Category::where('is_active', true)->get();
        return view('admin.product.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'price'       => 'required|numeric|min:0',
            'sale_price'  => 'nullable|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image'       => 'nullable|image|max:2048',
            'images.*'    => 'nullable|image|max:2048',
            'is_active'   => 'boolean',
        ]);

        // Update main image
        $imagePath = $product->image;
        if ($request->hasFile('image')) {
            if ($imagePath) Storage::disk('public')->delete($imagePath);
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // Update multiple images
        $imagesPaths = $product->images ?? [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $imagesPaths[] = $img->store('products', 'public');
            }
        }

        $product->update([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name) . '-' . Str::random(5),
            'description' => $request->description,
            'price'       => $request->price,
            'sale_price'  => $request->sale_price,
            'stock'       => $request->stock,
            'category_id' => $request->category_id,
            'image'       => $imagePath,
            'images'      => !empty($imagesPaths) ? $imagesPaths : null,
            'is_active'   => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.product.index')->with('success', 'Produk berhasil diupdate!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->image) Storage::disk('public')->delete($product->image);

        $product->delete();

        return redirect()->route('admin.product.index')->with('success', 'Produk berhasil dihapus!');
    }

    /**
     * Laporan Stok Gudang + Filter Instan + Download Excel & CSV
     */
public function stockReport(Request $request)
    {
        // 1. Inisialisasi Query Dasar
        $query = Product::with('category');

        // LOGIKA FILTER 1: Pencarian Teks
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('sku', 'like', '%' . $search . '%');
            });
        }

        // LOGIKA FILTER 2: Filter Kategori
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // LOGIKA FILTER 3: Filter Status Kondisi Stok
        if ($request->filled('stock_status')) {
            $status = $request->stock_status;
            if ($status == 'aman') {
                $query->where('stock', '>=', 10);
            } elseif ($status == 'menipis') {
                $query->where('stock', '>', 0)->where('stock', '<', 10);
            } elseif ($status == 'habis') {
                $query->where('stock', '<=', 0);
            }
        }

        // Ambil data produk ter-filter
        $products = $query->latest()->get();

        // LOGIKA EXPORT DIRECT EXCEL (Tetap langsung download)
        if ($request->export === 'excel') {
            $filename = "Laporan_Stok_Gudang_" . date('Y-m-d_H-i') . ".xls";
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=\"$filename\"");
            header("Pragma: no-cache");
            header("Expires: 0");

            $output = fopen("php://output", "w");
            fputcsv($output, ["SKU", "Nama Produk", "Kategori", "Harga Jual", "Sisa Stok", "Status"]);

            foreach ($products as $p) {
                $statusText = $p->stock >= 10 ? 'Aman' : ($p->stock > 0 ? 'Menipis' : 'Habis');
                fputcsv($output, [
                    $p->sku ?? 'PROD-'.$p->id,
                    $p->name,
                    $p->category->name ?? '-',
                    number_format($p->price, 0, '', ''),
                    $p->stock,
                    $statusText
                ]);
            }
            fclose($output);
            exit;
        }

        // 2. Hitung Kartu Statistik Dinamis
        $totalSku = Product::count();
        $lowStockCount = Product::where('stock', '>', 0)->where('stock', '<', 10)->count();
        $emptyStockCount = Product::where('stock', '<=', 0)->count();
        $totalStockValue = Product::selectRaw('SUM(stock * price) as total_val')->first()->total_val ?? 0;

        $stats = [
            'total_sku'   => $totalSku,
            'low_stock'   => $lowStockCount,
            'empty_stock' => $emptyStockCount,
            'value'       => $totalStockValue,
        ];

        $categories = Category::all();

        return view('admin.report.stock', compact('products', 'stats', 'categories'));
    }

    public function posProducts()
{
    $products = Product::with('category')
        ->where('is_active', true)
        ->where('stock', '>', 0)
        ->get()
        ->map(function($p) {
            return [
                'id'       => $p->id,
                'nama'     => $p->name,
                'harga'    => (float) $p->price,
                'stok'     => $p->stock,
                'kategori' => $p->category->slug ?? 'lainnya',
                'img'      => $p->image ? asset('storage/' . $p->image) : 'https://placehold.co/400x400?text=' . urlencode($p->name),
                'tag'      => $p->stock <= 5 ? 'Menipis' : 'Aman',
            ];
        });

    return response()->json($products);
}

public function exportPdf(Request $request)
{
    $query = Product::with('category');

    if ($request->filled('search')) {
        $query->where('name', 'like', "%{$request->search}%");
    }
    if ($request->filled('category')) {
        $query->where('category_id', $request->category);
    }
    if ($request->filled('status')) {
        $query->where('is_active', (bool) $request->status);
    }

    $products   = $query->latest()->get();
    $categories = Category::orderBy('name')->get();

    // Cari label kategori & status yang aktif untuk ditampilkan di PDF
    $filterCategory = $request->filled('category')
        ? $categories->find($request->category)?->name
        : 'Semua Kategori';
    $filterStatus = match($request->status) {
        '1'     => 'Aktif',
        '0'     => 'Nonaktif',
        default => 'Semua Status',
    };
    $filterSearch = $request->search ?? null;

    $html = view('admin.product.pdf', compact('products', 'filterCategory', 'filterStatus', 'filterSearch'))->render();
    $pdf  = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)->setPaper('a4', 'landscape');

    return $pdf->stream('laporan-produk-' . now()->format('Y-m-d') . '.pdf');
}
}