{{-- resources/views/admin/produk/index.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Produk')

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1>Produk</h1>
        <p>Kelola semua produk furniture</p>
    </div>
    <a href="{{ route('admin.produk.create') }}" class="btn btn-primary">+ Tambah Produk</a>
</div>

<div class="card">
    <div class="search-filter">
        <div class="search-input">
            <span>🔍</span>
            <input type="text" placeholder="Cari produk...">
        </div>
        <select class="form-control" style="width:auto;">
            <option>Semua Kategori</option>
            <option>Kursi</option>
            <option>Meja</option>
            <option>Lemari</option>
            <option>Sofa</option>
        </select>
        <select class="form-control" style="width:auto;">
            <option>Semua Status</option>
            <option>Aktif</option>
            <option>Nonaktif</option>
        </select>
    </div>

    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Terjual</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($produk ?? [
                ['icon'=>'🪑','nama'=>'Kursi Minimalis Kayu', 'kategori'=>'Kursi', 'harga'=>750000,  'stok'=>45,'terjual'=>120,'status'=>'Aktif'],
                ['icon'=>'🍽️','nama'=>'Meja Makan Jati',      'kategori'=>'Meja',  'harga'=>2500000, 'stok'=>12,'terjual'=>85, 'status'=>'Aktif'],
                ['icon'=>'🚪','nama'=>'Lemari Pakaian 3 Pintu','kategori'=>'Lemari','harga'=>3200000, 'stok'=>8, 'terjual'=>60, 'status'=>'Aktif'],
                ['icon'=>'🛋️','nama'=>'Sofa Minimalis Abu',   'kategori'=>'Sofa',  'harga'=>4500000, 'stok'=>5, 'terjual'=>42, 'status'=>'Aktif'],
                ['icon'=>'📚','nama'=>'Rak Buku Minimalis',   'kategori'=>'Rak',   'harga'=>850000,  'stok'=>20,'terjual'=>38, 'status'=>'Aktif'],
                ['icon'=>'🛏️','nama'=>'Tempat Tidur Minimalis','kategori'=>'Tempat Tidur','harga'=>5500000,'stok'=>3,'terjual'=>25,'status'=>'Nonaktif'],
            ] as $p)
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:38px;height:38px;border-radius:8px;background:#f0ece6;display:flex;align-items:center;justify-content:center;font-size:18px;">{{ $p['icon'] }}</div>
                        <span style="font-weight:600;">{{ $p['nama'] }}</span>
                    </div>
                </td>
                <td><span class="badge badge-gray">{{ $p['kategori'] }}</span></td>
                <td>Rp {{ number_format($p['harga'],0,',','.') }}</td>
                <td>{{ $p['stok'] }} pcs</td>
                <td>{{ $p['terjual'] }}</td>
                <td><span class="badge {{ $p['status']==='Aktif' ? 'badge-green' : 'badge-gray' }}">{{ $p['status'] }}</span></td>
                <td style="display:flex;gap:6px;">
                    <a href="{{ route('admin.produk.show', 1) }}" class="action-btn">👁️</a>
                    <a href="{{ route('admin.produk.edit', 1) }}" class="action-btn">✏️</a>
                    <form method="POST" action="{{ route('admin.produk.destroy', 1) }}" onsubmit="return confirm('Hapus produk ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="action-btn">🗑️</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="table-footer">
        <span>Menampilkan 6 dari 120 produk</span>
        {{ $produk->links() ?? '' }}
    </div>
</div>
@endsection