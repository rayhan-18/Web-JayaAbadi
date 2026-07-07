@extends('layouts.admin')

@section('title', 'Laporan Stok')

@section('styles')
<style>
    /* ── Premium Variables (Tema: Royal Blue & Minimalist Slate) ── */
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
        /* Shadow lebih tipis, tajam, dan elegan */
        --shadow-card: 0 2px 4px -1px rgba(15, 23, 42, 0.03), 0 1px 2px -1px rgba(15, 23, 42, 0.02);
        --shadow-hover: 0 10px 20px -5px rgba(15, 23, 42, 0.05);
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
        display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px;
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
    
    /* Icon Colors */
    .stat-card.total .stat-icon   { background: #eff6ff; color: var(--accent); }
    .stat-card.low .stat-icon     { background: #fffbeb; color: #f59e0b; }
    .stat-card.empty .stat-icon   { background: #fef2f2; color: #ef4444; }
    .stat-card.value .stat-icon   { background: #f3e8ff; color: #8b5cf6; }

    .stat-label { font-size: 12px; font-weight: 600; color: var(--text-sec); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em; }
    .stat-value { font-size: 26px; font-weight: 700; color: var(--text-main); letter-spacing: -0.02em; line-height: 1.1; }
    
    /* ── Filters ── */
    .filter-bar { display: flex; gap: 12px; margin-bottom: 24px; flex-wrap: wrap; align-items: center; }
    .search-box {
        display: flex; align-items: center; background: var(--bg-surface);
        border: 1px solid var(--border); border-radius: var(--radius-md);
        padding: 0 16px; gap: 10px; flex: 1; max-width: 320px; height: 44px; transition: all 0.2s;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }
    .search-box:focus-within { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15); }
    .search-box i { color: var(--text-sec); font-size: 18px; flex-shrink: 0; }
    .search-box input { border: none; outline: none; font-size: 13.5px; width: 100%; color: var(--text-main); background: transparent; padding: 0; }
    .search-box input::placeholder { color: var(--text-muted); }
    
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

    /* Export Dropdown */
    .export-dropdown { position: relative; display: inline-block; }
    .btn-export {
        background: var(--bg-surface); color: var(--text-main); border: 1px solid var(--border); box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        padding: 0 16px; height: 44px; border-radius: var(--radius-md); font-size: 13.5px; font-weight: 600;
        display: inline-flex; align-items: center; justify-content: center; gap: 8px; cursor: pointer; transition: 0.2s;
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

    /* ── Table Design ── */
    .table-wrapper { 
        background: var(--bg-surface); border-radius: var(--radius-lg); border: 1px solid var(--border); 
        box-shadow: var(--shadow-card); overflow-x: auto; -webkit-overflow-scrolling: touch; margin-bottom: 24px; padding-bottom: 4px;
    }
    .table-wrapper::-webkit-scrollbar { height: 6px; }
    .table-wrapper::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

    table { width: 100%; border-collapse: collapse; font-size: 13.5px; min-width: 850px; }
    th {
        text-align: left; padding: 16px 24px; background: rgba(0,0,0,0.015); font-weight: 600;
        color: var(--text-sec); border-bottom: 1px solid var(--border); font-size: 12px;
        text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap;
    }
    td { padding: 16px 24px; border-bottom: 1px solid var(--border); vertical-align: middle; color: var(--text-main); white-space: nowrap; transition: background 0.2s; }
    tr:last-child td { border-bottom: none; }
    tbody tr:hover { background: var(--bg-hover); }

    .product-info { display: flex; align-items: center; gap: 14px; }
    .product-img {
        width: 48px; height: 48px; border-radius: 10px; background: var(--bg-hover);
        border: 1px solid var(--border); display: flex; align-items: center; justify-content: center;
        font-size: 20px; color: var(--text-sec); flex-shrink: 0;
    }
    .product-details { display: flex; flex-direction: column; }
    .product-name { font-weight: 600; color: var(--text-main); font-size: 13.5px; }
    .product-sku { font-size: 11.5px; color: var(--text-muted); margin-top: 2px; font-family: monospace; }

    /* Stock Visual Indicator */
    .stock-container { width: 100%; max-width: 120px; }
    .stock-text { font-weight: 700; font-size: 14px; margin-bottom: 6px; display: inline-block; }
    .stock-progress { width: 100%; height: 6px; background: #f1f5f9; border-radius: 4px; overflow: hidden; }
    .stock-bar { height: 100%; border-radius: 4px; transition: width 0.5s ease; }
    
    .status-aman .stock-text { color: #059669; }
    .status-aman .stock-bar { background: #10b981; }
    
    .status-menipis .stock-text { color: #d97706; }
    .status-menipis .stock-bar { background: #f59e0b; }
    
    .status-habis .stock-text { color: #dc2626; }
    .status-habis .stock-bar { background: transparent; width: 0% !important; }

    /* Badges Status */
    .status-badge {
        display: inline-flex; align-items: center; justify-content: center;
        padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 700; letter-spacing: 0.04em; white-space: nowrap;
    }
    .badge-aman { background: #ecfdf5; color: #059669; }
    .badge-menipis { background: #fffbeb; color: #d97706; }
    .badge-habis { background: #fef2f2; color: #dc2626; }
    
    /* ── Tombol Aksi Premium (Solid Light Blue Default) ── */
    .action-btn {
        padding: 8px 16px; 
        border-radius: 10px; 
        background: var(--accent-light);
        border: 1px solid transparent; 
        display: inline-flex; 
        align-items: center; 
        justify-content: center;
        cursor: pointer; 
        color: var(--accent); 
        font-size: 12.5px; 
        font-weight: 600; 
        transition: all 0.2s ease; 
        gap: 6px; 
        text-decoration: none;
        box-shadow: none;
        width: max-content !important; 
        height: auto !important;
        white-space: nowrap;
    }
    .action-btn i { font-size: 16px; transition: transform 0.3s ease; }
    
    .action-btn:hover { 
        background: var(--accent); 
        color: #ffffff; 
        transform: translateY(-1px); 
        box-shadow: 0 4px 10px rgba(37, 99, 235, 0.15); 
    }
    .action-btn:hover i { transform: rotate(15deg); }
    .action-btn:active { transform: scale(0.96); box-shadow: 0 2px 8px rgba(37, 99, 235, 0.1); }

    /* ── RESPONSIVE MOBILE & TABLET ── */
    @media (max-width: 1024px) {
        .stats-row { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 768px) {
        .page-header { flex-direction: column; align-items: flex-start; gap: 16px; margin-bottom: 20px; }
        .filter-bar { flex-direction: column; align-items: stretch; gap: 12px; }
        .search-box, .select-wrapper, .filter-select { max-width: 100%; width: 100%; min-width: 100%; }
        .export-dropdown, .btn-export { width: 100%; justify-content: center; }
        
        .stat-card { padding: 16px; border-radius: 16px; }
        .stat-label { font-size: 10.5px; }
        .stat-value { font-size: 20px; }
    }
    @media (max-width: 480px) {
        .stats-row { grid-template-columns: 1fr; }
    }

    /* ==========================================================================
       CSS PRINT / PREVIEW PDF LOGIC
       ========================================================================== */
    @media print {
        aside, nav, header, .sidebar, .main-header, .breadcrumb, #filterForm, .action-btn, th:last-child, td:last-child { display: none !important; }
        body, .content-wrapper, main, .container { background: #fff !important; color: #000 !important; padding: 0 !important; margin: 0 !important; width: 100% !important; max-width: 100% !important; }
        .table-wrapper { border: none !important; box-shadow: none !important; overflow: visible !important; }
        table { min-width: 100% !important; width: 100% !important; }
        th { background: #f5f5f5 !important; color: #000 !important; border-bottom: 2px solid #000 !important; }
        td { border-bottom: 1px solid #ccc !important; }
        .stock-progress { display: none !important; }
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1>Laporan Stok Gudang</h1>
        <div class="breadcrumb">JayaAbadi / Laporan / Stok</div>
    </div>
</div>

<div class="stats-row">
    <div class="stat-card total">
        <div class="stat-icon"><i class="ti ti-packages"></i></div>
        <div class="stat-label">Total SKU Produk</div>
        <div class="stat-value">{{ number_format($stats['total_sku'] ?? 0, 0, ',', '.') }}</div>
    </div>
    <div class="stat-card low">
        <div class="stat-icon"><i class="ti ti-alert-triangle"></i></div>
        <div class="stat-label">Stok Menipis ( < 10 )</div>
        <div class="stat-value" style="color: #d97706;">{{ number_format($stats['low_stock'] ?? 0, 0, ',', '.') }}</div>
    </div>
    <div class="stat-card empty">
        <div class="stat-icon"><i class="ti ti-xbox-x"></i></div>
        <div class="stat-label">Stok Habis</div>
        <div class="stat-value" style="color: #dc2626;">{{ number_format($stats['empty_stock'] ?? 0, 0, ',', '.') }}</div>
    </div>
    <div class="stat-card value">
        <div class="stat-icon"><i class="ti ti-report-money"></i></div>
        <div class="stat-label">Estimasi Nilai Stok</div>
        <div class="stat-value">Rp {{ number_format(($stats['value'] ?? 0), 0, ',', '.') }}</div>
    </div>
</div>

<form action="{{ route('admin.report.stock') }}" method="GET" class="filter-bar" id="filterForm">
    
    <div class="search-box">
        <i class="ti ti-search"></i>
        <input type="text" name="search" id="searchInput" placeholder="Cari Nama Produk atau SKU..." value="{{ request('search') }}" onchange="document.getElementById('filterForm').submit()">
    </div>
    
    <div class="select-wrapper">
        <i class="ti ti-category prefix-icon"></i>
        <select class="filter-select" name="category_id" onchange="document.getElementById('filterForm').submit()">
            <option value="">Semua Kategori</option>
            @foreach($categories ?? [] as $category)
                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>
    
    <div class="select-wrapper">
        <i class="ti ti-filter prefix-icon"></i>
        <select class="filter-select" name="stock_status" onchange="document.getElementById('filterForm').submit()">
            <option value="">Semua Status Stok</option>
            <option value="aman" {{ request('stock_status') == 'aman' ? 'selected' : '' }}>Stok Aman</option>
            <option value="menipis" {{ request('stock_status') == 'menipis' ? 'selected' : '' }}>Stok Menipis</option>
            <option value="habis" {{ request('stock_status') == 'habis' ? 'selected' : '' }}>Stok Habis</option>
        </select>
    </div>

    <div class="export-dropdown">
        <button type="button" class="btn-export"><i class="ti ti-download"></i> Export Data <i class="ti ti-chevron-down" style="font-size: 14px;"></i></button>
        <div class="export-dropdown-content">
            <a href="#" onclick="doExport('excel')"><i class="ti ti-file-spreadsheet"></i> Export Excel</a>
            <a href="#" onclick="previewAndPdf()"><i class="ti ti-file-text"></i> Preview & Cetak PDF</a>
        </div>
    </div>

    <input type="hidden" name="export" id="exportFormat" value="">

    @if(request('search') || request('category_id') || request('stock_status'))
        <a href="{{ route('admin.report.stock') }}" class="action-btn" style="height: 44px; padding: 0 16px; border-color: #fecaca; color: #ef4444; background: #fef2f2; border-radius: var(--radius-md);">
            <i class="ti ti-refresh"></i> Reset Filter
        </a>
    @endif
</form>

<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th>Kategori</th>
                <th>Harga Jual</th>
                <th style="width: 140px;">Sisa Stok</th>
                <th>Status</th>
                <th style="text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products ?? [] as $product)
                @php
                    $slugKategori = Str::slug($product->category->name ?? 'default');
                    $icon = match($slugKategori) {
                        'sofa' => 'ti-sofa',
                        'meja' => 'ti-desk',
                        'lemari' => 'ti-door',
                        'kursi' => 'ti-armchair',
                        'rak' => 'ti-books',
                        'tempat-tidur' => 'ti-bed',
                        default => 'ti-package'
                    };

                    if ($product->stock >= 10) {
                        $statusText = 'Aman';
                        $statusClass = 'status-aman';
                        $badgeClass = 'badge-aman';
                        $percentage = min(($product->stock / 50) * 100, 100); 
                    } elseif ($product->stock > 0 && $product->stock < 10) {
                        $statusText = 'Menipis';
                        $statusClass = 'status-menipis';
                        $badgeClass = 'badge-menipis';
                        $percentage = ($product->stock / 10) * 100;
                    } else {
                        $statusText = 'Habis';
                        $statusClass = 'status-habis';
                        $badgeClass = 'badge-habis';
                        $percentage = 0;
                    }
                @endphp
                <tr>
                    <td>
                        <div class="product-info">
                            <div class="product-img"><i class="ti {{ $icon }}"></i></div>
                            <div class="product-details">
                                <div class="product-name">{{ $product->name }}</div>
                                <div class="product-sku">SKU: {{ $product->sku ?? 'PROD-'.$product->id }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="color: var(--text-sec);">{{ $product->category->name ?? '-' }}</td>
                    <td style="font-weight: 600;">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td>
                        <div class="stock-container {{ $statusClass }}">
                            <span class="stock-text">{{ $product->stock }} Unit</span>
                            <div class="stock-progress">
                                <div class="stock-bar" style="width: {{ $percentage }}%;"></div>
                            </div>
                        </div>
                    </td>
                    <td><span class="status-badge {{ $badgeClass }}">{{ $statusText }}</span></td>
                    <td style="text-align: center;">
                        <a href="{{ route('admin.product.edit', $product->id) }}" class="action-btn">
                            <i class="ti ti-edit"></i> Update
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 48px; color: var(--text-muted);">
                        <i class="ti ti-inbox" style="font-size: 32px; display: block; margin-bottom: 8px; opacity: 0.5;"></i>
                        Tidak ada data produk yang cocok dengan filter.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('exportFormat').value = ''; 
            document.getElementById('filterForm').submit();
        }
    });

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
</script>
@endsection