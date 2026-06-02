@extends('layouts.app')

@section('title', 'Checkout - Sanctuari')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10">
    <h1 class="text-3xl font-serif font-semibold mb-6">Checkout</h1>

    @if($cart->items->isEmpty())
        <div class="text-center py-10">Keranjang kosong. <a href="{{ route('products.index') }}" class="text-amber-700">Belanja sekarang</a></div>
    @else
        <form action="{{ route('checkout.process') }}" method="POST" enctype="multipart/form-data" id="checkoutForm">
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

                        <h2 class="text-xl font-semibold mb-2 mt-6">Metode Pembayaran</h2>
                        <div class="grid grid-cols-3 gap-3 mt-2 mb-6">
                            <label class="border rounded-lg p-3 cursor-pointer text-center hover:border-amber-500 has-[:checked]:border-amber-600 has-[:checked]:bg-amber-50">
                                <input type="radio" name="payment_method" value="cash" required {{ old('payment_method') == 'cash' ? 'checked' : '' }}>
                                <i class="fa-solid fa-money-bill-wave text-xl text-amber-600 block mb-1"></i>
                                <p class="text-sm font-medium">Cash (COD)</p>
                            </label>
                            <label class="border rounded-lg p-3 cursor-pointer text-center hover:border-amber-500 has-[:checked]:border-amber-600 has-[:checked]:bg-amber-50">
                                <input type="radio" name="payment_method" value="transfer" {{ old('payment_method') == 'transfer' ? 'checked' : '' }}>
                                <i class="fa-solid fa-building-columns text-xl text-amber-600 block mb-1"></i>
                                <p class="text-sm font-medium">Transfer Bank</p>
                            </label>
                            <label class="border rounded-lg p-3 cursor-pointer text-center hover:border-amber-500 has-[:checked]:border-amber-600 has-[:checked]:bg-amber-50">
                                <input type="radio" name="payment_method" value="ewallet" {{ old('payment_method') == 'ewallet' ? 'checked' : '' }}>
                                <i class="fa-solid fa-wallet text-xl text-amber-600 block mb-1"></i>
                                <p class="text-sm font-medium">E-Wallet</p>
                            </label>
                        </div>

                        {{-- PANEL INFO PEMBAYARAN DINAMIS --}}
                        <div id="payment-instruction-panel" class="hidden mb-6 border border-dashed border-amber-300 rounded-xl p-5 bg-amber-50/30 space-y-5">
                            
                            {{-- Info Rekening Bank --}}
                            <div id="info-transfer" class="hidden space-y-3">
                                <h4 class="font-semibold text-amber-900 text-sm uppercase tracking-wider">Rekening Tujuan Transfer</h4>
                                <div class="bg-white p-4 border rounded-lg shadow-sm space-y-2">
                                    <div class="flex justify-between items-center text-sm border-b pb-2">
                                        <span class="text-gray-500">Bank</span>
                                        <span class="font-bold text-gray-800">Bank Central Asia (BCA)</span>
                                    </div>
                                    <div class="flex justify-between items-center text-sm border-b pb-2">
                                        <span class="text-gray-500">Nomor Rekening</span>
                                        <span class="font-mono font-bold text-amber-800 tracking-wider">1234567890</span>
                                    </div>
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-gray-500">Atas Nama</span>
                                        <span class="font-semibold text-gray-800">PT Sanctuari FurniHome</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Info QRIS E-Wallet --}}
                            <div id="info-ewallet" class="hidden text-center space-y-3">
                                <h4 class="font-semibold text-amber-900 text-sm uppercase tracking-wider text-left">Scan QRIS E-Wallet</h4>
                                <div class="bg-white p-6 border rounded-lg shadow-sm flex flex-col items-center justify-center">
                                    @php
                                        $qrisUrl = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=SanctuariPaymentQRIS";
                                    @endphp
                                    <img src="{{ $qrisUrl }}" alt="QRIS Sanctuari" class="border p-2 bg-white rounded-lg shadow-sm max-w-[180px]">
                                    <span class="mt-3 font-bold text-gray-700 text-sm">QRIS - SANCTUARI FURNIHOME</span>
                                    <p class="text-xs text-gray-400 mt-1 mb-3">Mendukung GoPay, OVO, Dana, LinkAja</p>
                                    
                                    {{-- FITUR DOWNLOAD QRIS --}}
                                    <button type="button" onclick="downloadQRIS()" class="mt-4 inline-flex items-center justify-center gap-2 text-xs bg-amber-600 hover:bg-amber-700 text-white font-semibold px-4 py-2.5 rounded-lg transition duration-200 shadow-sm hover:shadow-md active:scale-95 cursor-pointer">
                                        <i class="fa-solid fa-cloud-arrow-down text-sm"></i> Download Gambar QRIS
                                    </button>
                                </div>
                            </div>

                            {{-- FITUR UPLOAD BUKTI PEMBAYARAN (Muncul untuk Non-COD) --}}
                            <div id="upload-proof-section" class="border-t border-amber-200 pt-4">
                                <label class="block text-sm font-semibold text-gray-800 mb-1">Upload Bukti Pembayaran <span class="text-red-500">*</span></label>
                                <p class="text-xs text-gray-500 mb-2">Format: JPG, JPEG, PNG (Maks. 2MB)</p>
                                <input type="file" name="payment_proof" id="paymentProofInput" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-amber-100 file:text-amber-800 hover:file:bg-amber-200">
                                @error('payment_proof') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>
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
                        $subtotal = $cart->items->sum(fn($i) => $i->price * $i->quantity);
                        $shipping = 150000;
                        $tax = round($subtotal * 0.11);
                        $total = $subtotal + $shipping + $tax;
                    @endphp

                    {{-- List item --}}
                    <div class="space-y-2 mb-4">
                        @foreach($cart->items as $item)
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>{{ $item->product->name ?? 'Produk' }} <span class="text-gray-400">x{{ $item->quantity }}</span></span>
                            <span>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    </div>

                    <div class="border-t pt-3 space-y-3 text-gray-700">
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
        const qrisUrl = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=SanctuariPaymentQRIS";
        
        fetch(qrisUrl)
            .then(response => response.blob()) // Mengubah gambar menjadi data biner lokal
            .then(blob => {
                const blobUrl = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.style.display = 'none';
                a.href = blobUrl;
                a.download = 'QRIS_Sanctuari_Payment.png'; // Nama file hasil unduhan
                document.body.appendChild(a);
                a.click(); // Memicu aksi unduh otomatis browser
                window.URL.revokeObjectURL(blobUrl);
                document.body.removeChild(a);
            })
            .catch(() => alert('Gagal mengunduh QRIS, silakan screenshot layar Anda atau coba lagi.'));
    }
</script>
@endsection