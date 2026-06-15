@extends('layouts.admin')

@section('title', 'Kategori')

@section('styles')
<style>
    /* ── Reset & Premium Variables ── */
    :root {
        --accent: #2563eb; 
        --accent-dark: #1e40af;
        --border: rgba(15, 23, 42, 0.06);
        --bg-body: #f8fafc;
        --bg-surface: #ffffff;
        --bg-hover: #f1f5f9;
        --text-main: #0f172a;
        --text-sec: #475569;
        --radius-md: 14px;
        --radius-lg: 20px;
        --shadow-card: 0 10px 30px -10px rgba(15, 23, 42, 0.05);
    }

    body { color: var(--text-main); background: var(--bg-body); }

    /* ── Page Header ── */
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; flex-wrap: wrap; gap: 16px; }
    .page-title h1 { font-size: 24px; font-weight: 700; color: var(--text-main); margin: 0; }
    
    .btn-primary {
        background-color: var(--accent) !important; color: #ffffff !important;
        padding: 12px 20px; border-radius: 12px; font-size: 13.5px; font-weight: 600;
        text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 8px; border: none; cursor: pointer;
        transition: all 0.2s;
    }
    .btn-primary:hover { background-color: var(--accent-dark) !important; transform: translateY(-2px); }

    /* ── Filter Bar ── */
    .filter-bar { display: flex; justify-content: space-between; gap: 16px; margin-bottom: 24px; flex-wrap: wrap; }
    
    .search-box {
        display: flex; align-items: center; background: var(--bg-surface);
        border: 1px solid var(--border); border-radius: var(--radius-md);
        padding: 0 16px; gap: 10px; width: 100%; max-width: 360px; height: 44px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02); transition: all 0.2s;
    }
    .search-box:focus-within { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15); }
    .search-box i { color: var(--text-sec); font-size: 18px; flex-shrink: 0; }
    .search-box input { border: none; outline: none; font-size: 13.5px; width: 100%; color: var(--text-main); background: transparent; padding: 0; }
    
    .filter-select {
        height: 44px; padding: 0 36px 0 16px; border: 1px solid var(--border); border-radius: var(--radius-md);
        appearance: none; font-size: 13.5px; font-weight: 500; color: var(--text-main); cursor: pointer; 
        background: var(--bg-surface) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23475569' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E") no-repeat right 12px center;
        transition: all 0.2s; min-width: 170px;
    }
    .filter-select:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15); }

    /* ── Table Design ── */
    .table-wrapper {
        background: var(--bg-surface); border-radius: var(--radius-lg); border: 1px solid var(--border);
        box-shadow: var(--shadow-card); width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; padding-bottom: 4px;
    }
    .table-wrapper::-webkit-scrollbar { height: 6px; }
    .table-wrapper::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

    table { width: 100%; border-collapse: collapse; font-size: 13.5px; min-width: 800px; }
    th { text-align: left; padding: 16px 24px; background: rgba(0,0,0,0.015); font-weight: 600; color: var(--text-sec); border-bottom: 1px solid var(--border); text-transform: uppercase; font-size: 12px; white-space: nowrap; }
    td { padding: 16px 24px; border-bottom: 1px solid var(--border); color: var(--text-main); font-weight: 500; white-space: nowrap; }
    tbody tr:hover { background: var(--bg-hover); }

    .category-img {
        width: 48px; height: 48px; background: var(--bg-hover); border: 1px solid var(--border);
        border-radius: 10px; overflow: hidden; display: flex; align-items: center; justify-content: center;
    }
    .category-img img { width: 100%; height: 100%; object-fit: cover; }

    .status-badge { display: inline-flex; align-items: center; padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 700; }
    .status-badge.aktif { background: #ecfdf5; color: #059669; }
    .status-badge.nonaktif { background: #fef2f2; color: #dc2626; }

    .action-btn {
        display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 8px;
        background: var(--bg-surface); border: 1px solid #e2e8f0; color: var(--text-sec); text-decoration: none; font-size: 16px; cursor: pointer; transition: 0.2s;
    }
    .action-btn:hover { background: var(--bg-hover); color: var(--accent); border-color: var(--accent); }

    .pagination { display: flex; justify-content: space-between; align-items: center; margin-top: 24px; font-size: 13.5px; color: var(--text-sec); flex-wrap: wrap; gap: 16px; }
    .pagination-links { display: flex; gap: 4px; }
    .pagination-links a, .pagination-links span { display: inline-flex; justify-content: center; align-items: center; min-width: 34px; height: 34px; padding: 0 10px; border: 1px solid var(--border); border-radius: 8px; margin: 0 2px; color: var(--text-main); font-weight: 600; text-decoration: none; transition: 0.2s; }
    .pagination-links a:hover { background: var(--bg-hover); }
    .pagination-links .active { background: var(--accent); color: white; border-color: var(--accent); }

    /* ── RESPONSIVE MOBILE & TABLET ── */
    @media (max-width: 768px) {
        .page-header { flex-direction: column; align-items: flex-start; gap: 12px; margin-bottom: 20px; }
        .btn-primary { width: 100%; }
        
        .filter-bar { flex-direction: column; align-items: stretch; gap: 12px; }
        .search-box { max-width: 100%; }
        .filter-select { width: 100%; }

        .pagination { flex-direction: column; text-align: center; }
        .pagination-links { justify-content: center; flex-wrap: wrap; }
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="page-title"><h1>Kategori</h1></div>
    <a href="{{ route('admin.category.create') }}" class="btn-primary"><i class="ti ti-plus"></i> Tambah Kategori</a>
</div>

<form method="GET" action="{{ route('admin.category.index') }}" id="filter-form">
    <div class="filter-bar">
        <div class="search-box">
            <i class="ti ti-search"></i>
            <input type="text" name="search" placeholder="Cari kategori..." value="{{ request('search') }}" oninput="debounceSubmit()">
        </div>
        <select class="filter-select" name="status" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
        </select>
    </div>
</form>

<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th style="width: 60px;">#</th>
                <th style="text-align: center;">Gambar</th>
                <th>Kategori</th>
                <th>Deskripsi</th>
                <th>Total Produk</th>
                <th>Status</th>
                <th style="text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $index => $item)
            <tr>
                <td>{{ $categories->firstItem() + $index }}</td>
                <td>
                    <div class="category-img">
                        @php
                            $imageUrl = (filter_var($item->image, FILTER_VALIDATE_URL)) ? $item->image : asset('storage/' . $item->image);
                        @endphp
                        <img src="{{ $item->image ? $imageUrl : 'https://placehold.co/100x100?text=No+Img' }}" alt="{{ $item->name }}" onerror="this.src='https://placehold.co/100x100?text=Error'">
                    </div>
                </td>
                <td><strong style="color: var(--text-main);">{{ $item->name }}</strong></td>
                <td>{{ Str::limit($item->description, 50) }}</td>
                <td>{{ $item->products_count ?? 0 }} Produk</td>
                <td>
                    <span class="status-badge {{ $item->is_active ? 'aktif' : 'nonaktif' }}">
                        {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td style="text-align: center;">
                    <div style="display: flex; gap: 8px; justify-content: center;">
                        <a href="{{ route('admin.category.edit', $item->id) }}" class="action-btn" title="Edit"><i class="ti ti-edit"></i></a>
                        <form action="{{ route('admin.category.destroy', $item->id) }}" method="POST" id="delete-form-{{ $item->id }}">
                            @csrf @method('DELETE')
                            <button type="button" class="action-btn" onclick="confirmDelete({{ $item->id }})"><i class="ti ti-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center; padding: 40px;">Data tidak ditemukan.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="pagination">
    <div>Menampilkan {{ $categories->firstItem() ?? 0 }} - {{ $categories->lastItem() ?? 0 }} dari {{ $categories->total() }} kategori</div>
    <div class="pagination-links">{!! $categories->links() !!}</div>
</div>

<script>
    let debounceTimer;
    function debounceSubmit() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => { document.getElementById('filter-form').submit(); }, 400);
    }
    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus Kategori?',
            icon: 'warning',
            text: 'Data ini tidak bisa dikembalikan!',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) { document.getElementById('delete-form-' + id).submit(); }
        });
    }
</script>
@endsection