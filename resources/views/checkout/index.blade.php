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
    <label class="block text-sm font-medium mb-1">Nomor HP</label>
    <input type="text" name="phone" value="{{ old('phone') }}"
           class="w-full border rounded-lg px-3 py-2 focus:ring-amber-500 focus:border-amber-500" required>
    @error('phone') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
</div>

<div class="mb-4">
    <label class="block text-sm font-medium mb-1">Metode Pembayaran</label>
    <div class="grid grid-cols-3 gap-3 mt-2">
        <label class="border rounded-lg p-3 cursor-pointer text-center hover:border-amber-500 has-[:checked]:border-amber-600 has-[:checked]:bg-amber-50">
            <input type="radio" name="payment_method" value="cash" class="hidden" required>
            <i class="fa-solid fa-money-bill-wave text-xl text-amber-600 mb-1"></i>
            <p class="text-sm font-medium">Cash (COD)</p>
        </label>
        <label class="border rounded-lg p-3 cursor-pointer text-center hover:border-amber-500 has-[:checked]:border-amber-600 has-[:checked]:bg-amber-50">
            <input type="radio" name="payment_method" value="transfer" class="hidden">
            <i class="fa-solid fa-building-columns text-xl text-amber-600 mb-1"></i>
            <p class="text-sm font-medium">Transfer Bank</p>
        </label>
        <label class="border rounded-lg p-3 cursor-pointer text-center hover:border-amber-500 has-[:checked]:border-amber-600 has-[:checked]:bg-amber-50">
            <input type="radio" name="payment_method" value="ewallet" class="hidden">
            <i class="fa-solid fa-wallet text-xl text-amber-600 mb-1"></i>
            <p class="text-sm font-medium">E-Wallet</p>
        </label>
    </div>
    @error('payment_method') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
</div>

<div class="mb-4">
    <label class="block text-sm font-medium mb-1">Catatan (opsional)</label>
    <textarea name="notes" rows="2"
              class="w-full border rounded-lg px-3 py-2 focus:ring-amber-500 focus:border-amber-500">{{ old('notes') }}</textarea>
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