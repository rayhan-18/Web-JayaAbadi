<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::where('role', 'user')
            ->withCount('orders')
            ->withSum('orders', 'total_amount')
            ->latest()
            ->paginate(10);

        // Menghitung jumlah member VIP (yang total ordernya >= 5)
        $vipCount = User::where('role', 'user')
            ->has('orders', '>=', 5)
            ->count();

        $stats = [
            'total'  => User::where('role', 'user')->count(),
            'vip'    => $vipCount, // KEY VIP SEKARANG SUDAH ADA
            'new'    => User::where('role', 'user')
                            ->whereMonth('created_at', now()->month)
                            ->whereYear('created_at', now()->year)
                            ->count(),
            'active' => User::where('role', 'user')
                            ->whereHas('orders', function($q) {
                                $q->whereMonth('created_at', now()->month)
                                  ->whereYear('created_at', now()->year);
                            })->count(),
        ];

        $customersJson = $customers->map(function($c) {
            return [
                'id_asli'     => $c->id, // <--- TAMBAHKAN BARIS INI (ID asli database tanpa padding)
                'id'          => 'CST-' . str_pad($c->id, 4, '0', STR_PAD_LEFT),
                'nama'        => $c->name,
                'email'       => $c->email,
                'hp'          => $c->phone ?? '-',
                'total_order' => $c->orders_count,
                'total_spent' => (float) ($c->orders_sum_total_amount ?? 0),
                'tipe'        => $c->orders_count >= 5 ? 'VIP' : 'Regular',
                'join_date'   => $c->created_at->format('d M Y'),
                'alamat'      => '-',
            ];
        });

        return view('admin.customer.index', compact('customers', 'stats', 'customersJson'));
    }


    public function getCustomerOrders($id)
    {
    // Mengambil data user berdasarkan ID yang dikirim dari JavaScript beserta riwayat ordernya
    $user = User::where('role', 'user')->with('orders')->findOrFail($id);
    
    // Mengembalikan data dalam bentuk JSON agar bisa dibaca oleh JavaScript
    return response()->json([
        'orders' => $user->orders->map(function($order) {
            return [
                'invoice' => 'INV-' . str_pad($order->id, 5, '0', STR_PAD_LEFT),
                'tanggal' => $order->created_at->format('d M Y'),
                'total'   => (float) $order->total_amount,
                'status'  => $order->status ?? 'Selesai', // Sesuaikan dengan kolom status di table order-mu
            ];
        })
    ]);
    }
}