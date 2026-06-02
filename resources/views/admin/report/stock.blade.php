@extends('layouts.admin')

@section('title', 'Laporan Stok')

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
        display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;
    }
    .page-title h1 {
        font-size: 22px; font-weight: 700; color: var(--text-main); margin: 0; letter-spacing: -0.02em;
    }
    .page-title .breadcrumb {
        font-size: 13px; color: var(--text-sec); margin-top: 4px;
    }

    /* Stats Grid */
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
    
    /* Icon Colors */
    .stat-card.total .stat-icon   { background: transparent; color: #5c7b9e; }
    .stat-card.low .stat-icon     { background: transparent; color: #b89247; }
    .stat-card.empty .stat-icon   { background: transparent; color: #c47a7a; }
    .stat-card.value .stat-icon   { background: transparent; color: var(--accent); }

    .stat-label { font-size: 12.5px; font-weight: 600; color: var(--text-sec); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.02em; }
    .stat-value { font-size: 24px; font-weight: 700; color: var(--text-main); letter-spacing: -0.02em; }
    
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

    /* Export Dropdown */
    .export-dropdown { position: relative; display: inline-block; }
    .btn-export {
        background: var(--bg-surface); color: var(--text-main); border: 1px solid var(--border);
        padding: 0 16px; height: 40px; border-radius: var(--radius-md); font-size: 13px; font-weight: 600;
        display: inline-flex; align-items: center; gap: 8px; cursor: pointer; transition: 0.2s;
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
    .export-dropdown-content a:hover { background: var(--bg-hover); color: var(--accent); }
    .export-dropdown:hover .export-dropdown-content { display: block; }

    /* Table Design */
    .table-wrapper { background: var(--bg-surface); border-radius: var(--radius-lg); border: 1px solid var(--border); overflow-x: auto; margin-bottom: 24px; }
    table { width: 100%; border-collapse: collapse; font-size: 13px; min-width: 800px; }
    th {
        text-align: left; padding: 14px 20px; background: var(--bg-hover); font-weight: 600;
        color: var(--text-sec); border-bottom: 1px solid var(--border); font-size: 12px;
        text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap;
    }
    td { padding: 14px 20px; border-bottom: 1px solid var(--border); vertical-align: middle; color: var(--text-main); }
    tr:last-child td { border-bottom: none; }
    tbody tr:hover { background: var(--bg-hover); }

    .product-info { display: flex; align-items: center; gap: 12px; }
    .product-img {
        width: 44px; height: 44px; border-radius: 8px; background: var(--bg-hover);
        border: 1px solid var(--border); display: flex; align-items: center; justify-content: center;
        font-size: 20px; color: var(--text-sec); flex-shrink: 0;
    }
    .product-details { display: flex; flex-direction: column; }
    .product-name { font-weight: 600; color: var(--text-main); font-size: 13.5px; }
    .product-sku { font-size: 11.5px; color: var(--text-muted); margin-top: 2px; font-family: monospace; }

    /* Stock Visual Indicator */
    .stock-container { width: 100%; max-width: 120px; }
    .stock-text { font-weight: 700; font-size: 14px; margin-bottom: 4px; display: inline-block; }
    .stock-progress { width: 100%; height: 6px; background: #f0f2ef; border-radius: 4px; overflow: hidden; }
    .stock-bar { height: 100%; border-radius: 4px; }
    
    .status-aman .stock-text { color: var(--accent); }
    .status-aman .stock-bar { background: var(--accent); }
    
    .status-menipis .stock-text { color: #b89247; }
    .status-menipis .stock-bar { background: #d99e52; }
    
    .status-habis .stock-text { color: #c47a7a; }
    .status-habis .stock-bar { background: transparent; width: 0% !important; }

    /* Badges Status */
    .status-badge {
        display: inline-flex; align-items: center; justify-content: center;
        padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; letter-spacing: 0.02em; white-space: nowrap;
    }
    .badge-aman { background: var(--accent-light); color: var(--accent-dark); }
    .badge-menipis { background: #fcf6e8; color: #b89247; }
    .badge-habis { background: #fdf5f5; color: #c47a7a; }
    
    .action-btn {
        padding: 6px 12px; border-radius: 6px; background: var(--bg-surface);
        border: 1px solid var(--border); display: inline-flex; align-items: center; justify-content: center;
        cursor: pointer; color: var(--text-main); font-size: 12px; font-weight: 600; transition: 0.15s; gap: 6px;
        text-decoration: none;
    }
    .action-btn:hover { background: var(--bg-hover); border-color: #d1d6cf; color: var(--accent); }

    /* ==========================================================================
       CSS PRINT / PREVIEW PDF LOGIC (Menyembunyikan elemen admin saat cetak PDF)
       ========================================================================== */
    @media print {
        /* Sembunyikan Sidebar & Navbar bawaan layout admin */
        aside, nav, header, .sidebar, .main-header, .breadcrumb { display: none !important; }
        
        /* Sembunyikan Form Filter, Tombol Reset, dan Kolom Aksi Tabel */
        #filterForm, .action-btn, th:last-child, td:last-child { display: none !important; }
        
        /* Sesuaikan layout utama agar melebar penuh di kertas PDF */
        body, .content-wrapper, main, .container {
            background: #fff !important; color: #000 !important;
            padding: 0 !important; margin: 0 !important; width: 100% !important; max-width: 100% !important;
        }
        .table-wrapper { border: none !important; box-shadow: none !important; overflow: visible !important; }
        table { min-width: 100% !important; width: 100% !important; }
        th { background: #f5f5f5 !important; color: #000 !important; border-bottom: 2px solid #000 !important; }
        td { border-bottom: 1px solid #ccc !important; }
        .stock-progress { display: none !important; } /* Sembunyikan Progress bar di kertas */
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1>Laporan Stok Gudang</h1>
        <div class="breadcrumb">FurniHome / Laporan / Stok</div>
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
        <div class="stat-value" style="color: #b89247;">{{ number_format($stats['low_stock'] ?? 0, 0, ',', '.') }}</div>
    </div>
    <div class="stat-card empty">
        <div class="stat-icon"><i class="ti ti-xbox-x"></i></div>
        <div class="stat-label">Stok Habis</div>
        <div class="stat-value" style="color: #c47a7a;">{{ number_format($stats['empty_stock'] ?? 0, 0, ',', '.') }}</div>
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
        <a href="{{ route('admin.report.stock') }}" class="action-btn" style="height: 40px; padding: 0 16px; border-color: #c47a7a; color: #c47a7a;">
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
                    <td style="font-weight: 500;">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
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
                            <i class="ti ti-adjustments"></i> Update
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-muted);">Tidak ada data produk yang cocok dengan filter.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
    // Submit otomatis saat menekan enter pada input text search
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('exportFormat').value = ''; 
            document.getElementById('filterForm').submit();
        }
    });

    // Menjalankan fungsi export direct file Excel
    function doExport(format) {
        const form = document.getElementById('filterForm');
        const exportInput = document.getElementById('exportFormat');
        
        exportInput.value = format;
        form.submit();
        
        setTimeout(() => {
            exportInput.value = '';
        }, 500);
    }

    // FUNGSI BARU: Membuka Print Preview Browser untuk disimpan ke PDF
    function previewAndPdf() {
        // Otomatis memicu fungsi print preview bawaan windows/browser
        window.print();
    }
</script>
@endsection