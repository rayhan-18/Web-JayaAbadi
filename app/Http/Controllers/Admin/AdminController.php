<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Mengolah Data Dashboard Utama secara Real-Time dan Sinkron Database
     */
    public function dashboard()
    {
        // 1. HITUNG 4 BLOK KARTU UTAMA
        $stats = [
            'products' => Product::count(),
            'orders'   => Order::count(),
            'revenue'  => (float) Order::where('payment_status', 'paid')
                                    ->where('status', '!=', 'cancelled')
                                    ->sum('total_amount'),
            'users'    => User::where('role', 'user')->count(),
        ];

        // 2. RANGKUMAN PENDAPATAN (HARI INI, MINGGU INI, BULAN INI)
        $revenueSummary = [
            'today' => (float) Order::where('payment_status', 'paid')->whereDate('created_at', Carbon::today())->sum('total_amount'),
            'week'  => (float) Order::where('payment_status', 'paid')->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('total_amount'),
            'month' => (float) Order::where('payment_status', 'paid')->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->sum('total_amount'),
        ];

        // 3. TABEL KIRI: AMBIL 5 PESANAN PALING BARU MASUK
        $recentOrders = Order::with('user')->latest()->take(5)->get();

        // 4. KARTU KANAN: AMBIL 5 PRODUK PALING BANYAK TERJUAL
        $bestSellersRaw = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_terjual'))
            ->groupBy('product_id')
            ->orderBy('total_terjual', 'desc')
            ->take(5)
            ->get();

        $topProducts = [];
        foreach ($bestSellersRaw as $item) {
            $product = Product::find($item->product_id);
            if ($product) {
                $topProducts[] = [
                    'nama'    => $product->name,
                    'terjual' => (int) $item->total_terjual,
                    'harga'   => (float) $product->price,
                    'img'     => $product->image ? asset('storage/' . $product->image) : 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=100&auto=format&fit=crop&q=60'
                ];
            }
        }

        // Fallback jika data pesanan pembeli masih kosong sama sekali di database
        if (empty($topProducts)) {
            $topProducts = Product::take(5)->get()->map(function($p) {
                return [
                    'nama'    => $p->name,
                    'terjual' => 0,
                    'harga'   => (float) $p->price,
                    'img'     => $p->image ? asset('storage/' . $p->image) : 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=100&auto=format&fit=crop&q=60'
                ];
            })->toArray();
        }

        // Kirimkan semua variabel murni ke folder view admin/dashboard/index.blade.php kamu
        return view('admin.dashboard.index', compact('stats', 'revenueSummary', 'recentOrders', 'topProducts'));
    }
}