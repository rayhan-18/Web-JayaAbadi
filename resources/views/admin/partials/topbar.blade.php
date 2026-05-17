<style>
    .topbar {
        height: 56px; background: #fff; border-bottom: 1px solid #e6e9e4;
        display: flex; align-items: center; padding: 0 20px 0 16px;
        gap: 12px; position: sticky; top: 0; z-index: 50;
    }
    .tb-hamburger {
        width: 34px; height: 34px; border-radius: 8px; border: none;
        background: none; display: flex; align-items: center;
        justify-content: center; cursor: pointer; color: #7a9080;
        transition: background .12s;
    }
    .tb-hamburger:hover { background: #f0f3ef; }
    .tb-hamburger i { font-size: 18px; }
    .tb-divider { width: 1px; height: 20px; background: #e6e9e4; }
    .tb-breadcrumb { display: flex; align-items: center; gap: 6px; font-size: 13px; color: #9aada2; }
    .tb-breadcrumb .current { color: #2d3b32; font-weight: 500; }
    .tb-breadcrumb i { font-size: 12px; }
    .tb-right { margin-left: auto; display: flex; align-items: center; gap: 8px; }
    .tb-search-wrap {
        display: flex; align-items: center; gap: 8px;
        background: #f5f7f4; border: 1px solid #e4e8e2; border-radius: 8px;
        padding: 0 12px; height: 34px; width: 220px;
    }
    .tb-search-wrap i { font-size: 15px; color: #9aada2; }
    .tb-search-wrap input { border: none; background: none; outline: none; font-size: 12.5px; color: #2d3b32; width: 100%; }
    .tb-search-wrap input::placeholder { color: #b0bfaa; }
    .tb-user {
        display: flex; align-items: center; gap: 8px; cursor: pointer;
        padding: 5px 10px 5px 5px; border-radius: 9px;
        border: 1px solid #e6e9e4; background: #fff; transition: background .12s;
    }
    .tb-user:hover { background: #f5f7f4; }
    .tb-av {
        width: 28px; height: 28px; border-radius: 7px; background: #1e2e23;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .tb-av i { font-size: 14px; color: #5c9e74; }
    .tb-uname { font-size: 12px; font-weight: 600; color: #2d3b32; line-height: 1.2; }
    .tb-urole { font-size: 10.5px; color: #9aada2; }
    .tb-chevd i { font-size: 13px; color: #b0bfaa; }
</style>

<header class="topbar">
    <button class="tb-hamburger" id="menuToggle">
        <i class="ti ti-menu-2"></i>
    </button>
    <div class="tb-divider"></div>
    <div class="tb-breadcrumb">
        <span>FurniHome</span>
        <i class="ti ti-chevron-right"></i>
        <span class="current">Dashboard</span>
    </div>
    <div class="tb-right">
        <div class="tb-search-wrap">
            <i class="ti ti-search"></i>
            <input type="text" placeholder="Cari produk, pesanan...">
        </div>
        <div class="tb-user">
            <div class="tb-av">
                @auth
                    @if(Auth::user()->avatar)
                        <img src="{{ Auth::user()->avatar }}" style="width:100%;height:100%;object-fit:cover;border-radius:7px">
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const btn = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const main = document.querySelector('.main');
        if (btn && sidebar) {
            btn.addEventListener('click', function () {
                sidebar.classList.toggle('hidden');
                if (main) main.style.marginLeft = sidebar.classList.contains('hidden') ? '0' : '218px';
            });
        }
    });
</script>