@extends('layouts.admin')

@section('title', 'Produk')

@section('styles')
<style>
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
    .product-img {
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
        <h1>Produk</h1>
        <div class="breadcrumb">Dashboard / Produk</div>
    </div>
    <a href="#" class="btn-primary">+ Tambah Produk</a>
</div>

<div class="filter-bar">
    <div class="search-box">
        <span>🔍</span>
        <input type="text" placeholder="Cari produk...">
    </div>
    <select class="filter-select">
        <option>Semua Kategori</option>
        <option>Kursi</option>
        <option>Meja</option>
        <option>Lemari</option>
        <option>Sofa</option>
        <option>Rak</option>
        <option>Tempat Tidur</option>
    </select>
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
                <th>Gambar</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Status</th>
                <th style="width: 80px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @php
                $produk = [
                    ['no'=>1, 'icon'=>'🪑', 'nama'=>'Kursi Minimalis Kayu', 'kategori'=>'Kursi', 'harga'=>750000, 'stok'=>45, 'status'=>'Aktif'],
                    ['no'=>2, 'icon'=>'🍽️', 'nama'=>'Meja Makan Jati', 'kategori'=>'Meja', 'harga'=>2500000, 'stok'=>38, 'status'=>'Aktif'],
                    ['no'=>3, 'icon'=>'🚪', 'nama'=>'Lemari Pakaian 3 Pintu', 'kategori'=>'Lemari', 'harga'=>3200000, 'stok'=>30, 'status'=>'Aktif'],
                    ['no'=>4, 'icon'=>'🛋️', 'nama'=>'Sofa Minimalis Abu', 'kategori'=>'Sofa', 'harga'=>4500000, 'stok'=>28, 'status'=>'Aktif'],
                    ['no'=>5, 'icon'=>'📚', 'nama'=>'Rak Buku Minimalis', 'kategori'=>'Rak', 'harga'=>850000, 'stok'=>25, 'status'=>'Aktif'],
                    ['no'=>6, 'icon'=>'☕', 'nama'=>'Meja Kopi Bulat', 'kategori'=>'Meja', 'harga'=>650000, 'stok'=>40, 'status'=>'Aktif'],
                    ['no'=>7, 'icon'=>'🛏️', 'nama'=>'Tempat Tidur Queen', 'kategori'=>'Tempat Tidur', 'harga'=>3750000, 'stok'=>15, 'status'=>'Aktif'],
                    ['no'=>8, 'icon'=>'📺', 'nama'=>'Meja TV Minimalis', 'kategori'=>'Meja', 'harga'=>1350000, 'stok'=>33, 'status'=>'Aktif'],
                ];
            @endphp
            @foreach($produk as $item)
            <tr>
                <td>{{ $item['no'] }}</td>
                <td><div class="product-img">{{ $item['icon'] }}</div></td>
                <td><strong>{{ $item['nama'] }}</strong><br><span style="font-size:11px; color:#9ca3af;">SKU: {{ strtoupper(substr($item['nama'],0,3)).'-'.str_pad($item['no'],3,'0',STR_PAD_LEFT) }}</span></td>
                <td>{{ $item['kategori'] }}</td>
                <td>Rp {{ number_format($item['harga'],0,',','.') }}</td>
                <td>{{ $item['stok'] }}</td>
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
    <div>Menampilkan 1 - 8 dari 120 produk</div>
    <div class="pagination-links">
        <span class="active">1</span>
        <a href="#">2</a>
        <a href="#">3</a>
        <a href="#">4</a>
        <a href="#">...</a>
        <a href="#">15</a>
        <a href="#">→</a>
    </div>
</div>
@endsection