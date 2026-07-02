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

        // ── STRATEGI AMANKAN GAMBAR UNTUK USER WEBSITE ──
        // Ambil item pertama yang dibeli dari dalam keranjang belanja
        $firstItem = $cart->items->first();
        $productImage = null;

        if ($firstItem && $firstItem->product) {
            // Ambil string gambar dari produk pertama (sesuai kolom database 'img' atau 'image')
            $productImage = $firstItem->product->img ?? $firstItem->product->image ?? null;
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
            'payment_status'   => 'unpaid', 
            'shipping_address' => $request->shipping_address,
            'phone'            => $request->phone,
            'notes'            => $request->notes,
            'payment_proof'    => $proofPath, 
            'image'            => $productImage, // <-- SEKARANG GAMBAR DIJAMIN MASUK KE DATABASE!
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
                // Mengantisipasi perbedaan nama kolom stock / stok di database lu
                if (\Schema::hasColumn('products', 'stock')) {
                    $item->product->decrement('stock', $item->quantity);
                } else {
                    $item->product->decrement('stok', $item->quantity);
                }
            }
        }

        // Bersihkan isi keranjang belanja
        $cart->items()->delete();
        $cart->delete();

        // UBAHAN DI SINI: Redirect ke halaman invoice success, bukan langsung ke orders.index
        return redirect()->route('checkout.success', $order->id)->with('success', 'Pesanan Anda berhasil dibuat!');
    }

    /**
     * Halaman Invoice Setelah Checkout Berhasil
     */
    public function success($id)
    {
        // Ambil data order beserta relasi item dan produknya
        $order = Order::with('items.product')->findOrFail($id);

        // Keamanan ekstra: Pastikan order ini milik user yang sedang login
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke invoice ini.');
        }

        return view('checkout.success', compact('order'));
    }
}