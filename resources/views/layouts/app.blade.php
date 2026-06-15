<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sanctuari')</title>
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
                        <a href="{{ route('home') }}" class="text-gray-900">Sanctuari</a>
                    </div>
                </div>

                <div class="hidden lg:flex items-center space-x-8 text-sm font-medium">
                    <a href="{{ route('products.category', 'ruang-tamu') }}" class="text-gray-700 hover:text-amber-700 transition">Ruang Tamu</a>
                    <a href="{{ route('products.category', 'kamar-tidur') }}" class="text-gray-700 hover:text-amber-700 transition">Kamar Tidur</a>
                    <a href="{{ route('products.category', 'ruang-makan') }}" class="text-gray-700 hover:text-amber-700 transition">Ruang Makan</a>
                    <a href="{{ route('products.category', 'koleksi-baru') }}" class="text-gray-700 hover:text-amber-700 transition">Koleksi Baru</a>
                    <a href="#" class="text-gray-700 hover:text-amber-700 transition">Inspirasi</a>
                </div>

                <div class="flex items-center space-x-5">
                    <form action="{{ route('products.search') }}" method="GET" class="hidden lg:block">
                        <div class="relative">
                            <input type="text" name="q" placeholder="Cari produk..." value="{{ request('q') }}"
                                   class="pl-9 pr-4 py-1.5 rounded-full border border-gray-200 text-sm focus:outline-none focus:border-amber-300 w-48 xl:w-64 transition-all">
                            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        </div>
                    </form>

                    <a href="{{ route('cart.index') }}" class="relative text-gray-700 hover:text-amber-700 transition transform hover:scale-105">
                        <i class="fa-solid fa-bag-shopping text-xl"></i>
                        @php $cartCount = count(session()->get('cart', [])); @endphp
                        @if($cartCount > 0)
                            <span class="absolute -top-2 -right-2 bg-amber-600 text-white text-xs rounded-full min-w-[18px] h-[18px] flex items-center justify-center px-1">{{ $cartCount }}</span>
                        @endif
                    </a>

                    @auth
                        <div class="relative group hidden lg:block">
                            <button class="flex items-center space-x-1 text-gray-700 py-4">
                                <i class="fa-regular fa-user text-xl"></i>
                            </button>
                            <div class="absolute right-0 top-12 mt-1 w-32 bg-white rounded-md shadow-lg hidden group-hover:block z-50 border border-gray-100 overflow-hidden">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-amber-50 hover:text-amber-700 transition">Logout</button>
                                </form>
                            </div>
                        </div>
                        
                        <div class="block lg:hidden">
                             <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-gray-700 hover:text-amber-700 transition" title="Logout">
                                    <i class="fa-solid fa-right-from-bracket text-xl"></i>
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-amber-700">
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

    <footer class="bg-gray-50 border-t border-gray-100 mt-20">
        <div class="max-w-7xl mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="font-serif text-xl font-semibold mb-4">Sanctuari</h3>
                    <p class="text-gray-500 text-sm">Menciptakan ruang tenang melalui desain furnitur yang dipraktik secara berkelanjutan.</p>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-3">Belanja</h4>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><a href="{{ route('products.category', 'ruang-tamu') }}" class="hover:text-amber-700">Ruang Tamu</a></li>
                        <li><a href="{{ route('products.category', 'kamar-tidur') }}" class="hover:text-amber-700">Kamar Tidur</a></li>
                        <li><a href="{{ route('products.category', 'koleksi-baru') }}" class="hover:text-amber-700">Koleksi Baru</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold text-gray-900 mb-3">Perusahaan</h4>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><a href="#" class="hover:text-amber-700">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-amber-700">Kontak</a></li>
                        <li><a href="#" class="hover:text-amber-700">Karir</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-200 mt-8 pt-6 text-center text-xs text-gray-400">
                © 2025 Sanctuari. All rights reserved.
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

        // Script Alert
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