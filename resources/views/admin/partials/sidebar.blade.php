<style>
    .sidebar {
        width: 210px;
        background: var(--sidebar-bg);
        display: flex;
        flex-direction: column;
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        z-index: 100;
        transition: transform 0.3s ease;
        transform: translateX(0);
    }
    .sidebar.hidden {
        transform: translateX(-100%);
    }
    .sidebar-logo {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 18px 20px 16px;
        border-bottom: 1px solid rgba(255,255,255,0.08);
    }
    .logo-icon {
        width: 36px;
        height: 36px;
        background: #c9a86c;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }
    .logo-text h2 {
        color: #fff;
        font-size: 16px;
        font-weight: 700;
        line-height: 1.1;
        margin: 0;
    }
    .logo-text p {
        color: #a0b8ad;
        font-size: 10px;
        margin: 0;
    }
    .sidebar-nav {
        flex: 1;
        padding: 12px 0;
        overflow-y: auto;
    }
    .nav-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 20px;
        color: #a0b8ad;
        font-size: 14px;
        cursor: pointer;
        text-decoration: none;
        transition: background 0.15s;
        border: none;
        background: none;
        width: 100%;
        white-space: nowrap;
    }
    .nav-item:hover {
        background: var(--sidebar-hover);
        color: #d4e8dc;
    }
    .nav-item.active {
        background: var(--sidebar-active);
        color: #fff;
        border-left: 3px solid #6aaa82;
    }
    .nav-icon {
        font-size: 16px;
        width: 20px;
        text-align: center;
    }
    .nav-badge {
        margin-left: auto;
        background: #e05c5c;
        color: #fff;
        font-size: 10px;
        font-weight: 700;
        padding: 1px 6px;
        border-radius: 10px;
    }
    .sidebar-section {
        padding: 10px 20px 4px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: rgba(255,255,255,0.25);
    }
    .sidebar-logout {
        border-top: 1px solid rgba(255,255,255,0.08);
        padding: 12px 0;
    }
</style>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <div class="logo-icon">🪑</div>
        <div class="logo-text">
            <h2>FurniHome</h2>
            <p>Admin Panel</p>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="sidebar-section">Main</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="nav-icon">🏠</span> Dashboard
        </a>

        <div class="sidebar-section">Katalog</div>
        <a href="#" class="nav-item">
            <span class="nav-icon">📦</span> Produk
        </a>
        <a href="#" class="nav-item">
            <span class="nav-icon">⊞</span> Kategori
        </a>

        <div class="sidebar-section">Transaksi</div>
        <a href="#" class="nav-item">
            <span class="nav-icon">🛍️</span> Pesanan
            <span class="nav-badge">8</span>
        </a>
        <a href="#" class="nav-item">
            <span class="nav-icon">💳</span> Pembayaran
        </a>

        <div class="sidebar-section">Data</div>
        <a href="#" class="nav-item">
            <span class="nav-icon">👤</span> Pelanggan
        </a>
        <a href="#" class="nav-item">
            <span class="nav-icon">📊</span> Laporan
        </a>

        <div class="sidebar-section">Sistem</div>
        <a href="#" class="nav-item">
            <span class="nav-icon">⚙️</span> Settings
        </a>
    </nav>

    <div class="sidebar-logout">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-item">
                <span class="nav-icon">🚪</span> Logout
            </button>
        </form>
    </div>
</aside>