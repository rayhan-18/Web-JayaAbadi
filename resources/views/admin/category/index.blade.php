@extends('layouts.admin')

@section('title', 'Kategori')

@section('styles')
<style>
    /* Gunakan style yang sama dengan halaman produk/kategori sebelumnya */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }
    .page-title h1 {
        font-size: 24px;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }
    .page-title .breadcrumb {
        font-size: 13px;
        color: #6b7280;
        margin-top: 4px;
    }
    .btn-primary {
        background: #4a7c5e;
        color: white;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: 0.2s;
    }
    .btn-primary:hover {
        background: #3d6a50;
    }
    .filter-bar {
        display: flex;
        gap: 12px;
        margin-bottom: 24px;
        flex-wrap: wrap;
        align-items: center;
    }
    .search-box {
        display: flex;
        align-items: center;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 6px 12px;
        gap: 8px;
        flex: 1;
        max-width: 300px;
    }
    .search-box input {
        border: none;
        outline: none;
        font-size: 13px;
        width: 100%;
    }
    .filter-select {
        padding: 6px 12px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background: #fff;
        font-size: 13px;
    }
    .table-wrapper {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        overflow-x: auto;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }
    th {
        text-align: left;
        padding: 14px 16px;
        background: #f9fafb;
        font-weight: 600;
        color: #4b5563;
        border-bottom: 1px solid #e5e7eb;
    }
    td {
        padding: 12px 16px;
        border-bottom: 1px solid #f3f4f6;
        color: #374151;
        vertical-align: middle;
    }
    .category-icon {
        width: 36px;
        height: 36px;
        background: #f3f4f6;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }
    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        background: #dcfce7;
        color: #15803d;
    }
    .action-icons {
        display: flex;
        gap: 8px;
    }
    .action-icons a {
        color: #6b7280;
        text-decoration: none;
        font-size: 16px;
        transition: 0.2s;
    }
    .action-icons a:hover {
        color: #4a7c5e;
    }
    .pagination {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 24px;
        font-size: 13px;
        color: #6b7280;
    }
    .pagination-links {
        display: flex;
        gap: 8px;
    }
    .pagination-links a, .pagination-links span {
        padding: 6px 10px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        text-decoration: none;
        color: #4b5563;
    }
    .pagination-links .active {
        background: #4a7c5e;
        border-color: #4a7c5e;
        color: white;
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1>Kategori</h1>
        <div class="breadcrumb">Dashboard / Kategori</div>
    </div>
    <a href="#" class="btn-primary">+ Tambah Kategori</a>
</div>

<div class="filter-bar">
    <div class="search-box">
        <span>🔍</span>
        <input type="text" placeholder="Cari kategori...">
    </div>
    <select class="filter-select">
        <option>Semua Status</option>
        <option>Aktif</option>
        <option>Nonaktif</option>
    </select>
</div>

<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th style="width: 50px;">#</th>
                <th>Ikon</th>
                <th>Nama Kategori</th>
                <th>Deskripsi</th>
                <th>Jumlah Produk</th>
                <th>Status</th>
                <th style="width: 80px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @php
                $categories = [
                    ['id'=>1, 'icon'=>'🛋️', 'nama'=>'Sofa', 'deskripsi'=>'Berbagai pilihan sofa untuk ruang tamu dengan desain minimalis hingga mewah.', 'jumlah'=>24, 'status'=>'Aktif'],
                    ['id'=>2, 'icon'=>'🪑', 'nama'=>'Kursi', 'deskripsi'=>'Kursi minimalis, kursi makan, kursi kerja, dan berbagai model lainnya.', 'jumlah'=>18, 'status'=>'Aktif'],
                    ['id'=>3, 'icon'=>'🍽️', 'nama'=>'Meja', 'deskripsi'=>'Meja makan, meja tamu, meja kerja, dan berbagai jenis meja lainnya.', 'jumlah'=>27, 'status'=>'Aktif'],
                    ['id'=>4, 'icon'=>'🚪', 'nama'=>'Lemari', 'deskripsi'=>'Lemari pakai, lemari penyimpanan, dengan berbagai ukuran.', 'jumlah'=>15, 'status'=>'Aktif'],
                    ['id'=>5, 'icon'=>'🛏️', 'nama'=>'Tempat Tidur', 'deskripsi'=>'Tempat tidur single, queen, king size dengan material berkualitas.', 'jumlah'=>12, 'status'=>'Aktif'],
                    ['id'=>6, 'icon'=>'📚', 'nama'=>'Rak & Penyimpanan', 'deskripsi'=>'Rak buku, rak dinding, rak sepatu, dan solusi penyimpanan lainnya.', 'jumlah'=>20, 'status'=>'Aktif'],
                    ['id'=>7, 'icon'=>'💼', 'nama'=>'Meja Kerja', 'deskripsi'=>'Meja kerja minimalis dan ergonomis untuk produktivitas.', 'jumlah'=>10, 'status'=>'Aktif'],
                ];
            @endphp
            @foreach($categories as $item)
            <tr>
                <td>{{ $item['id'] }}</td>
                <td><div class="category-icon">{{ $item['icon'] }}</div></td>
                <td><strong>{{ $item['nama'] }}</strong></td>
                <td>{{ $item['deskripsi'] }}</td>
                <td>{{ $item['jumlah'] }} Produk</td>
                <td><span class="status-badge">● {{ $item['status'] }}</span></td>
                <td class="action-icons">
                    <a href="#" title="Edit">✏️</a>
                    <a href="#" title="Hapus">🗑️</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="pagination">
    <div>Menampilkan 1 - 7 dari 7 kategori</div>
    <div class="pagination-links">
        <span class="active">1</span>
    </div>
</div>
@endsection