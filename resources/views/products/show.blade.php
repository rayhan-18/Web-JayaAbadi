@php
    $slug = request()->segment(2);
    
    $allProducts = [
        // ========== RUANG TAMU ==========
        'kursi-lengan-nordik' => [
            'name' => 'Kursi Lengan Nordik',
            'category' => 'Ruang Tamu',
            'category_slug' => 'ruang-tamu',
            'price' => 4250000,
            'image' => 'https://images.unsplash.com/photo-1567538096630-e0c55bd6374c?w=800&h=800&fit=crop',
            'rating' => 4.8,
            'reviews' => 124,
            'description' => 'Kursi dengan desain Nordik yang minimalis, berbahan linen abu terang, rangka kayu jati.',
            'material' => 'Kayu Jati, Linen',
            'dimension' => '85cm x 90cm x 75cm',
            'weight' => '25 kg',
            'stock' => 12,
        ],
        'meja-samping-walnut' => [
            'name' => 'Meja Samping Walnut',
            'category' => 'Ruang Tamu',
            'category_slug' => 'ruang-tamu',
            'price' => 1850000,
            'image' => 'https://images.unsplash.com/photo-1533090481720-856c6e3c1fdc?w=800&h=800&fit=crop',
            'rating' => 4.7,
            'reviews' => 89,
            'description' => 'Meja samping dari kayu walnut solid, finishing natural.',
            'material' => 'Kayu Walnut',
            'dimension' => '50cm x 40cm x 60cm',
            'weight' => '12 kg',
            'stock' => 15,
        ],
        'sofa-velvet-serenity' => [
            'name' => 'Sofa Velvet Serenity',
            'category' => 'Ruang Tamu',
            'category_slug' => 'ruang-tamu',
            'price' => 12500000,
            'image' => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=800&h=800&fit=crop',
            'rating' => 4.9,
            'reviews' => 245,
            'description' => 'Sofa 3 seater berbahan velvet hijau sage, bantalan tebal, rangka kayu.',
            'material' => 'Velvet, Kayu Jati',
            'dimension' => '210cm x 85cm x 80cm',
            'weight' => '45 kg',
            'stock' => 5,
        ],
        'lampu-gantung-aruma' => [
            'name' => 'Lampu Gantung Aruma',
            'category' => 'Ruang Tamu',
            'category_slug' => 'ruang-tamu',
            'price' => 2100000,
            'image' => 'https://images.unsplash.com/photo-1513506003901-1e6a229e2d15?w=800&h=800&fit=crop',
            'rating' => 4.6,
            'reviews' => 67,
            'description' => 'Lampu gantung dengan desain modern, material metal hitam dan kaca opal.',
            'material' => 'Metal, Kaca',
            'dimension' => 'Diameter 40cm x Tinggi 60cm',
            'weight' => '3 kg',
            'stock' => 8,
        ],
        'rak-kayu-modular' => [
            'name' => 'Rak Kayu Modular',
            'category' => 'Ruang Tamu',
            'category_slug' => 'ruang-tamu',
            'price' => 3500000,
            'image' => 'https://images.unsplash.com/photo-1595425970376-ef85e18f2f69?w=800&h=800&fit=crop',
            'rating' => 4.8,
            'reviews' => 112,
            'description' => 'Rak dinding modular dari kayu ek, bisa disusun sesuai kebutuhan.',
            'material' => 'Kayu Ek',
            'dimension' => '120cm x 30cm x 120cm',
            'weight' => '18 kg',
            'stock' => 6,
        ],
        'meja-kopi-horizon' => [
            'name' => 'Meja Kopi Horizon',
            'category' => 'Ruang Tamu',
            'category_slug' => 'ruang-tamu',
            'price' => 4200000,
            'image' => 'https://images.unsplash.com/photo-1532372576444-dda954194ad6?w=800&h=800&fit=crop',
            'rating' => 4.7,
            'reviews' => 93,
            'description' => 'Meja kopi dengan meja kayu walnut solid dan kaki besi hitam.',
            'material' => 'Kayu Walnut, Besi',
            'dimension' => '120cm x 60cm x 45cm',
            'weight' => '22 kg',
            'stock' => 7,
        ],
        // ========== KAMAR TIDUR ==========
        'tempat-tidur-jati' => [
            'name' => 'Tempat Tidur Jati',
            'category' => 'Kamar Tidur',
            'category_slug' => 'kamar-tidur',
            'price' => 8500000,
            'image' => 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?w=800&h=800&fit=crop',
            'rating' => 4.9,
            'reviews' => 67,
            'description' => 'Tempat tidur ukuran king size dari kayu jati solid, desain minimalis modern.',
            'material' => 'Kayu Jati',
            'dimension' => '200cm x 180cm x 40cm',
            'weight' => '70 kg',
            'stock' => 3,
        ],
        'lemari-sliding' => [
            'name' => 'Lemari Sliding',
            'category' => 'Kamar Tidur',
            'category_slug' => 'kamar-tidur',
            'price' => 4200000,
            'image' => 'https://images.unsplash.com/photo-1595425970376-ef85e18f2f69?w=800&h=800&fit=crop',
            'rating' => 4.7,
            'reviews' => 45,
            'description' => 'Lemari sliding dengan pintu kaca dan rangka aluminium.',
            'material' => 'Aluminium, Kaca',
            'dimension' => '180cm x 60cm x 210cm',
            'weight' => '45 kg',
            'stock' => 5,
        ],
        'nightstand-kayu' => [
            'name' => 'Nightstand Kayu',
            'category' => 'Kamar Tidur',
            'category_slug' => 'kamar-tidur',
            'price' => 1250000,
            'image' => 'https://images.unsplash.com/photo-1533090481720-856c6e3c1fdc?w=800&h=800&fit=crop',
            'rating' => 4.6,
            'reviews' => 32,
            'description' => 'Meja samping tempat tidur dari kayu jati, dua laci.',
            'material' => 'Kayu Jati',
            'dimension' => '50cm x 40cm x 60cm',
            'weight' => '10 kg',
            'stock' => 12,
        ],
        'karpet-bulu' => [
            'name' => 'Karpet Bulu',
            'category' => 'Kamar Tidur',
            'category_slug' => 'kamar-tidur',
            'price' => 2100000,
            'image' => 'https://images.unsplash.com/photo-1532372576444-dda954194ad6?w=800&h=800&fit=crop',
            'rating' => 4.8,
            'reviews' => 56,
            'description' => 'Karpet bulu lembut, warna broken white, ukuran 200x300cm.',
            'material' => 'Sintetis Premium',
            'dimension' => '200cm x 300cm',
            'weight' => '8 kg',
            'stock' => 9,
        ],
        // ========== RUANG MAKAN ==========
        'meja-makan-kayu' => [
            'name' => 'Meja Makan Kayu',
            'category' => 'Ruang Makan',
            'category_slug' => 'ruang-makan',
            'price' => 5500000,
            'image' => 'https://images.unsplash.com/photo-1617806118233-18e1de247200?w=800&h=800&fit=crop',
            'rating' => 4.8,
            'reviews' => 62,
            'description' => 'Meja makan kayu jati untuk 6 kursi, desain minimalis.',
            'material' => 'Kayu Jati',
            'dimension' => '160cm x 90cm x 75cm',
            'weight' => '40 kg',
            'stock' => 4,
        ],
        'kursi-makan-set' => [
            'name' => 'Kursi Makan Set',
            'category' => 'Ruang Makan',
            'category_slug' => 'ruang-makan',
            'price' => 3200000,
            'image' => 'https://images.unsplash.com/photo-1533090481720-856c6e3c1fdc?w=800&h=800&fit=crop',
            'rating' => 4.7,
            'reviews' => 38,
            'description' => 'Set 6 kursi makan dengan sandaran nyaman, bahan kayu.',
            'material' => 'Kayu Jati, Busa',
            'dimension' => '45cm x 50cm x 95cm per kursi',
            'weight' => '35 kg (set)',
            'stock' => 6,
        ],
        'lemari-piring' => [
            'name' => 'Lemari Piring',
            'category' => 'Ruang Makan',
            'category_slug' => 'ruang-makan',
            'price' => 4800000,
            'image' => 'https://images.unsplash.com/photo-1595425970376-ef85e18f2f69?w=800&h=800&fit=crop',
            'rating' => 4.9,
            'reviews' => 27,
            'description' => 'Lemari piring kaca dengan rak serbaguna.',
            'material' => 'Kayu Jati, Kaca',
            'dimension' => '120cm x 40cm x 180cm',
            'weight' => '38 kg',
            'stock' => 3,
        ],
        // ========== KOLEKSI BARU ==========
        'kursi-santai-modern' => [
            'name' => 'Kursi Santai Modern',
            'category' => 'Koleksi Baru',
            'category_slug' => 'koleksi-baru',
            'price' => 4750000,
            'image' => 'https://images.unsplash.com/photo-1567538096630-e0c55bd6374c?w=800&h=800&fit=crop',
            'rating' => 5.0,
            'reviews' => 19,
            'description' => 'Kursi santai dengan desain modern, bantalan tebal dan nyaman.',
            'material' => 'Kayu, Kain Beludru',
            'dimension' => '80cm x 85cm x 100cm',
            'weight' => '28 kg',
            'stock' => 7,
        ],
        'lampu-meja-minimalis' => [
            'name' => 'Lampu Meja Minimalis',
            'category' => 'Koleksi Baru',
            'category_slug' => 'koleksi-baru',
            'price' => 890000,
            'image' => 'https://images.unsplash.com/photo-1513506003901-1e6a229e2d15?w=800&h=800&fit=crop',
            'rating' => 4.9,
            'reviews' => 33,
            'description' => 'Lampu meja desain minimalis dengan pencahayaan hangat.',
            'material' => 'Metal, Kaca',
            'dimension' => '15cm x 15cm x 40cm',
            'weight' => '1.5 kg',
            'stock' => 20,
        ],
        'rak-dinding-geometris' => [
            'name' => 'Rak Dinding Geometris',
            'category' => 'Koleksi Baru',
            'category_slug' => 'koleksi-baru',
            'price' => 1750000,
            'image' => 'https://images.unsplash.com/photo-1595425970376-ef85e18f2f69?w=800&h=800&fit=crop',
            'rating' => 4.8,
            'reviews' => 16,
            'description' => 'Rak dinding bentuk geometris, cocok untuk dekorasi.',
            'material' => 'Kayu Lapis',
            'dimension' => 'Variabel',
            'weight' => '5 kg',
            'stock' => 10,
        ],
    ];
    
    $product = $allProducts[$slug] ?? null;
    if (!$product) {
        // Redirect ke halaman kategori Ruang Tamu jika produk tidak ditemukan
        header('Location: ' . route('products.category', 'ruang-tamu'));
        exit;
    }
