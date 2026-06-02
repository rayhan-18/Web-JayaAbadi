<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function index()
    {
        $cart = $this->getOrCreateCart();
        $cart->load('items.product');
        
        return view('cart.index', compact('cart'));
    }
    
    public function add(Request $request, Product $product)
    {
        Log::info('Cart add called', ['product_id' => $product->id, 'quantity' => $request->quantity]);
        
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock
        ]);
        
        $cart = $this->getOrCreateCart();
        Log::info('Cart retrieved', ['cart_id' => $cart->id]);
        
        $cartItem = $cart->items()->where('product_id', $product->id)->first();
        
        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $request->quantity;
            if ($newQuantity > $product->stock) {
                return redirect()->back()->with('error', 'Stok tidak mencukupi!');
            }
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $product->price, // <-- Ganti final_price dengan price
            ]);
        }
        
        return redirect()->back()->with('success', 'Produk ditambahkan ke keranjang!');
    }
    
    public function update(Request $request, CartItem $cartItem)
    {
        // Pastiin product ada dulu
        if (!$cartItem->product) {
            return redirect()->route('cart.index')->with('error', 'Produk tidak ditemukan!');
        }

        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $cartItem->product->stock
        ]);

        $cartItem->update(['quantity' => $request->quantity]);

        return redirect()->route('cart.index')->with('success', 'Keranjang berhasil diupdate!');
    }    
    public function remove($id)
    {
        $cartItem = \App\Models\CartItem::findOrFail($id);
        $cartItem->delete();

        return redirect()->route('cart.index')->with('success', 'Produk dihapus dari keranjang!');
    }
    
    public function clear()
    {
        $cart = $this->getOrCreateCart();
        $cart->items()->delete();
        
        return redirect()->route('cart.index')->with('success', 'Keranjang dikosongkan!');
    }

    public function getCart()
    {
    return $this->getOrCreateCart()->load('items.product');
    }
    
    public function getOrCreateCart()
    {
        if (Auth::check()) {
            // Cek apakah ada cart guest yang perlu di-merge
            $sessionId = session()->getId();
            $guestCart = Cart::where('session_id', $sessionId)
                            ->where('is_active', true)
                            ->whereNull('user_id')
                            ->first();

            $userCart = Cart::firstOrCreate([
                'user_id'   => Auth::id(),
                'is_active' => true,
            ]);

            // Merge guest cart ke user cart
            if ($guestCart && $guestCart->id !== $userCart->id) {
                foreach ($guestCart->items as $item) {
                    $existing = $userCart->items()->where('product_id', $item->product_id)->first();
                    if ($existing) {
                        $existing->update(['quantity' => $existing->quantity + $item->quantity]);
                    } else {
                        $userCart->items()->create([
                            'product_id' => $item->product_id,
                            'quantity'   => $item->quantity,
                            'price'      => $item->price,
                        ]);
                    }
                }
                $guestCart->items()->delete();
                $guestCart->delete();
            }

            return $userCart;
        }

        $sessionId = session()->getId();
        return Cart::firstOrCreate([
            'session_id' => $sessionId,
            'is_active'  => true,
        ]);
    }
}