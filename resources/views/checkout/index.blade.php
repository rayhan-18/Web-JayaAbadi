@extends('layouts.app')

@section('title', 'Checkout - Sanctuari')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10">
    <h1 class="text-3xl font-serif font-semibold mb-6">Checkout</h1>

    @php $cart = session()->get('cart', []); @endphp
    @if(empty($cart))
        <div class="text-center py-10">Keranjang kosong. <a href="{{ route('products.index') }}" class="text-amber-700">Belanja sekarang</a></div>
    @else
        <form action="{{ route('checkout.process') }}" method="POST">
            @csrf
            <div class="grid lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white border rounded-xl p-6">
                        <h2 class="text-xl font-semibold mb-4">Alamat Pengiriman</h2>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Alamat Lengkap</label>
                            <textarea name="shipping_address" rows="3" class="w-full border rounded-lg px-3 py-2 focus:ring-amber-500 focus:border-amber-500" required>{{ old('shipping_address') }}</textarea>
                            @error('shipping_address') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Metode Pembayaran</label>
                            <select name="payment_method" class="w-full border rounded-lg px-3 py-2">
                                <option value="transfer">Transfer Bank (Virtual Account)</option>
                                <option value="card">Kartu Kredit / Debit</option>
                                <option value="cod">Bayar di Tempat (COD)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-xl p-6 h-fit sticky top-24">
                    <h3 class="text-xl font-semibold mb-4">Ringkasan Pesanan</h3>
                    @php
                        $subtotal = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cart));
                        $shipping = 150000;
                        $tax = round($subtotal * 0.11);
                        $total = $subtotal + $shipping + $tax;
                    @endphp
                    <div class="space-y-3 text-gray-700">
                        <div class="flex justify-between"><span>Subtotal</span><span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span></div>
                        <div class="flex justify-between"><span>Pengiriman</span><span>Rp {{ number_format($shipping, 0, ',', '.') }}</span></div>
                        <div class="flex justify-between"><span>Pajak (PPN 11%)</span><span>Rp {{ number_format($tax, 0, ',', '.') }}</span></div>
                        <div class="border-t pt-3 mt-3">
                            <div class="flex justify-between font-bold text-lg">
                                <span>Total</span>
                                <span class="text-amber-700">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-gray-900 hover:bg-amber-700 text-white py-3 rounded-full font-semibold mt-6 transition">Buat Pesanan</button>
                </div>
            </div>
        </form>
    @endif
</div>
@endsection