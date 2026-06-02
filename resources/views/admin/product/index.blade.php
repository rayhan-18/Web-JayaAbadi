@extends('layouts.admin')

@section('title', 'Produk')

@section('styles')
<style>
    /* Premium Variables (Konsisten dengan Dashboard & Sidebar) */
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

    /* Page Header & Tambah Produk Button */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }
    .page-title h1 {
        font-size: 22px;
        font-weight: 700;
        color: var(--text-main);
        margin: 0;
        letter-spacing: -0.02em;
    }
    .page-title .breadcrumb {
        font-size: 13px;
        color: var(--text-sec);
        margin-top: 4px;
    }
    
    /* Premium Button Style - Direct Hex Colors */
    .btn-primary {
        background-color: #5c9e74 !important;
        color: #ffffff !important;
        padding: 10px 20px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: none;
        box-shadow: 0 2px 6px rgba(92, 158, 116, 0.2);
        outline: none;
        cursor: pointer;
        -webkit-tap-highlight-color: transparent;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .btn-primary:hover {
        background-color: #3a5c48 !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(58, 92, 72, 0.3);
    }

    .btn-primary:active {
        transform: scale(0.95);
        background-color: #2d4a3a !important;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.2);
        transition: all 0.1s;
    }
    .btn-primary i {
        font-size: 16px;
    }
    
    /* Modern Filter & Search Bar Layout */
    .filter-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }
    .filter-left {
        display: flex;
        align-items: center;
        flex: 1;
        min-width: 280px;
    }
    .filter-right {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    
    /* Premium Search Box */
    .search-box {
        display: flex;
        align-items: center;
        background: var(--bg-surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 0 14px;
        gap: 10px;
        width: 100%;
        max-width: 320px;
        height: 40px;
        transition: all 0.2s ease;
        box-shadow: 0 1px 2px rgba(0,0,0,0.01);
    }
    .search-box:focus-within {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(92, 158, 116, 0.15);
        background: var(--bg-surface);
    }
    .search-box i { color: var(--text-sec); font-size: 16px; }
    .search-box input {
        border: none;
        outline: none;
        font-size: 13px;
        width: 100%;
        color: var(--text-main);
        background: transparent;
    }
    .search-box input::placeholder { color: var(--text-muted); }
    
    /* Premium Dropdown/Select Custom Wrapper */
    .select-wrapper {
        position: relative;
        display: inline-block;
    }
    .select-wrapper i.prefix-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-sec);
        font-size: 15px;
        pointer-events: none;
    }
    .filter-select {
        height: 40px;
        padding: 0 36px 0 34px;
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        background: var(--bg-surface) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%237a9080' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E") no-repeat right 12px center;
        appearance: none;
        font-size: 13px;
        font-weight: 500;
        color: var(--text-main);
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 1px 2px rgba(0,0,0,0.01);
        min-width: 160px;
    }
    .filter-select:hover {
        background-color: var(--bg-hover);
        border-color: #d1d6cf;
    }
    .filter-select:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(92, 158, 116, 0.15);
    }

    /* Table Design */
    .table-wrapper {
        background: var(--bg-surface);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border);
        overflow-x: auto;
    }
    table { width: 100%; border-collapse: collapse; font-size: 13px; }
    th {
        text-align: left; padding: 14px 20px;
        background: var(--bg-hover); font-weight: 600;
        color: var(--text-sec); border-bottom: 1px solid var(--border);
        font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em;
    }
    td {
        padding: 14px 20px; border-bottom: 1px solid var(--border);
        color: var(--text-main); font-weight: 500; vertical-align: middle;
    }
    tr:last-child td { border-bottom: none; }
    tbody tr:hover { background: var(--bg-hover); }

    /* AKTIF: Style Modifikasi untuk Gambar Produk Realistis */
    .product-img {
        width: 44px; height: 44px;
        background: var(--bg-hover); 
        border: 1px solid var(--border);
        border-radius: 8px;
        overflow: hidden;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .product-img img {
        width: 100%; height: 100%;
        object-fit: cover;
        transition: transform 0.2s ease;
    }
    tbody tr:hover .product-img img {
        transform: scale(1.08); /* Efek zoom mikro saat baris tabel di-hover */
    }

    .status-badge {
        display: inline-flex; align-items: center; justify-content: center;
        padding: 4px 10px; border-radius: 6px;
        font-size: 11px; font-weight: 700; letter-spacing: 0.02em;
        background: var(--accent-light); color: var(--accent-dark);
    }
    .status-badge::before {
        content: ''; width: 6px; height: 6px; border-radius: 50%;
        background: var(--accent); margin-right: 6px;
    }

    .action-icons { display: flex; gap: 8px; }
    .action-btn {
        display: inline-flex; align-items: center; justify-content: center;
        width: 30px; height: 30px; border-radius: 8px;
        background: var(--bg-surface); border: 1px solid var(--border);
        color: var(--text-sec); text-decoration: none;
        transition: all 0.15s; font-size: 15px;
    }
    .action-btn:hover { background: var(--bg-hover); color: var(--accent); border-color: #d1d6cf; }
    .action-btn.delete:hover { color: #c47a7a; border-color: #e8caca; background: #fdf5f5; }

    .pagination {
        display: flex; justify-content: space-between; align-items: center;
        margin-top: 24px; font-size: 13px; color: var(--text-sec);
    }
    .pagination-links { display: flex; gap: 6px; }
    .pagination-links a, .pagination-links span {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 30px; height: 30px; padding: 0 10px;
        border: 1px solid var(--border); border-radius: 6px;
        text-decoration: none; color: var(--text-main); font-weight: 500;
        transition: 0.15s; background: var(--bg-surface);
    }
    .pagination-links a:hover { background: var(--bg-hover); border-color: var(--accent); color: var(--accent); }
    .pagination-links .active { background: var(--accent); border-color: var(--accent); color: white; }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1>Produk</h1>
        <div class="breadcrumb">FurniHome / Produk</div>
    </div>

    <a href="{{ route('admin.product.create') }}" class="btn-primary">
        <i class="ti ti-plus"></i> Tambah Produk
    </a>
</div>

<div class="filter-bar">
    <div class="filter-left">
        <div class="search-box">
            <i class="ti ti-search"></i>
            <input type="text" placeholder="Cari nama produk, SKU...">
        </div>
    </div>
    <div class="filter-right">
        <div class="select-wrapper">
            <i class="ti ti-category prefix-icon"></i>
            <select class="filter-select">
                <option>Semua Kategori</option>
                <option>Kursi</option>
                <option>Meja</option>
                <option>Lemari</option>
                <option>Sofa</option>
                <option>Rak</option>
                <option>Tempat Tidur</option>
            </select>
        </div>
        <div class="select-wrapper">
            <i class="ti ti-circle-check prefix-icon"></i>
            <select class="filter-select">
                <option>Semua Status</option>
                <option>Aktif</option>
                <option>Nonaktif</option>
            </select>
        </div>
    </div>
</div>

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
                <th style="width: 100px; text-align: center;">Aksi</th>
            </tr>
        </thead>
<tbody>
    @forelse($products as $index => $product)
    <tr>
        <td style="color: var(--text-sec);">{{ $products->firstItem() + $index }}</td>
        <td>
            <div style="display: flex; align-items: center; gap: 12px;">
                <div class="product-img">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                    @else
                        <img src="https://placehold.co/100x100?text=No+Image" alt="{{ $product->name }}">
                    @endif
                </div>
                <div>
                    <strong style="color: var(--text-main); font-size: 13.5px;">{{ $product->name }}</strong><br>
                    <span style="font-size:11.5px; color: var(--text-muted);">{{ $product->slug }}</span>
                </div>
            </div>
        </td>
        <td>{{ $product->category->name ?? '-' }}</td>
        <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
        <td>{{ $product->stock }}</td>
        <td>
            <span class="status-badge" style="{{ !$product->is_active ? 'background:#fdf5f5; color:#c47a7a;' : '' }}">
                {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
            </span>
        </td>
        <td class="action-icons" style="justify-content: center;">
            <a href="{{ route('admin.product.edit', $product->id) }}" class="action-btn" title="Edit">
                <i class="ti ti-edit"></i>
            </a>
            <form method="POST" action="{{ route('admin.product.destroy', $product->id) }}" onsubmit="return confirm('Hapus produk ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="action-btn delete" title="Hapus">
                    <i class="ti ti-trash"></i>
                </button>
            </form>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="7" style="text-align: center; padding: 40px; color: var(--text-muted);">
            Belum ada produk.
        </td>
    </tr>
    @endforelse
</tbody>    </table>
</div>

<div class="pagination">
    <div>Menampilkan {{ $products->firstItem() }} - {{ $products->lastItem() }} dari {{ $products->total() }} produk</div>
    <div class="pagination-links">
        @if($products->onFirstPage())
            <span style="opacity:0.4; border: 1px solid var(--border); border-radius: 6px; padding: 0 10px; height: 30px; display:inline-flex; align-items:center;">
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
            <span style="opacity:0.4; border: 1px solid var(--border); border-radius: 6px; padding: 0 10px; height: 30px; display:inline-flex; align-items:center;">
                <i class="ti ti-chevron-right" style="font-size: 16px;"></i>
            </span>
        @endif
    </div>
</div>
@endsection