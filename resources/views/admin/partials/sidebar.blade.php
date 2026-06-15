<style>
    /* ── Sidebar Utama (Tema: Obsidian Black & Champagne Gold) ── */
    .sidebar {
        width: 218px; background: #0b0d10; /* Hitam Obsidian */
        display: flex; flex-direction: column;
        position: fixed; top: 0; left: 0; bottom: 0;
        z-index: 1000;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-right: 1px solid #1c1f26; /* Garis batas abu-abu sangat gelap */
    }

    /* ── Layar Gelap (Backdrop) ── */
    .sb-backdrop {
        position: fixed; inset: 0;
        background: rgba(0,0,0,0.65);
        z-index: 990; 
        opacity: 0; visibility: hidden; 
        transition: opacity 0.3s ease, visibility 0.3s ease;
        backdrop-filter: blur(2px); /* Efek blur premium di layar belakang */
    }
    .sb-backdrop.visible {
        opacity: 1; visibility: visible; 
    }

    /* ── Aturan Responsif ── */
    @media (max-width: 1024px) {
        .sidebar { transform: translateX(-100%); } 
        .sidebar.mobile-open { transform: translateX(0); } 
    }
    @media (min-width: 1025px) {
        .sidebar { transform: translateX(0); }
        .sidebar.hidden { transform: translateX(-100%); }
    }

    /* ── Styling Konten (Menu & Logo) ── */
    .sb-logo { display: flex; align-items: center; gap: 11px; padding: 16px; border-bottom: 1px solid #1c1f26; }
    .sb-icon-wrap { width: 34px; height: 34px; border-radius: 9px; background: #14171c; border: 1px solid #232730; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .sb-icon-wrap i { font-size: 18px; color: #d4af37; } /* Champagne Gold */
    .sb-name { color: #ffffff; font-size: 13.5px; font-weight: 600; letter-spacing: .02em; }
    .sb-tag { display: inline-flex; align-items: center; margin-top: 2px; background: #14171c; border: 1px solid #232730; border-radius: 4px; padding: 1px 6px; font-size: 9px; color: #8a919e; letter-spacing: .06em; font-weight: 600; }
    
    .sb-nav { flex: 1; padding: 10px 8px; overflow-y: auto; }
    .sb-nav::-webkit-scrollbar { display: none; }
    .sb-sec { padding: 12px 8px 4px; font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: .18em; color: #5d6573; }
    
    .nav-row { display: flex; align-items: center; gap: 9px; padding: 8px 10px; color: #8a919e; font-size: 12.5px; cursor: pointer; text-decoration: none; border: none; background: none; width: 100%; border-radius: 7px; transition: all .15s ease; white-space: nowrap; }
    .nav-row:hover { background: #14171c; color: #d1d5db; }
    .nav-row.active { background: #18150c; color: #f3e8d3; } /* BG emas sangat gelap untuk menu aktif */
    .nav-row.active .nr-icon-wrap { background: #262011; border-color: #403417; }
    .nav-row.active .nr-icon-wrap i { color: #d4af37; }
    
    .nr-icon-wrap { width: 26px; height: 26px; border-radius: 6px; background: #121419; border: 1px solid #1c1f26; display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: all .15s ease; }
    .nr-icon-wrap i { font-size: 14px; color: #5d6573; transition: color .15s ease; }
    .nav-row:hover .nr-icon-wrap { background: #1c1f26; border-color: #2b303b; }
    .nav-row:hover .nr-icon-wrap i { color: #c09b5a; }
    
    .nav-row .badge { margin-left: auto; background: #4a1c1c; color: #fca5a5; font-size: 9px; font-weight: 700; padding: 2px 6px; border-radius: 5px; letter-spacing: .05em; }
    .nav-row .chev { margin-left: auto; font-size: 12px; color: #5d6573; transition: transform .2s ease, color .15s ease; }
    .nav-row:hover .chev, .nav-row.active .chev { color: #a3aab5; }
    .parent.open > .nav-row .chev { transform: rotate(90deg); }
    
    .sub { max-height: 0; overflow: hidden; transition: max-height .25s ease; }
    .parent.open .sub { max-height: 300px; }
    
    .sub-row { display: flex; align-items: center; gap: 8px; padding: 7px 10px 7px 44px; color: #6e7685; font-size: 12px; text-decoration: none; border-radius: 6px; transition: all .15s ease; margin: 1px 0; }
    .sub-row:hover { background: #14171c; color: #d4af37; }
    .sub-row.active { color: #d4af37; background: #14171c; font-weight: 500; }
    .sub-dot { width: 4px; height: 4px; border-radius: 50%; background: #2b303b; flex-shrink: 0; transition: background .15s ease; }
    .sub-row:hover .sub-dot, .sub-row.active .sub-dot { background: #d4af37; box-shadow: 0 0 4px rgba(212, 175, 55, 0.4); } /* Efek glow kecil di dot */
    
    .sb-foot { padding: 8px; border-top: 1px solid #1c1f26; }
    .sb-logout { display: flex; align-items: center; gap: 9px; padding: 8px 10px; color: #6e7685; font-size: 12.5px; cursor: pointer; border-radius: 7px; border: none; background: none; width: 100%; transition: all .15s ease; }
    .sb-logout:hover { background: #1a0f11; color: #e05a5a; }
    .sb-logout .nr-icon-wrap { background: #141011; border-color: #211618; }
    .sb-logout .nr-icon-wrap i { color: #8a3a3a; }
    .sb-logout:hover .nr-icon-wrap { background: #2b1417; border-color: #4a1c22; }
    .sb-logout:hover .nr-icon-wrap i { color: #e05a5a; }
</style>

<div class="sb-backdrop" id="sbBackdrop" onclick="window.closeAdminSidebar()"></div>

<aside class="sidebar" id="sidebar">
    <div class="sb-logo">
        <div class="sb-icon-wrap"><i class="ti ti-armchair"></i></div>
        <div>
            <div class="sb-name">Jaya Abadi</div> <div class="sb-tag">ADMIN</div>
        </div>
    </div>

    <nav class="sb-nav">
        <div class="sb-sec">Main</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-row {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <div class="nr-icon-wrap"><i class="ti ti-layout-dashboard"></i></div> Dashboard
        </a>

        <div class="sb-sec">Katalog</div>
        <div class="parent {{ request()->routeIs('admin.product.*') ? 'open' : '' }}" id="p-produk">
            <div class="nav-row" onclick="tog('p-produk')">
                <div class="nr-icon-wrap"><i class="ti ti-box"></i></div> Produk
                <i class="ti ti-chevron-right chev"></i>
            </div>
            <div class="sub">
                <a href="{{ route('admin.product.index') }}" class="sub-row {{ request()->routeIs('admin.product.index') ? 'active' : '' }}"><span class="sub-dot"></span>Semua Produk</a>
                <a href="{{ route('admin.product.create') }}" class="sub-row {{ request()->routeIs('admin.product.create') ? 'active' : '' }}"><span class="sub-dot"></span>Tambah Produk</a>
            </div>
        </div>

        <div class="parent {{ request()->routeIs('admin.category.*') ? 'open' : '' }}" id="p-kat">
            <div class="nav-row" onclick="tog('p-kat')">
                <div class="nr-icon-wrap"><i class="ti ti-tag"></i></div> Kategori
                <i class="ti ti-chevron-right chev"></i>
            </div>
            <div class="sub">
                <a href="{{ route('admin.category.index') }}" class="sub-row {{ request()->routeIs('admin.category.index') ? 'active' : '' }}"><span class="sub-dot"></span>Semua Kategori</a>
                <a href="{{ route('admin.category.create') }}" class="sub-row {{ request()->routeIs('admin.category.create') ? 'active' : '' }}"><span class="sub-dot"></span>Tambah Kategori</a>
            </div>
        </div>

        <div class="sb-sec">Transaksi</div>
        
        @php
            // Ambil jumlah pesanan dengan status 'pending' (Sesuaikan jika nama statusnya berbeda di DB)
            // Jika ingin menghitung semua pesanan, gunakan: \App\Models\Order::count();
            $pendingOrders = \App\Models\Order::where('status', 'pending')->count();
        @endphp

        <div class="parent {{ request()->routeIs('admin.order.*') ? 'open' : '' }}" id="p-order">
            <div class="nav-row" onclick="tog('p-order')">
                <div class="nr-icon-wrap"><i class="ti ti-clipboard-list"></i></div> Pesanan
                
                @if($pendingOrders > 0)
                    <span class="badge">{{ $pendingOrders }}</span>
                @endif
                
                <i class="ti ti-chevron-right chev" style="margin-left:4px"></i>
            </div>
            <div class="sub">
                <a href="{{ route('admin.order.index') }}" class="sub-row {{ request()->routeIs('admin.order.index') ? 'active' : '' }}"><span class="sub-dot"></span>Semua Pesanan</a>
            </div>
        </div>

        <div class="parent {{ request()->routeIs('admin.payment.*') ? 'open' : '' }}" id="p-bayar">
            <div class="nav-row" onclick="tog('p-bayar')">
                <div class="nr-icon-wrap"><i class="ti ti-receipt"></i></div> Pembayaran
                <i class="ti ti-chevron-right chev"></i>
            </div>
            <div class="sub">
                <a href="{{ route('admin.payment.index') }}" class="sub-row {{ request()->routeIs('admin.payment.index') ? 'active' : '' }}"><span class="sub-dot"></span>Riwayat Pembayaran</a>
            </div>
        </div>

        <div class="sb-sec">Data</div>
        <div class="parent {{ request()->routeIs('admin.customer.*') ? 'open' : '' }}" id="p-pelanggan">
            <div class="nav-row" onclick="tog('p-pelanggan')">
                <div class="nr-icon-wrap"><i class="ti ti-users"></i></div> Pelanggan
                <i class="ti ti-chevron-right chev"></i>
            </div>
            <div class="sub">
                <a href="{{ route('admin.customer.index') }}" class="sub-row {{ request()->routeIs('admin.customer.index') ? 'active' : '' }}"><span class="sub-dot"></span>Semua Pelanggan</a>
            </div>
        </div>

        <div class="parent {{ request()->routeIs('admin.report.*') ? 'open' : '' }}" id="p-laporan">
            <div class="nav-row" onclick="tog('p-laporan')">
                <div class="nr-icon-wrap"><i class="ti ti-report-analytics"></i></div> Laporan
                <i class="ti ti-chevron-right chev"></i>
            </div>
            <div class="sub">
                <a href="{{ route('admin.report.sales') }}" class="sub-row {{ request()->routeIs('admin.report.sales') ? 'active' : '' }}"><span class="sub-dot"></span>Penjualan</a>
                <a href="{{ route('admin.report.stock') }}" class="sub-row {{ request()->routeIs('admin.report.stock') ? 'active' : '' }}"><span class="sub-dot"></span>Stok</a>
            </div>
        </div>

        <div class="sb-sec">Saluran</div>
        <div class="parent {{ request()->routeIs('admin.kasir.*') ? 'open' : '' }}" id="p-kasir">
            <div class="nav-row" onclick="tog('p-kasir')">
                <div class="nr-icon-wrap"><i class="ti ti-cash-register"></i></div> Kasir
                <i class="ti ti-chevron-right chev"></i>
            </div>
            <div class="sub">
                <a href="{{ route('admin.kasir.pos') }}" class="sub-row {{ request()->routeIs('admin.casier.pos') ? 'active' : '' }}"><span class="sub-dot"></span>Transaksi POS</a>
            </div>
        </div>
    </nav>

    <div class="sb-foot">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sb-logout">
                <div class="nr-icon-wrap"><i class="ti ti-logout"></i></div> Logout
            </button>
        </form>
    </div>
</aside>

<script>
    // Logika buka-tutup list dropdown menu internal sidebar
    function tog(id) { document.getElementById(id).classList.toggle('open'); }

    // ── KELOLA BODY SCROLL LOCK SECARA TERPUSAT ──
    window._bodyLockCount = window._bodyLockCount || 0;

    window._lockBodyScroll = function() {
        window._bodyLockCount++;
        document.body.style.overflow = 'hidden';
    };

    window._unlockBodyScroll = function() {
        window._bodyLockCount = Math.max(0, window._bodyLockCount - 1);
        if (window._bodyLockCount === 0) {
            document.body.style.overflow = '';
        }
    };

    // Fungsi Global Penutup Sidebar (Dipakai saat backdrop diklik)
    window.closeAdminSidebar = function() {
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('sbBackdrop');
        if (sidebar) sidebar.classList.remove('mobile-open');
        if (backdrop) backdrop.classList.remove('visible');
        window._unlockBodyScroll();
    };
</script>