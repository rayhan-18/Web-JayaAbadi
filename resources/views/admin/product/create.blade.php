@extends('layouts.admin')

@section('title', 'Tambah Produk Baru')

@section('styles')
<style>
    /* ── Reset & Premium Variables (Tema: Royal Blue & Minimalist Slate) ── */
    :root {
        --accent: #2563eb; 
        --accent-dark: #1e40af;
        --border: rgba(15, 23, 42, 0.08);
        --bg-body: #f8fafc;
        --bg-surface: #ffffff;
        --bg-hover: #f1f5f9;
        --text-main: #0f172a;
        --text-sec: #475569;
        --text-muted: #94a3b8;
        --radius-md: 12px;
        --radius-lg: 16px;
        --shadow-card: 0 10px 30px -10px rgba(15, 23, 42, 0.05);
    }

    body { color: var(--text-main); background: var(--bg-body); }

    /* ── Page Header ── */
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
    .page-title h1 { font-size: 24px; font-weight: 700; color: var(--text-main); margin: 0; letter-spacing: -0.02em; }
    .page-title .breadcrumb { font-size: 13.5px; color: var(--text-sec); margin-top: 4px; }

    /* ── Two Column Grid Setup ── */
    .form-grid { display: grid; grid-template-columns: 1fr 380px; gap: 24px; align-items: start; }

    /* ── Premium Cards ── */
    .card {
        background: var(--bg-surface); border-radius: var(--radius-lg);
        border: 1px solid var(--border); padding: 28px; margin-bottom: 24px;
        box-shadow: var(--shadow-card);
    }
    .card-title {
        font-size: 16px; font-weight: 700; color: var(--text-main); margin-bottom: 24px;
        letter-spacing: -0.01em; display: flex; align-items: center; gap: 10px;
    }
    .card-title i { color: var(--accent); font-size: 20px; }

    /* ── Form Elements ── */
    .form-group { margin-bottom: 20px; }
    .form-group:last-child { margin-bottom: 0; }
    .form-label { display: block; font-size: 13.5px; font-weight: 600; color: var(--text-main); margin-bottom: 10px; }
    
    .form-control {
        width: 100%; height: 44px; padding: 0 16px; border: 1px solid var(--border);
        border-radius: var(--radius-md); font-size: 14px; font-weight: 500; color: var(--text-main);
        background: var(--bg-surface); box-sizing: border-box; transition: all 0.2s ease;
    }
    .form-control:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15); }
    .form-control::placeholder { color: var(--text-muted); font-weight: 400; }

    textarea.form-control { height: auto; padding: 14px 16px; resize: vertical; min-height: 140px; line-height: 1.5; }

    /* Prefix Wrapper (Rp / IDR) */
    .input-prefix-group { position: relative; display: flex; align-items: center; }
    .input-prefix { position: absolute; left: 16px; font-size: 14px; font-weight: 600; color: var(--text-sec); pointer-events: none; }
    .input-prefix-group .form-control { padding-left: 44px; }

    /* Select Wrapper */
    .select-wrapper { position: relative; }
    .select-wrapper i.prefix-icon { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: var(--text-sec); font-size: 18px; pointer-events: none; }
    .select-wrapper .form-control {
        padding-left: 44px; padding-right: 40px; appearance: none;
        background: var(--bg-surface) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23475569' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E") no-repeat right 16px center;
        cursor: pointer;
    }

    /* ── Upload Area ── */
    .upload-zone {
        border: 2px dashed #cbd5e1; border-radius: var(--radius-md); padding: 40px 20px;
        text-align: center; cursor: pointer; transition: all 0.2s ease; background: #f8fafc;
        display: flex; flex-direction: column; align-items: center; gap: 12px;
    }
    .upload-zone:hover { border-color: var(--accent); background: var(--accent-light); }
    .upload-zone i { font-size: 36px; color: var(--accent); }
    .upload-zone span { font-size: 14px; font-weight: 600; color: var(--text-main); }
    .upload-zone p { font-size: 12px; color: var(--text-muted); margin: 0; }

    .form-row { display: flex; gap: 20px; }
    .form-row .form-group { flex: 1; }

    /* ── Action Buttons ── */
    .form-actions { display: flex; align-items: center; justify-content: flex-end; gap: 16px; margin-top: 16px; }
    
    .btn-submit {
        background-color: var(--accent) !important; color: #ffffff !important;
        padding: 14px 28px; border-radius: 12px; font-size: 14px; font-weight: 600;
        display: inline-flex; align-items: center; gap: 8px; border: none; outline: none; cursor: pointer;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2); transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .btn-submit:hover { background-color: var(--accent-dark) !important; transform: translateY(-2px); box-shadow: 0 6px 15px rgba(30, 64, 175, 0.3); }
    .btn-submit:active { transform: scale(0.97); }

    .btn-cancel {
        background-color: var(--bg-surface); color: var(--text-sec);
        padding: 14px 24px; border-radius: 12px; font-size: 14px; font-weight: 600;
        text-decoration: none; border: 1px solid #cbd5e1; cursor: pointer; transition: all 0.15s;
    }
    .btn-cancel:hover { background-color: var(--bg-hover); color: var(--text-main); border-color: #94a3b8; }

    /* ── Responsive Mobile ── */
    @media (max-width: 1024px) {
        .form-grid { grid-template-columns: 1fr; gap: 0; }
        .card { padding: 20px; }
        .form-row { flex-direction: column; gap: 0; }
        .btn-submit, .btn-cancel { width: 100%; justify-content: center; }
        .form-actions { flex-direction: column-reverse; gap: 12px; }
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1>Tambah Produk</h1>
        <div class="breadcrumb">FurniHome / Produk / Tambah Baru</div>
    </div>
</div>

<form action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-grid">
        
        {{-- KOLOM KIRI: Utama --}}
        <div class="form-left">
            <div class="card">
                <div class="card-title"><i class="ti ti-info-circle"></i> Informasi Produk</div>
                
                <div class="form-group">
                    <label class="form-label" for="nama_produk">Nama Produk</label>
                    <input type="text" id="nama_produk" name="name" class="form-control" placeholder="Contoh: Kursi Minimalis Kayu Jati" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="kategori">Kategori</label>
                        <div class="select-wrapper">
                            <i class="ti ti-category prefix-icon"></i>
                        <select id="kategori" name="category_id" class="form-control" required>
                            <option value="" disabled selected>Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="sku">SKU (Kode Produk)</label>
                        <input type="text" id="sku" name="sku" class="form-control" placeholder="Contoh: KRS-001">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="deskripsi">Deskripsi Lengkap</label>
                    <textarea id="deskripsi" name="description" class="form-control" placeholder="Tuliskan deskripsi produk, bahan, ukuran, dan detail lainnya dengan jelas..."></textarea>
                </div>
            </div>

            <div class="card">
                <div class="card-title"><i class="ti ti-receipt"></i> Harga & Stok</div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="price">Harga Jual</label>
                        <div class="input-prefix-group">
                            <span class="input-prefix">Rp</span>
                            <input type="number" id="harga" name="price" class="form-control" placeholder="0" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="stok">Jumlah Stok</label>
                        <input type="number" id="stok" name="stock" class="form-control" placeholder="0" required>
                    </div>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: Media & Status --}}
        <div class="form-right">
            <div class="card">
                <div class="card-title"><i class="ti ti-photo"></i> Foto Produk</div>
                <div class="form-group">
                    <div class="upload-zone" onclick="document.getElementById('file-input').click()">
                        <i class="ti ti-cloud-upload"></i>
                        <span id="upload-text">Pilih atau Taruh Foto</span>
                        <p>Format PNG, JPG, JPEG maks. 2MB</p>
                    </div>
                    <input type="file" id="file-input" name="image" style="display: none;" accept="image/*">
                    <div id="preview-container" style="margin-top: 16px; display: none;">
                        <img id="image-preview" src="" style="width: 100%; border-radius: 12px; border: 1px solid var(--border); object-fit: cover; max-height: 220px;">
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-title"><i class="ti ti-toggle-channels"></i> Visibilitas</div>
                <div class="form-group">
                    <label class="form-label" for="status">Status Produk</label>
                    <div class="select-wrapper">
                        <i class="ti ti-circle-check prefix-icon"></i>
                        <select id="status" name="is_active" class="form-control">
                            <option value="1">Aktif (Tampil di Toko)</option>
                            <option value="0">Nonaktif (Arsip)</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.product.index') }}" class="btn-cancel" style="text-align:center;">Batal</a>
                <button type="submit" class="btn-submit"><i class="ti ti-device-floppy"></i> Simpan Produk</button>
            </div>
        </div>

    </div>
</form>

<script>
    document.getElementById('file-input').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            document.getElementById('upload-text').innerText = file.name;
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('image-preview').src = e.target.result;
                document.getElementById('preview-container').style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection