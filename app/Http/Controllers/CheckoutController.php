<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kosong.');
        }

        $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
        return view('checkout.index', compact('cart', 'total'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string|min:10',
            'payment_method'   => 'required|in:transfer,card,cod',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong.');
        }

        DB::beginTransaction();
        try {
            // Hitung total
            $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));

            // Buat order
            $order = Order::create([
                'user_id'          => auth()->id(),
                'invoice_number'   => 'INV-' . date('Ymd') . '-' . auth()->id() . '-' . time(),
                'total_amount'     => $total,
                'status'           => 'pending',
                'shipping_address' => $request->shipping_address,
            ]);

            // Simpan order items & kurangi stok
            foreach ($cart as $id => $item) {
                $product = Product::findOrFail($id);
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stok produk '{$product->name}' tidak mencukupi (tersisa {$product->stock}).");
                }
                // Kurangi stok
                $product->decrement('stock', $item['quantity']);

                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $id,
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price'],
                ]);
            }

            // Kosongkan keranjang
            session()->forget('cart');
            DB::commit();

            return redirect()->route('orders.show', $order)->with('success', 'Pesanan berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }
}