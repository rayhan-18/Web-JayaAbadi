@extends('layouts.admin')

@section('title', 'Tambah Kategori Baru')

@section('styles')
<style>
    :root { --accent: #2563eb; --accent-dark: #1e40af; --border: #e6e9e4; --bg-surface: #ffffff; --text-main: #2d3b32; --text-sec: #7a9080; }
    .form-grid { display: grid; grid-template-columns: 1fr 340px; gap: 20px; }
    .card { background: var(--bg-surface); border-radius: 14px; border: 1px solid var(--border); padding: 24px; margin-bottom: 20px; }
    .card-title { font-weight: 700; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; }
    .form-group { margin-bottom: 20px; }
    .form-label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; }
    .form-control { width: 100%; height: 45px; padding: 0 14px; border: 1px solid var(--border); border-radius: 10px; font-size: 13px; }
    .btn-submit { background: #2563eb; color: #fff; padding: 12px 24px; border-radius: 10px; font-weight: 600; border: none; width: 100%; cursor: pointer; }
    @media (max-width: 992px) { .form-grid { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="page-title"><h1>Tambah Kategori</h1></div>
</div>

<form action="{{ route('admin.category.store') }}" method="POST">
    @csrf
    <div class="form-grid">
        <div class="form-left">
            <div class="card">
                <div class="card-title"><i class="ti ti-info-circle"></i> Informasi Kategori</div>
                <div class="form-group">
                    <label class="form-label">Nama Kategori</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" style="height: 120px;"></textarea>
                </div>
            </div>
        </div>
        <div class="form-right">
            <div class="card">
                <div class="card-title"><i class="ti ti-settings"></i> Pengaturan</div>
                <div class="form-group">
                    <label class="form-label">Link Gambar</label>
                    <input type="url" name="image" class="form-control" placeholder="https://..." required>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="Aktif">Aktif</option>
                        <option value="Nonaktif">Nonaktif</option>
                    </select>
                </div>
                <button type="submit" class="btn-submit">Simpan Kategori</button>
            </div>
        </div>
    </div>
</form>
@endsection