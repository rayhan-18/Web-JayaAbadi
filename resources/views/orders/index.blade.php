@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-12 lg:py-16">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-10">
        <div>
            <h1 class="text-3xl font-serif font-semibold text-gray-900">
                Pesanan Saya
            </h1>
            <p class="text-gray-600 mt-2">
                Kelola dan lacak semua pesanan Anda
            </p>
        </div>

        <a href="{{ route('products.index') }}"
            class="mt-6 md:mt-0 inline-flex items-center gap-2 bg-amber-600 hover:bg-amber-700 text-white px-6 py-3.5 rounded-2xl font-medium transition">
            <i class="fa-solid fa-bag-shopping"></i>
            Mulai Belanja
        </a>
    </div>

    @if($orders->count() > 0)

    <div class="bg-white rounded-3xl shadow-sm overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-8 py-6 text-left text-[11px] font-bold text-gray-400 uppercase tracking-[0.15em]">No. Invoice</th>
                        <th class="px-8 py-6 text-left text-[11px] font-bold text-gray-400 uppercase tracking-[0.15em]">Tanggal</th>
                        <th class="px-8 py-6 text-left text-[11px] font-bold text-gray-400 uppercase tracking-[0.15em]">Total</th>
                        <th class="px-8 py-6 text-center text-[11px] font-bold text-gray-400 uppercase tracking-[0.15em]">Status</th>
                        <th class="px-8 py-6 text-left text-[11px] font-bold text-gray-400 uppercase tracking-[0.15em]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($orders as $order)
                    <tr class="group hover:bg-amber-50/30 transition-colors duration-200">
                        <td class="px-8 py-7">
                            <span class="font-mono font-bold text-gray-900 group-hover:text-amber-700 transition-colors">#{{ $order->order_number }}</span>
                        </td>
                        <td class="px-8 py-7 text-sm text-gray-600">
                            {{ $order->created_at->format('d M Y') }}
                        </td>
                        <td class="px-8 py-7 text-sm font-semibold text-gray-900">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </td>
                        <td class="px-8 py-7">
                            <div class="flex justify-center">
                                @php
                                    $statusColors = [
                                        'paid'      => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'pending'   => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'shipped'   => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'delivered' => 'bg-purple-50 text-purple-700 border-purple-200',
                                    ];
                                    $color = $statusColors[$order->status] ?? 'bg-gray-100 text-gray-600 border-gray-200';
                                @endphp
                                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[11px] font-bold uppercase tracking-wide border {{ $color }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                        </td>
                        <td class="px-8 py-7">
                            <a href="{{ route('orders.show', $order) }}" 
                            class="inline-flex items-center gap-2 text-sm font-bold text-gray-900 hover:text-amber-700 transition group">
                                DETAIL
                                <span class="opacity-0 group-hover:opacity-100 transition-opacity translate-x-[-10px] group-hover:translate-x-0">→</span>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-10 flex justify-center">
        {{ $orders->links() }}
    </div>

    @else

    <div class="bg-white rounded-3xl shadow-sm py-24 text-center">

        <div class="mx-auto w-24 h-24 bg-gray-100 rounded-3xl flex items-center justify-center mb-8">
            <i class="fa-regular fa-receipt text-6xl text-gray-300"></i>
        </div>

        <h3 class="text-2xl font-semibold text-gray-900 mb-3">
            Belum ada pesanan
        </h3>

        <p class="text-gray-500 max-w-md mx-auto">
            Anda belum melakukan pemesanan apa pun. Yuk mulai belanja sekarang!
        </p>

        <a href="{{ route('products.index') }}"
            class="mt-10 inline-flex items-center gap-3 bg-gray-900 hover:bg-black text-white px-8 py-4 rounded-2xl font-medium transition">

            <i class="fa-solid fa-bag-shopping"></i>

            Mulai Belanja

        </a>

    </div>

    @endif
</div>
@endsection