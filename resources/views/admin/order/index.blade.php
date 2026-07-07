@extends('layouts.admin')

@section('title', 'Pesanan')

@section('styles')
<style>
    /* ── Reset & Premium Variables (Tema: Royal Blue & Minimalist Slate) ── */
    :root {
        --accent: #2563eb; 
        --accent-dark: #1e40af;
        --accent-light: #eff6ff;
        --border: rgba(15, 23, 42, 0.06);
        --bg-body: #f8fafc;
        --bg-surface: #ffffff;
        --bg-hover: #f1f5f9;
        --text-main: #0f172a;
        --text-sec: #475569;
        --text-muted: #94a3b8;
        --radius-md: 14px;
        --radius-lg: 20px;
        --shadow-card: 0 10px 30px -10px rgba(15, 23, 42, 0.05);
        --shadow-hover: 0 20px 40px -12px rgba(15, 23, 42, 0.09);
    }

    body { color: var(--text-main); background: var(--bg-body); }

    /* ── Page Header ── */
    .page-header {
        display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; flex-wrap: wrap; gap: 16px;
    }
    .page-title h1 {
        font-size: 24px; font-weight: 700; color: var(--text-main); margin: 0; letter-spacing: -0.02em;
    }
    .page-title .breadcrumb {
        font-size: 13.5px; color: var(--text-sec); margin-top: 4px;
    }

    /* ── Stats Grid ── */
    .stats-row {
        display: grid; grid-template-columns: repeat(5, 1fr); gap: 16px; margin-bottom: 24px;
    }
    .stat-card {
        background: var(--bg-surface); border-radius: var(--radius-lg); border: 1px solid var(--border);
        padding: 20px 16px; text-align: center; box-shadow: var(--shadow-card); transition: all 0.3s ease;
    }
    .stat-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-hover); }
    
    .stat-icon {
        width: 46px; height: 46px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 24px; margin: 0 auto 12px;
    }
    
    /* Warna Ikon Stat */
    .stat-card.all .stat-icon       { background: #f1f5f9; color: #475569; }
    .stat-card.pending .stat-icon   { background: #fffbeb; color: #f59e0b; }
    .stat-card.proses .stat-icon    { background: #eff6ff; color: #3b82f6; }
    .stat-card.kirim .stat-icon     { background: #f3e8ff; color: #8b5cf6; }
    .stat-card.selesai .stat-icon   { background: #ecfdf5; color: #10b981; }

    .stat-label { font-size: 12px; font-weight: 600; color: var(--text-sec); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.06em; }
    .stat-value { font-size: 26px; font-weight: 700; color: var(--text-main); letter-spacing: -0.02em; line-height: 1.1; }
    
    /* ── Filters ── */
    .filter-bar { display: flex; gap: 12px; margin-bottom: 24px; flex-wrap: wrap; align-items: center; }
    .search-box {
        display: flex; align-items: center; background: var(--bg-surface);
        border: 1px solid var(--border); border-radius: var(--radius-md);
        padding: 0 16px; gap: 10px; height: 44px; flex: 1; max-width: 320px; height: 44px; transition: all 0.2s;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }
    .search-box:focus-within { border-color: var(--accent); background: var(--bg-surface); box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15); }
    .search-box i { color: var(--text-sec); font-size: 18px; }
    .search-box input { border: none; outline: none; background: transparent; font-size: 13.5px; width: 100%; color: var(--text-main); font-weight: 500; }
    .search-box input::placeholder { color: var(--text-muted); font-weight: 400; }
    
    .select-wrapper { position: relative; display: inline-block; }
    .select-wrapper i.prefix-icon {
        position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--text-sec); font-size: 16px; pointer-events: none;
    }
    .filter-select {
        height: 44px; padding: 0 36px 0 38px; border: 1px solid var(--border); border-radius: var(--radius-md);
        background: var(--bg-surface) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23475569' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E") no-repeat right 12px center;
        appearance: none; font-size: 13.5px; font-weight: 500; color: var(--text-main); cursor: pointer; transition: all 0.2s; min-width: 170px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }
    .filter-select:hover { border-color: #cbd5e1; }
    .filter-select:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15); }

    /* ── Export Laporan Dropdown ── */
    .export-dropdown { position: relative; display: inline-block; }
    .btn-export {
        background: var(--bg-surface); color: var(--text-main); border: 1px solid var(--border); box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        padding: 0 16px; height: 44px; border-radius: var(--radius-md); font-size: 14px; font-weight: 700;
        cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .btn-export i { font-size: 16px; color: var(--text-sec); }
    .btn-export:hover { border-color: #cbd5e1; color: var(--accent); }
    .btn-export:hover i { color: var(--accent); }
    
    .export-dropdown-content {
        display: none; position: absolute; right: 0; top: 50px; background: var(--bg-surface);
        min-width: 180px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border-radius: 12px;
        z-index: 10; border: 1px solid var(--border); overflow: hidden;
    }
    .export-dropdown-content a {
        padding: 12px 16px; display: flex; align-items: center; gap: 10px; text-decoration: none;
        color: var(--text-main); font-size: 13.5px; font-weight: 500; border-bottom: 1px solid #f1f5f9; transition: background 0.2s;
    }
    .export-dropdown-content a:last-child { border-bottom: none; }
    .export-dropdown-content a:hover { background: var(--bg-hover); color: var(--accent); }
    .export-dropdown:hover .export-dropdown-content { display: block; }

    /* ── Layout & Table ── */
    .layout-order { display: flex; gap: 24px; align-items: flex-start; min-width: 0; }
    .table-section { flex: 1; min-width: 0; width: 100%; }
    
    .table-wrapper { 
        background: var(--bg-surface); border-radius: var(--radius-lg); border: 1px solid var(--border); 
        box-shadow: var(--shadow-card);
        width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; padding-bottom: 4px;
    }
    .table-wrapper::-webkit-scrollbar { height: 6px; }
    .table-wrapper::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

    table { width: 100%; border-collapse: collapse; font-size: 13.5px; min-width: 960px; }
    th {
        text-align: left; padding: 16px 24px; background: rgba(0,0,0,0.015); font-weight: 600;
        color: var(--text-sec); border-bottom: 1px solid var(--border); font-size: 12px;
        text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap;
    }
    td { padding: 16px 24px; border-bottom: 1px solid var(--border); vertical-align: middle; color: var(--text-main); white-space: nowrap; transition: background 0.2s; }
    tr:last-child td { border-bottom: none; }
    tbody tr:hover { background: var(--bg-hover); }

    .invoice-link { font-weight: 700; color: var(--text-main); text-decoration: none; transition: color 0.2s; }
    .invoice-link:hover { color: var(--accent); }

    .customer-info { line-height: 1.4; }
    .customer-name { font-weight: 600; color: var(--text-main); font-size: 13.5px; }
    .customer-email { font-size: 11.5px; color: var(--text-muted); margin-top: 2px; }

    /* ── Status & Channel Badges ── */
    .status-badge {
        display: inline-flex; align-items: center; justify-content: center;
        padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 700; letter-spacing: 0.04em; white-space: nowrap;
    }
    .status-badge::before { content: ''; width: 6px; height: 6px; border-radius: 50%; margin-right: 6px; }

    .status-pending { background: #fffbeb; color: #d97706; }
    .status-pending::before { background: #f59e0b; }
    
    .status-diproses { background: #eff6ff; color: #2563eb; }
    .status-diproses::before { background: #3b82f6; }
    
    .status-dikirim { background: #f5f3ff; color: #7c3aed; }
    .status-dikirim::before { background: #8b5cf6; }
    
    .status-selesai { background: #ecfdf5; color: #059669; }
    .status-selesai::before { background: #10b981; }

    .status-dibatalkan { background: #fef2f2; color: #dc2626; }
    .status-dibatalkan::before { background: #ef4444; }

    .channel-badge {
        display: inline-flex; align-items: center; justify-content: center; gap: 6px;
        padding: 6px 10px; border-radius: 8px; font-size: 11.5px; font-weight: 600; white-space: nowrap;
    }
    .channel-online  { background: #f0fdf4; color: #16a34a; border: 1px solid #dcfce7; }
    .channel-offline { background: #f8fafc; color: #475569; border: 1px solid #e2e8f0; }
    
    .action-btn {
        width: 32px; height: 32px; border-radius: 8px; background: var(--bg-surface);
        border: 1px solid #e2e8f0; display: inline-flex; align-items: center; justify-content: center;
        cursor: pointer; color: var(--text-sec); font-size: 16px; transition: 0.2s; text-decoration: none;
    }
    .action-btn:hover { background: var(--accent-light); color: var(--accent); border-color: var(--accent); box-shadow: 0 4px 10px rgba(37, 99, 235, 0.15); }

    /* ── Pagination ── */
    .pagination { display: flex; justify-content: space-between; align-items: center; margin-top: 24px; font-size: 13.5px; font-weight: 500; color: var(--text-sec); flex-wrap: wrap; gap: 12px; }
    .pagination-links { display: flex; gap: 6px; }
    .pagination-links a, .pagination-links span {
        display: inline-flex; align-items: center; justify-content: center; min-width: 32px; height: 32px; padding: 0 10px;
        border: 1px solid var(--border); border-radius: 8px; text-decoration: none; color: var(--text-main); font-weight: 600;
        transition: 0.2s; background: var(--bg-surface);
    }
    .pagination-links a:hover { background: var(--bg-hover); border-color: #cbd5e1; color: var(--text-main); }
    .pagination-links .active { background: var(--accent); border-color: var(--accent); color: white; }

    /* ── Detail Panel Sidebar ── */
    .detail-panel {
        width: 380px; flex-shrink: 0; background: var(--bg-surface) !important; border-radius: var(--radius-lg);
        border: 1px solid var(--border); display: none; flex-direction: column; box-shadow: var(--shadow-card);
        position: sticky; top: 100px; max-height: calc(100vh - 120px); overflow-y: auto;
    }
    .detail-panel.open { display: flex; animation: slideIn 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    @keyframes slideIn { from { opacity: 0; transform: translateX(30px); } to { opacity: 1; transform: translateX(0); } }
    
    .detail-panel::-webkit-scrollbar { width: 6px; }
    .detail-panel::-webkit-scrollbar-track { background: transparent; }
    .detail-panel::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

    .dp-header {
        display: flex; justify-content: space-between; align-items: center; padding: 20px 24px;
        border-bottom: 1px solid var(--border); background: var(--bg-surface) !important; position: sticky; top: 0; z-index: 20; 
    }
    .dp-header h3 { font-size: 16px; font-weight: 700; margin: 0; color: var(--text-main); }
    .dp-close {
        width: 32px; height: 32px; border-radius: 8px; background: var(--bg-hover); border: 1px solid transparent;
        display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 16px; color: var(--text-sec); transition: 0.2s;
    }
    .dp-close:hover { background: #fef2f2; color: #ef4444; border-color: #fecaca; }
    
    .dp-body { padding: 24px; }
    .dp-order-id { font-size: 18px; font-weight: 700; color: var(--text-main); margin-bottom: 18px; letter-spacing: -0.02em; }
    .dp-row { display: flex; gap: 12px; margin-bottom: 14px; align-items: flex-start; }
    .dp-label { font-size: 13px; color: var(--text-sec); font-weight: 500; min-width: 100px; }
    .dp-value { font-size: 13.5px; color: var(--text-main); font-weight: 500; flex: 1; line-height: 1.5; }
    
    .dp-section-title { font-size: 12.5px; font-weight: 700; color: var(--text-sec); margin: 24px 0 16px; text-transform: uppercase; letter-spacing: 0.08em; }
    .dp-divider { border: none; border-top: 1px dashed #cbd5e1; margin: 20px 0; }
    
    /* Gambar Produk di Detail Panel */
    .dp-product { display: flex; align-items: center; gap: 16px; margin-bottom: 16px; }
    .dp-product-img {
        width: 48px; height: 48px; border-radius: 10px; background: var(--bg-hover);
        border: 1px solid var(--border); overflow: hidden; flex-shrink: 0;
    }
    .dp-product-img img { width: 100%; height: 100%; object-fit: cover; }
    .dp-product-name { font-size: 13.5px; font-weight: 600; color: var(--text-main); margin-bottom: 2px; }
    .dp-product-qty { font-size: 12px; font-weight: 500; color: var(--text-muted); }
    .dp-product-price { margin-left: auto; font-size: 14px; font-weight: 700; color: var(--text-main); white-space: nowrap; }
    
    .dp-total-row { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 13.5px; }
    .dp-total-row .lbl { color: var(--text-sec); font-weight: 500;}
    .dp-total-row .val { font-weight: 600; color: var(--text-main); }
    .dp-total-row.grand { margin-top: 16px; padding-top: 16px; border-top: 1px solid var(--border); }
    .dp-total-row.grand .lbl { font-weight: 700; color: var(--text-main); font-size: 14.5px; }
    .dp-total-row.grand .val { font-weight: 800; color: var(--accent); font-size: 18px; }

    .status-select {
        width: 100%; height: 44px; padding: 0 16px; border: 1px solid #cbd5e1;
        border-radius: var(--radius-md); font-size: 13.5px; font-weight: 500; color: var(--text-main);
        background: var(--bg-surface); margin-top: 16px; cursor: pointer; transition: all 0.2s;
    }
    .status-select:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15); }
    
    .btn-update {
        width: 100%; padding: 14px; background-color: var(--accent) !important; color: #ffffff !important;
        border: none; border-radius: 12px; font-size: 13.5px; font-weight: 600;
        cursor: pointer; margin-top: 16px; display: flex; align-items: center; justify-content: center; gap: 8px;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2); outline: none; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); -webkit-tap-highlight-color: transparent;
    }
    .btn-update:hover { background-color: var(--accent-dark) !important; transform: translateY(-2px); box-shadow: 0 6px 15px rgba(30, 64, 175, 0.3); }
    .btn-update:active { transform: scale(0.98); box-shadow: inset 0 2px 4px rgba(0,0,0,0.2); transition: all 0.1s; }

    /* ── RESPONSIVE MOBILE & TABLET ── */
    @media (max-width: 1200px) {
        .stats-row { grid-template-columns: repeat(3, 1fr); }
    }

    @media (max-width: 1024px) {
        .layout-order { flex-direction: column; }
        .detail-panel { width: 100%; }
        
        /* Modal Panel Mobile */
        .detail-panel.open {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            width: 100%; max-height: 100vh; z-index: 1000;
            border-radius: 0; border: none;
            animation: slideUp 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        @keyframes slideUp { from { opacity: 0; transform: translateY(100px); } to { opacity: 1; transform: translateY(0); } }
    }

    @media (max-width: 768px) {
        .filter-bar { flex-direction: column; align-items: stretch; }
        .search-box, .select-wrapper, .filter-select { max-width: 100%; width: 100%; min-width: 100%; }
        .page-header { flex-direction: column; align-items: flex-start; }
        .export-dropdown, .btn-export { width: 100%; }
        
        .stats-row { grid-template-columns: repeat(2, 1fr); }
        .stat-card { padding: 16px; border-radius: 16px; }
        .stat-label { font-size: 10.5px; }
        .stat-value { font-size: 20px; }
        .stat-icon { width: 40px; height: 40px; font-size: 20px; }
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
        <div class="breadcrumb">JayaAbadi / Pesanan</div>
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
        <a href="{{ route('admin.order.index') }}" class="action-btn" style="height: 44px; width: auto; padding: 0 16px; border-color: #fecaca; color: #ef4444; background: #fef2f2;">
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
                        <td><a href="#" class="invoice-link" onclick="showDetail({{ $index }}); return false;">{{ $order->order_number }}</a></td>
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
                        <td style="font-weight: 600;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        <td style="color: var(--text-sec); font-size: 12.5px; font-weight: 500;">{{ ucfirst($order->payment_method) }}</td>
                        <td>
                            @php
                                $statusClass = match($order->status) {
                                    'pending'   => 'status-pending',
                                    'paid'      => 'status-diproses',
                                    'shipping'  => 'status-dikirim',
                                    'delivered' => 'status-selesai',
                                    'cancelled' => 'status-dibatalkan',
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
                        <td colspan="8" style="text-align: center; padding: 48px; color: var(--text-muted);">
                            <i class="ti ti-inbox" style="font-size: 32px; display: block; margin-bottom: 8px; opacity: 0.5;"></i>
                            Belum ada pesanan yang sesuai.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination">
            <div>Menampilkan {{ $orders->firstItem() ?? 0 }} - {{ $orders->lastItem() ?? 0 }} dari {{ $orders->total() }} pesanan</div>
            <div class="pagination-links">
                @if($orders->onFirstPage())
                    <span style="opacity:0.4; border:1px solid var(--border); border-radius:8px; padding:0 10px; height:32px; display:inline-flex; align-items:center;">
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
                    <span style="opacity:0.4; border:1px solid var(--border); border-radius:8px; padding:0 10px; height:32px; display:inline-flex; align-items:center;">
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

    function doExport(format) {
        const form = document.getElementById('filterForm');
        const exportInput = document.getElementById('exportFormat');
        
        exportInput.value = format;
        form.submit();
        
        setTimeout(() => {
            exportInput.value = '';
        }, 500);
    }

    function previewAndPdf() {
        window.print();
    }

    function getStatusClass(status) {
        const map = {
            'Pending'    : 'status-pending',
            'Diproses'   : 'status-diproses',
            'Dikirim'    : 'status-dikirim',
            'Selesai'    : 'status-selesai',
            'Dibatalkan' : 'status-dibatalkan'
        };
        return map[status] || '';
    }

function showDetail(index) {
        const o = orders[index];
        const panel = document.getElementById('detailPanel');
        const body = document.getElementById('detailBody');

        const itemsHtml = o.items.map(item => {
            const productImgSrc = (item.img && item.img.startsWith('http')) ? item.img : '/storage/' + item.img;
            return `
                <div class="dp-product">
                    <div class="dp-product-img">
                        <img src="${productImgSrc}" alt="${item.nama}" onerror="this.src='https://placehold.co/100x100?text=No+Img'">
                    </div>
                    <div style="min-width: 0; flex: 1;">
                        <div class="dp-product-name" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${item.nama}</div>
                        <div class="dp-product-qty">Qty: ${item.qty}</div>
                    </div>
                    <div class="dp-product-price">${formatRupiah(item.harga)}</div>
                </div>
            `;
        }).join('');

        // ── FIX LOGIKA SALURAN: Deteksi berdasarkan awalan nomor invoice (ORD atau POS) ──
        // Cara ini paling aman karena gak bergantung 100% sama map JSON dari controller
        const isOnline = o.id.startsWith('ORD'); 
        const channelHtml = isOnline 
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
                <div class="dp-value" style="font-size:13px; line-height: 1.5; color: var(--text-sec);">${o.alamat}</div>
            </div>
            <div class="dp-row">
                <div class="dp-label">Pembayaran</div>
                <div class="dp-value" style="font-weight: 600;">${o.metode}</div>
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
        
        if (window.innerWidth <= 1024) {
            document.body.style.overflow = 'hidden';
        }
    }

    function closeDetail() {
        document.getElementById('detailPanel').classList.remove('open');
        document.body.style.overflow = '';
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
                    confirmButtonColor: '#2563eb',
                }).then(() => location.reload());
            }
        })
        .catch(() => {
            Swal.fire('Error', 'Gagal update status.', 'error');
        });
    }

    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('exportFormat').value = ''; 
            document.getElementById('filterForm').submit();
        }
    });
</script>
@endsection