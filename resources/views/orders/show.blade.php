@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->invoice_number)

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10">
    <div class="bg-white rounded-xl shadow-sm border p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-serif font-semibold">Detail Pesanan</h1>
            <a href="{{ route('orders.index') }}" class="text-amber-700 hover:text-amber-800">← Kembali</a>
        </div>

        <div class="grid md:grid-cols-2 gap-6 mb-8">
            <div>
                <h2 class="font-semibold text-gray-900 mb-3">Informasi Pesanan</h2>
                <p class="text-sm"><strong>No. Invoice:</strong> {{ $order->invoice_number }}</p>
                <p class="text-sm mt-1"><strong>Tanggal:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                <p class="text-sm mt-1"><strong>Status:</strong>
                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                        @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                        @elseif($order->status == 'paid') bg-blue-100 text-blue-800
                        @elseif($order->status == 'shipped') bg-purple-100 text-purple-800
                        @elseif($order->status == 'delivered') bg-green-100 text-green-800
                        @else bg-red-100 text-red-800
                        @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                </p>
            </div>
            <div>
                <h2 class="font-semibold text-gray-900 mb-3">Alamat Pengiriman</h2>
                <p class="text-sm">{{ $order->shipping_address }}</p>
            </div>
        </div>

        <h2 class="font-semibold text-gray-900 mb-4">Produk yang Dipesan</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Produk</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Harga</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Jumlah</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($order->items as $item)
                    <tr>
                        <td class="px-4 py-3 text-sm">{{ $item->product->name }}</td>
                        <td class="px-4 py-3 text-sm">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-sm">{{ $item->quantity }}</td>
                        <td class="px-4 py-3 text-sm font-medium">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="3" class="px-4 py-3 text-right font-semibold">Total</td>
                        <td class="px-4 py-3 font-bold text-amber-700">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection