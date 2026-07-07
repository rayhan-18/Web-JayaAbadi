<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\OrderItem; 
use App\Models\Product;    
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Halaman Utama Manajemen Pesanan Admin + Filter + Search + Export
     */
    public function index(Request $request)
    {
        // 1. Inisialisasi Query Utama Pesanan beserta Relasinya
        $query = Order::with(['user', 'items.product']);

        // LOGIKA FILTER 1: Pencarian (Berdasarkan Invoice Nomor atau Nama Pelanggan)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        // LOGIKA FILTER 2: Filter Saluran / Sumber Transaksi (FIX TOTAL: Berdasarkan Prefix Order ID ORD- atau POS-)
        if ($request->filled('channel')) {
            if ($request->channel === 'pos') {
                $query->where('order_number', 'like', 'POS-%');
            } elseif ($request->channel === 'website') {
                $query->where('order_number', 'like', 'ORD-%');
            }
        }

        // LOGIKA FILTER 3: Filter Status Pesanan
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Dapatkan hasil data pesanan yang sudah tersaring oleh filter
        $orders = $query->latest()->paginate(10)->withQueryString();

        // LOGIKA DOWNLOAD DIRECT EXCEL
        if ($request->export === 'excel') {
            $filename = "Laporan_Pesanan_JayaAbadi_" . date('Y-m-d_H-i') . ".xls";
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=\"$filename\"");
            header("Pragma: no-cache");
            header("Expires: 0");

            $output = fopen("php://output", "w");
            fputcsv($output, ["Tanggal", "No Invoice", "Sumber", "Pelanggan", "Metode Bayar", "Total Pembayaran", "Status"]);

            $allFilteredOrders = $query->latest()->get(); 
            foreach ($allFilteredOrders as $o) {
                // DETEKSI EXCEL: Murni dari order_number prefix
                $channelText = str_starts_with($o->order_number, 'POS-') ? 'Kasir POS' : 'Website';
                
                $statusText = match($o->status) {
                    'pending' => 'Pending', 
                    'paid' => 'Diproses', 
                    'shipping' => 'Dikirim', 
                    'delivered' => 'Selesai', 
                    'cancelled' => 'Dibatalkan', 
                    default => $o->status
                };
                fputcsv($output, [
                    $o->created_at->format('d M Y H:i'),
                    $o->order_number,
                    $channelText,
                    str_starts_with($o->order_number, 'POS-') ? ($o->notes ?? 'Guest POS') : ($o->user->name ?? 'Guest'),
                    ucfirst($o->payment_method),
                    number_format($o->total_amount, 0, '', ''),
                    $statusText
                ]);
            }
            fclose($output);
            exit;
        }

        // 2. Hitung Ulang Kartu Statistik Berdasarkan Data Murni
        $stats = [
            'all'       => Order::count(),
            'pending'   => Order::where('status', 'pending')->count(),
            'paid'      => Order::where('status', 'paid')->count(),
            'shipping'  => Order::where('status', 'shipping')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
        ];

        // 3. Mapping data ter-filter untuk kebutuhan Panel Detail di Front-End
        // 3. Mapping data ter-filter untuk kebutuhan Panel Detail di Front-End
        $ordersJson = $orders->map(function($o) {
            // FIX: Cek apakah nomor invoice diawali 'POS-'
            $isPos = str_starts_with($o->order_number, 'POS-');
            $channelLabel = $isPos ? 'POS' : 'Online';
            
            return [
                'id'         => $o->order_number,
                'order_id'   => $o->id,
                // Jika dari kasir POS, tampilkan nama pelanggan dari kolom notes, jika dari web tampilkan nama user asli
                'nama'       => $isPos ? ($o->notes ?? 'Guest POS') : ($o->user->name ?? 'Guest'),
                'email'      => $isPos ? '-' : ($o->user->email ?? '-'),
                'hp'         => $o->phone,
                'tanggal'    => $o->created_at->format('d M Y H:i'),
                'total'      => (float) $o->total_amount,
                'total2'     => (float) $o->total_amount,
                'subtotal'   => (float) $o->total_amount,
                'ongkir'     => 0,
                'metode'     => ucfirst($o->payment_method),
                'status'     => match($o->status) {
                    'pending'   => 'Pending',
                    'paid'      => 'Diproses',
                    'shipping'  => 'Dikirim',
                    'delivered' => 'Selesai',
                    'cancelled' => 'Dibatalkan',
                    default     => ucfirst($o->status)
                },
                'status_raw' => $o->status,
                'alamat'     => $o->shipping_address,
                'image'      => $o->image, 
                'channel'    => $channelLabel, // Online tetap Online walau bayar cash/COD!
                
                'items'      => $o->items->map(function($i) {
                    return [
                        'nama'  => $i->product->name ?? 'Produk',
                        'qty'   => $i->quantity,
                        'harga' => (float) ($i->price * $i->quantity),
                        'icon'  => 'ti-package',
                        'img'   => $i->product->img ?? $i->product->image ?? 'https://placehold.co/100x100?text=No+Image',
                    ];
                })->toArray(),
            ];
        });

        return view('admin.order.index', compact('orders', 'stats', 'ordersJson'));
    }

    /**
     * Update Status Pengiriman / Progres Pesanan
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,shipping,delivered,cancelled',
        ]);

        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);

        return response()->json(['success' => true, 'status' => $order->status]);
    }

    /**
     * Halaman Verifikasi Pembayaran Manual (Transfer & E-Wallet)
     */
    public function payment(Request $request)
    {
        $query = Order::with(['user'])
            ->whereIn('payment_method', ['transfer', 'ewallet']);

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', '%' . $request->search . '%'));
            });
        }

        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        if ($request->filled('month')) {
            $start = \Carbon\Carbon::parse($request->month . '-01')->startOfMonth();
            $end   = \Carbon\Carbon::parse($request->month . '-01')->endOfMonth();
            $query->whereBetween('created_at', [$start, $end]);
        }

        $payments = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'total'   => Order::whereIn('payment_method', ['transfer', 'ewallet'])
                            ->where('payment_status', 'paid')->sum('total_amount'),
            'pending' => Order::whereIn('payment_method', ['transfer', 'ewallet'])
                            ->where('payment_status', 'unpaid')->count(),
            'success' => Order::whereIn('payment_method', ['transfer', 'ewallet'])
                            ->where('payment_status', 'paid')->count(),
            'failed'  => Order::whereIn('payment_method', ['transfer', 'ewallet'])
                            ->where('payment_status', 'failed')->count(),
        ];

        $paymentsJson = $payments->map(function($p) {
            return [
                'invoice'     => $p->order_number,
                'order_id'    => $p->order_number,
                'order_db_id' => $p->id,
                'bukti_foto'  => $p->payment_proof,
                'nama'        => $p->user->name ?? 'Guest',
                'email'       => $p->user->email ?? '-',
                'tanggal'     => $p->created_at->format('d M Y H:i'),
                'metode'      => ucfirst($p->payment_method),
                'jumlah'      => (float) $p->total_amount,
                'status'      => match($p->payment_status) {
                    'unpaid' => 'Menunggu Konfirmasi',
                    'paid'   => 'Berhasil',
                    'failed' => 'Gagal',
                    default  => ucfirst($p->payment_status),
                },
                'status_raw'  => $p->payment_status,
                'bank_sender' => '-',
                'rek_num'     => '-',
            ];
        });

        return view('admin.payment.index', compact('payments', 'stats', 'paymentsJson'));
    }

    /**
     * Update Status Pembayaran (Disetujui / Ditolak)
     */
    public function updatePaymentStatus(Request $request, $id)
    {
        $request->validate([
            'payment_status' => 'required|in:paid,failed',
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'payment_status' => $request->payment_status,
            'status'         => $request->payment_status === 'paid' ? 'paid' : 'cancelled',
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * FITUR REAL-TIME: Mengambil Riwayat Transaksi Spesifik Pelanggan
     */
    public function getCustomerOrders($id)
    {
        $user = User::where('role', 'user')->with('orders')->findOrFail($id);
        
        return response()->json([
            'orders' => $user->orders->map(function($order) {
                return [
                    'invoice' => $order->order_number,
                    'tanggal' => $order->created_at->format('d M Y'),
                    'total'   => (float) $order->total_amount,
                    'status'  => match($order->status) {
                        'pending'   => 'Pending',
                        'paid'      => 'Diproses',
                        'shipping'  => 'Dikirim',
                        'delivered' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                        default     => ucfirst($order->status)
                    },
                ];
            })
        ]);
    }

    /**
     * FITUR REAL-TIME: Menghasikan Laporan Penjualan Dinamis untuk Dashboard Laporan
     */
    public function salesReport(Request $request)
    {
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        $completedOrdersQuery = Order::whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('status', ['paid', 'shipping', 'delivered']);

        $totalRevenue = (float) $completedOrdersQuery->sum('total_amount');
        $totalOrdersCount = $completedOrdersQuery->count();
        $averageOrderValue = $totalOrdersCount > 0 ? $totalRevenue / $totalOrdersCount : 0;
        
        $totalItemsSold = (int) Order::whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('status', ['paid', 'shipping', 'delivered'])
            ->withSum('items', 'quantity')
            ->get()
            ->sum('items_sum_quantity');

        $startLastMonth = Carbon::now()->startOfMonth()->subMonth();
        $endLastMonth = Carbon::now()->endOfMonth()->subMonth();
        $lastMonthRevenue = Order::whereBetween('created_at', [$startLastMonth, $endLastMonth])
            ->whereIn('status', ['paid', 'shipping', 'delivered'])
            ->sum('total_amount');
        
        $revenueTrend = 0;
        if ($lastMonthRevenue > 0) {
            $revenueTrend = (($totalRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100;
        }

        $chartData = [];
        for ($i = 1; $i <= 24; $i += 2) {
            $day = str_pad($i, 2, '0', STR_PAD_LEFT);
            $dailyRevenue = Order::whereDate('created_at', Carbon::now()->setDay($i)->format('Y-m-d'))
                ->whereIn('status', ['paid', 'shipping', 'delivered'])
                ->sum('total_amount');
            
            $yPixel = 170 - (($dailyRevenue / 15000000) * 150);
            if ($yPixel < 20) $yPixel = 20;

            $chartData[] = [
                'tgl' => $day,
                'revenue' => $dailyRevenue,
                'y_pixel' => $yPixel
            ];
        }

        $recentSales = Order::with(['user', 'items'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->get();

        $stats = [
            'revenue' => $totalRevenue,
            'orders' => $totalOrdersCount,
            'aov' => $averageOrderValue,
            'items' => $totalItemsSold,
            'trend_revenue' => $revenueTrend
        ];

        return view('admin.report.sales', compact('stats', 'chartData', 'recentSales'));
    }

    /**
     * POS ENGINE: Memproses transaksi kasir langsung dari halaman POS
     */
/**
     * POS ENGINE: Memproses transaksi kasir langsung dari halaman POS (FIXED ANTI EROR 500)
     */
    public function posCheckout(Request $request)
    {
        $request->validate([
            'items'          => 'required|array',
            'items.*.id'     => 'required|exists:products,id',
            'items.*.qty'    => 'required|integer|min:1',
            'items.*.harga'  => 'required|numeric',
            'channel'        => 'required|in:offline,online',
            'notes'          => 'nullable|string',
        ]);

        $subtotal = array_sum(array_map(fn($i) => $i['harga'] * $i['qty'], $request->items));
        $shipping = $request->channel === 'online' ? 50000 : 0;
        $total    = $subtotal + $shipping;

        $firstItem = collect($request->items)->first();
        $product = Product::find($firstItem['id']);
        $productImage = $product ? $product->img : null; 

        // FIX: user_id dikembalikan ke auth()->id() agar tidak melanggar aturan NOT NULL database
        $order = Order::create([
            'order_number'     => 'POS-' . strtoupper(Str::random(8)), // <── Kunci utama: Prefix POS-
            'user_id'          => auth()->id(), 
            'total_amount'     => $total,
            'status'           => 'delivered', 
            'payment_method'   => 'cash',
            'payment_status'   => 'paid',
            'shipping_address' => 'Toko Offline',
            'phone'            => '-',
            'notes'            => $request->notes ?? 'Customer POS', 
            'image'            => $productImage, 
        ]);

        foreach ($request->items as $item) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $item['id'],
                'quantity'   => $item['qty'],
                'price'      => $item['harga'],
            ]);

            Product::find($item['id'])->decrement('stock', $item['qty']);
        }

        return response()->json([
            'success'      => true,
            'order_id'     => $order->id,        
            'order_number' => $order->order_number,
            'total'        => $total,
        ]);
    }

    public function exportPdf(Request $request)
    {
        $query = Order::with(['user', 'items.product']);

        // FIX SINKRON PDF: Murni check prefix ORD- atau POS-
        if ($request->filled('channel')) {
            if ($request->channel === 'pos') {
                $query->where('order_number', 'like', 'POS-%');
            } elseif ($request->channel === 'website') {
                $query->where('order_number', 'like', 'ORD-%');
            }
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->get();

        $filterChannel = match($request->channel) {
            'pos'     => 'Kasir POS',
            'website' => 'Website Online',
            default   => 'Semua Saluran',
        };
        $filterStatus = match($request->status) {
            'pending'   => 'Pending',
            'paid'      => 'Diproses',
            'shipping'  => 'Dikirim',
            'delivered' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default     => 'Semua Status',
        };
        $filterSearch = $request->search ?? null;

        $html = view('admin.order.pdf', compact('orders', 'filterChannel', 'filterStatus', 'filterSearch'))->render();
        $pdf  = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)->setPaper('a4', 'landscape');

        return $pdf->stream('laporan-pesanan-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Halaman Invoice (Print-Friendly di Browser)
     * (TAMBAHKAN BARIS INI BIAR ROUTE REDIRECT KASIR KEMBALI NORMAL)
     */
    public function invoice($id)
    {
        $order = Order::with(['user', 'items.product'])->findOrFail($id);

        $subtotal = $order->items->sum(fn($i) => $i->price * $i->quantity);
        $extra    = max(0, $order->total_amount - $subtotal); 

        // Deteksi sumber berdasarkan prefix invoice
        $channel = str_starts_with($order->order_number, 'POS-')
            ? 'Kasir POS (Toko)'
            : 'Website Online';

        return view('admin.order.invoice', compact('order', 'subtotal', 'extra', 'channel'));
    }

    /**
     * Download Invoice sebagai PDF
     */
    public function invoicePdf($id)
    {
        $order = Order::with(['user', 'items.product'])->findOrFail($id);

        $subtotal = $order->items->sum(fn($i) => $i->price * $i->quantity);
        $extra    = max(0, $order->total_amount - $subtotal);

        $channel = str_starts_with($order->order_number, 'POS-')
            ? 'Kasir POS (Toko)'
            : 'Website Online';

        $html = view('admin.order.invoice', compact('order', 'subtotal', 'extra', 'channel'))->render();
        $pdf  = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)->setPaper('a4', 'portrait');

        return $pdf->stream('invoice-' . $order->order_number . '.pdf');
    }
}