@extends('layouts.app')

@section('title', 'Invoice #' . $order->order_number)

@section('content')
<div class="bg-slate-50 min-h-screen py-12">
    <div class="max-w-3xl mx-auto px-4">

        {{-- Tombol Kembali --}}
        <div class="mb-6">
            <a href="{{ route('orders.index') }}" 
               class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-900 transition-colors">
                <i class="fa-solid fa-arrow-left mr-2"></i>
                Kembali ke Daftar Pesanan
            </a>
        </div>
        
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 md:p-12">
            {{-- Header Toko & Invoice --}}
            <div class="flex justify-between items-start mb-10">
                <div>
                    <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">JayaAbadi</h1>
                    <p class="text-slate-600 text-[15px] mt-0.5">Toko Furnitur & Perabot Rumah</p>
                    <p class="text-slate-400 text-xs mt-3 uppercase tracking-wider font-semibold">Invoice Resmi Transaksi</p>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-black text-slate-900">INVOICE</p>
                    <p class="text-slate-500 font-medium mt-0.5 uppercase">{{ $order->order_number }}</p>
                    <span class="inline-block mt-3 px-4 py-1 rounded-md text-[11px] font-bold uppercase bg-emerald-50 text-emerald-700 border border-emerald-100">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>

            <div class="border-b-2 border-slate-900 mb-10"></div>

            {{-- Info Kasir & Detail Transaksi --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-3">Kasir Yang Bertugas</p>
                    <p class="font-bold text-slate-900 text-sm">Alfan Fadhillah Ramadhan</p>
                    <p class="text-slate-600 text-sm">iniabadi26@gmail.com</p>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-3">Detail Transaksi</p>
                    <div class="space-y-1.5">
                        <div class="flex justify-between">
                            <span class="text-sm text-slate-500">Tanggal:</span>
                            <span class="text-sm font-semibold text-slate-900">{{ $order->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-slate-500">Saluran:</span>
                            <span class="text-sm font-semibold text-slate-900">Kasir POS (Toko)</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-slate-500">Metode Bayar:</span>
                            <span class="text-sm font-semibold text-slate-900">Cash</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabel Produk --}}
            <div class="mb-10">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-200">
                            <th class="pb-3 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Produk</th>
                            <th class="pb-3 text-center text-[11px] font-bold text-slate-400 uppercase tracking-wider">Qty</th>
                            <th class="pb-3 text-right text-[11px] font-bold text-slate-400 uppercase tracking-wider">Harga Satuan</th>
                            <th class="pb-3 text-right text-[11px] font-bold text-slate-400 uppercase tracking-wider">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($order->items as $item)
                        <tr>
                            <td class="py-5 text-sm text-slate-900 font-medium capitalize">{{ $item->product->name }}</td>
                            <td class="py-5 text-sm text-slate-600 text-center">{{ $item->quantity }}</td>
                            <td class="py-5 text-sm text-slate-600 text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="py-5 text-sm font-bold text-slate-900 text-right">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Total Section --}}
            <div class="flex justify-end mb-10">
                <div class="w-full sm:w-64 space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Subtotal Pesanan</span>
                        <span class="font-semibold text-slate-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="border-t-2 border-slate-900 pt-4 flex justify-between items-center">
                        <span class="font-bold text-slate-900 uppercase text-xs">Total Akhir</span>
                        <span class="font-black text-slate-900 text-xl">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="border-t border-dashed border-slate-200 pt-8 text-center">
                <p class="text-slate-500 text-sm font-medium">Terima kasih telah berbelanja di JayaAbadi.</p>
                <p class="text-slate-400 text-[11px] mt-1.5 uppercase tracking-wider">
                    Invoice ini dihasilkan otomatis oleh sistem — {{ $order->created_at->format('d M Y, H:i') }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection