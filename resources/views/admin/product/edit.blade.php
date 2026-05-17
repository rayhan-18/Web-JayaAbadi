@extends('layouts.admin')

@section('title', 'Edit Produk')

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
        padding: 24px 16px;
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
        font-size: 24px;
        color: var(--text-sec);
    }
    .upload-zone span {
        font-size: 12px;
        font-weight: 500;
        color: var(--text-main);
    }

    /* Thumbnail Preview current image */
    .current-img-preview {
        width: 100%;
        height: 120px;
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 42px;
        color: var(--text-sec);
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
        <h1>Edit Produk</h1>
        <div class="breadcrumb">FurniHome / Produk / Edit</div>
    </div>
    <a href="{{ route('admin.product.index') }}" class="btn-cancel"><i class="ti ti-arrow-left"></i> Kembali</a>
</div>

{{-- Diarahkan menggunakan metode PUT --}}
<form action="#" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="form-grid">
        
        {{-- KOLOM KIRI: Utama --}}
        <div class="form-left">
            <div class="card">
                <div class="card-title"><i class="ti ti-info-circle"></i> Informasi Produk</div>
                
                <div class="form-group">
                    <label class="form-label" for="nama_produk">Nama Produk</label>
                    <input type="text" id="nama_produk" name="nama" class="form-control" value="Kursi Minimalis Kayu" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="kategori">Kategori</label>
                        <div class="select-wrapper">
                            <i class="ti ti-category prefix-icon"></i>
                            <select id="kategori" name="kategori_id" class="form-control" required>
                                <option value="1" selected>Kursi</option>
                                <option value="2">Meja</option>
                                <option value="3">Lemari</option>
                                <option value="4">Sofa</option>
                                <option value="5">Rak</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="sku">SKU (Kode Produk)</label>
                        <input type="text" id="sku" name="sku" class="form-control" value="KRS-001">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="deskripsi">Deskripsi Lengkap</label>
                    <textarea id="deskripsi" name="deskripsi" class="form-control">Kursi minimalis terbuat dari kayu jati berkualitas tinggi dengan finishing halus alami. Sangat cocok diletakkan di ruang tamu maupun ruang makan untuk mempercantik estetika rumah Anda.</textarea>
                </div>
            </div>

            <div class="card">
                <div class="card-title"><i class="ti ti-receipt"></i> Harga & Stok</div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="harga">Harga Jual</label>
                        <div class="input-prefix-group">
                            <span class="input-prefix">Rp</span>
                            <input type="number" id="harga" name="harga" class="form-control" value="750000" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="stok">Jumlah Stok</label>
                        <input type="number" id="stok" name="stok" class="form-control" value="45" required>
                    </div>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: Media & Status --}}
        <div class="form-right">
            <div class="card">
                <div class="card-title"><i class="ti ti-photo"></i> Foto Produk</div>
                
                {{-- Preview Foto Saat Ini (menggunakan placeholder ikon sesuai dengan index dummy) --}}
                <div class="current-img-preview">
                    <i class="ti ti-armchair"></i>
                </div>

                <div class="form-group">
                    <div class="upload-zone" onclick="document.getElementById('file-input').click()">
                        <i class="ti ti-cloud-upload"></i>
                        <span>Ganti Foto Baru</span>
                        <input type="file" id="file-input" name="foto" style="display: none;" accept="image/*">
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-title"><i class="ti ti-toggle-channels"></i> Visibilitas</div>
                <div class="form-group">
                    <label class="form-label" for="status">Status Produk</label>
                    <div class="select-wrapper">
                        <i class="ti ti-circle-check prefix-icon"></i>
                        <select id="status" name="status" class="form-control">
                            <option value="Aktif" selected>Aktif (Tampil di Toko)</option>
                            <option value="Nonaktif">Nonaktif (Arsip)</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.product.index') }}" class="btn-cancel">Batal</a>
                <button type="submit" class="btn-submit"><i class="ti ti-device-floppy"></i> Update Produk</button>
            </div>
        </div>

    </div>
</form>
@endsection