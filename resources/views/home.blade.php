@extends('layouts.app')

@section('title', 'Beranda - Sanctuari')

@section('content')
<!-- Hero Slider (tidak diubah, tetap ada) -->
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
            <div class="absolute inset-0 bg-cover bg-center scale-animation"
                 style="background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('{{ $slide['image'] }}');">
            </div>
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

    <button id="prevSlide" class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-10 flex items-center justify-center group z-10 focus:outline-none">
        <div class="h-0.5 w-6 bg-white/60 group-hover:bg-white group-hover:w-8 transition-all duration-300"></div>
    </button>
    <button id="nextSlide" class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-10 flex items-center justify-center group z-10 focus:outline-none">
        <div class="h-0.5 w-6 bg-white/60 group-hover:bg-white group-hover:w-8 transition-all duration-300"></div>
    </button>

    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2 z-10">
        @foreach($slides as $index => $slide)
        <button class="dot w-2 h-2 rounded-full bg-white/50 hover:bg-white transition duration-300 {{ $index == 0 ? 'bg-white w-4' : '' }}" data-index="{{ $index }}"></button>
        @endforeach
    </div>
</section>

<style>
    /* ===== HERO SLIDER STYLES ===== */
    .scale-animation {
        animation: kenBurnsZoom 20s ease-in-out infinite alternate;
        transform-origin: center;
    }
    @keyframes kenBurnsZoom {
        0% { transform: scale(1); }
        100% { transform: scale(1.08); }
    }
    .slide {
        transition: opacity 1000ms ease-in-out;
    }
    .slide .scale-animation {
        animation: kenBurnsZoom 20s ease-in-out infinite alternate;
    }
    .slide[style*="opacity: 0"] .scale-animation {
        animation: none;
    }

    /* ===== PREMIUM SCROLL REVEAL ANIMATIONS ===== */
    .animate-section {
        opacity: 0;
        transform: translateY(45px) scale(0.98);
        filter: blur(4px);
        transition: opacity 1.1s cubic-bezier(0.2, 0.9, 0.4, 1.1),
                    transform 1.1s cubic-bezier(0.2, 0.9, 0.4, 1.1),
                    filter 0.8s cubic-bezier(0.2, 0.9, 0.4, 1.1);
        will-change: transform, opacity, filter;
    }
    .animate-section.is-visible {
        opacity: 1;
        transform: translateY(0) scale(1);
        filter: blur(0);
    }

    .animate-item {
        opacity: 0;
        transform: translateY(30px) scale(0.96);
        transition: opacity 0.85s cubic-bezier(0.23, 1, 0.32, 1),
                    transform 0.85s cubic-bezier(0.23, 1, 0.32, 1);
        will-change: transform, opacity;
    }
    .animate-item.is-visible {
        opacity: 1;
        transform: translateY(0) scale(1);
    }

    /* Stagger delay yang lebih smooth (interval 0.08s) */
    .delay-1 { transition-delay: 0.08s; }
    .delay-2 { transition-delay: 0.16s; }
    .delay-3 { transition-delay: 0.24s; }
    .delay-4 { transition-delay: 0.32s; }
    .delay-5 { transition-delay: 0.40s; }
    .delay-6 { transition-delay: 0.48s; }

    /* Efek hardware acceleration untuk semua card */
    .category-card, .product-card {
        transform: translateZ(0);
        backface-visibility: hidden;
        transition: transform 0.4s cubic-bezier(0.2, 0.9, 0.4, 1.1), box-shadow 0.4s ease;
    }
    .category-card:hover, .product-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 25px 35px -15px rgba(0,0,0,0.2);
    }
    .category-card img, .product-card img {
        transition: transform 0.7s cubic-bezier(0.2, 0.9, 0.4, 1.1);
    }
    .category-card:hover img, .product-card:hover img {
        transform: scale(1.08);
    }
</style>

