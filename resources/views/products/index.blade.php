@php
    // Data produk statis (16 produk) - sama persis dengan sebelumnya
    $allProducts = [
        'kursi-lengan-nordik' => ['name' => 'Kursi Lengan Nordik', 'category' => 'Ruang Tamu', 'category_slug' => 'ruang-tamu', 'price' => 4250000, 'image' => 'https://images.unsplash.com/photo-1567538096630-e0c55bd6374c?w=600&h=600&fit=crop', 'rating' => 4.8],
        'meja-samping-walnut' => ['name' => 'Meja Samping Walnut', 'category' => 'Ruang Tamu', 'category_slug' => 'ruang-tamu', 'price' => 1850000, 'image' => 'https://images.unsplash.com/photo-1533090481720-856c6e3c1fdc?w=600&h=600&fit=crop', 'rating' => 4.7],
        'sofa-velvet-serenity' => ['name' => 'Sofa Velvet Serenity', 'category' => 'Ruang Tamu', 'category_slug' => 'ruang-tamu', 'price' => 12500000, 'image' => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=600&h=600&fit=crop', 'rating' => 4.9],
        'lampu-gantung-aruma' => ['name' => 'Lampu Gantung Aruma', 'category' => 'Ruang Tamu', 'category_slug' => 'ruang-tamu', 'price' => 2100000, 'image' => 'https://images.unsplash.com/photo-1513506003901-1e6a229e2d15?w=600&h=600&fit=crop', 'rating' => 4.6],
        'rak-kayu-modular' => ['name' => 'Rak Kayu Modular', 'category' => 'Ruang Tamu', 'category_slug' => 'ruang-tamu', 'price' => 3500000, 'image' => 'https://images.unsplash.com/photo-1595425970376-ef85e18f2f69?w=600&h=600&fit=crop', 'rating' => 4.8],
        'meja-kopi-horizon' => ['name' => 'Meja Kopi Horizon', 'category' => 'Ruang Tamu', 'category_slug' => 'ruang-tamu', 'price' => 4200000, 'image' => 'https://images.unsplash.com/photo-1532372576444-dda954194ad6?w=600&h=600&fit=crop', 'rating' => 4.7],
        'tempat-tidur-jati' => ['name' => 'Tempat Tidur Jati', 'category' => 'Kamar Tidur', 'category_slug' => 'kamar-tidur', 'price' => 8500000, 'image' => 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?w=600&h=600&fit=crop', 'rating' => 4.9],
        'lemari-sliding' => ['name' => 'Lemari Sliding', 'category' => 'Kamar Tidur', 'category_slug' => 'kamar-tidur', 'price' => 4200000, 'image' => 'https://images.unsplash.com/photo-1595425970376-ef85e18f2f69?w=600&h=600&fit=crop', 'rating' => 4.7],
        'nightstand-kayu' => ['name' => 'Nightstand Kayu', 'category' => 'Kamar Tidur', 'category_slug' => 'kamar-tidur', 'price' => 1250000, 'image' => 'https://images.unsplash.com/photo-1533090481720-856c6e3c1fdc?w=600&h=600&fit=crop', 'rating' => 4.6],
        'karpet-bulu' => ['name' => 'Karpet Bulu', 'category' => 'Kamar Tidur', 'category_slug' => 'kamar-tidur', 'price' => 2100000, 'image' => 'https://images.unsplash.com/photo-1532372576444-dda954194ad6?w=600&h=600&fit=crop', 'rating' => 4.8],
        'meja-makan-kayu' => ['name' => 'Meja Makan Kayu', 'category' => 'Ruang Makan', 'category_slug' => 'ruang-makan', 'price' => 5500000, 'image' => 'https://images.unsplash.com/photo-1617806118233-18e1de247200?w=600&h=600&fit=crop', 'rating' => 4.8],
        'kursi-makan-set' => ['name' => 'Kursi Makan Set', 'category' => 'Ruang Makan', 'category_slug' => 'ruang-makan', 'price' => 3200000, 'image' => 'https://images.unsplash.com/photo-1533090481720-856c6e3c1fdc?w=600&h=600&fit=crop', 'rating' => 4.7],
        'lemari-piring' => ['name' => 'Lemari Piring', 'category' => 'Ruang Makan', 'category_slug' => 'ruang-makan', 'price' => 4800000, 'image' => 'https://images.unsplash.com/photo-1595425970376-ef85e18f2f69?w=600&h=600&fit=crop', 'rating' => 4.9],
        'kursi-santai-modern' => ['name' => 'Kursi Santai Modern', 'category' => 'Koleksi Baru', 'category_slug' => 'koleksi-baru', 'price' => 4750000, 'image' => 'https://images.unsplash.com/photo-1567538096630-e0c55bd6374c?w=600&h=600&fit=crop', 'rating' => 5.0],
        'lampu-meja-minimalis' => ['name' => 'Lampu Meja Minimalis', 'category' => 'Koleksi Baru', 'category_slug' => 'koleksi-baru', 'price' => 890000, 'image' => 'https://images.unsplash.com/photo-1513506003901-1e6a229e2d15?w=600&h=600&fit=crop', 'rating' => 4.9],
        'rak-dinding-geometris' => ['name' => 'Rak Dinding Geometris', 'category' => 'Koleksi Baru', 'category_slug' => 'koleksi-baru', 'price' => 1750000, 'image' => 'https://images.unsplash.com/photo-1595425970376-ef85e18f2f69?w=600&h=600&fit=crop', 'rating' => 4.8],
    ];
    
    $page = request()->get('page', 1);
    $perPage = 4;
    $totalProducts = count($allProducts);
    $lastPage = ceil($totalProducts / $perPage);
    $offset = ($page - 1) * $perPage;
    $productsOnPage = array_slice($allProducts, $offset, $perPage, true);
@endphp

@extends('layouts.app')

@section('title', 'Semua Produk - Sanctuari')

@section('content')
<div class="bg-white">
    <!-- Hero Section Minimalis Elegan: Gradien + Pattern + Glassmorphism -->
    <section class="relative py-20 md:py-28 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-amber-50 via-white to-amber-50/30"></div>
        <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(#d4a373 1px, transparent 1px); background-size: 24px 24px;"></div>
        <div class="absolute top-0 -left-20 w-80 h-80 bg-amber-300/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 -right-20 w-96 h-96 bg-stone-300/20 rounded-full blur-3xl"></div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center animate-section">
            <div class="inline-flex items-center gap-2 bg-white/60 backdrop-blur-sm text-amber-800 text-xs font-semibold px-4 py-1.5 rounded-full shadow-sm mb-5 animate-item">
                <i class="fas fa-sparkle text-amber-500 text-xs"></i>
                <span>Koleksi Eksklusif 2025</span>
            </div>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-serif font-semibold text-gray-900 animate-item delay-1">
                Temukan Furniture <span class="text-amber-600">Impian Anda</span>
            </h1>
            <div class="w-16 h-0.5 bg-amber-400 mx-auto my-5 rounded-full animate-item delay-2"></div>
            <p class="text-gray-600 max-w-2xl mx-auto text-base md:text-lg animate-item delay-3">
                Dari ruang tamu hingga kamar tidur, kami hadirkan koleksi terbaik dengan kualitas premium dan desain timeless.
            </p>
            <div class="flex gap-4 justify-center mt-8 animate-item delay-4">
                <a href="#produk" class="bg-gray-900 hover:bg-amber-600 text-white px-6 py-2.5 rounded-full text-sm font-medium transition transform hover:scale-105 shadow-lg flex items-center gap-2">
                    <i class="fas fa-arrow-down"></i> Lihat Koleksi
                </a>
                <a href="#" class="border border-gray-300 hover:border-amber-500 text-gray-700 hover:text-amber-600 px-6 py-2.5 rounded-full text-sm font-medium transition flex items-center gap-2">
                    <i class="fas fa-tag"></i> Promo Spesial
                </a>
            </div>
        </div>
    </section>

    <!-- Filter & Grid Produk (ukuran produk disamakan dengan category) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" id="produk">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar Filter (tetap ada) -->
            <aside class="lg:w-1/4 space-y-6 animate-section">
                <div>
                    <h3 class="font-semibold text-gray-800 text-sm uppercase tracking-wider mb-4 flex items-center gap-2 border-l-3 border-amber-400 pl-2">
                        <i class="fas fa-th-large text-amber-500 text-sm"></i> Kategori
                    </h3>
                    <ul class="space-y-2 text-sm" id="categoryFilter">
                        <li><a href="{{ route('products.index') }}" class="flex justify-between text-gray-800 font-medium hover:text-amber-600 transition"><span><i class="fas fa-th-large text-amber-500 mr-2 text-xs"></i>Semua Produk</span> <span class="text-gray-400">{{ $totalProducts }}</span></a></li>
                        <li><a href="{{ route('products.category', 'ruang-tamu') }}" class="flex justify-between text-gray-500 hover:text-amber-600 transition"><span><i class="fas fa-couch mr-2 text-xs"></i>Ruang Tamu</span> <span class="text-gray-400" id="count-ruang-tamu">0</span></a></li>
                        <li><a href="{{ route('products.category', 'kamar-tidur') }}" class="flex justify-between text-gray-500 hover:text-amber-600 transition"><span><i class="fas fa-bed mr-2 text-xs"></i>Kamar Tidur</span> <span class="text-gray-400" id="count-kamar-tidur">0</span></a></li>
                        <li><a href="{{ route('products.category', 'ruang-makan') }}" class="flex justify-between text-gray-500 hover:text-amber-600 transition"><span><i class="fas fa-utensils mr-2 text-xs"></i>Ruang Makan</span> <span class="text-gray-400" id="count-ruang-makan">0</span></a></li>
                        <li><a href="{{ route('products.category', 'koleksi-baru') }}" class="flex justify-between text-gray-500 hover:text-amber-600 transition"><span><i class="fas fa-star mr-2 text-xs"></i>Koleksi Baru</span> <span class="text-gray-400" id="count-koleksi-baru">0</span></a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-semibold text-gray-800 text-sm uppercase tracking-wider mb-4 flex items-center gap-2 border-l-3 border-amber-400 pl-2">
                        <i class="fas fa-chart-line text-amber-500 text-sm"></i> Harga Maksimal
                    </h3>
                    <div class="space-y-3">
                        <input type="range" id="priceRange" min="500000" max="15000000" step="500000" value="15000000" class="w-full h-1.5 bg-gray-200 rounded-lg accent-amber-500">
                        <div class="text-sm bg-gray-50 p-2 rounded-lg text-center border">
                            <i class="fas fa-tag text-amber-500 mr-1"></i> ≤ <span id="priceValue" class="font-semibold text-amber-700">Rp 15.000.000</span>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Grid Produk (4 kolom desktop, ukuran kecil seperti category) -->
            <div class="lg:w-3/4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="productGrid">
                    @foreach($productsOnPage as $slug => $product)
                    <div class="group bg-white rounded-xl shadow-sm hover:shadow-md transition overflow-hidden animate-item" data-category="{{ $product['category_slug'] }}" data-price="{{ $product['price'] }}">
                        <a href="{{ route('products.show', $slug) }}">
                            <div class="relative aspect-square overflow-hidden bg-gray-100">
                                <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                <div class="absolute top-2 left-2 bg-white/90 backdrop-blur-sm px-2 py-0.5 rounded-full text-xs">
                                    <i class="fas fa-star text-yellow-500 text-[10px] mr-1"></i> {{ $product['rating'] }}
                                </div>
                                <button class="absolute top-2 right-2 bg-white/90 w-7 h-7 rounded-full flex items-center justify-center text-gray-500 hover:text-rose-500 transition">
                                    <i class="far fa-heart text-sm"></i>
                                </button>
                            </div>
                            <div class="p-4">
                                <h3 class="font-medium text-gray-800">{{ $product['name'] }}</h3>
                                <p class="text-gray-400 text-xs mt-1">{{ $product['category'] }}</p>
                                <p class="text-amber-700 font-bold text-lg mt-2">Rp {{ number_format($product['price'], 0, ',', '.') }}</p>
                            </div>
                        </a>
                        <div class="px-4 pb-4">
                            <form action="#" method="POST" onsubmit="alert('Demo: Produk ditambahkan ke keranjang'); return false;">
                                @csrf
                                <button type="submit" class="w-full bg-gray-900 hover:bg-amber-600 text-white py-2 rounded-full text-sm font-medium transition flex items-center justify-center gap-1">
                                    <i class="fas fa-bag-shopping text-xs"></i> Keranjang
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($lastPage > 1)
                <div class="mt-10 flex justify-center">
                    <nav class="flex items-center gap-2">
                        @if($page > 1)
                        <a href="{{ route('products.index', ['page' => $page-1]) }}" class="px-3 py-1.5 border rounded-md text-sm hover:bg-gray-50 flex items-center gap-1"><i class="fas fa-chevron-left text-xs"></i> Prev</a>
                        @endif
                        @for($i = 1; $i <= $lastPage; $i++)
                            @if($i == $page)
                            <span class="px-3 py-1.5 bg-amber-600 text-white rounded-md text-sm">{{ $i }}</span>
                            @else
                            <a href="{{ route('products.index', ['page' => $i]) }}" class="px-3 py-1.5 border rounded-md text-sm hover:bg-gray-50">{{ $i }}</a>
                            @endif
                        @endfor
                        @if($page < $lastPage)
                        <a href="{{ route('products.index', ['page' => $page+1]) }}" class="px-3 py-1.5 border rounded-md text-sm hover:bg-gray-50 flex items-center gap-1">Next <i class="fas fa-chevron-right text-xs"></i></a>
                        @endif
                    </nav>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    /* Scroll reveal */
    .animate-section {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 0.8s cubic-bezier(0.2, 0.9, 0.4, 1.1), transform 0.8s cubic-bezier(0.2, 0.9, 0.4, 1.1);
        will-change: transform, opacity;
    }
    .animate-section.is-visible {
        opacity: 1;
        transform: translateY(0);
    }
    .animate-item {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.6s ease, transform 0.6s ease;
        will-change: transform, opacity;
    }
    .animate-item.is-visible {
        opacity: 1;
        transform: translateY(0);
    }
    .delay-1 { transition-delay: 0.08s; }
    .delay-2 { transition-delay: 0.16s; }
    .delay-3 { transition-delay: 0.24s; }
    .delay-4 { transition-delay: 0.32s; }
</style>

<script>
    (function() {
        // Scroll reveal
        const sections = document.querySelectorAll('.animate-section');
        const items = document.querySelectorAll('.animate-item');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -20px 0px' });
        sections.forEach(s => observer.observe(s));
        items.forEach(i => observer.observe(i));
        
        const alreadyVisible = () => {
            sections.forEach(el => {
                if (el.getBoundingClientRect().top < window.innerHeight - 50) {
                    el.classList.add('is-visible');
                    observer.unobserve(el);
                }
            });
            items.forEach(el => {
                if (el.getBoundingClientRect().top < window.innerHeight - 50) {
                    el.classList.add('is-visible');
                    observer.unobserve(el);
                }
            });
        };
        window.addEventListener('load', alreadyVisible);
        window.addEventListener('resize', alreadyVisible);
    })();

    // Update category counts & price filter
    document.addEventListener('DOMContentLoaded', function() {
        const products = @json($allProducts);
        const counts = { 'ruang-tamu': 0, 'kamar-tidur': 0, 'ruang-makan': 0, 'koleksi-baru': 0 };
        Object.values(products).forEach(p => {
            if (counts.hasOwnProperty(p.category_slug)) counts[p.category_slug]++;
        });
        document.getElementById('count-ruang-tamu').innerText = counts['ruang-tamu'];
        document.getElementById('count-kamar-tidur').innerText = counts['kamar-tidur'];
        document.getElementById('count-ruang-makan').innerText = counts['ruang-makan'];
        document.getElementById('count-koleksi-baru').innerText = counts['koleksi-baru'];

        const priceSlider = document.getElementById('priceRange');
        const priceValueSpan = document.getElementById('priceValue');
        const productsList = document.querySelectorAll('#productGrid .group');
        function filterByPrice() {
            let maxPrice = parseInt(priceSlider.value);
            priceValueSpan.innerText = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(maxPrice);
            productsList.forEach(product => {
                let price = parseInt(product.getAttribute('data-price'));
                product.style.display = price <= maxPrice ? '' : 'none';
            });
        }
        if (priceSlider) {
            priceSlider.addEventListener('input', filterByPrice);
            filterByPrice();
        }
    });
</script>
@endsection