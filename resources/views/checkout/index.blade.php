@extends('layouts.app')

@section('title', 'Checkout | Jaya Abadi')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-12 lg:py-16">
    <div class="mb-10">
        <h1 class="text-3xl font-serif font-bold text-gray-900">Checkout</h1>
        <p class="text-gray-500 mt-2">Selesaikan pesanan Anda dengan mengisi detail di bawah ini.</p>
    </div>

    @if($cart->items->isEmpty())
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 py-24 text-center">
            <div class="mx-auto w-24 h-24 bg-gray-50 rounded-3xl flex items-center justify-center mb-6">
                <i class="fa-solid fa-cart-shopping text-5xl text-gray-300"></i>
            </div>
            <h3 class="text-2xl font-semibold text-gray-900 mb-2">Keranjang masih kosong</h3>
            <p class="text-gray-500 mb-8">Anda belum menambahkan produk ke dalam keranjang.</p>
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 bg-gray-900 hover:bg-black text-white px-8 py-3.5 rounded-2xl font-medium transition">
                <i class="fa-solid fa-bag-shopping"></i> Belanja Sekarang
            </a>
        </div>
    @else
        <form action="{{ route('checkout.process') }}" method="POST" enctype="multipart/form-data" id="checkoutForm">
            @csrf
            <div class="grid lg:grid-cols-3 gap-10">
                
                <div class="lg:col-span-2 space-y-8">
                    
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                            <i class="fa-solid fa-truck-fast text-amber-600"></i>
                            Informasi Pengiriman
                        </h2>
                        
                        <div class="space-y-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Lengkap</label>
                                <textarea name="shipping_address" rows="3" 
                                          class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-4 py-3 text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 focus:bg-white transition-all outline-none" 
                                          placeholder="Tuliskan nama jalan, nomor rumah, RT/RW, kecamatan, kota, dan kode pos..." required>{{ old('shipping_address') }}</textarea>
                                @error('shipping_address') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor WhatsApp / HP</label>
                                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Contoh: 081234567890"
                                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 focus:bg-white transition-all outline-none" required>
                                @error('phone') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                            <i class="fa-solid fa-wallet text-amber-600"></i>
                            Metode Pembayaran
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                            <label class="border border-gray-200 rounded-2xl p-5 cursor-pointer text-center hover:border-amber-500 has-[:checked]:border-amber-600 has-[:checked]:bg-amber-50/50 has-[:checked]:shadow-sm transition-all group">
                                <input type="radio" name="payment_method" value="cash" class="hidden" required {{ old('payment_method') == 'cash' ? 'checked' : '' }}>
                                <div class="w-12 h-12 mx-auto bg-gray-50 group-hover:bg-amber-100/50 rounded-full flex items-center justify-center mb-3 transition-colors">
                                    <i class="fa-solid fa-money-bill-wave text-xl text-amber-600"></i>
                                </div>
                                <p class="text-sm font-bold text-gray-900">Cash (COD)</p>
                            </label>
                            
                            <label class="border border-gray-200 rounded-2xl p-5 cursor-pointer text-center hover:border-amber-500 has-[:checked]:border-amber-600 has-[:checked]:bg-amber-50/50 has-[:checked]:shadow-sm transition-all group">
                                <input type="radio" name="payment_method" value="transfer" class="hidden" {{ old('payment_method') == 'transfer' ? 'checked' : '' }}>
                                <div class="w-12 h-12 mx-auto bg-gray-50 group-hover:bg-amber-100/50 rounded-full flex items-center justify-center mb-3 transition-colors">
                                    <i class="fa-solid fa-building-columns text-xl text-amber-600"></i>
                                </div>
                                <p class="text-sm font-bold text-gray-900">Transfer Bank</p>
                            </label>
                            
                            <label class="border border-gray-200 rounded-2xl p-5 cursor-pointer text-center hover:border-amber-500 has-[:checked]:border-amber-600 has-[:checked]:bg-amber-50/50 has-[:checked]:shadow-sm transition-all group">
                                <input type="radio" name="payment_method" value="ewallet" class="hidden" {{ old('payment_method') == 'ewallet' ? 'checked' : '' }}>
                                <div class="w-12 h-12 mx-auto bg-gray-50 group-hover:bg-amber-100/50 rounded-full flex items-center justify-center mb-3 transition-colors">
                                    <i class="fa-solid fa-qrcode text-xl text-amber-600"></i>
                                </div>
                                <p class="text-sm font-bold text-gray-900">E-Wallet</p>
                            </label>
                        </div>

                        {{-- PANEL INFO PEMBAYARAN DINAMIS --}}
                        <div id="payment-instruction-panel" class="hidden mb-6 border border-amber-200 rounded-2xl p-6 bg-amber-50/30">
                            
                            {{-- Info Rekening Bank --}}
                            <div id="info-transfer" class="hidden space-y-4">
                                <h4 class="font-bold text-amber-900 text-xs uppercase tracking-widest">Rekening Tujuan Transfer</h4>
                                <div class="bg-white p-5 border border-amber-100 rounded-xl shadow-sm space-y-3">
                                    <div class="flex justify-between items-center text-sm border-b border-gray-100 pb-3">
                                        <span class="text-gray-500">Bank</span>
                                        <span class="font-bold text-gray-900">Bank Central Asia (BCA)</span>
                                    </div>
                                    <div class="flex justify-between items-center text-sm border-b border-gray-100 pb-3">
                                        <span class="text-gray-500">Nomor Rekening</span>
                                        <span class="font-mono font-bold text-lg text-amber-700 tracking-wider">1234567890</span>
                                    </div>
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-gray-500">Atas Nama</span>
                                        <span class="font-semibold text-gray-900">PT Jaya Abadi Furnitur</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Info QRIS E-Wallet --}}
                            <div id="info-ewallet" class="hidden text-center space-y-4">
                                <h4 class="font-bold text-amber-900 text-xs uppercase tracking-widest text-left">Scan QRIS E-Wallet</h4>
                                <div class="bg-white p-8 border border-amber-100 rounded-xl shadow-sm flex flex-col items-center justify-center">
                                    @php
                                        $qrisUrl = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=JayaAbadiPaymentQRIS";
                                    @endphp
                                    <img src="{{ $qrisUrl }}" alt="QRIS Jaya Abadi" class="border border-gray-100 p-3 bg-white rounded-xl shadow-sm max-w-[200px]">
                                    <span class="mt-4 font-bold text-gray-900 text-sm tracking-wide">QRIS - JAYA ABADI</span>
                                    <p class="text-xs text-gray-500 mt-1 mb-4">Mendukung GoPay, OVO, DANA</p>
                                    
                                    {{-- FITUR DOWNLOAD QRIS --}}
                                    <button type="button" onclick="downloadQRIS()" class="mt-2 inline-flex items-center justify-center gap-2 text-xs bg-amber-600 hover:bg-amber-700 text-white font-semibold px-5 py-2.5 rounded-xl transition duration-200 shadow-sm hover:shadow-md cursor-pointer">
                                        <i class="fa-solid fa-cloud-arrow-down"></i> Download Gambar QRIS
                                    </button>
                                </div>
                            </div>

                            {{-- FITUR UPLOAD BUKTI PEMBAYARAN (Muncul untuk Non-COD) --}}
                            <div id="upload-proof-section" class="border-t border-amber-200/60 mt-6 pt-6">
                                <label class="block text-sm font-bold text-gray-900 mb-1">Upload Bukti Pembayaran <span class="text-red-500">*</span></label>
                                <p class="text-xs text-gray-500 mb-3">Format: JPG, JPEG, PNG (Maks. 2MB)</p>
                                <input type="file" name="payment_proof" id="paymentProofInput" accept="image/*" 
                                       class="w-full text-sm text-gray-600 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:uppercase file:tracking-wide file:bg-amber-100 file:text-amber-800 hover:file:bg-amber-200 transition-colors bg-white border border-gray-200 rounded-xl pr-3">
                                @error('payment_proof') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan Pesanan <span class="text-gray-400 font-normal">(Opsional)</span></label>
                            <textarea name="notes" rows="2" placeholder="Tinggalkan pesan untuk penjual..."
                                      class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 focus:bg-white transition-all outline-none">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-gray-50 border border-gray-100 rounded-3xl p-8 sticky top-28">
                        <h3 class="text-xl font-bold text-gray-900 mb-6">Ringkasan Pesanan</h3>

                        @php
                            $subtotal = $cart->items->sum(fn($i) => $i->price * $i->quantity);
                            $shipping = 150000;
                            $tax = round($subtotal * 0.11);
                            $total = $subtotal + $shipping + $tax;
                        @endphp

                        {{-- List item --}}
                        <div class="space-y-4 mb-6">
                            @foreach($cart->items as $item)
                            <div class="flex justify-between items-start text-sm">
                                <div class="pr-4">
                                    <span class="font-medium text-gray-900 block">{{ $item->product->name ?? 'Produk' }}</span>
                                    <span class="text-gray-500 text-xs">Qty: {{ $item->quantity }}</span>
                                </div>
                                <span class="font-medium text-gray-900 whitespace-nowrap">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                            </div>
                            @endforeach
                        </div>

                        <div class="border-t border-gray-200 pt-6 space-y-3 text-sm">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal Produk</span>
                                <span class="font-medium text-gray-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Ongkos Kirim</span>
                                <span class="font-medium text-gray-900">Rp {{ number_format($shipping, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Pajak (PPN 11%)</span>
                                <span class="font-medium text-gray-900">Rp {{ number_format($tax, 0, ',', '.') }}</span>
                            </div>
                            
                            <div class="border-t border-gray-200 pt-4 mt-4">
                                <div class="flex justify-between items-center">
                                    <span class="font-bold text-gray-900 uppercase tracking-wide text-xs">Total Pembayaran</span>
                                    <span class="text-2xl font-bold text-amber-700">Rp {{ number_format($total, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="w-full flex items-center justify-center gap-2 bg-gray-900 hover:bg-black text-white py-4 rounded-2xl font-semibold mt-8 transition duration-200 shadow-sm active:scale-[0.98]">
                            <i class="fa-solid fa-lock text-sm"></i> Buat Pesanan
                        </button>
                        <p class="text-[11px] text-center text-gray-500 mt-4 flex items-center justify-center gap-1.5">
                            <i class="fa-solid fa-shield-halved"></i> Pembayaran Anda aman dan terenkripsi
                        </p>
                    </div>
                </div>
            </div>
        </form>
    @endif
</div>

{{-- SCRIPT INTERAKTIF UPDATE --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const radioButtons = document.querySelectorAll('input[name="payment_method"]');
        const panelContainer = document.getElementById('payment-instruction-panel');
        const infoTransfer = document.getElementById('info-transfer');
        const infoEwallet = document.getElementById('info-ewallet');
        const proofInput = document.getElementById('paymentProofInput');

        function togglePaymentInstruction(value) {
            panelContainer.classList.add('hidden');
            infoTransfer.classList.add('hidden');
            infoEwallet.classList.add('hidden');
            
            // Set input file bukti pembayaran menjadi tidak wajib jika COD (cash)
            proofInput.removeAttribute('required');

            if (value === 'transfer') {
                panelContainer.classList.remove('hidden');
                infoTransfer.classList.remove('hidden');
                proofInput.setAttribute('required', 'required'); // Wajib diisi jika transfer
            } else if (value === 'ewallet') {
                panelContainer.classList.remove('hidden');
                infoEwallet.classList.remove('hidden');
                proofInput.setAttribute('required', 'required'); // Wajib diisi jika ewallet
            }
        }

        radioButtons.forEach(radio => {
            radio.addEventListener('change', function() {
                togglePaymentInstruction(this.value);
            });
        });

        const currentChecked = document.querySelector('input[name="payment_method"]:checked');
        if (currentChecked) {
            togglePaymentInstruction(currentChecked.value);
        }
    });

    // =========================================================================
    // SOLUSI UTAMA: Ditaruh di luar scope DOMContentLoaded agar terbaca sebagai global function
    // =========================================================================
    function downloadQRIS() {
        const qrisUrl = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=JayaAbadiPaymentQRIS";
        
        fetch(qrisUrl)
            .then(response => response.blob()) // Mengubah gambar menjadi data biner lokal
            .then(blob => {
                const blobUrl = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.style.display = 'none';
                a.href = blobUrl;
                a.download = 'QRIS_Jaya_Abadi_Payment.png'; // Nama file hasil unduhan
                document.body.appendChild(a);
                a.click(); // Memicu aksi unduh otomatis browser
                window.URL.revokeObjectURL(blobUrl);
                document.body.removeChild(a);
            })
            .catch(() => alert('Gagal mengunduh QRIS, silakan screenshot layar Anda atau coba lagi.'));
    }
</script>
@endsection