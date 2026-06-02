@extends('layouts.admin')

@section('title', 'Tambah Produk Baru')

@section('styles')
<style>
    /* Premium Variables (Konsisten dengan halaman sebelumnya) */
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

    /* Page Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }
    .page-title h1 {
        font-size: 22px;
        font-weight: 700;
        color: var(--text-main);
        margin: 0;
        letter-spacing: -0.02em;
    }
    .page-title .breadcrumb {
        font-size: 13px;
        color: var(--text-sec);
        margin-top: 4px;
    }

    /* Two Column Grid Setup */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 360px;
        gap: 20px;
        align-items: start;
    }

    @media (max-width: 992px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Premium Cards */
    .card {
        background: var(--bg-surface);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border);
        padding: 24px;
        margin-bottom: 20px;
    }
    .card-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 20px;
        letter-spacing: -0.01em;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .card-title i {
        color: var(--accent);
        font-size: 18px;
    }

    /* Form Layout Elements */
    .form-group {
        margin-bottom: 18px;
    }
    .form-group:last-child {
        margin-bottom: 0;
    }
    .form-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: var(--text-main);
        margin-bottom: 8px;
    }
    
    /* Inputs, Selects, and Textareas */
    .form-control {
        width: 100%;
        height: 40px;
        padding: 0 14px;
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        font-size: 13px;
        color: var(--text-main);
        background: var(--bg-surface);
        box-sizing: border-box;
        transition: all 0.2s ease;
    }
    .form-control:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(92, 158, 116, 0.15);
    }
    .form-control::placeholder { color: var(--text-muted); }

    textarea.form-control {
        height: auto;
        padding: 12px 14px;
        resize: vertical;
        min-height: 120px;
    }

    /* Input Prefix Wrapper (Untuk Rp / IDR) */
    .input-prefix-group {
        position: relative;
        display: flex;
        align-items: center;
    }
    .input-prefix {
        position: absolute;
        left: 14px;
        font-size: 13px;
        font-weight: 600;
        color: var(--text-sec);
        pointer-events: none;
    }
    .input-prefix-group .form-control {
        padding-left: 38px;
    }

    /* Select Wrapper with Prefix Icon */
    .select-wrapper {
        position: relative;
    }
    .select-wrapper i.prefix-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-sec);
        font-size: 15px;
        pointer-events: none;
    }
    .select-wrapper .form-control {
        padding-left: 34px;
        padding-right: 36px;
        appearance: none;
        background: var(--bg-surface) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%237a9080' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E") no-repeat right 12px center;
        cursor: pointer;
    }

    /* Premium Image Drag & Drop Area (Tanpa Solid Background) */
    .upload-zone {
        border: 2px dashed var(--border);
        border-radius: var(--radius-md);
        padding: 32px 16px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }
    .upload-zone:hover {
        border-color: var(--accent);
        background: var(--bg-hover);
    }
    .upload-zone i {
        font-size: 28px;
        color: var(--text-sec);
    }
    .upload-zone span {
        font-size: 12.5px;
        font-weight: 500;
        color: var(--text-main);
    }
    .upload-zone p {
        font-size: 11px;
        color: var(--text-muted);
        margin: 0;
    }

    /* Row Form Group Flex */
    .form-row {
        display: flex;
        gap: 16px;
    }
    .form-row .form-group {
        flex: 1;
    }

    /* Action Buttons (Menggunakan Direct Hex untuk mencegah crash visual) */
    .form-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 10px;
    }
    
    .btn-submit {
        background-color: #5c9e74 !important;
        color: #ffffff !important;
        padding: 10px 24px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: none;
        box-shadow: 0 2px 6px rgba(92, 158, 116, 0.2);
        outline: none;
        cursor: pointer;
        -webkit-tap-highlight-color: transparent;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .btn-submit:hover {
        background-color: #3a5c48 !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(58, 92, 72, 0.3);
    }
    .btn-submit:active {
        transform: scale(0.95);
        background-color: #2d4a3a !important;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.2);
        transition: all 0.1s;
    }

    .btn-cancel {
        background-color: transparent;
        color: var(--text-sec);
        padding: 10px 20px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        border: 1px solid var(--border);
        cursor: pointer;
        transition: all 0.15s;
    }
    .btn-cancel:hover {
        background-color: var(--bg-hover);
        color: var(--text-main);
        border-color: #d1d6cf;
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
            <div class="card">
                <div class="card-title"><i class="ti ti-photo"></i> Foto Produk</div>
                <div class="form-group">
                    <div class="upload-zone" onclick="document.getElementById('file-input').click()">
                        <i class="ti ti-cloud-upload"></i>
                        <span id="upload-text">Pilih atau Taruh Foto</span>
                        <p>Format PNG, JPG, JPEG maks. 2MB</p>
                    </div>
                    <input type="file" id="file-input" name="image" style="display: none;" accept="image/*">
                    <div id="preview-container" style="margin-top: 10px; display: none;">
                        <img id="image-preview" src="" style="width: 100%; border-radius: 8px; object-fit: cover; max-height: 200px;">
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
                <button type="button" class="btn-cancel">Batal</button>
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