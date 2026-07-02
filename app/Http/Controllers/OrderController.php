<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart; 
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index()
    {
        // Mengambil pesanan murni milik user yang sedang login saat ini
        $orders = Order::where('user_id', auth()->id())
            ->latest()
            ->paginate(10); 

        return view('orders.index', compact('orders'));
    }
 
    public function show(Order $order)
    {
        // Pastiin user hanya bisa lihat order miliknya sendiri (Proteksi Data Pelanggan)
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke pesanan ini.');
        }

        $order->load('items.product');

        return view('orders.show', compact('order'));
    }

    /**
     * PROSES CHECKOUT JALUR WEBSITE USER (FIX PREFIX ORD-)
     */
    public function store(Request $request)
    {
        // 1. Ambil murni data keranjang belanja milik user yang sedang login
        $cartItems = Cart::where('user_id', auth()->id())->get(); 

        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Keranjang belanja Anda kosong!');
        }

        // 2. Validasi input form dari user di front-end website
        $request->validate([
            'shipping_address' => 'required|string',
            'phone'            => 'required|string',
            'payment_method'   => 'required|in:cash,transfer,ewallet',
            'payment_proof'    => 'required_if:payment_method,transfer,ewallet|image|max:2048',
        ]);

        // 3. Simpan file bukti pembayaran ke Storage jika user memilih Transfer / E-Wallet
        $proofPath = null;
        if ($request->hasFile('payment_proof')) {
            $proofPath = $request->file('payment_proof')->store('receipts', 'public');
        }

        // Ambil data item pertama untuk backup gambar perwakilan order jika dibutuhkan
        $firstItem = $cartItems->first();
        $product = Product::find($firstItem->product_id);
        $productImage = $product ? ($product->img ?? $product->image) : null; 

        // 4. Proses hitung subtotal, pajak, & ongkir murni dari website online
        $subtotal = $cartItems->sum(fn($item) => $item->price * $item->quantity);
        $tax = round($subtotal * 0.11); // PPN 11%
        $shipping = 150000; 
        $totalAmount = $subtotal + $shipping + $tax;

        // 5. Simpan data order UTAMA ke database tabel orders dengan data USER ASLI
        $order = Order::create([
            'order_number'     => 'ORD-' . strtoupper(Str::random(8)), // <── PREFIX FIX WEBSITE SELALU ORD-
            'user_id'          => auth()->id(), 
            'total_amount'     => $totalAmount,
            'status'           => 'pending', 
            'payment_method'   => $request->payment_method,
            'payment_status'   => 'unpaid',
            'shipping_address' => $request->shipping_address, 
            'phone'            => $request->phone,
            'notes'            => $request->notes,
            'payment_proof'    => $proofPath, 
            'image'            => $productImage, 
        ]);

        // 6. Pindahkan item dari cart ke tabel detail pesanan (OrderItem)
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $item->product_id,
                'quantity'   => $item->quantity,
                'price'      => $item->price,
            ]);

            // Potong stok produk di database secara dinamis
            $prod = Product::find($item->product_id);
            if ($prod) {
                if (\Schema::hasColumn('products', 'stock')) {
                    $prod->decrement('stock', $item->quantity);
                } else {
                    $prod->decrement('stok', $item->quantity);
                }
            }
        }

        // 7. Bersihkan isi keranjang belanja user setelah sukses melakukan transaksi
        Cart::where('user_id', auth()->id())->delete();

        return redirect()->route('orders.index')->with('success', 'Pesanan Anda berhasil dibuat!');
    }
}