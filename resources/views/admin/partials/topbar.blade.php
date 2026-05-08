<style>
    .topbar {
        background: #fff;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        padding: 0 24px;
        height: 58px;
        gap: 16px;
        position: sticky;
        top: 0;
        z-index: 50;
    }
    .topbar-menu {
        font-size: 20px;
        color: var(--text-secondary);
        cursor: pointer;
    }
    .search-box {
        flex: 1;
        max-width: 380px;
        display: flex;
        align-items: center;
        background: #f9fafb;
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 6px 12px;
        gap: 8px;
    }
    .search-box input {
        border: none;
        background: transparent;
        outline: none;
        font-size: 13px;
        width: 100%;
    }
    .search-box input::placeholder {
        color: var(--text-muted);
    }
    .topbar-right {
        margin-left: auto;
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .notif-btn {
        position: relative;
        cursor: pointer;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f9fafb;
        border-radius: 50%;
        font-size: 16px;
    }
    .notif-badge {
        position: absolute;
        top: -2px;
        right: -2px;
        background: #e05c5c;
        color: #fff;
        font-size: 9px;
        font-weight: 700;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #fff;
    }
    .user-info {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
    }
    .user-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #c9a86c, #a0856a);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        overflow: hidden;
    }
    .user-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .user-name {
        font-size: 13px;
        font-weight: 600;
        line-height: 1.2;
    }
    .user-role {
        font-size: 11px;
        color: var(--text-muted);
    }
</style>

<header class="topbar">
    <span class="topbar-menu" id="menuToggle">☰</span>

    <div class="search-box">
        <span style="color:var(--text-muted);font-size:14px;">🔍</span>
        <input type="text" placeholder="Cari sesuatu...">
    </div>

    <div class="topbar-right">
        <div class="notif-btn">
            🔔
            <div class="notif-badge">3</div>
        </div>

        <div class="user-info">
            <div class="user-avatar">
                @auth
                    @if(Auth::user()->avatar)
                        <img src="{{ Auth::user()->avatar }}" alt="Avatar">
                    @else
                        🧑
                    @endif
                @else
                    🧑
                @endauth
            </div>
            <div>
                <div class="user-name">@auth {{ Auth::user()->name }} @else Admin @endauth</div>
                <div class="user-role">@auth {{ Auth::user()->role ?? 'Super Admin' }} @else Super Admin @endauth</div>
            </div>
            <span style="color:var(--text-muted);font-size:12px;">▾</span>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('menuToggle');
        const sidebar = document.querySelector('.sidebar');
        const mainContent = document.querySelector('.main');

        if (toggleBtn && sidebar) {
            toggleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                sidebar.classList.toggle('hidden');
                // Sesuaikan margin konten utama jika sidebar hilang/muncul
                if (mainContent) {
                    if (sidebar.classList.contains('hidden')) {
                        mainContent.style.marginLeft = '0';
                    } else {
                        mainContent.style.marginLeft = '210px';
                    }
                }
            });
        }
    });
</script>