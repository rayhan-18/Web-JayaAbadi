@extends('layouts.admin')

@section('title', 'Semua Pelanggan')

@section('styles')
<style>
    /* Premium Variables */
    :root {
        --accent: #5c9e74;
        --accent-dark: #3a5c48;
        --accent-light: #e8f0eb;
        --border: #e6e9e4;
        --bg-surface: #ffffff;
        --bg-hover: #f5f7f4;
        --text-main: #2d3b32;
        --text-sec: #7a9080;
        --text-muted: #9aada2;
        --radius-md: 10px;
        --radius-lg: 14px;
    }

    body { color: var(--text-main); }

    .page-header {
        display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;
    }
    .page-title h1 {
        font-size: 22px; font-weight: 700; color: var(--text-main); margin: 0; letter-spacing: -0.02em;
    }
    .page-title .breadcrumb {
        font-size: 13px; color: var(--text-sec); margin-top: 4px;
    }

    /* Stats Grid - Menyesuaikan dengan tambahan Member VIP dari branch main */
    .stats-row {
        display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px;
    }
    .stat-card {
        background: var(--bg-surface); border-radius: var(--radius-lg); border: 1px solid var(--border);
        padding: 20px 16px; text-align: center; transition: all 0.2s ease;
    }
    .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(45, 59, 50, 0.04); }
    
    .stat-icon {
        width: 42px; height: 42px; border-radius: var(--radius-md);
        display: flex; align-items: center; justify-content: center;
        font-size: 24px; margin: 0 auto 12px;
    }
    
    .stat-card.total .stat-icon   { background: transparent; color: var(--accent); }
    .stat-card.member .stat-icon  { background: transparent; color: #b89247; }
    .stat-card.active .stat-icon  { background: transparent; color: #5c7b9e; }
    .stat-card.new .stat-icon     { background: transparent; color: #865c9e; }

    .stat-label { font-size: 12.5px; font-weight: 600; color: var(--text-sec); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.02em; }
    .stat-value { font-size: 24px; font-weight: 700; color: var(--text-main); letter-spacing: -0.02em; }
    
    .filter-bar { display: flex; gap: 12px; margin-bottom: 24px; flex-wrap: wrap; align-items: center; }
    .search-box {
        display: flex; align-items: center; background: var(--bg-surface);
        border: 1px solid var(--border); border-radius: var(--radius-md);
        padding: 0 14px; gap: 10px; flex: 1; max-width: 320px; height: 40px; transition: all 0.2s;
    }
    .search-box:focus-within { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(92, 158, 116, 0.15); }
    .search-box i { color: var(--text-sec); font-size: 16px; }
    .search-box input { border: none; outline: none; font-size: 13px; width: 100%; color: var(--text-main); background: transparent; }
    .search-box input::placeholder { color: var(--text-muted); }
    
    .select-wrapper { position: relative; display: inline-block; }
    .select-wrapper i.prefix-icon {
        position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-sec); font-size: 15px; pointer-events: none;
    }
    .filter-select {
        height: 40px; padding: 0 36px 0 34px; border: 1px solid var(--border); border-radius: var(--radius-md);
        background: var(--bg-surface) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%237a9080' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E") no-repeat right 12px center;
        appearance: none; font-size: 13px; font-weight: 500; color: var(--text-main); cursor: pointer; transition: all 0.2s; min-width: 160px;
    }
    .filter-select:hover { background-color: var(--bg-hover); border-color: #d1d6cf; }
    .filter-select:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px rgba(92, 158, 116, 0.15); }

    .export-dropdown { position: relative; display: inline-block; }
    .btn-export {
        background: var(--bg-surface); color: var(--text-main); border: 1px solid var(--border);
        padding: 0 16px; height: 40px; border-radius: var(--radius-md); font-size: 13px; font-weight: 600;
        display: inline-flex; align-items: center; justify-content: center; gap: 8px; cursor: pointer; transition: 0.2s;
    }
    .btn-export i { font-size: 16px; color: var(--text-sec); }
    .btn-export:hover { background: var(--bg-hover); border-color: #d1d6cf; }
    
    .export-dropdown-content {
        display: none; position: absolute; right: 0; top: 46px; background: var(--bg-surface);
        min-width: 180px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); border-radius: 10px;
        z-index: 10; border: 1px solid var(--border); overflow: hidden;
    }
    .export-dropdown-content a {
        padding: 10px 16px; display: flex; align-items: center; gap: 10px; text-decoration: none;
        color: var(--text-main); font-size: 13px; font-weight: 500; border-bottom: 1px solid #f0f2ef;
    }
    .export-dropdown-content a:last-child { border-bottom: none; }
    .export-dropdown-content a:hover { background: var(--bg-hover); color: var(--accent); }
    .export-dropdown:hover .export-dropdown-content { display: block; }

    .layout-order { display: flex; gap: 20px; align-items: flex-start; }
    .table-section { flex: 1; min-width: 0; }
    
    .table-wrapper { background: var(--bg-surface); border-radius: var(--radius-lg); border: 1px solid var(--border); overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; font-size: 13px; min-width: 700px; } 
    th {
        text-align: left; padding: 14px 20px; background: var(--bg-hover); font-weight: 600;
        color: var(--text-sec); border-bottom: 1px solid var(--border); font-size: 12px;
        text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap;
    }
    td { padding: 14px 20px; border-bottom: 1px solid var(--border); vertical-align: middle; color: var(--text-main); }
    tr:last-child td { border-bottom: none; }
    tbody tr:hover { background: var(--bg-hover); }

    .customer-profile { display: flex; align-items: center; gap: 12px; }
    .customer-avatar {
        width: 38px; height: 38px; border-radius: 50%; background: var(--accent-light);
        color: var(--accent-dark); display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 14px; flex-shrink: 0; border: 1px solid var(--border);
    }
    
    .customer-info { line-height: 1.4; }
    .customer-name { font-weight: 600; color: var(--text-main); font-size: 13.5px; }
    .customer-email { font-size: 11.5px; color: var(--text-muted); margin-top: 2px; }

    .status-badge {
        display: inline-flex; align-items: center; justify-content: center;
        padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; letter-spacing: 0.02em; white-space: nowrap;
    }
    .badge-vip     { background: #fcf6e8; color: #b89247; }
    .badge-regular { background: #f0f4f8; color: #4a6b8c; }
    
    .status-badge::before { content: ''; width: 6px; height: 6px; border-radius: 50%; margin-right: 6px; }
    .badge-vip::before     { background: #d99e52; }
    .badge-regular::before { background: #6993c4; }

    .action-btn {
        width: 32px; height: 32px; border-radius: 8px; background: var(--bg-surface);
        border: 1px solid var(--border); display: inline-flex; align-items: center; justify-content: center;
        cursor: pointer; color: var(--text-sec); font-size: 16px; transition: 0.15s;
    }
    .action-btn:hover { background: var(--bg-hover); color: var(--accent); border-color: #d1d6cf; }

    /* Pagination */
    .pagination { display: flex; justify-content: space-between; align-items: center; margin-top: 24px; font-size: 13px; color: var(--text-sec); flex-wrap: wrap; gap: 12px;}
    .pagination-links { display: flex; gap: 6px; }
    .pagination-links a, .pagination-links span {
        display: inline-flex; align-items: center; justify-content: center; min-width: 30px; height: 30px; padding: 0 10px;
        border: 1px solid var(--border); border-radius: 6px; text-decoration: none; color: var(--text-main); font-weight: 500;
        transition: 0.15s; background: var(--bg-surface);
    }
    .pagination-links a:hover { background: var(--bg-hover); border-color: var(--accent); color: var(--accent); }
    .pagination-links .active { background: var(--accent); border-color: var(--accent); color: white; }

    .detail-panel {
        width: 340px; flex-shrink: 0; background: #ffffff !important; border-radius: var(--radius-lg);
        border: 1px solid var(--border); display: none; flex-direction: column;
        position: sticky; top: 80px; max-height: calc(100vh - 100px); overflow-y: auto;
        box-shadow: -4px 0 24px rgba(0, 0, 0, 0.04);
    }
    .detail-panel.open { display: flex; animation: slideIn 0.3s ease-out; }
    @keyframes slideIn { from { opacity: 0; transform: translateX(20px); } to { opacity: 1; transform: translateX(0); } }
    
    .detail-panel::-webkit-scrollbar { width: 5px; }
    .detail-panel::-webkit-scrollbar-track { background: transparent; }
    .detail-panel::-webkit-scrollbar-thumb { background: #d1d6cf; border-radius: 10px; }

    .dp-header {
        display: flex; justify-content: space-between; align-items: center; padding: 18px 20px;
        border-bottom: 1px solid var(--border); background: #ffffff !important;
        position: sticky; top: 0; z-index: 20;
    }
    .dp-header h3 { font-size: 15px; font-weight: 700; margin: 0; color: var(--text-main); }
    .dp-close {
        width: 30px; height: 30px; border-radius: 8px; background: var(--bg-hover); border: 1px solid transparent;
        display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 16px; color: var(--text-sec); transition: 0.2s;
    }
    .dp-close:hover { background: #fdf5f5; color: #c47a7a; border-color: #e8caca; }
    
    .dp-body { padding: 20px; }
    
    .dp-profile-header { display: flex; flex-direction: column; align-items: center; text-align: center; margin-bottom: 20px; }
    .dp-profile-avatar {
        width: 72px; height: 72px; border-radius: 50%; background: var(--accent-light);
        color: var(--accent-dark); display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 28px; margin-bottom: 12px; border: 2px solid var(--border);
    }
    .dp-profile-name { font-size: 18px; font-weight: 700; color: var(--text-main); margin-bottom: 4px; }
    .dp-profile-join { font-size: 12px; color: var(--text-muted); }

    .dp-row { display: flex; gap: 12px; margin-bottom: 14px; align-items: flex-start; }
    .dp-label { font-size: 12.5px; color: var(--text-sec); font-weight: 500; min-width: 90px; }
    .dp-value { font-size: 13px; color: var(--text-main); font-weight: 500; flex: 1; line-height: 1.4; }
    
    .dp-section-title { font-size: 12px; font-weight: 700; color: var(--text-main); margin: 24px 0 14px; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid var(--border); padding-bottom: 8px; }
    
    .dp-stat-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px; }
    .dp-stat-box { background: var(--bg-hover); border-radius: 10px; padding: 12px; text-align: center; border: 1px solid var(--border); }
    .dp-stat-box .lbl { font-size: 11px; color: var(--text-sec); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600; }
    .dp-stat-box .val { font-size: 16px; font-weight: 700; color: var(--accent); }

    .btn-action {
        width: 100%; padding: 10px; color: var(--text-main); background: var(--bg-surface);
        border: 1px solid var(--border); border-radius: 10px; font-size: 13px; font-weight: 600;
        cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); margin-top: 10px;
    }
    .btn-action:hover { background: var(--bg-hover); border-color: #d1d6cf; }
    .btn-action:active { transform: scale(0.97); background: #f0f2ef; }

    /* =========================================
       SISTEM RESPONSIVE (MOBILE & TABLET)
       ========================================= */
    @media (max-width: 1024px) {
        .stats-row { grid-template-columns: repeat(2, 1fr); }
        .layout-order { flex-direction: column; }
        
        .detail-panel.open {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            width: 100%; max-height: 100vh; z-index: 1000;
            border-radius: 0; border: none;
            animation: slideUp 0.3s ease-out;
        }
        @keyframes slideUp { from { opacity: 0; transform: translateY(50px); } to { opacity: 1; transform: translateY(0); } }
    }

    @media (max-width: 768px) {
        .stats-row { grid-template-columns: 1fr; }
        .filter-bar { flex-direction: column; align-items: stretch; }
        .search-box, .select-wrapper, .filter-select { max-width: 100%; width: 100%; min-width: 100%; }
        .page-header { flex-direction: column; align-items: flex-start; }
        .export-dropdown, .btn-export { width: 100%; }
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1>Semua Pelanggan</h1>
        <div class="breadcrumb">FurniHome / Pelanggan</div>
    </div>
    <div class="export-dropdown">
        <button type="button" class="btn-export">
            <i class="ti ti-download"></i> Export Data
            <i class="ti ti-chevron-down" style="font-size:14px;"></i>
        </button>
        <div class="export-dropdown-content">
            <a href="{{ route('admin.customer.export.pdf', request()->query()) }}" target="_blank">
                <i class="ti ti-file-type-pdf"></i> Export PDF
            </a>
            <a href="{{ route('admin.customer.export.csv', request()->query()) }}">
                <i class="ti ti-file-type-csv"></i> Export CSV
            </a>
        </div>
    </div>
</div>

<form method="GET" action="{{ route('admin.customer.index') }}" id="filterForm">
<div class="filter-bar">
    <div class="search-box">
        <i class="ti ti-search"></i>
        <input type="text" name="search"
            placeholder="Cari Nama, Email, atau No. HP..."
            value="{{ request('search') }}"
            oninput="debounceSubmit()">
    </div>
    <div class="select-wrapper">
        <i class="ti ti-sort-descending prefix-icon"></i>
        <select class="filter-select" name="sort" onchange="this.form.submit()">
            <option value="latest"   {{ request('sort', 'latest') === 'latest'   ? 'selected' : '' }}>Urutkan: Terbaru</option>
            <option value="spending" {{ request('sort') === 'spending' ? 'selected' : '' }}>Urutkan: Pembelanjaan Terbanyak</option>
        </select>
    </div>
    @if(request('search') || request('sort'))
        <a href="{{ route('admin.customer.index') }}"
           style="height:40px; padding:0 16px; border:1px solid #e8caca; color:#c47a7a; border-radius:var(--radius-md); display:inline-flex; align-items:center; gap:6px; font-size:13px; font-weight:600; text-decoration:none; background:var(--bg-surface);">
            <i class="ti ti-refresh"></i> Reset
        </a>
    @endif
</div>
</form>

<div class="stats-row">
    <div class="stat-card total">
        <div class="stat-icon"><i class="ti ti-users"></i></div>
        <div class="stat-label">Total Pelanggan</div>
        <div class="stat-value">{{ $stats['total'] }}</div>
    </div>
    <div class="stat-card member">
        <div class="stat-icon"><i class="ti ti-vip"></i></div>
        <div class="stat-label">Member VIP</div>
        <div class="stat-value">{{ $stats['vip'] }}</div>
    </div>
    <div class="stat-card active">
        <div class="stat-icon"><i class="ti ti-shopping-cart-check"></i></div>
        <div class="stat-label">Aktif Belanja (Bulan Ini)</div>
        <div class="stat-value">{{ $stats['active'] }}</div>
    </div>
    <div class="stat-card new">
        <div class="stat-icon"><i class="ti ti-user-plus"></i></div>
        <div class="stat-label">Pelanggan Baru</div>
        <div class="stat-value">+{{ $stats['new'] }}</div>
    </div>
</div>

<div class="filter-bar">
    <div class="search-box">
        <i class="ti ti-search"></i>
        <input type="text" placeholder="Cari Nama, Email, atau No. HP...">
    </div>
    <div class="select-wrapper">
        <i class="ti ti-sort-descending prefix-icon"></i>
        <select class="filter-select">
            <option>Urutkan: Terbaru</option>
            <option>Urutkan: Pembelanjaan Terbanyak</option>
        </select>
    </div>
</div>

<div class="layout-order">
    {{-- TABLE SECTION --}}
    <div class="table-section">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th style="width: 80px;">Cust ID</th>
                        <th>Profil Pelanggan</th>
                        <th>No. Telepon</th>
                        <th style="text-align: center;">Total Order</th>
                        <th style="text-align: center;">Status</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $index => $c)
                    <tr>
                        <td style="color: var(--text-sec); font-size: 12.5px;">CST-{{ str_pad($c->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td>
                            <div class="customer-profile">
                                @php
                                    $inisial = substr($c->name, 0, 1);
                                    $tipe = $c->orders_count >= 5 ? 'VIP' : 'Regular';
                                    $vipClass = $tipe == 'VIP' ? 'vip' : '';
                                @endphp
                                <div class="customer-avatar">{{ $inisial }}</div>
                                <div class="customer-info">
                                    <div class="customer-name">{{ $c->name }}</div>
                                    <div class="customer-email">{{ $c->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="color: var(--text-sec); font-size: 12.5px;">{{ $c->phone ?? '-' }}</td>
                        <td style="text-align: center; font-weight: 600;">{{ $c->orders_count }}</td>
                        <td style="text-align: center;">
                            @php $badgeClass = $tipe == 'VIP' ? 'badge-vip' : 'badge-regular'; @endphp
                            <span class="status-badge {{ $badgeClass }}">{{ $tipe }}</span>
                        </td>
                        <td style="text-align: center;">
                            <div class="action-btn" onclick="showCustomerDetail({{ $index }})" title="Lihat Detail">
                                <i class="ti ti-user-search"></i>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-muted);">Belum ada pelanggan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination">
            <div>Menampilkan {{ $customers->firstItem() ?? 0 }} - {{ $customers->lastItem() ?? 0 }} dari {{ $customers->total() }} pelanggan</div>
            <div class="pagination-links">
                @if($customers->onFirstPage())
                    <span style="opacity:0.4; border:1px solid var(--border); border-radius:6px; padding:0 10px; height:30px; display:inline-flex; align-items:center;">←</span>
                @else
                    <a href="{{ $customers->previousPageUrl() }}">←</a>
                @endif
                @foreach($customers->getUrlRange(1, $customers->lastPage()) as $page => $url)
                    @if($page == $customers->currentPage())
                        <span class="active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
                @if($customers->hasMorePages())
                    <a href="{{ $customers->nextPageUrl() }}">→</a>
                @else
                    <span style="opacity:0.4; border:1px solid var(--border); border-radius:6px; padding:0 10px; height:30px; display:inline-flex; align-items:center;">→</span>
                @endif
            </div>
        </div>
    </div>
    {{-- END TABLE SECTION --}}

    {{-- DETAIL PANEL --}}
    <div class="detail-panel" id="customerPanel">
        <div class="dp-header">
            <h3>Detail Pelanggan</h3>
            <div class="dp-close" onclick="closeCustomerDetail()"><i class="ti ti-x"></i></div>
        </div>
        <div class="dp-body" id="customerBody"></div>
    </div>
</div>

<script>
    const customers = @json($customersJson);

    function formatRupiah(n) {
        return 'Rp ' + Number(n).toLocaleString('id-ID');
    }

    function showCustomerDetail(index) {
        const c = customers[index];
        const panel = document.getElementById('customerPanel');
        const body = document.getElementById('customerBody');

        // Menggunakan OR (||) agar kompatibel dengan data dummy lamamu atau data database baru
        const nama = c.name || c.nama;
        const inisial = nama.charAt(0);
        const hp = c.phone || c.hp || '-';
        const totalOrder = c.orders_count || c.total_order || 0;
        const alamat = c.address || c.alamat || 'Alamat tidak tersedia';
        const tglJoin = c.join_date || c.created_at || '-';
        const idAsli = c.id_asli || c.id;

        body.innerHTML = `
            <div class="dp-profile-header">
                <div class="dp-profile-avatar">${inisial}</div>
                <div class="dp-profile-name">${nama}</div>
                <div class="dp-profile-join">Bergabung sejak ${tglJoin}</div>
            </div>

            <div class="dp-stat-grid">
                <div class="dp-stat-box">
                    <div class="lbl">Total Order</div>
                    <div class="val">${totalOrder}x</div>
                </div>
                <div class="dp-stat-box">
                    <div class="lbl">Total Belanja</div>
                    <div class="val" style="font-size:14px;">${formatRupiah(c.total_spent || 0)}</div>
                </div>
            </div>

            <div class="dp-section-title">Informasi Kontak</div>
            <div class="dp-row">
                <div class="dp-label"><i class="ti ti-mail" style="font-size:16px; vertical-align:middle; margin-right:4px;"></i> Email</div>
                <div class="dp-value">${c.email || '-'}</div>
            </div>
            <div class="dp-row">
                <div class="dp-label"><i class="ti ti-phone" style="font-size:16px; vertical-align:middle; margin-right:4px;"></i> No. HP</div>
                <div class="dp-value">${hp}</div>
            </div>

            <div class="dp-section-title">Alamat Pengiriman Utama</div>
            <div style="font-size:12.5px; color:var(--text-main); line-height:1.5; padding: 12px; background: var(--bg-hover); border-radius: 8px; border: 1px solid var(--border);">
                ${alamat}
            </div>

            <div style="margin-top: 24px;">
                <button class="btn-action" id="btn-riwayat" onclick="loadOrderHistory('${idAsli}')">
                    <i class="ti ti-history"></i> Riwayat Pesanan Lengkap
                </button>
                <div id="history-container" style="margin-top: 14px; display: none;"></div>
                <button class="btn-action" style="margin-top: 14px;"><i class="ti ti-send"></i> Kirim Email</button>
            </div>
        `;

        panel.classList.add('open');
    }

    function closeCustomerDetail() {
        document.getElementById('customerPanel').classList.remove('open');
    }

    function loadOrderHistory(userId) {
        const container = document.getElementById('history-container');
        const btn = document.getElementById('btn-riwayat');
        
        btn.innerHTML = `<i class="ti ti-loader animate-spin"></i> Mengambil Data...`;
        btn.disabled = true;

        fetch(`/admin/customers/${userId}/orders`)
            .then(response => response.json())
            .then(data => {
                if (data.orders.length === 0) {
                    container.innerHTML = `<div style="font-size:12px; color:var(--text-muted); text-align:center; padding:10px; background:var(--bg-hover); border-radius:8px;">Belum ada riwayat pesanan.</div>`;
                } else {
                    let html = `
                        <div class="dp-section-title" style="margin: 10px 0 8px 0;">Daftar Transaksi</div>
                        <div style="max-height: 200px; overflow-y: auto; display: flex; flex-direction: column; gap: 8px;">
                    `;
                    
                    data.orders.forEach(order => {
                        html += `
                            <div style="display:flex; justify-content:space-between; align-items:center; padding:10px; background:var(--bg-hover); border-radius:8px; border:1px solid var(--border); font-size:12px;">
                                <div>
                                    <strong style="color:var(--text-main); font-family:monospace;">${order.invoice}</strong>
                                    <div style="color:var(--text-muted); font-size:11px; margin-top:2px;">${order.tanggal}</div>
                                </div>
                                <div style="text-align:right;">
                                    <span style="font-weight:700; color:var(--accent);">${formatRupiah(order.total)}</span>
                                    <div style="font-size:10px; color:var(--text-sec); font-weight:600; text-transform:uppercase;">${order.status}</div>
                                </div>
                            </div>
                        `;
                    });
                    
                    html += `</div>`;
                    container.innerHTML = html;
                }
                
                container.style.display = 'block';
                btn.style.display = 'none';
            })
            .catch(error => {
                alert('Gagal mengambil data riwayat pesanan.');
                btn.innerHTML = `<i class="ti ti-history"></i> Riwayat Pesanan Lengkap`;
                btn.disabled = false;
            });
    }

    let debounceTimer;
    function debounceSubmit() {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        document.getElementById('filterForm').submit();
    }, 400);
}
</script>
@endsection