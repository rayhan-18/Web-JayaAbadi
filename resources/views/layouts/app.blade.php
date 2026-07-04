<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Jaya Abadi')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&family=Playfair+Display:ital,wght@0,400;0,500;0,600;1,400&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-serif { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-white text-gray-800">

    <nav class="bg-white/90 backdrop-blur-md border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                
                <div class="flex items-center gap-4">
                    <button id="mobile-menu-btn" class="lg:hidden text-gray-600 hover:text-amber-700 focus:outline-none p-2 -ml-2">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>

                    <div class="text-2xl font-serif font-semibold tracking-wide">
                        <a href="{{ route('home') }}" class="text-gray-900">Jaya Abadi</a>
                    </div>
                </div>

                <div class="hidden lg:flex items-center space-x-8 text-sm font-medium">
                    <a href="{{ route('products.category', 'ruang-tamu') }}" class="text-gray-700 hover:text-amber-700 transition">Ruang Tamu</a>
                    <a href="{{ route('products.category', 'kamar-tidur') }}" class="text-gray-700 hover:text-amber-700 transition">Kamar Tidur</a>
                    <a href="{{ route('products.category', 'ruang-makan') }}" class="text-gray-700 hover:text-amber-700 transition">Ruang Makan</a>
                    <a href="{{ route('products.category', 'koleksi-baru') }}" class="text-gray-700 hover:text-amber-700 transition">Koleksi Baru</a>
                    <a href="#" class="text-gray-700 hover:text-amber-700 transition">Inspirasi</a>
                </div>

                <div class="flex items-center space-x-5 h-full">
                    <form action="{{ route('products.search') }}" method="GET" class="hidden lg:block">
                        <div class="relative">
                            <input type="text" name="q" placeholder="Cari produk..." value="{{ request('q') }}"
                                   class="pl-9 pr-4 py-1.5 rounded-full border border-gray-200 text-sm focus:outline-none focus:border-amber-300 w-48 xl:w-64 transition-all">
                            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        </div>
                    </form>

                <a href="{{ route('cart.index') }}" class="relative text-gray-700 hover:text-amber-700 transition transform hover:scale-105">
                    <i class="fa-solid fa-bag-shopping text-xl"></i>
                    
                    {{-- Cek apakah user sudah login, karena tabel cart biasanya butuh user_id --}}
                    @auth
                    @php
                        // 1. Cari keranjang 'induk' milik user yang statusnya masih aktif
                        $activeCart = \App\Models\Cart::where('user_id', auth()->id())
                                                    ->where('is_active', true)
                                                    ->first();
                        
                        // 2. Jika keranjang aktif ada, hitung total quantity dari tabel detailnya (relasi 'items')
                        $cartCount = $activeCart ? $activeCart->items()->sum('quantity') : 0;
                    @endphp

                    @if($cartCount > 0)
                        <span class="absolute -top-2 -right-2 bg-amber-600 text-white text-xs rounded-full min-w-[18px] h-[18px] flex items-center justify-center px-1">
                            {{ $cartCount > 99 ? '99+' : $cartCount }}
                        </span>
                    @endif
                @endauth
                </a>

                    @auth
                        <div class="relative group hidden lg:flex items-center h-full">
                            <button class="flex items-center space-x-1 text-gray-700 h-full px-2 py-6">
                                <i class="fa-regular fa-user text-xl"></i>
                            </button>
                            
                            <div class="absolute right-0 top-full pt-0 w-40 hidden group-hover:block z-50">
                                <div class="bg-white rounded-md shadow-lg border border-gray-100 overflow-hidden flex flex-col">
                                    
                                    <a href="{{ route('orders.index') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-amber-50 hover:text-amber-700 transition">
                                        Pesanan Saya
                                    </a>
                                    
                                    <div class="border-t border-gray-100"></div>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-amber-50 hover:text-amber-700 transition cursor-pointer">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <div class="lg:hidden flex items-center space-x-4">

                            <a href="{{ route('orders.index') }}" class="text-gray-700 hover:text-amber-700 transition" title="Pesanan Saya">
                                <i class="fa-solid fa-file-invoice text-xl"></i>
                            </a>
                            
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-gray-700 hover:text-amber-700 transition" title="Logout">
                                    <i class="fa-solid fa-right-from-bracket text-xl"></i>
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-amber-700 transition transform hover:scale-105">
                            <i class="fa-regular fa-user text-xl"></i>
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <div id="mobile-menu-panel" class="hidden lg:hidden bg-white border-t border-gray-100 absolute w-full shadow-lg">
            <div class="px-4 pt-4 pb-6 space-y-2">
                <form action="{{ route('products.search') }}" method="GET" class="mb-5">
                    <div class="relative">
                        <input type="text" name="q" placeholder="Cari produk..." value="{{ request('q') }}"
                               class="w-full pl-9 pr-3 py-2.5 rounded-lg border border-gray-200 text-sm focus:outline-none focus:border-amber-300">
                        <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    </div>
                </form>

                <a href="{{ route('products.category', 'ruang-tamu') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-amber-700 hover:bg-gray-50">Ruang Tamu</a>
                <a href="{{ route('products.category', 'kamar-tidur') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-amber-700 hover:bg-gray-50">Kamar Tidur</a>
                <a href="{{ route('products.category', 'ruang-makan') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-amber-700 hover:bg-gray-50">Ruang Makan</a>
                <a href="{{ route('products.category', 'koleksi-baru') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-amber-700 hover:bg-gray-50">Koleksi Baru</a>
                <a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-amber-700 hover:bg-gray-50">Inspirasi</a>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="bg-gray-50 border-t border-gray-200 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-8">
                
                <div class="lg:col-span-2 pr-4">
                    <h3 class="font-serif text-2xl font-bold mb-4 text-gray-900 tracking-wide">Jaya Abadi</h3>
                    <p class="text-gray-500 text-sm leading-relaxed max-w-sm">
                        Menciptakan ruang tenang dan nyaman melalui desain furnitur berkualitas tinggi yang dibuat dengan dedikasi dan material terbaik untuk rumah Anda.
                    </p>
                    <div class="mt-6 flex space-x-5">
                        <a href="#" class="text-gray-400 hover:text-amber-700 transition transform hover:scale-110">
                            <i class="fa-brands fa-instagram text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-amber-700 transition transform hover:scale-110">
                            <i class="fa-brands fa-facebook text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-amber-700 transition transform hover:scale-110">
                            <i class="fa-brands fa-whatsapp text-xl"></i>
                        </a>
                    </div>
                </div>
                
                <div>
                    <h4 class="font-semibold text-gray-900 mb-5 uppercase text-xs tracking-widest">Belanja</h4>
                    <ul class="space-y-3 text-sm text-gray-600">
                        <li><a href="{{ route('products.category', 'ruang-tamu') }}" class="hover:text-amber-700 transition">Ruang Tamu</a></li>
                        <li><a href="{{ route('products.category', 'kamar-tidur') }}" class="hover:text-amber-700 transition">Kamar Tidur</a></li>
                        <li><a href="{{ route('products.category', 'ruang-makan') }}" class="hover:text-amber-700 transition">Ruang Makan</a></li>
                        <li><a href="{{ route('products.category', 'koleksi-baru') }}" class="hover:text-amber-700 transition">Koleksi Baru</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold text-gray-900 mb-5 uppercase text-xs tracking-widest">Perusahaan</h4>
                    <ul class="space-y-3 text-sm text-gray-600">
                        <li><a href="#" class="hover:text-amber-700 transition">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-amber-700 transition">Hubungi Kami</a></li>
                        <li><a href="#" class="hover:text-amber-700 transition">Syarat & Ketentuan</a></li>
                        <li><a href="#" class="hover:text-amber-700 transition">Kebijakan Privasi</a></li>
                    </ul>
                </div>
                
            </div>

            <div class="border-t border-gray-200 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="text-xs text-gray-500">
                    © {{ date('Y') }} Jaya Abadi. Hak cipta dilindungi.
                </div>
                <div class="text-xs text-gray-400">
                    Dibuat dengan <i class="fa-solid fa-heart text-amber-600 mx-1"></i> di Indonesia
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Script untuk toggle Mobile Menu
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('mobile-menu-btn');
            const menu = document.getElementById('mobile-menu-panel');
            
            if(btn && menu) {
                btn.addEventListener('click', () => {
                    menu.classList.toggle('hidden');
                    // Ubah icon hamburger jadi 'X' saat terbuka
                    const icon = btn.querySelector('i');
                    if(menu.classList.contains('hidden')) {
                        icon.classList.remove('fa-xmark');
                        icon.classList.add('fa-bars');
                    } else {
                        icon.classList.remove('fa-bars');
                        icon.classList.add('fa-xmark');
                    }
                });
            }
        });

        // Script Alert SweetAlert
        @if(session('success'))
            Swal.fire({
                title: 'Berhasil!',
                text: '{{ session("success") }}',
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#92400e',
                timer: 3000,
                timerProgressBar: true,
            });
        @endif

        @if(session('error'))
            Swal.fire({
                title: 'Oops!',
                text: '{{ session("error") }}',
                icon: 'error',
                confirmButtonText: 'OK',
                confirmButtonColor: '#92400e',
            });
        @endif
    </script>
</body>
</html>