@extends('layouts.app')

@section('title', 'Ruang Tamu - Sanctuari')

@section('content')
<div class="bg-white">
    <!-- Hero Section (bersih, minimalis) -->
    <div class="bg-gradient-to-b from-amber-50/40 to-white border-b border-amber-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
            <div class="text-center">
                <div class="inline-flex items-center gap-2 bg-amber-100/80 text-amber-800 text-xs font-semibold tracking-wide uppercase px-3 py-1 rounded-full mb-4">
                    <i class="fas fa-sparkle text-amber-600 text-xs"></i>
                    <span>Koleksi Eksklusif</span>
                </div>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-serif font-semibold text-gray-900">Ruang Tamu</h1>
                <div class="w-12 h-0.5 bg-amber-400 mx-auto my-5 rounded-full"></div>
                <p class="text-gray-600 max-w-2xl mx-auto text-base md:text-lg leading-relaxed">
                    Ciptakan harmoni di pusat rumah Anda dengan kurasi furnitur yang menggabungkan estetika modern dan kenyamanan tak tertandingi.
                </p>
            </div>
        </div>
    </div>

    <!-- Filter & Grid Produk -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex flex-col lg:flex-row gap-10">
            <!-- Sidebar Filter -->
            <aside class="lg:w-1/4 space-y-8">
                <!-- Kategori -->
                <div>
                    <h3 class="font-semibold text-gray-800 text-sm uppercase tracking-wider mb-4 flex items-center gap-2 border-l-3 border-amber-400 pl-2">
                        <i class="fas fa-th-large text-amber-500 text-sm"></i> Kategori
                    </h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="flex justify-between text-gray-800 font-medium hover:text-amber-600 transition"><span><i class="fas fa-th-large text-amber-500 mr-2 text-xs"></i>Semua Produk</span> <span class="text-gray-400 text-sm">24</span></a></li>
                        <li><a href="#" class="flex justify-between text-gray-500 hover:text-amber-600 transition"><span><i class="fas fa-couch mr-2 text-xs"></i>Sofa & Sectional</span> <span class="text-gray-400 text-sm">8</span></a></li>
                        <li><a href="#" class="flex justify-between text-gray-500 hover:text-amber-600 transition"><span><i class="fas fa-table mr-2 text-xs"></i>Meja Kopi</span> <span class="text-gray-400 text-sm">6</span></a></li>
                        <li><a href="#" class="flex justify-between text-gray-500 hover:text-amber-600 transition"><span><i class="fas fa-chair mr-2 text-xs"></i>Kursi Lengan</span> <span class="text-gray-400 text-sm">5</span></a></li>
                        <li><a href="#" class="flex justify-between text-gray-500 hover:text-amber-600 transition"><span><i class="fas fa-tv mr-2 text-xs"></i>Unit TV & Rak</span> <span class="text-gray-400 text-sm">5</span></a></li>
                    </ul>
                </div>

                <!-- Rentang Harga -->
                <div>
                    <h3 class="font-semibold text-gray-800 text-sm uppercase tracking-wider mb-4 flex items-center gap-2 border-l-3 border-amber-400 pl-2">
                        <i class="fas fa-chart-line text-amber-500 text-sm"></i> Rentang Harga
                    </h3>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Rp 1.000.000</span>
                            <span>Rp 50.000.000+</span>
                        </div>
                        <input type="range" min="1000000" max="50000000" step="1000000" value="25000000" class="w-full h-1.5 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-amber-500">
                        <div class="text-sm text-gray-700 bg-gray-50 p-2 rounded-lg text-center border border-gray-100">
                            <i class="fas fa-tag text-amber-500 mr-1"></i> Harga mulai: <span class="font-semibold text-amber-700">Rp 25.000.000</span>
                        </div>
                    </div>
                </div>

                <!-- Material -->
                <div>
                    <h3 class="font-semibold text-gray-800 text-sm uppercase tracking-wider mb-4 flex items-center gap-2 border-l-3 border-amber-400 pl-2">
                        <i class="fas fa-cube text-amber-500 text-sm"></i> Material
                    </h3>
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer hover:text-amber-600">
                            <input type="checkbox" class="rounded border-gray-300 text-amber-500 focus:ring-amber-400"> Kayu Jati
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer hover:text-amber-600">
                            <input type="checkbox" class="rounded border-gray-300 text-amber-500 focus:ring-amber-400"> Linen
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer hover:text-amber-600">
                            <input type="checkbox" class="rounded border-gray-300 text-amber-500 focus:ring-amber-400"> Velvet
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer hover:text-amber-600">
                            <input type="checkbox" class="rounded border-gray-300 text-amber-500 focus:ring-amber-400"> Walnut
                        </label>
                    </div>
                </div>
            </aside>

            <!-- Grid Produk Statis -->
            <div class="lg:w-3/4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-10">
                    @php
                        $products = [
                            ['name' => 'Kursi Lengan Nordik', 'category' => 'Kursi Lengan', 'price' => 4250000, 'image' => 'https://images.unsplash.com/photo-1567538096630-e0c55bd6374c?w=600&h=600&fit=crop', 'rating' => 4.8],
                            ['name' => 'Meja Samping Walnut', 'category' => 'Meja Kopi', 'price' => 1850000, 'image' => 'https://images.unsplash.com/photo-1533090481720-856c6e3c1fdc?w=600&h=600&fit=crop', 'rating' => 4.7],
                            ['name' => 'Sofa Velvet Serenity', 'category' => 'Sofa & Sectional', 'price' => 12500000, 'image' => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=600&h=600&fit=crop', 'rating' => 4.9],
                            ['name' => 'Lampu Gantung Aruma', 'category' => 'Pencahayaan', 'price' => 2100000, 'image' => 'https://images.unsplash.com/photo-1513506003901-1e6a229e2d15?w=600&h=600&fit=crop', 'rating' => 4.6],
                            ['name' => 'Rak Kayu Modular', 'category' => 'Unit TV & Rak', 'price' => 3500000, 'image' => 'https://images.unsplash.com/photo-1595425970376-ef85e18f2f69?w=600&h=600&fit=crop', 'rating' => 4.8],
                            ['name' => 'Meja Kopi Horizon', 'category' => 'Meja Kopi', 'price' => 4200000, 'image' => 'https://images.unsplash.com/photo-1532372576444-dda954194ad6?w=600&h=600&fit=crop', 'rating' => 4.7],
                        ];
                    @endphp

                    @foreach($products as $product)
                    <div class="group">
                        <div class="relative bg-gray-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300">
                            <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="w-full aspect-square object-cover transition duration-500 group-hover:scale-105">
                            <!-- Rating badge -->
                            <div class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm px-2 py-0.5 rounded-full text-xs font-medium shadow-sm">
                                <i class="fas fa-star text-yellow-500 text-[10px] mr-1"></i> {{ $product['rating'] }}
                            </div>
                            <!-- Wishlist button -->
                            <button class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm w-8 h-8 rounded-full flex items-center justify-center text-gray-500 hover:text-rose-500 transition shadow-sm">
                                <i class="far fa-heart"></i>
                            </button>
                        </div>
                        <div class="text-center mt-4">
                            <h3 class="font-medium text-gray-800 text-lg">{{ $product['name'] }}</h3>
                            <p class="text-gray-400 text-sm mt-0.5">{{ $product['category'] }}</p>
                            <p class="text-amber-700 font-bold text-xl mt-1">Rp {{ number_format($product['price'], 0, ',', '.') }}</p>
                            <button class="mt-3 w-full bg-gray-900 hover:bg-amber-600 text-white py-2.5 rounded-full text-sm font-medium transition duration-200 flex items-center justify-center gap-2 shadow-sm">
                                <i class="fas fa-bag-shopping text-sm"></i> Tambah ke Keranjang
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-12 flex justify-center">
                    <nav class="flex items-center gap-2">
                        <button class="px-3 py-1.5 border border-gray-300 rounded-md text-sm text-gray-600 hover:bg-gray-50 transition flex items-center gap-1">
                            <i class="fas fa-chevron-left text-xs"></i> Prev
                        </button>
                        <button class="px-3 py-1.5 bg-amber-600 text-white rounded-md text-sm shadow-sm">1</button>
                        <button class="px-3 py-1.5 border border-gray-300 rounded-md text-sm text-gray-600 hover:bg-gray-50 transition">2</button>
                        <button class="px-3 py-1.5 border border-gray-300 rounded-md text-sm text-gray-600 hover:bg-gray-50 transition">3</button>
                        <button class="px-3 py-1.5 border border-gray-300 rounded-md text-sm text-gray-600 hover:bg-gray-50 transition flex items-center gap-1">
                            Next <i class="fas fa-chevron-right text-xs"></i>
                        </button>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection