<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\CartController;

class CheckoutController extends Controller
{
    /**
     * Halaman Checkout Website
     */
    public function index()
    {
        $cart = app(CartController::class)->getOrCreateCart()->load('items.product');

        return view('checkout.index', compact('cart'));
    }    
    
    /**
     * Proses Pembuatan Pesanan Pelanggan
     */
    public function process(Request $request)
    {
        // Instansiasi data keranjang belanja
        $cart = app(CartController::class)->getOrCreateCart()->load('items.product');

        // Validasi pengaman keranjang kosong
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('products.index')->with('error', 'Keranjang belanja Anda kosong.');
        }

        // Validasi input form dari front-end website
        $request->validate([
            'shipping_address' => 'required|string',
            'phone'            => 'required|string',
            'payment_method'   => 'required|in:cash,transfer,ewallet',
            'payment_proof'    => 'required_if:payment_method,transfer,ewallet|image|max:2048',
        ]);

        // Simpan file bukti pembayaran ke Storage Public jika ada
        $proofPath = null;
        if ($request->hasFile('payment_proof')) {
            $proofPath = $request->file('payment_proof')->store('receipts', 'public');
        }

        // Hitung akumulasi nilai tagihan
        $subtotal = $cart->items->sum(fn($i) => $i->price * $i->quantity);
        $shipping = 150000;
        $tax = round($subtotal * 0.11);
        $total = $subtotal + $shipping + $tax;

        // Simpan data order utama ke database tabel orders
        $order = Order::create([
            'order_number'     => 'ORD-' . strtoupper(Str::random(8)),
            'user_id'          => auth()->id(),
            'total_amount'     => $total,
            'status'           => 'pending', 
            'payment_method'   => $request->payment_method,
            'payment_status'   => 'unpaid', // <-- SEKARANG DEFAULT 'unpaid' AGAR HARUS DIVERIFIKASI ADMIN
            'shipping_address' => $request->shipping_address,
            'phone'            => $request->phone,
            'notes'            => $request->notes,
            'payment_proof'    => $proofPath, 
        ]);

        // Pindahkan data dari tabel keranjang ke tabel Detail Pesanan (OrderItem)
        foreach ($cart->items as $item) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $item->product_id,
                'quantity'   => $item->quantity,
                'price'      => $item->price,
            ]);

            // Potong jumlah stok produk di database
            if ($item->product) {
                $item->product->decrement('stock', $item->quantity);
            }
        }

        // Bersihkan isi keranjang belanja
        $cart->items()->delete();
        $cart->delete();

        return redirect()->route('orders.index')->with('success', 'Pesanan Anda berhasil dibuat! Silakan tunggu admin memverifikasi bukti pembayaran Anda.');
    }
}