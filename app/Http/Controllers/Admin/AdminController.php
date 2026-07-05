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
use Illuminate\Support\Facades\Hash;

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

        // 5. FITUR SUPER ADMIN: Ambil daftar Admin
        $admins = [];
        // Asumsi auth()->user() memiliki properti role
        if (auth()->user()->role === 'superadmin') {
            $admins = User::where('role', 'admin')->get();
        }

        // Kirim semua data ke view
        return view('admin.dashboard.index', compact('stats', 'revenueSummary', 'recentOrders', 'topProducts', 'admins'));
    }

    /**
     * Memproses aksi on/off status akun Admin
     */
    public function superDashboard()
    {
        if (auth()->user()->role !== 'superadmin') abort(403);

        $stats = [
            'products' => Product::count(),
            'orders'   => Order::count(),
            'revenue'  => (float) Order::where('payment_status', 'paid')->where('status', '!=', 'cancelled')->sum('total_amount'),
            'users'    => User::where('role', 'user')->count(),
        ];

        $revenueSummary = [
            'today' => (float) Order::where('payment_status', 'paid')->whereDate('created_at', Carbon::today())->sum('total_amount'),
            'week'  => (float) Order::where('payment_status', 'paid')->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('total_amount'),
            'month' => (float) Order::where('payment_status', 'paid')->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->sum('total_amount'),
        ];

        $recentOrders = Order::with('user')->latest()->take(5)->get();

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

        // Fitur manajemen admin dihapus dari sini

        return view('superadmin.dashboard.index', compact('stats', 'revenueSummary', 'recentOrders', 'topProducts'));
    }

    // ==========================================
    // FITUR EKSKLUSIF SUPER ADMIN
    // ==========================================
    
    public function manageAdmins()
    {
        if (auth()->user()->role !== 'superadmin') abort(403);

        $admins = User::where('role', 'admin')->latest()->get();
        return view('superadmin.admins.index', compact('admins'));
    }

    public function storeAdmin(Request $request)
    {
        if (auth()->user()->role !== 'superadmin') abort(403);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone'    => 'nullable|string|max:20',
        ]);

        User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
            'phone'             => $request->phone,
            'role'              => 'admin',
            'is_active'         => true,
            'email_verified_at' => now(), // Bypass proses OTP
        ]);

        return back()->with('success', 'Admin baru berhasil didaftarkan dan langsung aktif.');
    }

    public function toggleAdminStatus($id)
    {
        if (auth()->user()->role !== 'superadmin') abort(403);

        $admin = User::findOrFail($id);

        if ($admin->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun sendiri.');
        }

        $admin->is_active = !$admin->is_active;
        $admin->save();

        return back()->with('success', 'Status akun admin berhasil diperbarui.');
    }
}