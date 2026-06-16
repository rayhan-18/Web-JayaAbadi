@extends('layouts.app')

@section('title', 'Keranjang Belanja - Sanctuari')

@section('content')
<div class="min-h-screen" style="background-color: #f5f7f3;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        
        {{-- Header --}}
        <div class="text-center mb-12">
            <h1 class="text-3xl sm:text-4xl font-semibold tracking-tight" style="color: #2d3a2e;">
                Keranjang Belanja
            </h1>
            <p class="mt-3 text-base max-w-md mx-auto" style="color: #8a9a82;">
                Satu langkah lagi menuju ruang impian Anda yang lebih tenang dan nyaman.
            </p>
        </div>

        @if($cart->items->isEmpty())
            {{-- Empty State --}}
            <div class="flex flex-col items-center justify-center py-20">
                <div class="w-28 h-28 rounded-full flex items-center justify-center mb-6" style="background-color: #eef1ec;">
                    <svg class="w-12 h-12" style="color: #8a9a82;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-medium mb-2" style="color: #2d3a2e;">
                    Keranjang Anda masih kosong
                </h3>
                <p class="text-sm mb-8 max-w-sm text-center" style="color: #8a9a82;">
                    Jelajahi koleksi kami dan temukan furniture yang akan membuat ruang Anda lebih nyaman.
                </p>
                <a href="{{ route('products.index') }}" 
                   class="inline-flex items-center px-7 py-3 rounded-full text-base font-medium transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5"
                   style="background-color: #7a9471; color: #ffffff;">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                    </svg>
                    Mulai Belanja
                </a>
            </div>

        @else
            
            {{-- Aksi Atas (Kosongkan Keranjang & Info Jumlah) --}}
            <div class="flex items-center justify-between mb-5 pb-3 border-b" style="border-color: #e2e8df;">
                <span class="text-sm font-medium" style="color: #5a6a52;">
                    Total <strong style="color: #2d3a2e;">{{ $cart->items->count() }} item</strong> di keranjang
                </span>
                <form action="{{ route('cart.clear') }}" method="POST" class="m-0"
                      onsubmit="return confirm('Yakin ingin mengosongkan semua item di keranjang?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="group flex items-center gap-1.5 text-sm font-medium transition-colors duration-200 hover:text-red-600" 
                            style="color: #a0524e;">
                        <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Kosongkan Keranjang
                    </button>
                </form>
            </div>

            {{-- Grid Konten --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-10">
                
                {{-- Daftar Produk --}}
                <div class="lg:col-span-2 space-y-5">
                    @foreach($cart->items as $item)
                    <div class="flex flex-col sm:flex-row gap-5 p-5 rounded-2xl border transition-shadow duration-200 hover:shadow-md"
                         style="background-color: #ffffff; border-color: #e2e8df;">
                        
                        {{-- Gambar Produk --}}
                        <div class="shrink-0">
                            <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-xl overflow-hidden border" style="background-color: #f0f3ed; border-color: #e2e8df;">
                                @if($item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" 
                                         alt="{{ $item->product->name }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-8 h-8" style="color: #d4ddd0;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Info Produk & Aksi --}}
                        <div class="flex-1 flex flex-col justify-between">
                            <div class="flex justify-between items-start gap-4">
                                <div class="flex-1">
                                    {{-- BOX KATEGORI DIPERLEBAR DISINI (px-4) --}}
                                    <span class="inline-block text-[10px] font-bold tracking-wider uppercase px-4 py-1.5 rounded-full mb-2"
                                          style="background-color: #eef1ec; color: #7a9471;">
                                        {{ $item->product->category->name ?? 'Produk' }}
                                    </span>
                                    <h3 class="text-lg font-semibold leading-snug" style="color: #2d3a2e;">
                                        {{ $item->product->name }}
                                    </h3>
                                </div>
                                
                                <div class="text-right shrink-0">
                                    <p class="text-base font-bold" style="color: #2d3a2e;">
                                        Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                    </p>
                                    <p class="text-[13px] mt-1" style="color: #8a9a82;">
                                        @ Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>

                            {{-- Kuantitas & Hapus --}}
                            <div class="mt-4 pt-4 border-t flex items-center justify-between" style="border-color: #f0f3ed;">
                                
                                <div class="inline-flex items-center rounded-full border h-9" style="border-color: #d4ddd0;">
                                    <form action="{{ route('cart.update', $item->id) }}" method="POST" class="m-0 h-full">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="quantity" value="{{ max(1, $item->quantity - 1) }}">
                                        <button type="submit" 
                                                class="w-9 h-full flex items-center justify-center rounded-l-full transition-colors duration-150 hover:bg-gray-50 focus:outline-none"
                                                style="color: #2d3a2e;">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15"/>
                                            </svg>
                                        </button>
                                    </form>
                                    
                                    <span class="w-10 text-center text-sm font-semibold tabular-nums leading-none flex items-center justify-center h-full" style="color: #2d3a2e;">
                                        {{ $item->quantity }}
                                    </span>
                                    
                                    <form action="{{ route('cart.update', $item->id) }}" method="POST" class="m-0 h-full">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="quantity" value="{{ $item->quantity + 1 }}">
                                        <button type="submit" 
                                                class="w-9 h-full flex items-center justify-center rounded-r-full transition-colors duration-150 hover:bg-gray-50 focus:outline-none"
                                                style="color: #2d3a2e;">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>

                                <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="m-0"
                                      onsubmit="return confirm('Hapus item ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" 
                                            class="flex items-center gap-1.5 text-sm px-3 py-1.5 rounded-full transition-all duration-150 hover:bg-red-50 focus:outline-none"
                                            style="color: #a0524e;">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Ringkasan Pesanan --}}
                <div class="lg:col-span-1">
                    <div class="sticky top-8 rounded-2xl p-6 sm:p-7 border"
                         style="background-color: #ffffff; border-color: #e2e8df;">
                        <h2 class="text-lg font-semibold mb-6 flex items-center gap-2" style="color: #2d3a2e;">
                            <svg class="w-5 h-5" style="color: #7a9471;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3.75h.008v.008H6.75v-.008z"/>
                            </svg>
                            Ringkasan Pesanan
                        </h2>

                        @php
                            $total = $cart->items->sum(fn($i) => $i->price * $i->quantity);
                        @endphp

                        <div class="space-y-4 text-sm" style="color: #5a6a52;">
                            <div class="flex justify-between items-center pb-4 border-b" style="border-color: #e2e8df;">
                                <span>Subtotal Produk</span>
                                <span class="font-medium" style="color: #2d3a2e;">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>

                            <div class="flex justify-between items-center pt-1">
                                <span class="text-base font-semibold" style="color: #2d3a2e;">Total Tagihan</span>
                                <span class="text-xl font-bold" style="color: #7a9471;">
                                    Rp {{ number_format($total, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        {{-- Action Buttons -- BUTTONS DIPERBESAR DISINI --}}
                        <div class="mt-8 flex flex-col gap-4">
                            <a href="{{ route('checkout') }}"
                               class="flex items-center justify-center gap-2 w-full px-6 py-4 rounded-xl text-base font-semibold transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
                               style="background-color: #7a9471; color: #ffffff;">
                                Lanjut ke Pembayaran
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                                </svg>
                            </a>
                            <a href="{{ route('products.index') }}"
                               class="flex items-center justify-center gap-2 w-full px-6 py-4 rounded-xl text-base font-medium transition-all duration-200 hover:bg-gray-50 border"
                               style="border-color: #d4ddd0; color: #5a6a52; background-color: transparent;">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                                </svg>
                                Kembali Belanja
                            </a>
                        </div>

                        {{-- Trust Badges --}}
                        <div class="mt-8 pt-5 border-t grid grid-cols-2 gap-4 text-center"
                             style="border-color: #e2e8df;">
                            <div>
                                <svg class="w-5 h-5 mx-auto mb-1.5" style="color: #8a9a82;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                                </svg>
                                <span class="text-[11px] font-medium" style="color: #8a9a82;">Pembayaran Aman</span>
                            </div>
                            <div>
                                <svg class="w-5 h-5 mx-auto mb-1.5" style="color: #8a9a82;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 00-3.7-3.7 48.678 48.678 0 00-7.324 0 4.006 4.006 0 00-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3l-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 003.7 3.7 48.656 48.656 0 007.324 0 4.006 4.006 0 003.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3l-3 3"/>
                                </svg>
                                <span class="text-[11px] font-medium" style="color: #8a9a82;">Pengembalian Mudah</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection