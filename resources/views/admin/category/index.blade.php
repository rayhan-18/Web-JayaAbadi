@extends('layouts.admin')

@section('title', 'Kategori')

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
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 12px;
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

    /* Premium Button Style */
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
    }

    /* Filter Bar Layout Responsive */
    .filter-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }
    
    /* Search Box */
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
    }
    .search-box:focus-within {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(92, 158, 116, 0.15);
    }
    .search-box i { color: var(--text-sec); font-size: 16px; }
    .search-box input {
        border: none; outline: none; font-size: 13px;
        width: 100%; color: var(--text-main); background: transparent;
    }
    .search-box input::placeholder { color: var(--text-muted); }

    /* Select Dropdown */
    .select-wrapper { position: relative; display: inline-block; }
    .select-wrapper i.prefix-icon {
        position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
        color: var(--text-sec); font-size: 15px; pointer-events: none;
    }
    .filter-select {
        height: 40px; padding: 0 36px 0 34px;
        border: 1px solid var(--border); border-radius: var(--radius-md);
        background: var(--bg-surface) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%237a9080' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E") no-repeat right 12px center;
        appearance: none; font-size: 13px; font-weight: 500;
        color: var(--text-main); cursor: pointer; transition: all 0.2s ease;
        min-width: 160px;
    }
    .filter-select:hover { background-color: var(--bg-hover); border-color: #d1d6cf; }
    .filter-select:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px rgba(92, 158, 116, 0.15); }

    /* MOBILE RESPONSIVE TWEAKS */
    @media (max-width: 768px) {
        .filter-bar { flex-direction: column; align-items: stretch; }
        .search-box { max-width: 100%; }
        .select-wrapper, .filter-select { width: 100%; min-width: 100%; }
        .btn-primary { width: 100%; justify-content: center; }
    }

    /* Table Design Anti Pecah */
    .table-wrapper {
        background: var(--bg-surface); border-radius: var(--radius-lg);
        border: 1px solid var(--border); overflow-x: auto;
    }
    table { width: 100%; border-collapse: collapse; font-size: 13px; min-width: 800px; }
    th {
        text-align: left; padding: 14px 20px; background: var(--bg-hover);
        font-weight: 600; color: var(--text-sec); border-bottom: 1px solid var(--border);
        font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em;
    }
    td {
        padding: 14px 20px; border-bottom: 1px solid var(--border);
        color: var(--text-main); font-weight: 500; vertical-align: middle;
    }
    tr:last-child td { border-bottom: none; }
    tbody tr:hover { background: var(--bg-hover); }

    /* AKTIF: Style Modifikasi untuk Gambar Kategori */
    .category-img {
        width: 44px; height: 44px;
        background: var(--bg-hover); 
        border: 1px solid var(--border);
        border-radius: 8px;
        overflow: hidden;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto;
    }
    .category-img img {
        width: 100%; height: 100%;
        object-fit: cover;
        transition: transform 0.2s ease;
    }
    tbody tr:hover .category-img img {
        transform: scale(1.08); /* Efek zoom halus */
    }

    /* Badges */
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
    .status-badge.nonaktif {
        background: #fdf5f5; color: #c47a7a;
    }
    .status-badge.nonaktif::before { background: #c47a7a; }

    /* Action Buttons */
    .action-icons { display: flex; gap: 8px; justify-content: center; }
    .action-btn {
        display: inline-flex; align-items: center; justify-content: center;
        width: 32px; height: 32px; border-radius: 8px;
        background: var(--bg-surface); border: 1px solid var(--border);
        color: var(--text-sec); text-decoration: none;
        transition: all 0.15s; font-size: 15px;
    }
    .action-btn:hover { background: var(--bg-hover); color: var(--accent); border-color: #d1d6cf; }
    .action-btn.delete:hover { color: #c47a7a; border-color: #e8caca; background: #fdf5f5; }

    /* Pagination */
    .pagination {
        display: flex; justify-content: space-between; align-items: center;
        margin-top: 24px; font-size: 13px; color: var(--text-sec); flex-wrap: wrap; gap: 12px;
    }
    .pagination-links { display: flex; gap: 6px; }
    .pagination-links span, .pagination-links a {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 30px; height: 30px; padding: 0 10px;
        border: 1px solid var(--border); border-radius: 6px;
        text-decoration: none; color: var(--text-main); font-weight: 500;
        transition: 0.15s; background: var(--bg-surface);
    }
    .pagination-links .active { background: var(--accent); border-color: var(--accent); color: white; }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1>Kategori</h1>
        <div class="breadcrumb">FurniHome / Kategori</div>
    </div>
    <a href="{{ route('admin.category.create') }}" class="btn-primary">
        <i class="ti ti-plus"></i> Tambah Kategori
    </a>
</div>

<form method="GET" action="{{ route('admin.category.index') }}" id="filter-form">
<div class="filter-bar">
    <div class="search-box">
        <i class="ti ti-search"></i>
        <input 
            type="text" 
            name="search"
            placeholder="Cari kategori..."
            value="{{ request('search') }}"
            oninput="debounceSubmit()"
        >
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
</form>

<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th style="width: 50px;">#</th>
                <th style="width: 80px; text-align: center;">Gambar</th>
                <th>Kategori</th>
                <th style="width: 35%;">Deskripsi</th>
                <th>Total Produk</th>
                <th>Status</th>
                <th style="width: 100px; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $item)
            <tr>
            <td style="color: var(--text-sec);">
                {{ $categories->firstItem() + $loop->index }}
            </td>
                <td>
                    <div class="category-img">
                        <img src="{{ $item->image ?? 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=100&auto=format&fit=crop&q=60' }}" alt="{{ $item->name }}">
                    </div>
                </td>

                <td>
                    <strong style="color: var(--text-main); font-size: 13.5px;">
                        {{ $item->name }}
                    </strong>
                </td>

                <td style="color: var(--text-sec); font-size: 12.5px; line-height: 1.4;">
                    {{ $item->description }}
                </td>

                <td>
                    <strong>{{ $item->products_count ?? 0 }}</strong>
                    <span style="color: var(--text-sec); font-size: 12px;">Item</span>
                </td>

                <td>
                    <span class="status-badge {{ !$item->is_active ? 'nonaktif' : '' }}">
                        {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>

                <td class="action-icons">
                    <a href="{{ route('admin.category.edit', $item->id) }}" class="action-btn" title="Edit">
                        <i class="ti ti-edit"></i>
                    </a>
                    <a href="#" class="action-btn delete" title="Hapus">
                        <i class="ti ti-trash"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

            <div class="pagination">
                <div>Menampilkan {{ $categories->firstItem() }} - {{ $categories->lastItem() }} dari {{ $categories->total() }} kategori</div>
                <div class="pagination-links">
                    @if($categories->onFirstPage())
                        <span style="opacity:0.4;"><i class="ti ti-chevron-left" style="font-size: 16px;"></i></span>
                    @else
                        <a href="{{ $categories->previousPageUrl() }}"><i class="ti ti-chevron-left" style="font-size: 16px;"></i></a>
                    @endif

                    @foreach($categories->getUrlRange(1, $categories->lastPage()) as $page => $url)
                        @if($page == $categories->currentPage())
                            <span class="active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if($categories->hasMorePages())
                        <a href="{{ $categories->nextPageUrl() }}"><i class="ti ti-chevron-right" style="font-size: 16px;"></i></a>
                    @else
                        <span style="opacity:0.4;"><i class="ti ti-chevron-right" style="font-size: 16px;"></i></span>
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