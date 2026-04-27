@php
    $slug = request()->segment(2);
    
    $categories = [
        'ruang-tamu' => [
            'name' => 'Ruang Tamu',
            'description' => 'Ciptakan harmoni di pusat rumah Anda dengan kurasi furnitur yang menggabungkan estetika modern dan kenyamanan tak tertandingi.',
            'products' => [
                ['slug' => 'kursi-lengan-nordik', 'name' => 'Kursi Lengan Nordik', 'price' => 4250000, 'image' => 'https://images.unsplash.com/photo-1567538096630-e0c55bd6374c?w=500&h=500&fit=crop', 'rating' => 4.8],
                ['slug' => 'meja-samping-walnut', 'name' => 'Meja Samping Walnut', 'price' => 1850000, 'image' => 'https://images.unsplash.com/photo-1533090481720-856c6e3c1fdc?w=500&h=500&fit=crop', 'rating' => 4.7],
                ['slug' => 'sofa-velvet-serenity', 'name' => 'Sofa Velvet Serenity', 'price' => 12500000, 'image' => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=500&h=500&fit=crop', 'rating' => 4.9],
                ['slug' => 'lampu-gantung-aruma', 'name' => 'Lampu Gantung Aruma', 'price' => 2100000, 'image' => 'https://images.unsplash.com/photo-1513506003901-1e6a229e2d15?w=500&h=500&fit=crop', 'rating' => 4.6],
                ['slug' => 'rak-kayu-modular', 'name' => 'Rak Kayu Modular', 'price' => 3500000, 'image' => 'https://images.unsplash.com/photo-1595425970376-ef85e18f2f69?w=500&h=500&fit=crop', 'rating' => 4.8],
                ['slug' => 'meja-kopi-horizon', 'name' => 'Meja Kopi Horizon', 'price' => 4200000, 'image' => 'https://images.unsplash.com/photo-1532372576444-dda954194ad6?w=500&h=500&fit=crop', 'rating' => 4.7],
            ]
        ],
        'kamar-tidur' => [
            'name' => 'Kamar Tidur',
            'description' => 'Ciptakan suasana tenang dan nyaman di kamar tidur Anda dengan koleksi tempat tidur, lemari, dan dekorasi pilihan.',
            'products' => [
                ['slug' => 'tempat-tidur-jati', 'name' => 'Tempat Tidur Jati', 'price' => 8500000, 'image' => 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?w=500&h=500&fit=crop', 'rating' => 4.9],
                ['slug' => 'lemari-sliding', 'name' => 'Lemari Sliding', 'price' => 4200000, 'image' => 'https://images.unsplash.com/photo-1595425970376-ef85e18f2f69?w=500&h=500&fit=crop', 'rating' => 4.7],
                ['slug' => 'nightstand-kayu', 'name' => 'Nightstand Kayu', 'price' => 1250000, 'image' => 'https://images.unsplash.com/photo-1533090481720-856c6e3c1fdc?w=500&h=500&fit=crop', 'rating' => 4.6],
                ['slug' => 'karpet-bulu', 'name' => 'Karpet Bulu', 'price' => 2100000, 'image' => 'https://images.unsplash.com/photo-1532372576444-dda954194ad6?w=500&h=500&fit=crop', 'rating' => 4.8],
            ]
        ],
        'ruang-makan' => [
            'name' => 'Ruang Makan',
            'description' => 'Hadirkan kehangatan saat makan bersama keluarga dengan meja dan kursi makan berkualitas.',
            'products' => [
                ['slug' => 'meja-makan-kayu', 'name' => 'Meja Makan Kayu', 'price' => 5500000, 'image' => 'https://images.unsplash.com/photo-1617806118233-18e1de247200?w=500&h=500&fit=crop', 'rating' => 4.8],
                ['slug' => 'kursi-makan-set', 'name' => 'Kursi Makan Set', 'price' => 3200000, 'image' => 'https://images.unsplash.com/photo-1533090481720-856c6e3c1fdc?w=500&h=500&fit=crop', 'rating' => 4.7],
                ['slug' => 'lemari-piring', 'name' => 'Lemari Piring', 'price' => 4800000, 'image' => 'https://images.unsplash.com/photo-1595425970376-ef85e18f2f69?w=500&h=500&fit=crop', 'rating' => 4.9],
            ]
        ],
        'koleksi-baru' => [
            'name' => 'Koleksi Baru',
            'description' => 'Temukan produk-produk terbaru dari Sanctuari yang siap mempercantik rumah Anda.',
            'products' => [
                ['slug' => 'kursi-santai-modern', 'name' => 'Kursi Santai Modern', 'price' => 4750000, 'image' => 'https://images.unsplash.com/photo-1567538096630-e0c55bd6374c?w=500&h=500&fit=crop', 'rating' => 5.0],
                ['slug' => 'lampu-meja-minimalis', 'name' => 'Lampu Meja Minimalis', 'price' => 890000, 'image' => 'https://images.unsplash.com/photo-1513506003901-1e6a229e2d15?w=500&h=500&fit=crop', 'rating' => 4.9],
                ['slug' => 'rak-dinding-geometris', 'name' => 'Rak Dinding Geometris', 'price' => 1750000, 'image' => 'https://images.unsplash.com/photo-1595425970376-ef85e18f2f69?w=500&h=500&fit=crop', 'rating' => 4.8],
            ]
        ]
    ];

    $category = $categories[$slug] ?? $categories['ruang-tamu'];
    $categoryName = $category['name'];
    $categoryDescription = $category['description'];
    $allProducts = $category['products'];

    $page = request()->get('page', 1);
    $perPage = 4;
    $totalProducts = count($allProducts);
    $lastPage = ceil($totalProducts / $perPage);
    $offset = ($page - 1) * $perPage;
    $productsOnPage = array_slice($allProducts, $offset, $perPage, true);
@endphp

@extends('layouts.app')

@section('title', $categoryName . ' - Sanctuari')

@section('content')
<div class="bg-white">
    <!-- Hero Section Minimalis Elegan (SAMA PERSIS DENGAN INDEX) -->
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
                {{ $categoryName }}
            </h1>
            <div class="w-16 h-0.5 bg-amber-400 mx-auto my-5 rounded-full animate-item delay-2"></div>
            <p class="text-gray-600 max-w-2xl mx-auto text-base md:text-lg animate-item delay-3">
                {{ $categoryDescription }}
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

    <!-- Grid Produk Full Width (TANPA SIDEBAR FILTER) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" id="produk">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($productsOnPage as $product)
            <div class="group bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition overflow-hidden animate-item flex flex-col h-full">
                <a href="{{ route('products.show', $product['slug']) }}" class="flex-1">
                    <div class="relative aspect-square overflow-hidden bg-gray-100">
                        <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        <div class="absolute top-2 left-2 bg-white/90 backdrop-blur-sm px-2 py-0.5 rounded-full text-xs">
                            <i class="fas fa-star text-yellow-500 text-[10px] mr-1"></i> {{ $product['rating'] }}
                        </div>
                        <button class="absolute top-2 right-2 bg-white/90 w-7 h-7 rounded-full flex items-center justify-center text-gray-500 hover:text-rose-500 transition">
                            <i class="far fa-heart text-sm"></i>
                        </button>
                    </div>
                    <div class="p-4 pb-2">
                        <h3 class="font-medium text-gray-800">{{ $product['name'] }}</h3>
                        <p class="text-gray-400 text-xs mt-1">{{ $categoryName }}</p>
                        <p class="text-amber-700 font-bold text-lg mt-2">Rp {{ number_format($product['price'], 0, ',', '.') }}</p>
                    </div>
                </a>

                <div class="px-4 mb-6 mt-2"> 
                    <form action="#" method="POST" onsubmit="alert('Demo: Produk ditambahkan ke keranjang'); return false;">
                        @csrf
                        <button type="submit" class="w-full bg-gray-900 hover:bg-amber-600 text-white py-3 rounded-full text-sm font-bold tracking-tight transition-all duration-300 flex items-center justify-center gap-2 shadow-md hover:shadow-lg hover:-translate-y-1 active:scale-95">
                            <i class="fas fa-bag-shopping text-xs"></i> 
                            <span>Keranjang</span>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        @if($lastPage > 1)
        <div class="mt-10 flex justify-center">
            <nav class="flex items-center gap-2">
                @if($page > 1)
                <a href="{{ route('products.category', ['slug' => $slug, 'page' => $page-1]) }}" class="px-3 py-1.5 border rounded-md text-sm hover:bg-gray-50 flex items-center gap-1"><i class="fas fa-chevron-left text-xs"></i> Prev</a>
                @endif
                @for($i = 1; $i <= $lastPage; $i++)
                    @if($i == $page)
                    <span class="px-3 py-1.5 bg-amber-600 text-white rounded-md text-sm">{{ $i }}</span>
                    @else
                    <a href="{{ route('products.category', ['slug' => $slug, 'page' => $i]) }}" class="px-3 py-1.5 border rounded-md text-sm hover:bg-gray-50">{{ $i }}</a>
                    @endif
                @endfor
                @if($page < $lastPage)
                <a href="{{ route('products.category', ['slug' => $slug, 'page' => $page+1]) }}" class="px-3 py-1.5 border rounded-md text-sm hover:bg-gray-50 flex items-center gap-1">Next <i class="fas fa-chevron-right text-xs"></i></a>
                @endif
            </nav>
        </div>
        @endif
    </div>
</div>

<style>
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
        const sections = document.querySelectorAll('.animate-section');
        const items = document.querySelectorAll('.animate-item');
        const observer = new IntersectionObserver(entries => {
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
            sections.forEach(el => { if(el.getBoundingClientRect().top < window.innerHeight - 50) el.classList.add('is-visible'); });
            items.forEach(el => { if(el.getBoundingClientRect().top < window.innerHeight - 50) el.classList.add('is-visible'); });
        };
        window.addEventListener('load', alreadyVisible);
        window.addEventListener('resize', alreadyVisible);
    })();
</script>
@endsection