@extends('layouts.admin')

@section('title', 'Edit Kategori')

@section('styles')
<style>
    /* Menggunakan variabel yang sama dengan create.blade.php */
    .form-grid { display: grid; grid-template-columns: 1fr 340px; gap: 20px; }
    .card { background: #ffffff; border-radius: 14px; border: 1px solid #e6e9e4; padding: 24px; margin-bottom: 20px; }
    .form-control { width: 100%; height: 45px; padding: 0 14px; border: 1px solid #e6e9e4; border-radius: 10px; font-size: 13px; }
    .img-preview { width: 100%; height: 200px; object-fit: cover; border-radius: 10px; margin-bottom: 15px; border: 1px solid #e6e9e4; }
    .btn-submit { background: #2563eb; color: #fff; padding: 12px 24px; border-radius: 10px; font-weight: 600; border: none; width: 100%; cursor: pointer; }
    @media (max-width: 992px) { .form-grid { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="page-title"><h1>Edit Kategori</h1></div>
</div>

<form action="{{ route('admin.category.update', $category->id) }}" method="POST">
    @csrf @method('PUT')
    <div class="form-grid">
        <div class="form-left">
            <div class="card">
                <div class="form-group">
                    <label class="form-label">Nama Kategori</label>
                    <input type="text" name="nama" class="form-control" value="{{ $category->name }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" style="height: 120px;">{{ $category->description }}</textarea>
                </div>
            </div>
        </div>
        <div class="form-right">
            <div class="card">
                <div class="form-group">
                    <label class="form-label">Preview Gambar</label>
                    <img src="{{ $category->image }}" class="img-preview" onerror="this.src='https://placehold.co/400x200?text=Error+Image'">
                    <input type="url" name="image" class="form-control" value="{{ $category->image }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="Aktif" {{ $category->is_active ? 'selected' : '' }}>Aktif</option>
                        <option value="Nonaktif" {{ !$category->is_active ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <button type="submit" class="btn-submit">Update Kategori</button>
            </div>
        </div>
    </div>
</form>
@endsection