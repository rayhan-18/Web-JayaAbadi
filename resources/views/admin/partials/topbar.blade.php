<style>
    .topbar {
        height: 56px; background: #fff; border-bottom: 1px solid #e6e9e4;
        display: flex; align-items: center; padding: 0 16px; 
        gap: 12px; position: sticky; top: 0;
        /* FIX: z-index dinaikkan ke 1010 agar selalu di atas detail-panel (z-index:900)
           dan sb-backdrop (z-index:990), tapi masih bisa ditembus sidebar (z-index:1000).
           Nilai 1010 memastikan topbar + hamburger tidak pernah tertutup oleh elemen manapun. */
        z-index: 1010;
        width: 100%; 
    }
    .tb-hamburger {
        width: 34px; height: 34px; border-radius: 8px; border: none;
        background: none; display: flex; align-items: center;
        justify-content: center; cursor: pointer; color: #7a9080;
        transition: background .12s; flex-shrink: 0;
    }
    .tb-hamburger:hover { background: #f0f3ef; }
    .tb-hamburger i { font-size: 18px; pointer-events: none; }
    
    .tb-divider { width: 1px; height: 20px; background: #e6e9e4; flex-shrink: 0; }
    .tb-breadcrumb { display: flex; align-items: center; gap: 6px; font-size: 13px; color: #9aada2; min-width: 0; flex: 1; }
    .tb-breadcrumb .current { color: #2d3b32; font-weight: 500; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; display: block; }
    .tb-breadcrumb i { font-size: 12px; flex-shrink: 0; }
    .tb-right { margin-left: auto; display: flex; align-items: center; gap: 8px; flex-shrink: 0; }

    .tb-search-wrap { display: flex; align-items: center; gap: 8px; background: #f5f7f4; border: 1px solid #e4e8e2; border-radius: 8px; padding: 0 12px; height: 34px; width: 220px; }
    .tb-search-wrap i { font-size: 15px; color: #9aada2; flex-shrink: 0; }
    .tb-search-wrap input { border: none; background: none; outline: none; font-size: 12.5px; color: #2d3b32; width: 100%; }
    .tb-search-wrap input::placeholder { color: #b0bfaa; }

    .tb-user { display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 5px 10px 5px 5px; border-radius: 9px; border: 1px solid #e6e9e4; background: #fff; transition: background .12s; }
    .tb-user:hover { background: #f5f7f4; }
    .tb-av { width: 28px; height: 28px; border-radius: 7px; background: #1e2e23; display: flex; align-items: center; justify-content: center; flex-shrink: 0; overflow: hidden; }
    .tb-av i { font-size: 14px; color: #5c9e74; }
    .tb-av img { width: 100%; height: 100%; object-fit: cover; border-radius: 7px; }
    .tb-uname { font-size: 12px; font-weight: 600; color: #2d3b32; line-height: 1.2; }
    .tb-urole { font-size: 10.5px; color: #9aada2; }
    .tb-chevd i { font-size: 13px; color: #b0bfaa; }

    .tb-search-btn { display: none; width: 34px; height: 34px; border-radius: 8px; border: 1px solid #e4e8e2; background: #f5f7f4; align-items: center; justify-content: center; cursor: pointer; color: #7a9080; flex-shrink: 0; }
    .tb-search-btn i { font-size: 16px; pointer-events: none; }

    /* FIX: Search overlay z-index harus di bawah topbar tapi di atas konten biasa */
    .tb-search-overlay { display: none; position: absolute; top: 56px; left: 0; right: 0; background: #fff; border-bottom: 1px solid #e6e9e4; padding: 10px 16px; z-index: 1009; }
    .tb-search-overlay.open { display: flex; }
    .tb-search-overlay .tb-search-wrap { width: 100%; }

    @media (max-width: 768px) {
        .topbar { padding: 0 12px; gap: 8px; }
        .tb-search-wrap { display: none; }
        .tb-search-btn { display: flex; }
        .tb-breadcrumb span:not(.current), .tb-breadcrumb i { display: none; }
        .tb-uname, .tb-urole, .tb-chevd { display: none; }
        .tb-user { padding: 4px; }
    }
</style>

<header class="topbar">
    <button class="tb-hamburger" onclick="window.toggleAdminSidebar()" aria-label="Toggle menu">
        <i class="ti ti-menu-2"></i>
    </button>
    
    <div class="tb-divider"></div>
    
    <div class="tb-breadcrumb">
        <span>JayaAbadi</span>
        <i class="ti ti-chevron-right"></i>
        <span class="current">@yield('title', 'Admin Panel')</span>
    </div>
    
    <div class="tb-right">
        <div class="tb-search-wrap">
            <i class="ti ti-search"></i>
            <input type="text" placeholder="Cari produk, pesanan...">
        </div>
        <button class="tb-search-btn" onclick="document.getElementById('searchOverlay').classList.toggle('open')" aria-label="Search">
            <i class="ti ti-search"></i>
        </button>
        <div class="tb-user">
            <div class="tb-av">
                @auth
                    @if(Auth::user()->avatar)
                        <img src="{{ Auth::user()->avatar }}" alt="avatar">
                    @else
                        <i class="ti ti-user"></i>
                    @endif
                @else
                    <i class="ti ti-user"></i>
                @endauth
            </div>
            <div>
                <div class="tb-uname">@auth {{ Auth::user()->name }} @else Admin @endauth</div>
                <div class="tb-urole">@auth {{ Auth::user()->role ?? 'Super Admin' }} @else Super Admin @endauth</div>
            </div>
            <div class="tb-chevd"><i class="ti ti-chevron-down"></i></div>
        </div>
    </div>
</header>

<div class="tb-search-overlay" id="searchOverlay">
    <div class="tb-search-wrap">
        <i class="ti ti-search"></i>
        <input type="text" placeholder="Cari produk, pesanan..." id="mobileSearchInput">
    </div>
</div>

<script>
    // Daftarkan fungsi toggle global agar aman dieksekusi di ranah mobile & desktop
    window.toggleAdminSidebar = function() {
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('sbBackdrop');
        const mainContent = document.querySelector('.main-content') || document.querySelector('.main');

        if (sidebar) {
            if (window.innerWidth <= 1024) {
                // Eksekusi khusus mode Mobile / Apps / Tablet
                const isOpen = sidebar.classList.toggle('mobile-open');
                if (backdrop) backdrop.classList.toggle('visible', isOpen);

                // Sinkronisasi status penguncian layar gulir
                if (isOpen) {
                    if (typeof window._lockBodyScroll === 'function') window._lockBodyScroll();
                } else {
                    if (typeof window._unlockBodyScroll === 'function') window._unlockBodyScroll();
                }
            } else {
                // Eksekusi khusus mode PC / Desktop
                const isHidden = sidebar.classList.toggle('hidden');
                if (mainContent) {
                    mainContent.style.marginLeft = isHidden ? '0' : '218px';
                }
            }
        }
    };
</script>