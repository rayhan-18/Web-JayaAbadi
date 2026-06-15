@extends('layouts.admin')

@section('title', 'Produk')

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
    }

    body { color: var(--text-main); background: var(--bg-body); }

    /* ── Page Header & Buttons ── */
    .page-header {
        display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; flex-wrap: wrap; gap: 16px;
    }
    .page-title h1 {
        font-size: 24px; font-weight: 700; color: var(--text-main); margin: 0; letter-spacing: -0.02em;
    }
    .page-title .breadcrumb {
        font-size: 13.5px; color: var(--text-sec); margin-top: 4px;
    }
    
    .btn-primary {
        background-color: var(--accent) !important; color: #ffffff !important;
        padding: 12px 20px; border-radius: 12px; font-size: 13.5px; font-weight: 600;
        text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        border: none; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2); outline: none; cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .btn-primary:hover { background-color: var(--accent-dark) !important; transform: translateY(-2px); box-shadow: 0 6px 15px rgba(30, 64, 175, 0.3); }
    .btn-primary:active { transform: scale(0.97); }
    .btn-primary i { font-size: 18px; }

    .btn-danger {
        background-color: #ef4444 !important; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
    }
    .btn-danger:hover { background-color: #dc2626 !important; box-shadow: 0 6px 15px rgba(220, 38, 38, 0.3); }
    
    /* ── Filter & Search Bar ── */
    .filter-bar {
        display: flex; justify-content: space-between; align-items: center; gap: 16px; margin-bottom: 24px; flex-wrap: wrap;
    }
    .filter-left { display: flex; align-items: center; flex: 1; min-width: 280px; }
    .filter-right { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
    
    .search-box {
        display: flex; align-items: center; background: var(--bg-surface);
        border: 1px solid var(--border); border-radius: var(--radius-md);
        padding: 0 16px; gap: 10px; width: 100%; max-width: 360px; height: 44px;
        transition: all 0.2s ease; box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }
    .search-box:focus-within { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15); }
    .search-box i { color: var(--text-sec); font-size: 18px; }
    .search-box input { border: none; outline: none; font-size: 13.5px; width: 100%; color: var(--text-main); background: transparent; }
    .search-box input::placeholder { color: var(--text-muted); }
    
    .select-wrapper { position: relative; display: inline-block; }
    .select-wrapper i.prefix-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--text-sec); font-size: 16px; pointer-events: none; }
    .filter-select {
        height: 44px; padding: 0 36px 0 40px; border: 1px solid var(--border); border-radius: var(--radius-md);
        background: var(--bg-surface) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23475569' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E") no-repeat right 12px center;
        appearance: none; font-size: 13.5px; font-weight: 500; color: var(--text-main); cursor: pointer; transition: all 0.2s ease; box-shadow: 0 2px 10px rgba(0,0,0,0.02); min-width: 170px;
    }
    .filter-select:hover { border-color: #cbd5e1; }
    .filter-select:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15); }

    /* ── Table Design (Responsive Scroll) ── */
    .table-wrapper {
        background: var(--bg-surface); border-radius: var(--radius-lg); border: 1px solid var(--border); box-shadow: var(--shadow-card);
        width: 100%; display: block; overflow-x: auto; -webkit-overflow-scrolling: touch; padding-bottom: 4px;
    }
    .table-wrapper::-webkit-scrollbar { height: 6px; }
    .table-wrapper::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

    table { width: 100%; border-collapse: collapse; font-size: 13.5px; min-width: 800px; }
    th {
        text-align: left; padding: 16px 24px; background: rgba(0,0,0,0.015); font-weight: 600;
        color: var(--text-sec); border-bottom: 1px solid var(--border); font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap;
    }
    td { padding: 16px 24px; border-bottom: 1px solid var(--border); color: var(--text-main); font-weight: 500; vertical-align: middle; white-space: nowrap; transition: background 0.2s; }
    tr:last-child td { border-bottom: none; }
    tbody tr:hover { background: var(--bg-hover); }

    .product-img {
        width: 48px; height: 48px; background: var(--bg-hover); border: 1px solid var(--border);
        border-radius: 10px; overflow: hidden; display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .product-img img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease; }
    tbody tr:hover .product-img img { transform: scale(1.1); }

    .status-badge {
        display: inline-flex; align-items: center; justify-content: center; padding: 6px 12px; border-radius: 8px;
        font-size: 11px; font-weight: 700; letter-spacing: 0.04em;
    }
    .status-badge.aktif { background: #ecfdf5; color: #059669; }
    .status-badge.aktif::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: #10b981; margin-right: 6px; }
    .status-badge.nonaktif { background: #fef2f2; color: #dc2626; }
    .status-badge.nonaktif::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: #ef4444; margin-right: 6px; }

    .action-icons { display: flex; gap: 8px; }
    .action-btn {
        display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 8px;
        background: var(--bg-surface); border: 1px solid #e2e8f0; color: var(--text-sec); text-decoration: none; transition: all 0.2s; font-size: 16px; cursor: pointer;
    }
    .action-btn:hover { background: var(--bg-hover); color: var(--accent); border-color: var(--accent); }
    .action-btn.delete:hover { color: #ef4444; border-color: #ef4444; background: #fef2f2; }

    /* ── Pagination ── */
    .pagination { display: flex; justify-content: space-between; align-items: center; margin-top: 24px; font-size: 13.5px; font-weight: 500; color: var(--text-sec); flex-wrap: wrap; gap: 12px; }
    .pagination-links { display: flex; gap: 6px; }
    .pagination-links a, .pagination-links span {
        display: inline-flex; align-items: center; justify-content: center; min-width: 34px; height: 34px; padding: 0 10px;
        border: 1px solid var(--border); border-radius: 8px; text-decoration: none; color: var(--text-main); font-weight: 600; transition: 0.2s; background: var(--bg-surface);
    }
    .pagination-links a:hover { background: var(--bg-hover); border-color: #cbd5e1; }
    .pagination-links .active { background: var(--accent); border-color: var(--accent); color: white; }

    /* ── Mobile Responsive ── */
    @media (max-width: 768px) {
        .page-header { flex-direction: column; align-items: flex-start; gap: 16px; margin-bottom: 20px; }
        .page-header > div:last-child { width: 100%; display: grid !important; grid-template-columns: 1fr 1fr; gap: 10px; }
        .btn-primary { width: 100%; padding: 12px 10px; font-size: 12.5px; }
        
        .filter-bar { flex-direction: column; align-items: stretch; gap: 12px; }
        .filter-left, .filter-right, .search-box, .select-wrapper, .filter-select { width: 100%; max-width: 100%; min-width: 100%; }
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1>Produk</h1>
        <div class="breadcrumb">FurniHome / Produk</div>
    </div>
    <div style="display: flex; gap: 10px; align-items: center;">
        <a href="{{ route('admin.product.export.pdf', request()->query()) }}" target="_blank" class="btn-primary btn-danger">
            <i class="ti ti-file-type-pdf"></i> Export PDF
        </a>
        <a href="{{ route('admin.product.create') }}" class="btn-primary">
            <i class="ti ti-plus"></i> Tambah Produk
        </a>
    </div>
</div>

<form method="GET" action="{{ route('admin.product.index') }}" id="filter-form">
<div class="filter-bar">
    <div class="filter-left">
        <div class="search-box">
            <i class="ti ti-search"></i>
            <input type="text" name="search" placeholder="Cari nama produk, SKU..." value="{{ request('search') }}" oninput="debounceSubmit()">
        </div>
    </div>
    <div class="filter-right">
        <div class="select-wrapper">
            <i class="ti ti-category prefix-icon"></i>
            <select class="filter-select" name="category" onchange="this.form.submit()">
                <option value="">Semua Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="select-wrapper">
            <i class="ti ti-circle-check prefix-icon"></i>
            <select class="filter-select" name="status" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>
    </div>
</div>
</form>

<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th style="width: 50px;">#</th>
                <th>Produk</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Status</th>
                <th style="text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $index => $product)
            <tr>
                <td style="color: var(--text-sec);">{{ $products->firstItem() + $index }}</td>
                <td>
                    <div style="display: flex; align-items: center; gap: 14px;">
                        <div class="product-img">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                            @else
                                <img src="https://placehold.co/100x100?text=No+Image" alt="{{ $product->name }}">
                            @endif
                        </div>
                        <div>
                            <strong style="color: var(--text-main); font-size: 14px;">{{ $product->name }}</strong><br>
                            <span style="font-size:12px; font-weight: 500; color: var(--text-muted);">{{ $product->slug }}</span>
                        </div>
                    </div>
                </td>
                <td>{{ $product->category->name ?? '-' }}</td>
                <td style="font-weight: 700;">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                <td style="font-weight: 600;">{{ $product->stock }}</td>
                <td>
                    <span class="status-badge {{ $product->is_active ? 'aktif' : 'nonaktif' }}">
                        {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td style="text-align: center;">
                    <div class="action-icons" style="justify-content: center;">
                        <a href="{{ route('admin.product.edit', $product->id) }}" class="action-btn" title="Edit"><i class="ti ti-edit"></i></a>
                        <form method="POST" action="{{ route('admin.product.destroy', $product->id) }}" onsubmit="return confirm('Hapus produk ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="action-btn delete" title="Hapus"><i class="ti ti-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 48px; color: var(--text-muted);">Belum ada produk.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="pagination">
    <div>Menampilkan {{ $products->firstItem() }} - {{ $products->lastItem() }} dari {{ $products->total() }} produk</div>
    <div class="pagination-links">
        @if($products->onFirstPage())
            <span style="opacity:0.4; border: 1px solid var(--border); border-radius: 8px; padding: 0 10px; height: 34px; display:inline-flex; align-items:center;">
                <i class="ti ti-chevron-left" style="font-size: 16px;"></i>
            </span>
        @else
            <a href="{{ $products->previousPageUrl() }}"><i class="ti ti-chevron-left" style="font-size: 16px;"></i></a>
        @endif

        @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
            @if($page == $products->currentPage())
                <span class="active">{{ $page }}</span>
            @else
                <a href="{{ $url }}">{{ $page }}</a>
            @endif
        @endforeach

        @if($products->hasMorePages())
            <a href="{{ $products->nextPageUrl() }}"><i class="ti ti-chevron-right" style="font-size: 16px;"></i></a>
        @else
            <span style="opacity:0.4; border: 1px solid var(--border); border-radius: 8px; padding: 0 10px; height: 34px; display:inline-flex; align-items:center;">
                <i class="ti ti-chevron-right" style="font-size: 16px;"></i>
            </span>
        @endif
    </div>
</div>

<script>
    let debounceTimer;
    function debounceSubmit() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            document.getElementById('filter-form').submit();
        }, 400);
    }
</script>
@endsection