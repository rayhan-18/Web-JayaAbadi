{{-- resources/views/admin/produk/form.blade.php --}}
@extends('admin.layouts.app')
@section('title', isset($produk) ? 'Edit Produk' : 'Tambah Produk')

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1>{{ isset($produk) ? 'Edit Produk' : 'Tambah Produk' }}</h1>
        <p>{{ isset($produk) ? 'Perbarui informasi produk' : 'Isi detail produk baru' }}</p>
    </div>
    <a href="{{ route('admin.produk.index') }}" class="btn btn-outline">← Kembali</a>
</div>

<form method="POST" action="{{ isset($produk) ? route('admin.produk.update', $produk->id) : route('admin.produk.store') }}" enctype="multipart/form-data">
    @csrf
    @if(isset($produk)) @method('PUT') @endif

    <div style="display:grid;grid-template-columns:1fr 320px;gap:16px;">
        {{-- Main Info --}}
        <div style="display:flex;flex-direction:column;gap:16px;">
            <div class="card" style="padding:20px;">
                <h3 style="font-size:14px;font-weight:700;margin-bottom:16px;">Informasi Produk</h3>
                <div class="form-group">
                    <label>Nama Produk</label>
                    <input type="text" name="nama" class="form-control" value="{{ $produk->nama ?? '' }}" placeholder="Contoh: Kursi Minimalis Kayu" required>
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="4" placeholder="Deskripsi produk...">{{ $produk->deskripsi ?? '' }}</textarea>
                </div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label>Harga (Rp)</label>
                        <input type="number" name="harga" class="form-control" value="{{ $produk->harga ?? '' }}" placeholder="750000" required>
                    </div>
                    <div class="form-group">
                        <label>Stok</label>
                        <input type="number" name="stok" class="form-control" value="{{ $produk->stok ?? '' }}" placeholder="0" required>
                    </div>
                </div>
            </div>

            <div class="card" style="padding:20px;">
                <h3 style="font-size:14px;font-weight:700;margin-bottom:16px;">Foto Produk</h3>
                <div style="border:2px dashed var(--border);border-radius:10px;padding:32px;text-align:center;color:var(--text-muted);">
                    <div style="font-size:32px;margin-bottom:8px;">📷</div>
                    <p style="font-size:13px;">Drag & drop foto atau <span style="color:var(--accent);cursor:pointer;">pilih file</span></p>
                    <p style="font-size:11px;margin-top:4px;">PNG, JPG hingga 2MB</p>
                    <input type="file" name="foto" accept="image/*" style="display:none;">
                </div>
            </div>
        </div>

        {{-- Sidebar Info --}}
        <div style="display:flex;flex-direction:column;gap:16px;">
            <div class="card" style="padding:20px;">
                <h3 style="font-size:14px;font-weight:700;margin-bottom:16px;">Kategori & Status</h3>
                <div class="form-group">
                    <label>Kategori</label>
                    <select name="kategori_id" class="form-control" required>
                        <option value="">Pilih Kategori</option>
                        <option value="1" {{ isset($produk) && $produk->kategori_id==1 ? 'selected':'' }}>Kursi</option>
                        <option value="2" {{ isset($produk) && $produk->kategori_id==2 ? 'selected':'' }}>Meja</option>
                        <option value="3" {{ isset($produk) && $produk->kategori_id==3 ? 'selected':'' }}>Lemari</option>
                        <option value="4" {{ isset($produk) && $produk->kategori_id==4 ? 'selected':'' }}>Sofa</option>
                        <option value="5" {{ isset($produk) && $produk->kategori_id==5 ? 'selected':'' }}>Rak</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="aktif"    {{ isset($produk) && $produk->status=='aktif'    ? 'selected':'' }}>Aktif</option>
                        <option value="nonaktif" {{ isset($produk) && $produk->status=='nonaktif' ? 'selected':'' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Berat (gram)</label>
                    <input type="number" name="berat" class="form-control" value="{{ $produk->berat ?? '' }}" placeholder="0">
                </div>
            </div>

            <div class="card" style="padding:20px;">
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">
                    {{ isset($produk) ? '💾 Simpan Perubahan' : '➕ Tambah Produk' }}
                </button>
                <a href="{{ route('admin.produk.index') }}" class="btn btn-outline" style="width:100%;justify-content:center;margin-top:8px;">
                    Batal
                </a>
            </div>
        </div>
    </div>
</form>
@endsection