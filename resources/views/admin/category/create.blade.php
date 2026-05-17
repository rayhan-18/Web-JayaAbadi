@extends('layouts.admin')

@section('title', 'Tambah Kategori Baru')

@section('styles')
<style>
    /* Premium Variables */
    :root {
        --accent: #5c9e74;
        --accent-dark: #3a5c48;
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

    .page-header {
        display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;
    }
    .page-title h1 {
        font-size: 22px; font-weight: 700; color: var(--text-main); margin: 0; letter-spacing: -0.02em;
    }
    .page-title .breadcrumb {
        font-size: 13px; color: var(--text-sec); margin-top: 4px;
    }

    .form-grid {
        display: grid; grid-template-columns: 1fr 340px; gap: 20px; align-items: start;
    }
    @media (max-width: 992px) { .form-grid { grid-template-columns: 1fr; } }

    .card {
        background: var(--bg-surface); border-radius: var(--radius-lg);
        border: 1px solid var(--border); padding: 24px; margin-bottom: 20px;
    }
    .card-title {
        font-size: 15px; font-weight: 700; color: var(--text-main);
        margin-bottom: 20px; display: flex; align-items: center; gap: 8px;
    }
    .card-title i { color: var(--accent); font-size: 18px; }

    .form-group { margin-bottom: 18px; }
    .form-group:last-child { margin-bottom: 0; }
    .form-label { display: block; font-size: 13px; font-weight: 600; color: var(--text-main); margin-bottom: 8px; }
    
    .form-control {
        width: 100%; height: 40px; padding: 0 14px; border: 1px solid var(--border);
        border-radius: var(--radius-md); font-size: 13px; color: var(--text-main);
        background: var(--bg-surface); box-sizing: border-box; transition: all 0.2s ease;
    }
    .form-control:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px rgba(92, 158, 116, 0.15); }
    .form-control::placeholder { color: var(--text-muted); }

    textarea.form-control { height: auto; padding: 12px 14px; resize: vertical; min-height: 120px; }

    .select-wrapper { position: relative; }
    .select-wrapper i.prefix-icon {
        position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
        color: var(--text-sec); font-size: 15px; pointer-events: none;
    }
    .select-wrapper .form-control {
        padding-left: 34px; appearance: none;
        background: var(--bg-surface) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%237a9080' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E") no-repeat right 12px center;
        cursor: pointer;
    }

    /* Action Buttons (Fixed Hex) */
    .form-actions { display: flex; align-items: center; justify-content: flex-end; gap: 12px; margin-top: 10px; }
    
    .btn-submit {
        background-color: #5c9e74 !important; color: #ffffff !important;
        padding: 10px 24px; border-radius: 10px; font-size: 13px; font-weight: 600;
        border: none; display: inline-flex; align-items: center; gap: 8px; cursor: pointer;
        box-shadow: 0 2px 6px rgba(92, 158, 116, 0.2); transition: all 0.2s; -webkit-tap-highlight-color: transparent;
    }
    .btn-submit:hover { transform: translateY(-2px); background-color: #3a5c48 !important; box-shadow: 0 6px 15px rgba(58, 92, 72, 0.3); }
    .btn-submit:active { transform: scale(0.95); background-color: #2d4a3a !important; box-shadow: inset 0 2px 4px rgba(0,0,0,0.2); }

    .btn-cancel {
        background-color: transparent; color: var(--text-sec); padding: 10px 20px;
        border-radius: 10px; font-size: 13px; font-weight: 600; text-decoration: none;
        border: 1px solid var(--border); cursor: pointer; transition: all 0.15s;
    }
    .btn-cancel:hover { background-color: var(--bg-hover); color: var(--text-main); border-color: #d1d6cf; }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1>Tambah Kategori</h1>
        <div class="breadcrumb">FurniHome / Kategori / Tambah Baru</div>
    </div>
    <a href="{{ route('admin.category.index') }}" class="btn-cancel"><i class="ti ti-arrow-left"></i> Kembali</a>
</div>

<form action="#" method="POST">
    @csrf
    <div class="form-grid">
        {{-- KOLOM KIRI: Informasi Utama --}}
        <div class="form-left">
            <div class="card">
                <div class="card-title"><i class="ti ti-info-circle"></i> Informasi Kategori</div>
                
                <div class="form-group">
                    <label class="form-label" for="nama_kategori">Nama Kategori</label>
                    <input type="text" id="nama_kategori" name="nama" class="form-control" placeholder="Contoh: Sofa Ruang Tamu" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="deskripsi">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" class="form-control" placeholder="Tuliskan penjelasan singkat mengenai kategori ini..."></textarea>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: Pengaturan --}}
        <div class="form-right">
            <div class="card">
                <div class="card-title"><i class="ti ti-settings"></i> Pengaturan</div>
                
                <div class="form-group">
                    <label class="form-label" for="ikon">Ikon Kategori (Tabler Class)</label>
                    <div class="select-wrapper">
                        <i class="ti ti-icons prefix-icon"></i>
                        <input type="text" id="ikon" name="ikon" class="form-control" placeholder="Contoh: ti-sofa" style="padding-left: 34px;">
                    </div>
                    <span style="font-size: 11px; color: var(--text-muted); display: block; margin-top: 6px;">Cari referensi ikon di <a href="https://tabler.io/icons" target="_blank" style="color: var(--accent);">tabler.io/icons</a></span>
                </div>

                <div class="form-group" style="margin-top: 18px;">
                    <label class="form-label" for="status">Status</label>
                    <div class="select-wrapper">
                        <i class="ti ti-circle-check prefix-icon"></i>
                        <select id="status" name="status" class="form-control">
                            <option value="Aktif">Aktif</option>
                            <option value="Nonaktif">Nonaktif</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.category.index') }}" class="btn-cancel">Batal</a>
                <button type="submit" class="btn-submit"><i class="ti ti-device-floppy"></i> Simpan Kategori</button>
            </div>
        </div>
    </div>
</form>
@endsection