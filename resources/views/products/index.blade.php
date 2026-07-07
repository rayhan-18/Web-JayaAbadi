@extends('layouts.app')

@section('title', 'Semua Produk - JayaAbadi')

@section('content')
<div class="bg-white">
    <!-- Hero Section -->
    <section class="relative py-20 md:py-28 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-amber-50 via-white to-amber-50/30"></div>
        <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(#d4a373 1px, transparent 1px); background-size: 24px 24px;"></div>
        <div class="absolute top-0 -left-20 w-80 h-80 bg-amber-300/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 -right-20 w-96 h-96 bg-stone-300/20 rounded-full blur-3xl"></div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center animate-section">
            <div class="inline-flex items-center gap-2 bg-white/60 backdrop-blur-sm text-amber-800 text-xs font-semibold px-4 py-1.5 rounded-full shadow-sm mb-5 animate-item">
                <i class="fas fa-star text-amber-500 text-xs"></i>
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
            </div>
        </div>
    </section>

    <!-- Filter & Grid Produk -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" id="produk">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar Filter -->
            <aside class="lg:w-1/4 space-y-6 animate-section">
                <div>
                    <h3 class="font-semibold text-gray-800 text-sm uppercase tracking-wider mb-4 flex items-center gap-2 pl-2">
                        <i class="fas fa-th-large text-amber-500 text-sm"></i> Kategori
                    </h3>
                    <ul class="space-y-2 text-sm">
                        <li>
                            <a href="{{ route('products.index') }}" class="flex justify-between text-gray-800 font-medium hover:text-amber-600 transition">
                                <span><i class="fas fa-th-large text-amber-500 mr-2 text-xs"></i>Semua Produk</span>
                                <span class="text-gray-400">{{ $products->total() }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('products.category', 'ruang-tamu') }}" class="flex justify-between text-gray-500 hover:text-amber-600 transition">
                                <span><i class="fas fa-couch mr-2 text-xs"></i>Ruang Tamu</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('products.category', 'kamar-tidur') }}" class="flex justify-between text-gray-500 hover:text-amber-600 transition">
                                <span><i class="fas fa-bed mr-2 text-xs"></i>Kamar Tidur</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('products.category', 'ruang-makan') }}" class="flex justify-between text-gray-500 hover:text-amber-600 transition">
                                <span><i class="fas fa-utensils mr-2 text-xs"></i>Ruang Makan</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('products.category', 'koleksi-baru') }}" class="flex justify-between text-gray-500 hover:text-amber-600 transition">
                                <span><i class="fas fa-star mr-2 text-xs"></i>Koleksi Baru</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-semibold text-gray-800 text-sm uppercase tracking-wider mb-4 flex items-center gap-2 pl-2">
                        <i class="fas fa-chart-line text-amber-500 text-sm"></i> Harga Maksimal
                    </h3>
                    <div class="space-y-3">
                        <input type="range" id="priceRange" min="500000" max="50000000" step="500000" value="50000000" class="w-full h-1.5 bg-gray-200 rounded-lg accent-amber-500">
                        <div class="text-sm bg-gray-50 p-2 rounded-lg text-center border">
                            <i class="fas fa-tag text-amber-500 mr-1"></i> ≤ <span id="priceValue" class="font-semibold text-amber-700">Rp 50.000.000</span>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Grid Produk -->
            <div class="lg:w-3/4">
                @if($products->isEmpty())
                    <div class="text-center py-20">
                        <i class="fas fa-box-open text-5xl text-gray-200 mb-4"></i>
                        <p class="text-gray-400 text-lg">Belum ada produk tersedia.</p>
                    </div>
                @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="productGrid">
                    @foreach($products as $index => $product)
                    <div class="group bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition overflow-hidden animate-item flex flex-col h-full"
                         data-price="{{ $product->price }}">
                        <a href="{{ route('products.show', $product->slug) }}" class="flex-1">
                            <div class="relative aspect-square overflow-hidden bg-gray-100">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                @else
                                    <img src="https://placehold.co/600x600?text={{ urlencode($product->name) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                @endif
                                <button class="absolute top-2 right-2 bg-white/90 w-7 h-7 rounded-full flex items-center justify-center text-gray-500 hover:text-rose-500 transition">
                                    <i class="far fa-heart text-sm"></i>
                                </button>
                            </div>
                            <div class="p-4 pb-2">
                                <h3 class="font-medium text-gray-800">{{ $product->name }}</h3>
                                <p class="text-gray-400 text-xs mt-1">{{ $product->category->name ?? '' }}</p>
                                @if($product->sale_price)
                                    <div class="flex items-center gap-2 mt-2">
                                        <p class="text-amber-700 font-bold text-lg">Rp {{ number_format($product->sale_price, 0, ',', '.') }}</p>
                                        <p class="text-gray-400 text-sm line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                    </div>
                                @else
                                    <p class="text-amber-700 font-bold text-lg mt-2">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                @endif
                            </div>
                        </a>

                        <div class="px-4 mb-4 mt-2">
                            @auth
                                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="w-full bg-gray-900 hover:bg-amber-600 text-white py-2.5 rounded-full text-sm font-medium transition flex items-center justify-center gap-2 shadow-sm hover:-translate-y-0.5">
                                        <i class="fas fa-bag-shopping text-xs"></i> Keranjang
                                    </button>
                                </form>
                            @else
                                <button onclick="showLoginAlert()" class="w-full bg-gray-900 hover:bg-amber-600 text-white py-2.5 rounded-full text-sm font-medium transition flex items-center justify-center gap-2 shadow-sm hover:-translate-y-0.5">
                                    <i class="fas fa-bag-shopping text-xs"></i> Keranjang
                                </button>
                            @endauth
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($products->lastPage() > 1)
                <div class="mt-10 flex justify-center">
                    <nav class="flex items-center gap-2">
                        @if($products->onFirstPage())
                            <span class="px-3 py-1.5 border rounded-md text-sm text-gray-300">Prev</span>
                        @else
                            <a href="{{ $products->previousPageUrl() }}" class="px-3 py-1.5 border rounded-md text-sm hover:bg-gray-50 flex items-center gap-1"><i class="fas fa-chevron-left text-xs"></i> Prev</a>
                        @endif

                        @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                            @if($page == $products->currentPage())
                                <span class="px-3 py-1.5 bg-amber-600 text-white rounded-md text-sm">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="px-3 py-1.5 border rounded-md text-sm hover:bg-gray-50">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if($products->hasMorePages())
                            <a href="{{ $products->nextPageUrl() }}" class="px-3 py-1.5 border rounded-md text-sm hover:bg-gray-50 flex items-center gap-1">Next <i class="fas fa-chevron-right text-xs"></i></a>
                        @else
                            <span class="px-3 py-1.5 border rounded-md text-sm text-gray-300">Next</span>
                        @endif
                    </nav>
                </div>
                @endif
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .animate-section {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 0.8s cubic-bezier(0.2, 0.9, 0.4, 1.1), transform 0.8s cubic-bezier(0.2, 0.9, 0.4, 1.1);
        will-change: transform, opacity;
    }
    .animate-section.is-visible { opacity: 1; transform: translateY(0); }
    .animate-item {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.6s ease, transform 0.6s ease;
        will-change: transform, opacity;
    }
    .animate-item.is-visible { opacity: 1; transform: translateY(0); }
    .delay-1 { transition-delay: 0.08s; }
    .delay-2 { transition-delay: 0.16s; }
    .delay-3 { transition-delay: 0.24s; }
    .delay-4 { transition-delay: 0.32s; }
</style>

<script>
    (function() {
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
            [...sections, ...items].forEach(el => {
                if (el.getBoundingClientRect().top < window.innerHeight - 50) {
                    el.classList.add('is-visible');
                    observer.unobserve(el);
                }
            });
        };
        window.addEventListener('load', alreadyVisible);
        window.addEventListener('resize', alreadyVisible);
    })();

    // Price filter
    document.addEventListener('DOMContentLoaded', function() {
        const priceSlider = document.getElementById('priceRange');
        const priceValueSpan = document.getElementById('priceValue');
        const productsList = document.querySelectorAll('#productGrid .group');

        function filterByPrice() {
            let maxPrice = parseInt(priceSlider.value);
            priceValueSpan.innerText = new Intl.NumberFormat('id-ID', {
                style: 'currency', currency: 'IDR', minimumFractionDigits: 0
            }).format(maxPrice);
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

    function showLoginAlert() {
        Swal.fire({
            title: 'Login Diperlukan',
            text: 'Kamu harus login terlebih dahulu untuk menambahkan produk ke keranjang.',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Login Sekarang',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#92400e',
            cancelButtonColor: '#6b7280',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '{{ route("login") }}';
            }
        });
    }
</script>
@endsection