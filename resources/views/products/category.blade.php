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
    $products = $category['products'];
@endphp

@extends('layouts.app')

@section('title', $categoryName . ' - Sanctuari')

@section('content')
<div class="bg-white">
    <!-- Hero Kategori -->
    <div class="relative bg-gradient-to-r from-amber-50/60 via-white to-amber-50/60 border-b border-amber-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20 text-center">
            <div class="inline-flex items-center gap-2 bg-white/70 backdrop-blur-sm text-amber-700 text-xs font-semibold tracking-wide uppercase px-3 py-1 rounded-full border border-amber-200/50 mb-4">
                <i class="fas fa-tag text-amber-500 text-xs"></i>
                <span>Koleksi</span>
            </div>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-serif font-semibold text-gray-900">{{ $categoryName }}</h1>
            <div class="w-16 h-0.5 bg-amber-400 mx-auto my-5 rounded-full"></div>
            <p class="text-gray-600 max-w-2xl mx-auto text-base md:text-lg">{{ $categoryDescription }}</p>
        </div>
    </div>

    <!-- Grid Produk (Tanpa Wishlist) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($products as $product)
            <div class="group bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <a href="{{ route('products.show', $product['slug']) }}" class="block">
                    <div class="relative overflow-hidden bg-gray-100">
                        <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="w-full aspect-square object-cover transition duration-500 group-hover:scale-105">
                        <!-- Rating badge (tanpa wishlist) -->
                        <div class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm px-2 py-0.5 rounded-full text-xs font-semibold text-amber-700 shadow-sm">
                            <i class="fas fa-star text-yellow-500 text-[10px] mr-1"></i> {{ $product['rating'] }}
                        </div>
                    </div>
                    <div class="p-5 text-center">
                        <h3 class="font-semibold text-gray-800 text-lg">{{ $product['name'] }}</h3>
                        <p class="text-amber-700 font-bold text-xl mt-2">Rp {{ number_format($product['price'], 0, ',', '.') }}</p>
                        <div class="mt-4 flex justify-center">
                            <button onclick="event.preventDefault(); alert('Produk ditambahkan ke keranjang (demo)');" class="bg-gray-900 hover:bg-amber-600 text-white px-5 py-2 rounded-full text-sm font-medium transition flex items-center gap-2">
                                <i class="fas fa-bag-shopping text-sm"></i> Tambah
                            </button>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-12 flex justify-center">
            <nav class="flex gap-2">
                <button class="px-3 py-1.5 border border-gray-300 rounded-md text-sm text-gray-600 hover:bg-gray-50 transition">Prev</button>
                <button class="px-3 py-1.5 bg-amber-600 text-white rounded-md text-sm shadow-sm">1</button>
                <button class="px-3 py-1.5 border border-gray-300 rounded-md text-sm text-gray-600 hover:bg-gray-50 transition">2</button>
                <button class="px-3 py-1.5 border border-gray-300 rounded-md text-sm text-gray-600 hover:bg-gray-50 transition">3</button>
                <button class="px-3 py-1.5 border border-gray-300 rounded-md text-sm text-gray-600 hover:bg-gray-50 transition">Next</button>
            </nav>
        </div>
    </div>
</div>
@endsection