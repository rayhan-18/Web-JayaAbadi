@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10">
    <h1 class="text-3xl font-serif font-semibold mb-6">Pesanan Saya</h1>

    @if($orders->count() > 0)
        <div class="overflow-x-auto bg-white rounded-xl shadow-sm">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Invoice</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($orders as $order)
                    <tr>
                        <td class="px-6 py-4 text-sm">{{ $order->invoice_number }}</td>
                        <td class="px-6 py-4 text-sm">{{ $order->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-sm font-medium">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status == 'paid') bg-blue-100 text-blue-800
                                @elseif($order->status == 'shipped') bg-purple-100 text-purple-800
                                @elseif($order->status == 'delivered') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('orders.show', $order) }}" class="text-amber-700 hover:text-amber-800 text-sm">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $orders->links() }}</div>
    @else
        <div class="text-center py-16 bg-gray-50 rounded-xl">
            <i class="fa-regular fa-receipt text-5xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">Belum ada pesanan.</p>
            <a href="{{ route('products.index') }}" class="inline-block mt-4 bg-gray-900 text-white px-6 py-2 rounded-full">Mulai Belanja</a>
        </div>
    @endif
</div>
@endsection