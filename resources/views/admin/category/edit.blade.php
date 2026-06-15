@extends('layouts.admin')

@section('title', 'Edit Kategori')

@section('styles')
<style>
    /* ── Premium Variables (Royal Blue & Slate) ── */
    :root {
        --accent: #2563eb; 
        --accent-dark: #1e40af;
        --border: rgba(15, 23, 42, 0.06);
        --bg-body: #f8fafc;
        --bg-surface: #ffffff;
        --text-main: #0f172a;
        --text-sec: #475569;
        --text-muted: #94a3b8;
        --radius-md: 14px;
        --radius-lg: 20px;
        --shadow-card: 0 10px 30px -10px rgba(15, 23, 42, 0.05);
    }

    body { color: var(--text-main); background: var(--bg-body); }

    .page-header { margin-bottom: 28px; }
    .page-title h1 { font-size: 24px; font-weight: 700; margin: 0; letter-spacing: -0.02em; }
    .breadcrumb { font-size: 13.5px; color: var(--text-sec); margin-top: 4px; }

    /* ── Grid Form ── */
    .form-grid { display: grid; grid-template-columns: 1fr 360px; gap: 24px; align-items: start; }
    
    .card { background: var(--bg-surface); border-radius: var(--radius-lg); border: 1px solid var(--border); padding: 24px; box-shadow: var(--shadow-card); margin-bottom: 24px; }
    .card-title { font-size: 16px; font-weight: 700; display: flex; align-items: center; gap: 8px; margin-bottom: 24px; color: var(--text-main); }
    .card-title i { color: var(--accent); font-size: 18px; }

    .form-group { margin-bottom: 20px; }
    .form-label { display: block; font-size: 13.5px; font-weight: 600; margin-bottom: 8px; }
    
    .form-control { 
        width: 100%; height: 44px; padding: 0 16px; border: 1px solid var(--border); 
        border-radius: var(--radius-md); font-size: 13.5px; color: var(--text-main); 
        transition: all 0.2s; background: var(--bg-surface); 
    }
    .form-control:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15); }
    
    textarea.form-control { height: auto; min-height: 120px; padding: 12px 16px; resize: vertical; }

    .img-preview { 
        width: 100%; height: 200px; object-fit: cover; border-radius: var(--radius-md); 
        margin-bottom: 16px; border: 1px solid var(--border); background: var(--bg-body);
    }

    /* ── Buttons ── */
    .btn-submit { 
        background: var(--accent); color: #fff; padding: 12px 24px; border-radius: 12px; 
        font-size: 14px; font-weight: 600; border: none; width: 100%; cursor: pointer; 
        transition: 0.2s; display: flex; align-items: center; justify-content: center; gap: 8px; 
    }
    .btn-submit:hover { background: var(--accent-dark); transform: translateY(-2px); box-shadow: 0 6px 15px rgba(37, 99, 235, 0.2); }
    
    .btn-cancel { 
        background: transparent; color: var(--text-sec); border: 1px solid var(--border); 
        padding: 12px 24px; border-radius: 12px; font-size: 14px; font-weight: 600; 
        text-decoration: none; width: 100%; text-align: center; display: inline-block; 
        transition: 0.2s; margin-bottom: 12px; 
    }
    .btn-cancel:hover { background: var(--bg-body); color: var(--text-main); border-color: #cbd5e1; }

    /* ── Responsive ── */
    @media (max-width: 992px) { .form-grid { grid-template-columns: 1fr; } }
    @media (max-width: 768px) { 
        .card { padding: 20px; }
        .page-header { margin-bottom: 20px; }
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1>Edit Kategori</h1>
        <div class="breadcrumb">FurniHome / Kategori / Edit</div>
    </div>
</div>

<form action="{{ route('admin.category.update', $category->id) }}" method="POST">
    @csrf @method('PUT')
    <div class="form-grid">
        <div class="form-left">
            <div class="card">
                <div class="card-title"><i class="ti ti-info-circle"></i> Informasi Utama</div>
                <div class="form-group">
                    <label class="form-label">Nama Kategori</label>
                    <input type="text" name="nama" class="form-control" value="{{ $category->name }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi Singkat</label>
                    <textarea name="deskripsi" class="form-control">{{ $category->description }}</textarea>
                </div>
            </div>
        </div>
        
        <div class="form-right">
            <div class="card">
                <div class="card-title"><i class="ti ti-photo"></i> Visual & Status</div>
                <div class="form-group">
                    <label class="form-label">Preview Gambar</label>
                    <img src="{{ $category->image }}" class="img-preview" onerror="this.src='https://placehold.co/400x200?text=Image+Not+Found'">
                    
                    <label class="form-label">Link Gambar Baru</label>
                    <input type="url" name="image" class="form-control" value="{{ $category->image }}" required>
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Status Visibilitas</label>
                    <select name="status" class="form-control">
                        <option value="Aktif" {{ $category->is_active ? 'selected' : '' }}>Aktif (Ditampilkan)</option>
                        <option value="Nonaktif" {{ !$category->is_active ? 'selected' : '' }}>Nonaktif (Disembunyikan)</option>
                    </select>
                </div>
            </div>

            <a href="{{ route('admin.category.index') }}" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-submit"><i class="ti ti-device-floppy"></i> Update Kategori</button>
        </div>
    </div>
</form>
@endsection