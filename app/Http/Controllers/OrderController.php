<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->latest()
            ->paginate(10); // ganti get() ke paginate()

        return view('orders.index', compact('orders'));
    }
 
    public function show(Order $order)
    {
        // Pastiin user hanya bisa lihat order miliknya
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('items.product');

        return view('orders.show', compact('order'));
    }
}