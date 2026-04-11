@extends('layouts.app')

@section('title', 'Keranjang Belanja - Sanctuari')

@section('content')
<div class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Header -->
        <div class="mb-10">
            <h1 class="text-3xl md:text-4xl font-serif font-semibold text-gray-900">Keranjang Belanja</h1>
            <p class="text-gray-500 mt-2">Satu langkah lagi menuju ruang impian Anda yang lebih tenang dan nyaman.</p>
        </div>

        @php
            // Data statis produk (sesuai keinginan)
            $cartItems = [
                [
                    'id' => 1,
                    'name' => 'Kursi Santai Aris',
                    'material' => 'Kayu Jati & Linen Sage',
                    'price' => 4250000,
                    'quantity' => 1,
                    'image' => 'https://placehold.co/120x120?text=Kursi+Aris'
                ],
                [
                    'id' => 2,
                    'name' => 'Lampu Meja Kalla',
                    'material' => 'Cream Textured',
                    'price' => 1150000,
                    'quantity' => 2,
                    'image' => 'https://placehold.co/120x120?text=Lampu+Kalla'
                ],
                [
                    'id' => 3,
                    'name' => 'Meja Kopi Horizon',
                    'material' => 'Kayu Walnut Solid',
                    'price' => 4200000,
                    'quantity' => 1,
                    'image' => 'https://placehold.co/120x120?text=Meja+Horizon'
                ]
            ];
        @endphp

        <div class="flex flex-col lg:flex-row gap-10">
            <!-- Daftar Item -->
            <div class="lg:w-2/3 space-y-6">
                @foreach($cartItems as $item)
                <div class="flex flex-col sm:flex-row gap-5 bg-white border border-gray-100 rounded-xl p-5 shadow-sm">
                    <img src="{{ $item['image'] }}" class="w-28 h-28 object-cover rounded-lg">
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-800 text-lg">{{ $item['name'] }}</h3>
                        <p class="text-gray-400 text-sm mt-0.5">Material: {{ $item['material'] }}</p>
                        <p class="text-amber-700 font-bold text-xl mt-2">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                        <div class="flex flex-wrap items-center gap-4 mt-4">
                            <!-- Input quantity (hanya tampilan, tidak ada fungsi simpan) -->
                            <div class="flex items-center border border-gray-300 rounded-full overflow-hidden">
                                <button class="w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition decrement-btn" data-id="{{ $item['id'] }}">−</button>
                                <input type="number" value="{{ $item['quantity'] }}" min="1" class="w-12 text-center border-0 focus:ring-0 text-sm quantity-input" data-id="{{ $item['id'] }}" readonly>
                                <button class="w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition increment-btn" data-id="{{ $item['id'] }}">+</button>
                            </div>
                            <button class="text-amber-700 text-sm font-medium hover:text-amber-800 transition update-btn" data-id="{{ $item['id'] }}">Update</button>
                            <button class="text-gray-400 hover:text-red-500 transition text-sm flex items-center gap-1 remove-btn" data-id="{{ $item['id'] }}">
                                <i class="fa-regular fa-trash-can"></i> Hapus
                            </button>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-gray-800 text-lg item-total" data-id="{{ $item['id'] }}">
                            Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                        </p>
                        <a href="#" class="text-amber-600 text-xs mt-1 inline-block hover:underline">Lihat Promo</a>
                    </div>
                </div>
                @endforeach

                <!-- Kode promo -->
                <div class="bg-gray-50 rounded-xl p-4 flex justify-between items-center flex-wrap gap-2">
                    <span class="text-sm text-gray-600">Punya kode promo?</span>
                    <a href="#" class="text-amber-700 text-sm font-medium hover:underline">Gunakan saat pembayaran →</a>
                </div>
            </div>

            <!-- Ringkasan Pesanan (dihitung ulang via JavaScript) -->
            <div class="lg:w-1/3 bg-gray-50 rounded-xl p-6 h-fit sticky top-24">
                <h3 class="text-xl font-semibold text-gray-800 mb-5">Ringkasan Pesanan</h3>
                <div class="space-y-3 text-gray-600">
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span id="subtotal">Rp 0</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Estimasi Pengiriman</span>
                        <span>Rp 150.000</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Pajak (PPN 11%)</span>
                        <span id="tax">Rp 0</span>
                    </div>
                    <div class="border-t border-gray-200 pt-3 mt-3">
                        <div class="flex justify-between font-bold text-lg">
                            <span>Total</span>
                            <span id="total" class="text-amber-700">Rp 0</span>
                        </div>
                    </div>
                </div>
                <a href="{{ route('checkout') }}" class="block text-center bg-gray-900 hover:bg-amber-700 text-white py-3 rounded-full font-medium mt-6 transition">
                    Lanjut ke Pembayaran →
                </a>
                <div class="text-center mt-4">
                    <a href="{{ route('products.index') }}" class="text-sm text-gray-500 hover:text-amber-700 transition">
                        ← Kembali Belanja
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Data produk dari PHP (disimpan ke JavaScript agar bisa diupdate)
    const items = @json($cartItems);

    // Fungsi untuk memperbarui tampilan ringkasan
    function updateSummary() {
        let subtotal = 0;
        document.querySelectorAll('.item-total').forEach(el => {
            let totalText = el.innerText.replace('Rp ', '').replace(/\./g, '');
            subtotal += parseInt(totalText);
        });
        const shipping = 150000;
        const tax = Math.round(subtotal * 0.11);
        const total = subtotal + shipping + tax;

        document.getElementById('subtotal').innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
        document.getElementById('tax').innerText = 'Rp ' + tax.toLocaleString('id-ID');
        document.getElementById('total').innerHTML = 'Rp ' + total.toLocaleString('id-ID');
    }

    // Event listener untuk tombol +/-
    document.querySelectorAll('.increment-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            let id = this.getAttribute('data-id');
            let input = document.querySelector(`.quantity-input[data-id="${id}"]`);
            if (input) {
                let newVal = parseInt(input.value) + 1;
                input.value = newVal;
                // Update tampilan total item
                let item = items.find(i => i.id == id);
                if (item) {
                    let totalEl = document.querySelector(`.item-total[data-id="${id}"]`);
                    totalEl.innerText = 'Rp ' + (item.price * newVal).toLocaleString('id-ID');
                    updateSummary();
                }
            }
        });
    });

    document.querySelectorAll('.decrement-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            let id = this.getAttribute('data-id');
            let input = document.querySelector(`.quantity-input[data-id="${id}"]`);
            if (input && parseInt(input.value) > 1) {
                let newVal = parseInt(input.value) - 1;
                input.value = newVal;
                let item = items.find(i => i.id == id);
                if (item) {
                    let totalEl = document.querySelector(`.item-total[data-id="${id}"]`);
                    totalEl.innerText = 'Rp ' + (item.price * newVal).toLocaleString('id-ID');
                    updateSummary();
                }
            }
        });
    });

    // Tombol Update (hanya simulasi alert)
    document.querySelectorAll('.update-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            let id = this.getAttribute('data-id');
            let input = document.querySelector(`.quantity-input[data-id="${id}"]`);
            alert(`Quantity produk ID ${id} diubah menjadi ${input.value} (simulasi front-end). Untuk backend, integrasikan dengan API.`);
        });
    });

    // Tombol Hapus (simulasi)
    document.querySelectorAll('.remove-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            let id = this.getAttribute('data-id');
            if (confirm(`Hapus item ID ${id}?`)) {
                // Hapus elemen card (hanya front-end)
                this.closest('.flex.flex-col.sm\\:flex-row').remove();
                updateSummary();
            }
        });
    });

    // Hitung awal
    updateSummary();
</script>
@endsection