@endphp

@extends('layouts.app')

@section('title', $product['name'] . ' - Sanctuari')

@section('content')
<div class="bg-white">
    <!-- Breadcrumb -->
    <div class="border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <a href="{{ route('home') }}" class="hover:text-amber-600">Home</a>
                <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                <a href="{{ route('products.category', $product['category_slug']) }}" class="hover:text-amber-600">{{ $product['category'] }}</a>
                <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                <span class="text-gray-800">{{ $product['name'] }}</span>
            </div>
        </div>
    </div>

    <!-- Detail Produk (sama seperti sebelumnya) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex flex-col lg:flex-row gap-12">
            <div class="lg:w-1/2">
                <div class="bg-gray-100 rounded-2xl overflow-hidden shadow-md">
                    <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="w-full object-cover">
                </div>
                <div class="flex gap-3 mt-4">
                    <div class="w-20 h-20 bg-gray-100 rounded-lg overflow-hidden cursor-pointer border-2 border-amber-400">
                        <img src="{{ $product['image'] }}" class="w-full h-full object-cover">
                    </div>
                    <div class="w-20 h-20 bg-gray-100 rounded-lg overflow-hidden cursor-pointer">
                        <img src="https://images.unsplash.com/photo-1533090481720-856c6e3c1fdc?w=150&h=150&fit=crop" class="w-full h-full object-cover">
                    </div>
                    <div class="w-20 h-20 bg-gray-100 rounded-lg overflow-hidden cursor-pointer">
                        <img src="https://images.unsplash.com/photo-1567538096630-e0c55bd6374c?w=150&h=150&fit=crop" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>
            <div class="lg:w-1/2">
                <div class="flex items-center gap-2 mb-2">
                    <div class="flex text-yellow-400">
                        @for($i=1; $i<=5; $i++)
                            <i class="fas fa-star text-sm {{ $i <= round($product['rating']) ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                        @endfor
                    </div>
                    <span class="text-sm text-gray-500">({{ $product['reviews'] }} ulasan)</span>
                </div>
                <h1 class="text-3xl md:text-4xl font-serif font-semibold text-gray-900">{{ $product['name'] }}</h1>
                <p class="text-2xl text-amber-700 font-bold mt-3">Rp {{ number_format($product['price'], 0, ',', '.') }}</p>
                <div class="mt-6 border-t border-gray-100 pt-6">
                    <h3 class="font-semibold text-gray-800 mb-2">Deskripsi</h3>
                    <p class="text-gray-600 leading-relaxed">{{ $product['description'] }}</p>
                </div>
                <div class="mt-6 grid grid-cols-2 gap-4 text-sm">
                    <div><span class="font-medium text-gray-700">Material:</span> <span class="text-gray-600">{{ $product['material'] }}</span></div>
                    <div><span class="font-medium text-gray-700">Dimensi:</span> <span class="text-gray-600">{{ $product['dimension'] }}</span></div>
                    <div><span class="font-medium text-gray-700">Berat:</span> <span class="text-gray-600">{{ $product['weight'] }}</span></div>
                    <div><span class="font-medium text-gray-700">Stok:</span> <span class="text-amber-700">{{ $product['stock'] }} unit</span></div>
                </div>
                <div class="mt-8">
                    <button onclick="alert('Produk ditambahkan ke keranjang (demo)')" 
                            class="w-full bg-gray-900 hover:bg-amber-600 text-white px-6 py-3 rounded-full font-medium transition flex items-center justify-center gap-2">
                        <i class="fas fa-bag-shopping"></i> Tambah ke Keranjang
                    </button>
                </div>
            </div>
        </div>

        <!-- Produk Terkait -->
        <div class="mt-20">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-serif font-semibold text-gray-900">Anda Mungkin Juga Suka</h2>
                <a href="{{ route('products.category', $product['category_slug']) }}" class="text-amber-600 hover:text-amber-700 text-sm flex items-center gap-1">
                    Lihat Semua <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                    $related = array_filter($allProducts, function($p, $key) use ($product, $slug) {
                        return $p['category_slug'] == $product['category_slug'] && $key != $slug;
                    }, ARRAY_FILTER_USE_BOTH);
                    $related = array_slice($related, 0, 4);
                @endphp
                @foreach($related as $relSlug => $rel)
                <div class="group">
                    <a href="{{ route('products.show', $relSlug) }}">
                        <div class="bg-gray-100 rounded-xl overflow-hidden">
                            <img src="{{ $rel['image'] }}" class="w-full aspect-square object-cover group-hover:scale-105 transition duration-300">
                        </div>
                        <div class="text-center mt-3">
                            <h3 class="font-medium text-gray-800">{{ $rel['name'] }}</h3>
                            <p class="text-amber-700 font-semibold mt-1">Rp {{ number_format($rel['price'], 0, ',', '.') }}</p>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection