@extends('layouts.app')

@section('title', 'Beranda - Sanctuari')

@section('content')
<!-- Hero Slider dengan Ken Burns Effect -->
<section class="relative h-screen min-h-[600px] overflow-hidden">
    <div class="relative w-full h-full">
        @php
            $slides = [
                [
                    'image' => 'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?q=80&w=2070&auto=format',
                    'title' => 'Temukan Ketenangan di Setiap Sudut Rumah',
                    'subtitle' => 'Koleksi furnitur yang dikurasi khusus untuk menghadirkan harmoni antara estetika modern dan kenyamanan organik.'
                ],
                [
                    'image' => 'https://images.unsplash.com/photo-1583847268964-b28dc8f51f92?q=80&w=2070&auto=format',
                    'title' => 'Dibuat dengan Tangan, Dirancang untuk Jiwa',
                    'subtitle' => 'Setiap potongan adalah hasil dedikasi pengrajin lokal dengan material berkelanjutan.'
                ],
                [
                    'image' => 'https://images.unsplash.com/photo-1560185009-5bf9f2849488?q=80&w=2070&auto=format',
                    'title' => 'Ruang Tamu yang Harmonis',
                    'subtitle' => 'Kurasi furnitur modern dengan kenyamanan tak tertandingi untuk pusat rumah Anda.'
                ]
            ];
        @endphp

        @foreach($slides as $index => $slide)
        <div class="slide absolute inset-0 transition-opacity duration-1000 ease-in-out" style="opacity: {{ $index == 0 ? '1' : '0' }};">
            <!-- Gambar dengan efek zoom (Ken Burns) -->
            <div class="absolute inset-0 bg-cover bg-center scale-animation"
                 style="background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('{{ $slide['image'] }}');">
            </div>
            <!-- Konten teks -->
            <div class="absolute inset-0 flex items-center justify-center text-center text-white px-4">
                <div class="max-w-3xl">
                    <h1 class="text-4xl md:text-6xl font-serif font-semibold mb-4">{{ $slide['title'] }}</h1>
                    <p class="text-lg md:text-xl mb-8 max-w-2xl mx-auto opacity-90">{{ $slide['subtitle'] }}</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('products.index') }}" class="bg-white text-gray-900 px-6 py-3 rounded-full text-sm font-medium hover:bg-gray-100 transition">Jelajahi Koleksi</a>
                        <a href="#" class="border border-white text-white px-6 py-3 rounded-full text-sm font-medium hover:bg-white hover:text-gray-900 transition">Lihat Lookbook 2024</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Tombol Prev/Next (strip horizontal) -->
    <button id="prevSlide" class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-10 flex items-center justify-center group z-10 focus:outline-none">
        <div class="h-0.5 w-6 bg-white/60 group-hover:bg-white group-hover:w-8 transition-all duration-300"></div>
    </button>
    <button id="nextSlide" class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-10 flex items-center justify-center group z-10 focus:outline-none">
        <div class="h-0.5 w-6 bg-white/60 group-hover:bg-white group-hover:w-8 transition-all duration-300"></div>
    </button>

    <!-- Dot Indicators -->
    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2 z-10">
        @foreach($slides as $index => $slide)
        <button class="dot w-2 h-2 rounded-full bg-white/50 hover:bg-white transition duration-300 {{ $index == 0 ? 'bg-white w-4' : '' }}" data-index="{{ $index }}"></button>
        @endforeach
    </div>
</section>

<style>
    /* Efek Ken Burns (zoom halus) */
    .scale-animation {
        animation: kenBurnsZoom 20s ease-in-out infinite alternate;
        transform-origin: center;
    }
    @keyframes kenBurnsZoom {
        0% { transform: scale(1); }
        100% { transform: scale(1.08); }
    }
    /* Saat slide berganti, animasi zoom direset */
    .slide {
        transition: opacity 1000ms ease-in-out;
    }
    .slide .scale-animation {
        animation: kenBurnsZoom 20s ease-in-out infinite alternate;
    }
    /* Pastikan slide yang tidak aktif tidak mengganggu */
    .slide[style*="opacity: 0"] .scale-animation {
        animation: none;
    }
</style>

<script>
    // Hero Slider dengan reset animasi zoom
    (function() {
        const slides = document.querySelectorAll('.slide');
        const dots = document.querySelectorAll('.dot');
        const prevBtn = document.getElementById('prevSlide');
        const nextBtn = document.getElementById('nextSlide');
        let currentIndex = 0;
        let interval;

        function showSlide(index) {
            if (index < 0) index = slides.length - 1;
            if (index >= slides.length) index = 0;
            
            slides.forEach((slide, i) => {
                if (i === index) {
                    slide.style.opacity = '1';
                    // Restart animasi zoom pada slide yang aktif
                    const zoomDiv = slide.querySelector('.scale-animation');
                    if (zoomDiv) {
                        zoomDiv.style.animation = 'none';
                        zoomDiv.offsetHeight; // force reflow
                        zoomDiv.style.animation = 'kenBurnsZoom 20s ease-in-out infinite alternate';
                    }
                } else {
                    slide.style.opacity = '0';
                }
            });
            
            dots.forEach((dot, i) => {
                if (i === index) {
                    dot.classList.add('bg-white', 'w-4');
                    dot.classList.remove('bg-white/50', 'w-2');
                } else {
                    dot.classList.remove('bg-white', 'w-4');
                    dot.classList.add('bg-white/50', 'w-2');
                }
            });
            currentIndex = index;
        }

        function nextSlide() {
            showSlide(currentIndex + 1);
            resetInterval();
        }

        function prevSlide() {
            showSlide(currentIndex - 1);
            resetInterval();
        }

        function resetInterval() {
            if (interval) clearInterval(interval);
            interval = setInterval(() => {
                nextSlide();
            }, 5000); // ganti slide setiap 5 detik
        }

        if (prevBtn) prevBtn.addEventListener('click', prevSlide);
        if (nextBtn) nextBtn.addEventListener('click', nextSlide);
        dots.forEach((dot, i) => {
            dot.addEventListener('click', () => {
                showSlide(i);
                resetInterval();
            });
        });

        resetInterval();
    })();
