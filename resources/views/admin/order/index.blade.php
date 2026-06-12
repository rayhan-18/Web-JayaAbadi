@extends('layouts.admin')

@section('title', 'Pesanan')

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

    /* Page Header */
    .page-header {
        display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;
    }
    .page-title h1 {
        font-size: 22px; font-weight: 700; color: var(--text-main); margin: 0; letter-spacing: -0.02em;
    }
    .page-title .breadcrumb {
        font-size: 13px; color: var(--text-sec); margin-top: 4px;
    }

    /* Stats Grid */
    .stats-row {
        display: grid; grid-template-columns: repeat(5, 1fr); gap: 16px; margin-bottom: 24px;
    }
    .stat-card {
        background: var(--bg-surface); border-radius: var(--radius-lg); border: 1px solid var(--border);
        padding: 20px 16px; text-align: center; transition: all 0.2s ease;
    }
    .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(45, 59, 50, 0.04); }
    
    .stat-icon {
        width: 42px; height: 42px; border-radius: var(--radius-md);
        display: flex; align-items: center; justify-content: center;
        font-size: 22px; margin: 0 auto 12px;
    }
    
    .stat-card.all .stat-icon     { background: transparent; color: var(--text-sec); }
    .stat-card.pending .stat-icon { background: transparent; color: #b89247; }
    .stat-card.proses .stat-icon  { background: transparent; color: #5c7b9e; }
    .stat-card.kirim .stat-icon   { background: transparent; color: #865c9e; }
    .stat-card.selesai .stat-icon { background: transparent; color: var(--accent); }

    .stat-label { font-size: 12.5px; font-weight: 600; color: var(--text-sec); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.02em; }
    .stat-value { font-size: 26px; font-weight: 700; color: var(--text-main); letter-spacing: -0.02em; }
    
    /* Filters */
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

    /* Export Laporan Dropdown */
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

    /* Layout & Table */
    .layout-order { display: flex; gap: 20px; align-items: flex-start; }
    .table-section { flex: 1; min-width: 0; }
    
    .table-wrapper { background: var(--bg-surface); border-radius: var(--radius-lg); border: 1px solid var(--border); overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; font-size: 13px; min-width: 960px; }
    th {
        text-align: left; padding: 14px 20px; background: var(--bg-hover); font-weight: 600;
        color: var(--text-sec); border-bottom: 1px solid var(--border); font-size: 12px;
        text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap;
    }
    td { padding: 14px 20px; border-bottom: 1px solid var(--border); vertical-align: middle; color: var(--text-main); }
    tr:last-child td { border-bottom: none; }
    tbody tr:hover { background: var(--bg-hover); }

    .customer-info { line-height: 1.4; }
    .customer-name { font-weight: 600; color: var(--text-main); font-size: 13.5px; }
    .customer-email { font-size: 11.5px; color: var(--text-muted); margin-top: 2px; }

    .status-badge {
        display: inline-flex; align-items: center; justify-content: center;
        padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; letter-spacing: 0.02em; white-space: nowrap;
    }
    .status-pending  { background: #fdf5e6; color: #8a5a2e; }
    .status-diproses { background: #f0f4f8; color: #4a6b8c; }
    .status-dikirim  { background: #f3f0f8; color: #6b4a8c; }
    .status-selesai  { background: var(--accent-light); color: var(--accent-dark); }
    
    .status-badge::before { content: ''; width: 6px; height: 6px; border-radius: 50%; margin-right: 6px; }
    .status-pending::before  { background: #d99e52; }
    .status-diproses::before { background: #6993c4; }
    .status-dikirim::before  { background: #9269c4; }
    .status-selesai::before  { background: var(--accent); }

    /* Premium Channel Badges */
    .channel-badge {
        display: inline-flex; align-items: center; justify-content: center; gap: 4px;
        padding: 4px 8px; border-radius: 6px; font-size: 11px; font-weight: 600; white-space: nowrap;
    }
    .channel-online  { background: #eef7f2; color: #3b7a54; border: 1px solid #dbeee3; }
    .channel-offline { background: #f1f3f5; color: #495057; border: 1px solid #e9ecef; }
    
    .action-btn {
        width: 32px; height: 32px; border-radius: 8px; background: var(--bg-surface);
        border: 1px solid var(--border); display: inline-flex; align-items: center; justify-content: center;
        cursor: pointer; color: var(--text-sec); font-size: 16px; transition: 0.15s; text-decoration: none;
    }
    .action-btn:hover { background: var(--bg-hover); color: var(--accent); border-color: #d1d6cf; }

    /* Pagination */
    .pagination { display: flex; justify-content: space-between; align-items: center; margin-top: 24px; font-size: 13px; color: var(--text-sec); flex-wrap: wrap; gap: 12px; }
    .pagination-links { display: flex; gap: 6px; }
    .pagination-links a, .pagination-links span {
        display: inline-flex; align-items: center; justify-content: center; min-width: 30px; height: 30px; padding: 0 10px;
        border: 1px solid var(--border); border-radius: 6px; text-decoration: none; color: var(--text-main); font-weight: 500;
        transition: 0.15s; background: var(--bg-surface);
    }
    .pagination-links a:hover { background: var(--bg-hover); border-color: var(--accent); color: var(--accent); }
    .pagination-links .active { background: var(--accent); border-color: var(--accent); color: white; }

    /* Detail Panel Sidebar */
    .detail-panel {
        width: 360px; flex-shrink: 0; background: #ffffff !important; border-radius: var(--radius-lg);
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
        border-bottom: 1px solid var(--border); background: #ffffff !important; position: sticky; top: 0; z-index: 20; 
    }
    .dp-header h3 { font-size: 15px; font-weight: 700; margin: 0; color: var(--text-main); }
    .dp-close {
        width: 30px; height: 30px; border-radius: 8px; background: var(--bg-hover); border: 1px solid transparent;
        display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 16px; color: var(--text-sec); transition: 0.2s;
    }
    .dp-close:hover { background: #fdf5f5; color: #c47a7a; border-color: #e8caca; }
    
    .dp-body { padding: 20px; }
    .dp-order-id { font-size: 18px; font-weight: 700; color: var(--text-main); margin-bottom: 16px; letter-spacing: -0.02em; }
    .dp-row { display: flex; gap: 12px; margin-bottom: 14px; align-items: flex-start; }
    .dp-label { font-size: 12.5px; color: var(--text-sec); font-weight: 500; min-width: 100px; }
    .dp-value { font-size: 13px; color: var(--text-main); font-weight: 500; flex: 1; line-height: 1.4; }
    
    .dp-section-title { font-size: 13px; font-weight: 700; color: var(--text-main); margin: 20px 0 14px; text-transform: uppercase; letter-spacing: 0.02em; }
    .dp-divider { border: none; border-top: 1px dashed var(--border); margin: 16px 0; }
    
    /* Gambar Produk di Detail Panel */
    .dp-product { display: flex; align-items: center; gap: 14px; margin-bottom: 14px; }
    .dp-product-img {
        width: 46px; height: 46px; border-radius: 8px; background: var(--bg-hover);
        border: 1px solid var(--border); overflow: hidden; flex-shrink: 0;
    }
    .dp-product-img img { width: 100%; height: 100%; object-fit: cover; }
    .dp-product-name { font-size: 13px; font-weight: 600; color: var(--text-main); margin-bottom: 2px; }
    .dp-product-qty { font-size: 11.5px; color: var(--text-muted); }
    .dp-product-price { margin-left: auto; font-size: 13.5px; font-weight: 600; color: var(--text-main); white-space: nowrap; }
    
    .dp-total-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 13px; }
    .dp-total-row .lbl { color: var(--text-sec); }
    .dp-total-row .val { font-weight: 500; color: var(--text-main); }
    .dp-total-row.grand { margin-top: 14px; padding-top: 14px; border-top: 1px solid var(--border); }
    .dp-total-row.grand .lbl { font-weight: 700; color: var(--text-main); font-size: 14px; }
    .dp-total-row.grand .val { font-weight: 700; color: var(--accent); font-size: 18px; }

    .status-select {
        width: 100%; height: 40px; padding: 0 14px; border: 1px solid var(--border);
        border-radius: var(--radius-md); font-size: 13px; color: var(--text-main);
        background: var(--bg-surface); margin-top: 16px; cursor: pointer;
    }
    .status-select:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px rgba(92, 158, 116, 0.15); }
    
    .btn-update {
        width: 100%; padding: 12px; background-color: #5c9e74 !important; color: #ffffff !important;
        border: none; border-radius: 10px; font-size: 13px; font-weight: 600;
        cursor: pointer; margin-top: 12px; display: flex; align-items: center; justify-content: center; gap: 8px;
        box-shadow: 0 2px 6px rgba(92, 158, 116, 0.2); outline: none; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); -webkit-tap-highlight-color: transparent;
    }
    .btn-update:hover { background-color: #3a5c48 !important; transform: translateY(-2px); box-shadow: 0 6px 15px rgba(58, 92, 72, 0.3); }
    .btn-update:active { transform: scale(0.97); background-color: #2d4a3a !important; box-shadow: inset 0 2px 4px rgba(0,0,0,0.2); transition: all 0.1s; }

    /* =========================================
       SISTEM RESPONSIVE (MOBILE & TABLET)
       ========================================= */
    @media (max-width: 1200px) {
        .stats-row { grid-template-columns: repeat(3, 1fr); }
    }

    @media (max-width: 1024px) {
        .layout-order { flex-direction: column; }
        
        /* Modal Panel Mobile */
        .detail-panel.open {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            width: 100%; max-height: 100vh; z-index: 1000;
            border-radius: 0; border: none;
            animation: slideUp 0.3s ease-out;
        }
        @keyframes slideUp { from { opacity: 0; transform: translateY(50px); } to { opacity: 1; transform: translateY(0); } }
    }

    @media (max-width: 768px) {
        .stats-row { grid-template-columns: repeat(2, 1fr); }
        .filter-bar { flex-direction: column; align-items: stretch; }
        .search-box, .select-wrapper, .filter-select { max-width: 100%; width: 100%; min-width: 100%; }
        .page-header { flex-direction: column; align-items: flex-start; }
        .export-dropdown, .btn-export { width: 100%; }
    }

    @media (max-width: 480px) {
        .stats-row { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1>Pesanan</h1>
        <div class="breadcrumb">FurniHome / Pesanan</div>
    </div>
</div>

<div class="stats-row">
    <div class="stat-card all"><div class="stat-icon"><i class="ti ti-package"></i></div><div class="stat-label">Semua Pesanan</div><div class="stat-value">{{ $stats['all'] }}</div></div>
    <div class="stat-card pending"><div class="stat-icon"><i class="ti ti-clock-hour-4"></i></div><div class="stat-label">Pending</div><div class="stat-value">{{ $stats['pending'] }}</div></div>
    <div class="stat-card proses"><div class="stat-icon"><i class="ti ti-settings"></i></div><div class="stat-label">Diproses</div><div class="stat-value">{{ $stats['paid'] }}</div></div>
    <div class="stat-card kirim"><div class="stat-icon"><i class="ti ti-truck"></i></div><div class="stat-label">Dikirim</div><div class="stat-value">{{ $stats['shipping'] }}</div></div>
    <div class="stat-card selesai"><div class="stat-icon"><i class="ti ti-circle-check"></i></div><div class="stat-label">Selesai</div><div class="stat-value">{{ $stats['delivered'] }}</div></div>
</div>

<form action="{{ route('admin.order.index') }}" method="GET" class="filter-bar" id="filterForm">
    
    <div class="search-box">
        <i class="ti ti-search"></i>
        <input type="text" name="search" id="searchInput" placeholder="Cari Order ID, Pelanggan..." value="{{ request('search') }}" onchange="document.getElementById('filterForm').submit()">
    </div>
    
    <div class="select-wrapper">
        <i class="ti ti-filter prefix-icon"></i>
        <select class="filter-select" name="channel" onchange="document.getElementById('filterForm').submit()">
            <option value="">Semua Saluran</option>
            <option value="website" {{ request('channel') === 'website' ? 'selected' : '' }}>Website Online</option>
            <option value="pos" {{ request('channel') === 'pos' ? 'selected' : '' }}>Kasir POS Offline</option>
        </select>
    </div>
    
    <div class="select-wrapper">
        <i class="ti ti-circle-check prefix-icon"></i>
        <select class="filter-select" name="status" onchange="document.getElementById('filterForm').submit()">
            <option value="">Semua Status</option>
            <option value="pending"   {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="paid"      {{ request('status') === 'paid' ? 'selected' : '' }}>Diproses</option>
            <option value="shipping"  {{ request('status') === 'shipping' ? 'selected' : '' }}>Dikirim</option>
            <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Selesai</option>
            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
        </select>
    </div>

    <div class="export-dropdown">
        <button type="button" class="btn-export">
            <i class="ti ti-download"></i> Export Laporan 
            <i class="ti ti-chevron-down" style="font-size: 14px;"></i>
        </button>
        <div class="export-dropdown-content">
            <a href="{{ route('admin.order.export.pdf', request()->query()) }}" target="_blank">
                <i class="ti ti-file-type-pdf"></i> Export PDF
            </a>
            <a href="#" onclick="doExport('excel')">
                <i class="ti ti-file-spreadsheet"></i> Export Excel
            </a>
        </div>
    </div>

    <input type="hidden" name="export" id="exportFormat" value="">

    @if(request('search') || request('channel') || request('status'))
        <a href="{{ route('admin.order.index') }}" class="action-btn" style="height: 40px; padding: 0 16px; border-color: #c47a7a; color: #c47a7a;">
            <i class="ti ti-refresh"></i> Reset Filter
        </a>
    @endif
</form>

<div class="layout-order">
    <div class="table-section">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Sumber</th>
                        <th>Pelanggan</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Pembayaran</th>
                        <th>Status</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $index => $order)
                    <tr>
                        <td style="font-weight:600; color: var(--text-main);">{{ $order->order_number }}</td>
                        <td>
                            @if($order->payment_method === 'cash')
                                <span class="channel-badge channel-offline"><i class="ti ti-store"></i> Kasir POS</span>
                            @else
                                <span class="channel-badge channel-online"><i class="ti ti-world"></i> Website</span>
                            @endif
                        </td>
                        <td>
                            <div class="customer-info">
                                <div class="customer-name">{{ $order->user->name ?? 'Guest' }}</div>
                                <div class="customer-email">{{ $order->user->email ?? '-' }}</div>
                            </div>
                        </td>
                        <td style="color: var(--text-sec); font-size: 12.5px;">{{ $order->created_at->format('d M Y H:i') }}</td>
                        <td style="font-weight: 500;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        <td style="color: var(--text-sec); font-size: 12.5px;">{{ ucfirst($order->payment_method) }}</td>
                        <td>
                            @php
                                $statusClass = match($order->status) {
                                    'pending'   => 'status-pending',
                                    'paid'      => 'status-diproses',
                                    'shipping'  => 'status-dikirim',
                                    'delivered' => 'status-selesai',
                                    default     => ''
                                };
                                $statusLabel = match($order->status) {
                                    'pending'   => 'Pending',
                                    'paid'      => 'Diproses',
                                    'shipping'  => 'Dikirim',
                                    'delivered' => 'Selesai',
                                    'cancelled' => 'Dibatalkan',
                                    default     => ucfirst($order->status)
                                };
                            @endphp
                            <span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span>
                        </td>
                        <td style="text-align: center;">
                            <div class="action-btn" onclick="showDetail({{ $index }})" title="Lihat Detail"><i class="ti ti-eye"></i></div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 40px; color: var(--text-muted);">Belum ada pesanan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination">
            <div>Menampilkan {{ $orders->firstItem() ?? 0 }} - {{ $orders->lastItem() ?? 0 }} dari {{ $orders->total() }} pesanan</div>
            <div class="pagination-links">
                @if($orders->onFirstPage())
                    <span style="opacity:0.4; border:1px solid var(--border); border-radius:6px; padding:0 10px; height:30px; display:inline-flex; align-items:center;">
                        <i class="ti ti-chevron-left" style="font-size:16px;"></i>
                    </span>
                @else
                    <a href="{{ $orders->previousPageUrl() }}"><i class="ti ti-chevron-left" style="font-size:16px;"></i></a>
                @endif

                @foreach($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                    @if($page == $orders->currentPage())
                        <span class="active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach

                @if($orders->hasMorePages())
                    <a href="{{ $orders->nextPageUrl() }}"><i class="ti ti-chevron-right" style="font-size:16px;"></i></a>
                @else
                    <span style="opacity:0.4; border:1px solid var(--border); border-radius:6px; padding:0 10px; height:30px; display:inline-flex; align-items:center;">
                        <i class="ti ti-chevron-right" style="font-size:16px;"></i>
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="detail-panel" id="detailPanel">
        <div class="dp-header">
            <h3>Detail Pesanan</h3>
            <div class="dp-close" onclick="closeDetail()"><i class="ti ti-x"></i></div>
        </div>
        <div class="dp-body" id="detailBody"></div>
    </div>
</div>

<script>
    const orders = @json($ordersJson);

    function formatRupiah(n) {
        return 'Rp ' + Number(n).toLocaleString('id-ID');
    }

    // Melakukan submit form filter dibarengi instruksi cetak dokumen data ter-filter
    function doExport(format) {
        const form = document.getElementById('filterForm');
        const exportInput = document.getElementById('exportFormat');
        
        exportInput.value = format;
        form.submit();
        
        setTimeout(() => {
            exportInput.value = '';
        }, 500);
    }

    // Membuka Print Preview Browser untuk disimpan ke PDF
    function previewAndPdf() {
        window.print();
    }

    function getStatusClass(status) {
        const map = {
            'Pending'    : 'status-pending',
            'Diproses'   : 'status-diproses',
            'Dikirim'    : 'status-dikirim',
            'Selesai'    : 'status-selesai',
            'Dibatalkan' : ''
        };
        return map[status] || '';
    }

    function showDetail(index) {
        const o = orders[index];
        const panel = document.getElementById('detailPanel');
        const body = document.getElementById('detailBody');

        // UPDATE: Implementasi render image asli
        const itemsHtml = o.items.map(item => `
            <div class="dp-product">
                <div class="dp-product-img">
                    <img src="${item.img}" alt="${item.nama}">
                </div>
                <div>
                    <div class="dp-product-name">${item.nama}</div>
                    <div class="dp-product-qty">Qty: ${item.qty}</div>
                </div>
                <div class="dp-product-price">${formatRupiah(item.harga)}</div>
            </div>
        `).join('');

        const channelHtml = o.channel === 'Online' 
            ? `<span class="channel-badge channel-online"><i class="ti ti-world"></i> Website</span>`
            : `<span class="channel-badge channel-offline"><i class="ti ti-building-store"></i> POS Kasir</span>`;

        body.innerHTML = `
            <div class="dp-order-id">${o.id}</div>
            <div class="dp-row">
                <div class="dp-label">Saluran</div>
                <div class="dp-value">${channelHtml}</div>
            </div>
            <div class="dp-row">
                <div class="dp-label">Status</div>
                <div class="dp-value"><span class="status-badge ${getStatusClass(o.status)}">${o.status}</span></div>
            </div>
            <div class="dp-row">
                <div class="dp-label">Tanggal</div>
                <div class="dp-value">${o.tanggal}</div>
            </div>
            <div class="dp-row">
                <div class="dp-label">Pelanggan</div>
                <div class="dp-value">
                    <div style="font-weight: 600; margin-bottom: 2px;">${o.nama}</div>
                    <div style="color: var(--text-sec); font-size: 11.5px;">${o.email}</div>
                    <div style="color: var(--text-sec); font-size: 11.5px;">${o.hp || '-'}</div>
                </div>
            </div>
            <div class="dp-row">
                <div class="dp-label">Alamat</div>
                <div class="dp-value" style="font-size:12.5px; line-height: 1.5;">${o.alamat}</div>
            </div>
            <div class="dp-row">
                <div class="dp-label">Pembayaran</div>
                <div class="dp-value">${o.metode}</div>
            </div>
            <hr class="dp-divider">
            <div class="dp-section-title">Produk (${o.items.length})</div>
            ${itemsHtml}
            <div class="dp-total-row grand">
                <span class="lbl">Total Pembayaran</span>
                <span class="val">${formatRupiah(o.total2)}</span>
            </div>
            <hr class="dp-divider">
            <div class="dp-section-title">Update Status</div>
            <select id="statusSelect-${o.order_id}" class="status-select">
                <option value="pending"   ${o.status_raw === 'pending'   ? 'selected' : ''}>Pending</option>
                <option value="paid"      ${o.status_raw === 'paid'      ? 'selected' : ''}>Diproses</option>
                <option value="shipping"  ${o.status_raw === 'shipping'  ? 'selected' : ''}>Dikirim</option>
                <option value="delivered" ${o.status_raw === 'delivered' ? 'selected' : ''}>Selesai</option>
                <option value="cancelled" ${o.status_raw === 'cancelled' ? 'selected' : ''}>Dibatalkan</option>
            </select>
            <button class="btn-update" onclick="updateStatus(${o.order_id})">
                <i class="ti ti-refresh"></i> Update Status Pesanan
            </button>
        `;

        panel.classList.add('open');
    }

    function closeDetail() {
        document.getElementById('detailPanel').classList.remove('open');
    }

    function updateStatus(orderId) {
        const status = document.getElementById('statusSelect-' + orderId).value;
        fetch(`/admin/pesanan/${orderId}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ status })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Status pesanan berhasil diupdate.',
                    icon: 'success',
                    confirmButtonColor: '#5c9e74',
                }).then(() => location.reload());
            }
        })
        .catch(() => {
            Swal.fire('Error', 'Gagal update status.', 'error');
        });
    }

    // Submit otomatis saat menekan enter pada input text search
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('exportFormat').value = ''; 
            document.getElementById('filterForm').submit();
        }
    });
</script>
@endsection