@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->order_number)

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-serif font-semibold">Detail Pesanan</h1>
        
        <div class="text-right">
            <span class="text-xs text-gray-500">No. Invoice</span>
            <p class="font-mono font-semibold text-xl text-gray-900">#{{ $order->order_number }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Informasi Utama -->
        <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm p-8">
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h2 class="font-semibold text-gray-900 mb-1">Informasi Pesanan</h2>
                    <p class="text-sm text-gray-600">
                        {{ $order->created_at->format('d M Y • H:i') }}
                    </p>
                </div>
                <span class="px-5 py-2 rounded-2xl text-sm font-semibold
                    @if($order->status == 'pending') bg-yellow-100 text-yellow-700
                    @elseif($order->status == 'paid') bg-blue-100 text-blue-700
                    @elseif($order->status == 'shipped') bg-purple-100 text-purple-700
                    @elseif($order->status == 'delivered') bg-emerald-100 text-emerald-700
                    @else bg-red-100 text-red-700 @endif">
                    {{ ucfirst($order->status) }}
                </span>
            </div>

            <!-- Alamat Pengiriman -->
            <div class="mb-10">
                <h3 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                    <i class="fa-solid fa-location-dot"></i>
                    Alamat Pengiriman
                </h3>
                <div class="bg-gray-50 rounded-2xl p-5 text-sm leading-relaxed">
                    {{ $order->shipping_address }}
                </div>
            </div>

            <!-- Daftar Produk -->
            <h3 class="font-semibold text-gray-900 mb-4">Produk yang Dipesan</h3>
            <div class="space-y-6">
                @foreach($order->items as $item)
                <div class="flex gap-5 bg-gray-50 rounded-2xl p-5">
                    <div class="w-20 h-20 bg-white rounded-xl flex-shrink-0 border overflow-hidden">
                        @if($item->product->image ?? false)
                            <img src="{{ asset('storage/' . $item->product->image) }}" 
                                 alt="{{ $item->product->name }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                <i class="fa-solid fa-image text-gray-400"></i>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-medium text-gray-900 line-clamp-2">{{ $item->product->name }}</h4>
                        <div class="mt-3 flex justify-between items-end">
                            <div>
                                <p class="text-sm text-gray-500">Rp {{ number_format($item->price, 0, ',', '.') }} × {{ $item->quantity }}</p>
                            </div>
                            <p class="font-semibold text-lg text-amber-700">
                                Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Ringkasan Pembayaran -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-3xl shadow-sm p-8 sticky top-8">
                <h3 class="font-semibold text-lg mb-6">Ringkasan Pembayaran</h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Total Belanja</span>
                        <span class="font-medium">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="h-px bg-gray-100"></div>
                    <div class="flex justify-between text-xl font-semibold">
                        <span>Total</span>
                        <span class="text-amber-700">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="mt-10 pt-6 border-t">
                    <a href="{{ route('orders.index') }}" 
                       class="block w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-700 py-4 rounded-2xl font-medium transition">
                        Kembali ke Daftar Pesanan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection