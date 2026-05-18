@extends('layouts.app')

@section('title', 'Keranjang Belanja - Sanctuari')

@section('content')
<div class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="text-center mb-10">
            <h1 class="text-3xl md:text-4xl font-serif font-semibold text-gray-900">Keranjang Belanja</h1>
            <p class="text-gray-500 mt-2">Satu langkah lagi menuju ruang impian Anda yang lebih tenang dan nyaman.</p>
        </div>

        @if($cart->items->isEmpty())
            <div class="text-center py-20">
                <i class="fa-solid fa-bag-shopping text-5xl text-gray-200 mb-4"></i>
                <p class="text-gray-400 text-lg">Keranjang kosong.</p>
                <a href="{{ route('products.index') }}" class="mt-4 inline-block text-amber-700 hover:underline">Belanja sekarang</a>
            </div>
        @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Daftar Item -->
            <div class="lg:col-span-2 space-y-6">
                @foreach($cart->items as $item)
                <div class="flex flex-col sm:flex-row gap-5 bg-white border border-gray-100 rounded-xl p-5 shadow-sm">
                    <img src="{{ $item->product->image ?? 'https://placehold.co/120x120?text=Produk' }}" class="w-28 h-28 object-cover rounded-lg">
                    <div class="flex-1 flex flex-col sm:flex-row sm:justify-between gap-4">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-800 text-lg">{{ $item->product->name }}</h3>
                            <p class="text-gray-400 text-sm mt-0.5">{{ $item->product->category->name ?? '' }}</p>
                            <p class="text-amber-700 font-bold text-lg mt-2">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                        </div>
                        <div class="flex flex-col items-end justify-between gap-3">
                            <p class="font-bold text-gray-800 text-lg item-total" data-price="{{ $item->price }}">
                                Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                            </p>
                            <div class="flex items-center gap-2">
                                <div class="flex items-center border border-gray-300 rounded-full overflow-hidden">
                                    <form method="POST" action="{{ route('cart.update', $item->id) }}">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="quantity" value="{{ max(1, $item->quantity - 1) }}">
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition">−</button>
                                    </form>
                                    <span class="w-12 text-center text-sm quantity-val">{{ $item->quantity }}</span>
                                    <form method="POST" action="{{ route('cart.update', $item->id) }}">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="quantity" value="{{ $item->quantity + 1 }}">
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition">+</button>
                                    </form>
                                </div>
                                <form method="POST" action="{{ route('cart.remove', $item->id) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-500 transition text-sm">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Ringkasan Pesanan -->
            @php
                $subtotal = $cart->items->sum(fn($i) => $i->price * $i->quantity);
                $shipping = 150000;
                $tax = round($subtotal * 0.11);
                $total = $subtotal + $shipping + $tax;
            @endphp
            <div class="bg-gray-50 rounded-xl p-6 h-fit sticky top-24">
                <h3 class="text-xl font-semibold text-gray-800 mb-5">Ringkasan Pesanan</h3>
                <div class="space-y-3 text-gray-600">
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Estimasi Pengiriman</span>
                        <span>Rp {{ number_format($shipping, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Pajak (PPN 11%)</span>
                        <span>Rp {{ number_format($tax, 0, ',', '.') }}</span>
                    </div>
                    <div class="border-t border-gray-200 pt-3 mt-3">
                        <div class="flex justify-between font-bold text-lg">
                            <span>Total</span>
                            <span class="text-amber-700">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                <a href="{{ route('checkout') }}" class="block text-center bg-gray-900 hover:bg-amber-700 text-white py-3 rounded-full font-medium mt-6 transition">
                    Lanjut ke Pembayaran →
                </a>
                <div class="text-center mt-4">
                    <a href="{{ route('products.index') }}" class="text-sm text-gray-500 hover:text-amber-700 transition">
                        ← Kembali Belanja
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection