<style>
    .sidebar {
        width: 218px; background: #0f1610;
        display: flex; flex-direction: column;
        position: fixed; top: 0; left: 0; bottom: 0;
        z-index: 100; transition: transform 0.3s ease;
        border-right: 1px solid #1a2b1e;
    }
    .sidebar.hidden { transform: translateX(-100%); }

    .sb-logo {
        display: flex; align-items: center; gap: 11px;
        padding: 16px 16px 14px; border-bottom: 1px solid #1c2e20;
    }
    .sb-icon-wrap {
        width: 34px; height: 34px; border-radius: 9px;
        background: #1e2e23; border: 1px solid #2a4030;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .sb-icon-wrap i { font-size: 18px; color: #5c9e74; }
    .sb-name { color: #e8f0eb; font-size: 13px; font-weight: 600; letter-spacing: -.01em; }
    .sb-tag {
        display: inline-flex; align-items: center; margin-top: 2px;
        background: #1c2e20; border: 1px solid #2a4030; border-radius: 4px;
        padding: 1px 6px; font-size: 9px; color: #4d7a5c;
        letter-spacing: .04em; font-weight: 600;
    }

    .sb-nav { flex: 1; padding: 10px 8px; overflow-y: auto; }
    .sb-nav::-webkit-scrollbar { display: none; }

    .sb-sec {
        padding: 10px 8px 3px; font-size: 9px; font-weight: 700;
        text-transform: uppercase; letter-spacing: .14em; color: #2a4030;
    }

    .nav-row {
        display: flex; align-items: center; gap: 9px;
        padding: 8px 10px; color: #4d7060; font-size: 12.5px;
        cursor: pointer; text-decoration: none; border: none;
        background: none; width: 100%; border-radius: 7px;
        transition: background .12s, color .12s; white-space: nowrap;
    }
    .nav-row:hover { background: #162018; color: #a0c4ae; }
    .nav-row.active { background: #1a2e22; color: #e8f0eb; }
    .nav-row.active .nr-icon-wrap { background: #243d2c; border-color: #2e5038; }
    .nav-row.active .nr-icon-wrap i { color: #5c9e74; }

    .nr-icon-wrap {
        width: 26px; height: 26px; border-radius: 6px;
        background: #141f16; border: 1px solid #1e3022;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; transition: background .12s;
    }
    .nr-icon-wrap i { font-size: 14px; color: #3a5c48; transition: color .12s; }
    .nav-row:hover .nr-icon-wrap { background: #1c2e22; border-color: #274030; }
    .nav-row:hover .nr-icon-wrap i { color: #5c9e74; }

    .nav-row .badge {
        margin-left: auto; background: #7a2222; color: #ffb0b0;
        font-size: 9px; font-weight: 700; padding: 2px 6px; border-radius: 5px;
    }
    .nav-row .chev {
        margin-left: auto; font-size: 12px; color: #2a4030;
        transition: transform .2s; width: auto !important;
    }
    .nav-row:hover .chev, .nav-row.active .chev { color: #4d7060; }
    .parent.open > .nav-row .chev { transform: rotate(90deg); }

    .sub { max-height: 0; overflow: hidden; transition: max-height .22s ease; }
    .parent.open .sub { max-height: 300px; }

    .sub-row {
        display: flex; align-items: center; gap: 8px;
        padding: 6px 10px 6px 44px; color: #2e4d3a; font-size: 12px;
        text-decoration: none; border-radius: 6px;
        transition: background .12s, color .12s; margin: 1px 0;
    }
    .sub-row:hover { background: #162018; color: #7ab894; }
    .sub-row.active { color: #7ab894; background: #162018; }
    .sub-dot { width: 3px; height: 3px; border-radius: 50%; background: #243d2c; flex-shrink: 0; }
    .sub-row:hover .sub-dot, .sub-row.active .sub-dot { background: #5c9e74; }

    .sb-foot { padding: 8px; border-top: 1px solid #1c2e20; }
    .sb-logout {
        display: flex; align-items: center; gap: 9px; padding: 8px 10px;
        color: #3a5248; font-size: 12.5px; cursor: pointer; border-radius: 7px;
        border: none; background: none; width: 100%;
        transition: background .12s, color .12s;
    }
    .sb-logout:hover { background: #1c1212; color: #c47a7a; }
    .sb-logout .nr-icon-wrap { background: #1a1010; border-color: #2a1a1a; }
    .sb-logout .nr-icon-wrap i { color: #4d3030; }
    .sb-logout:hover .nr-icon-wrap { background: #2a1515; border-color: #3a2020; }
    .sb-logout:hover .nr-icon-wrap i { color: #c47a7a; }
</style>

<aside class="sidebar" id="sidebar">
    <div class="sb-logo">
        <div class="sb-icon-wrap"><i class="ti ti-armchair"></i></div>
        <div>
            <div class="sb-name">FurniHome</div>
            <div class="sb-tag">ADMIN</div>
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
        <div class="parent {{ request()->routeIs('admin.order.*') ? 'open' : '' }}" id="p-order">
            <div class="nav-row" onclick="tog('p-order')">
                <div class="nr-icon-wrap"><i class="ti ti-clipboard-list"></i></div> Pesanan
                <span class="badge">8</span>
                <i class="ti ti-chevron-right chev" style="margin-left:4px"></i>
            </div>
            <div class="sub">
                <a href="{{ route('admin.order.index') }}" class="sub-row"><span class="sub-dot"></span>Semua Pesanan</a>
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
        <div class="parent" id="p-pelanggan">
            <div class="nav-row" onclick="tog('p-pelanggan')">
                <div class="nr-icon-wrap"><i class="ti ti-users"></i></div> Pelanggan
                <i class="ti ti-chevron-right chev"></i>
            </div>
            <div class="sub">
                <a href="#" class="sub-row"><span class="sub-dot"></span>Semua Pelanggan</a>
                <a href="#" class="sub-row"><span class="sub-dot"></span>Member</a>
            </div>
        </div>

        <div class="parent" id="p-laporan">
            <div class="nav-row" onclick="tog('p-laporan')">
                <div class="nr-icon-wrap"><i class="ti ti-report-analytics"></i></div> Laporan
                <i class="ti ti-chevron-right chev"></i>
            </div>
            <div class="sub">
                <a href="#" class="sub-row"><span class="sub-dot"></span>Penjualan</a>
                <a href="#" class="sub-row"><span class="sub-dot"></span>Stok</a>
                <a href="#" class="sub-row"><span class="sub-dot"></span>Keuangan</a>
            </div>
        </div>

        <div class="sb-sec">Saluran</div>
        <div class="parent {{ request()->routeIs('admin.online.*') ? 'open' : '' }}" id="p-online">
            <div class="nav-row" onclick="tog('p-online')">
                <div class="nr-icon-wrap"><i class="ti ti-shopping-cart"></i></div> Online
                <i class="ti ti-chevron-right chev"></i>
            </div>
            <div class="sub">
                <a href="#" class="sub-row"><span class="sub-dot"></span>Toko Online</a>
                <a href="#" class="sub-row"><span class="sub-dot"></span>Promosi</a>
                <a href="#" class="sub-row"><span class="sub-dot"></span>Banner & Slider</a>
                <a href="#" class="sub-row"><span class="sub-dot"></span>Ulasan Produk</a>
            </div>
        </div>

        <div class="parent {{ request()->routeIs('admin.kasir.*') ? 'open' : '' }}" id="p-kasir">
            <div class="nav-row" onclick="tog('p-kasir')">
                <div class="nr-icon-wrap"><i class="ti ti-cash-register"></i></div> Kasir
                <i class="ti ti-chevron-right chev"></i>
            </div>
            <div class="sub">
                <a href="#" class="sub-row"><span class="sub-dot"></span>Transaksi POS</a>
                <a href="#" class="sub-row"><span class="sub-dot"></span>Shift Kasir</a>
                <a href="#" class="sub-row"><span class="sub-dot"></span>Diskon & Voucher</a>
                <a href="#" class="sub-row"><span class="sub-dot"></span>Riwayat Kasir</a>
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
    function tog(id) { document.getElementById(id).classList.toggle('open'); }
</script>