<!-- Ruang yang Terinspirasi (Kategori) -->
<section class="py-20 bg-white animate-section">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12 animate-item">
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
            @foreach($categories as $index => $cat)
            <a href="{{ route('products.category', $cat['slug']) }}" 
               class="category-card group relative overflow-hidden rounded-2xl aspect-[3/4] block animate-item delay-{{ ($index % 4) + 1 }}">
                <img src="{{ $cat['image'] }}" alt="{{ $cat['name'] }}" class="w-full h-full object-cover">
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

<!-- Produk Terpopuler dengan Gambar Placeholder External -->
<section class="py-20 bg-gray-50 animate-section">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-10 animate-item">
            <h2 class="text-3xl font-serif font-semibold">Produk Terpopuler</h2>
            <a href="{{ route('products.index') }}" class="text-amber-700 hover:text-amber-800 text-sm font-medium flex items-center gap-1">Semua Produk <i class="fa-solid fa-arrow-right text-xs"></i></a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @php
                $placeholderImages = [
                    'https://images.unsplash.com/photo-1567538096630-e0c55bd6374c?w=400&h=400&fit=crop',
                    'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=400&h=400&fit=crop',
                    'https://images.unsplash.com/photo-1583847268964-b28dc8f51f92?w=400&h=400&fit=crop',
                    'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?w=400&h=400&fit=crop',
                ];
                $popularProducts = \App\Models\Product::where('is_active', true)->latest()->take(4)->get();
            @endphp
            @forelse($popularProducts as $index => $product)
            <div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition product-card animate-item delay-{{ ($index % 4) + 1 }}">
                <a href="{{ route('products.show', $product->slug) }}">
                    @if($product->image && file_exists(public_path('storage/'.$product->image)))
                        <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" class="w-full h-64 object-cover">
                    @else
                        <img src="{{ $placeholderImages[$index % count($placeholderImages)] }}" alt="Furniture" class="w-full h-64 object-cover">
                    @endif
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
<section class="py-20 bg-white animate-section">
    <div class="container mx-auto px-4">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div class="animate-item delay-1 overflow-hidden rounded-2xl shadow-lg">
                <img src="https://images.unsplash.com/photo-1583847268964-b28dc8f51f92?q=80&w=2070&auto=format" alt="Pengrajin" class="w-full transition duration-700 hover:scale-105">
            </div>
            <div class="animate-item delay-2">
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

<script>
    (function() {
        // ========== HERO SLIDER (tetap) ==========
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
                    const zoomDiv = slide.querySelector('.scale-animation');
                    if (zoomDiv) {
                        zoomDiv.style.animation = 'none';
                        zoomDiv.offsetHeight;
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

        function nextSlide() { showSlide(currentIndex + 1); resetInterval(); }
        function prevSlide() { showSlide(currentIndex - 1); resetInterval(); }
        function resetInterval() {
            if (interval) clearInterval(interval);
            interval = setInterval(() => nextSlide(), 5000);
        }
        if (prevBtn) prevBtn.addEventListener('click', prevSlide);
        if (nextBtn) nextBtn.addEventListener('click', nextSlide);
        dots.forEach((dot, i) => dot.addEventListener('click', () => { showSlide(i); resetInterval(); }));
        resetInterval();

        // ========== SCROLL REVEAL PREMIUM ==========
        const animatedSections = document.querySelectorAll('.animate-section');
        const animatedItems = document.querySelectorAll('.animate-item');
        
        // Observer dengan threshold kecil agar muncul lebih awal
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -20px 0px' });
        
        animatedSections.forEach(section => observer.observe(section));
        animatedItems.forEach(item => observer.observe(item));
        
        // Fallback untuk elemen yang sudah terlihat saat load (misalnya karena kecepatan scroll)
        const alreadyVisible = () => {
            animatedSections.forEach(el => {
                const rect = el.getBoundingClientRect();
                if (rect.top < window.innerHeight - 50) {
                    el.classList.add('is-visible');
                    observer.unobserve(el);
                }
            });
            animatedItems.forEach(el => {
                const rect = el.getBoundingClientRect();
                if (rect.top < window.innerHeight - 50) {
                    el.classList.add('is-visible');
                    observer.unobserve(el);
                }
            });
        };
        window.addEventListener('load', alreadyVisible);
        window.addEventListener('resize', alreadyVisible);
    })();
</script>
@endsection