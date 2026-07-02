@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
<div class="bg-gray-50 min-h-screen py-16">
    <div class="max-w-3xl mx-auto px-4 sm:px-6">
        
        {{-- Header Section --}}
        <div class="mb-10 text-center sm:text-left">
            <h1 class="text-3xl font-serif font-bold text-gray-900">Pesanan Saya</h1>
            <p class="text-gray-500 mt-2">Daftar riwayat pesanan Anda di Sanctuari.</p>
        </div>

        @if($orders->isNotEmpty())
            <div class="space-y-6">
                @foreach($orders as $order)
                {{-- Card Pesanan --}}
                <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
                    <div class="p-6">
                        {{-- Header: Invoice & Status --}}
                        <div class="flex justify-between items-start gap-4">
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Nomor Invoice</p>
                                <p class="text-base font-bold text-gray-900">#{{ $order->order_number }}</p>
                            </div>
                            
                            @php
                                $statusStyles = [
                                    'paid'      => 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-200',
                                    'pending'   => 'bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-200',
                                    'shipped'   => 'bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-200',
                                    'delivered' => 'bg-purple-50 text-purple-700 ring-1 ring-inset ring-purple-200',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $statusStyles[$order->status] ?? 'bg-gray-100 text-gray-600 ring-1 ring-inset ring-gray-200' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>

                        {{-- Footer: Tanggal & Aksi --}}
                        <div class="flex justify-between items-center border-t border-gray-100 mt-6 pt-5">
                            <div class="text-[11px] text-gray-400 font-medium flex items-center">
                                <i class="fa-regular fa-calendar mr-1.5"></i> 
                                {{ $order->created_at->format('d M Y, H:i') }}
                            </div>
                            
                            <a href="{{ route('orders.show', $order) }}" 
                            class="group flex items-center gap-1.5 text-xs font-bold text-gray-900 hover:text-amber-600 transition-colors">
                                Detail
                                <i class="fa-solid fa-arrow-right text-[9px] transition-transform group-hover:translate-x-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-10">
                {{ $orders->links() }}
            </div>

        @else
            {{-- State Kosong --}}
            <div class="bg-white rounded-3xl border border-gray-200 py-16 px-6 text-center">
                <div class="w-16 h-16 bg-amber-50 text-amber-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fa-solid fa-box-open text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900">Belum ada pesanan</h3>
                <p class="text-gray-500 text-sm mt-2 mb-8 max-w-xs mx-auto">Anda belum melakukan transaksi apa pun. Jelajahi koleksi kami sekarang.</p>
                <a href="{{ route('products.index') }}"
                   class="inline-block bg-gray-900 hover:bg-amber-600 text-white px-8 py-3 rounded-full font-bold text-sm transition-colors shadow-lg shadow-gray-900/10">
                    Jelajahi Produk
                </a>
            </div>
        @endif
    </div>
</div>
@endsection