</script>

<!-- Ruang yang Terinspirasi (Kategori) -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-serif font-semibold text-gray-900">Ruang yang Terinspirasi</h2>
            <p class="text-gray-500 mt-2 max-w-2xl mx-auto">Pilih berdasarkan fungsi dan ciptakan ekosistem hidup yang selaras dengan gaya personal Anda.</p>
        </div>
        <div class="grid md:grid-cols-4 gap-6">
            @php
                $categories = [
                    ['name' => 'Kamar Tidur', 'image' => 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?q=80&w=2071&auto=format', 'slug' => 'kamar-tidur'],
                    ['name' => 'Ruang Tamu', 'image' => 'https://images.unsplash.com/photo-1560185009-5bf9f2849488?q=80&w=2070&auto=format', 'slug' => 'ruang-tamu'],
                    ['name' => 'Ruang Makan', 'image' => 'https://images.unsplash.com/photo-1617806118233-18e1de247200?q=80&w=2070&auto=format', 'slug' => 'ruang-makan'],
                    ['name' => 'Koleksi Baru', 'image' => 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?q=80&w=2070&auto=format', 'slug' => 'koleksi-baru'],
                ];
            @endphp
            @foreach($categories as $cat)
            <a href="{{ route('products.category', $cat['slug']) }}" class="group relative overflow-hidden rounded-2xl aspect-[3/4] block">
                <img src="{{ $cat['image'] }}" alt="{{ $cat['name'] }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                <div class="absolute inset-0 bg-black/30 group-hover:bg-black/40 transition flex items-end p-6">
                    <div class="text-white">
                        <h3 class="text-xl font-semibold">{{ $cat['name'] }}</h3>
                        <span class="inline-block mt-2 text-sm border-b border-white pb-0.5">Jelajahi →</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Produk Terpopuler -->
<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-10">
            <h2 class="text-3xl font-serif font-semibold">Produk Terpopuler</h2>
            <a href="{{ route('products.index') }}" class="text-amber-700 hover:text-amber-800 text-sm font-medium flex items-center gap-1">Semua Produk <i class="fa-solid fa-arrow-right text-xs"></i></a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @php
                $popularProducts = \App\Models\Product::where('is_active', true)->latest()->take(4)->get();
            @endphp
            @forelse($popularProducts as $product)
            <div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition group">
                <a href="{{ route('products.show', $product->slug) }}">
                    <img src="{{ $product->image ? asset('storage/'.$product->image) : 'https://placehold.co/400x400?text=Produk' }}" alt="{{ $product->name }}" class="w-full h-64 object-cover group-hover:scale-105 transition duration-300">
                </a>
                <div class="p-4">
                    <h3 class="font-semibold text-gray-800">{{ $product->name }}</h3>
                    <div class="flex items-center gap-1 mt-1">
                        @php $rating = $product->average_rating ?? 4.5; @endphp
                        @for($i=1; $i<=5; $i++)
                            <i class="fa-solid fa-star text-amber-400 text-sm {{ $i <= round($rating) ? '' : 'text-gray-300' }}"></i>
                        @endfor
                        <span class="text-sm text-gray-500 ml-1">{{ number_format($rating,1) }}</span>
                    </div>
                    <p class="text-amber-700 font-bold mt-2">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mt-3">
                        @csrf
                        <button type="submit" class="w-full bg-gray-900 text-white py-2 rounded-full text-sm hover:bg-amber-700 transition">Tambah ke Keranjang</button>
                    </form>
                </div>
            </div>
            @empty
            <div class="col-span-4 text-center py-10 text-gray-500">Belum ada produk.</div>
            @endforelse
        </div>
    </div>
</section>

<!-- Filosofi Kami -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div>
                <img src="https://images.unsplash.com/photo-1583847268964-b28dc8f51f92?q=80&w=2070&auto=format" alt="Pengrajin" class="rounded-2xl shadow-lg w-full">
            </div>
            <div>
                <h2 class="text-3xl md:text-4xl font-serif font-semibold mb-4">Dibuat dengan Tangan, Dirancang untuk Jiwa</h2>
                <p class="text-gray-600 leading-relaxed mb-6">Setiap potongan Sanctuari adalah hasil dari dedikasi pengrajin lokal yang memadukan teknik tradisional dengan visi desain kontemporer. Kami menggunakan material berkelanjutan untuk menciptakan furnitur yang tidak hanya indah, tetapi juga bertanggung jawab.</p>
                <div class="flex gap-6">
                    <div><span class="block text-2xl font-bold text-amber-700">+50</span><span class="text-sm text-gray-500">Pengrajin Ahli</span></div>
                    <div><span class="block text-2xl font-bold text-amber-700">100%</span><span class="text-sm text-gray-500">Material Lokal</span></div>
                    <div><span class="block text-2xl font-bold text-amber-700">5th</span><span class="text-sm text-gray-500">Garansi</span></div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection