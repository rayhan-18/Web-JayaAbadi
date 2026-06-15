@extends('layouts.admin')

@section('title', 'Edit Produk')

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

    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
    .page-title h1 { font-size: 24px; font-weight: 700; color: var(--text-main); margin: 0; letter-spacing: -0.02em; }
    .page-title .breadcrumb { font-size: 13.5px; color: var(--text-sec); margin-top: 4px; }

    .form-grid { display: grid; grid-template-columns: 1fr 380px; gap: 24px; align-items: start; }

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

    .input-prefix-group { position: relative; display: flex; align-items: center; }
    .input-prefix { position: absolute; left: 16px; font-size: 14px; font-weight: 600; color: var(--text-sec); pointer-events: none; }
    .input-prefix-group .form-control { padding-left: 44px; }

    .select-wrapper { position: relative; }
    .select-wrapper i.prefix-icon { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: var(--text-sec); font-size: 18px; pointer-events: none; }
    .select-wrapper .form-control {
        padding-left: 44px; padding-right: 40px; appearance: none;
        background: var(--bg-surface) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23475569' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E") no-repeat right 16px center;
        cursor: pointer;
    }

    /* ── Image Preview & Upload ── */
    .img-preview-wrapper {
        width: 100%; border-radius: var(--radius-md); overflow: hidden;
        border: 1px solid var(--border); margin-bottom: 16px; background: #f8fafc;
        display: flex; align-items: center; justify-content: center; min-height: 180px;
    }
    .img-preview-wrapper img { width: 100%; height: 200px; object-fit: cover; display: block; }
    .img-placeholder { display: flex; flex-direction: column; align-items: center; gap: 8px; padding: 32px; color: var(--text-muted); }
    .img-placeholder i { font-size: 40px; }
    .img-placeholder span { font-size: 13px; font-weight: 500; }

    .upload-zone {
        border: 2px dashed #cbd5e1; border-radius: var(--radius-md); padding: 24px 16px;
        text-align: center; cursor: pointer; transition: all 0.2s ease; background: #ffffff;
        display: flex; flex-direction: column; align-items: center; gap: 8px;
    }
    .upload-zone:hover { border-color: var(--accent); background: #eff6ff; }
    .upload-zone i { font-size: 28px; color: var(--accent); }
    .upload-zone span { font-size: 13px; font-weight: 600; color: var(--text-main); }
    .upload-zone p { font-size: 11px; color: var(--text-muted); margin: 0; }

    .form-row { display: flex; gap: 20px; }
    .form-row .form-group { flex: 1; }

    .form-actions { display: flex; align-items: center; justify-content: flex-end; gap: 16px; margin-top: 16px; }

    .btn-submit {
        background-color: var(--accent) !important; color: #ffffff !important;
        padding: 14px 28px; border-radius: 12px; font-size: 14px; font-weight: 600;
        display: inline-flex; align-items: center; gap: 8px; border: none; outline: none; cursor: pointer;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2); transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        width: 100%; justify-content: center;
    }
    .btn-submit:hover { background-color: var(--accent-dark) !important; transform: translateY(-2px); box-shadow: 0 6px 15px rgba(30, 64, 175, 0.3); }
    .btn-submit:active { transform: scale(0.97); }

    /* Alert error */
    .alert-error {
        background: #fef2f2; border: 1px solid #fecaca; border-radius: var(--radius-md);
        padding: 16px 20px; margin-bottom: 24px; font-size: 13.5px; color: #ef4444;
    }
    .alert-error ul { margin: 8px 0 0 16px; padding: 0; }

    /* ── Responsive Mobile ── */
    @media (max-width: 1024px) {
        .form-grid { grid-template-columns: 1fr; gap: 0; }
        .card { padding: 20px; }
        .form-row { flex-direction: column; gap: 0; }
        .page-header { flex-direction: column; align-items: flex-start; gap: 16px;}
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1>Edit Produk</h1>
        <div class="breadcrumb">FurniHome / Produk / Edit</div>
    </div>
</div>

@if($errors->any())
    <div class="alert-error">
        <strong><i class="ti ti-alert-triangle"></i> Terdapat kesalahan:</strong>
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="form-grid">

        {{-- KOLOM KIRI --}}
        <div class="form-left">
            <div class="card">
                <div class="card-title"><i class="ti ti-info-circle"></i> Informasi Produk</div>

                <div class="form-group">
                    <label class="form-label">Nama Produk</label>
                    <input type="text" name="name" class="form-control"
                           value="{{ old('name', $product->name) }}"
                           placeholder="Contoh: Kursi Minimalis Kayu Jati" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Kategori</label>
                        <div class="select-wrapper">
                            <i class="ti ti-category prefix-icon"></i>
                            <select name="category_id" class="form-control" required>
                                <option value="" disabled>Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">SKU (Kode Produk)</label>
                        <input type="text" name="sku" class="form-control"
                               value="{{ old('sku', $product->slug) }}"
                               placeholder="Contoh: KRS-001">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi Lengkap</label>
                    <textarea name="description" class="form-control"
                              placeholder="Tuliskan deskripsi produk...">{{ old('description', $product->description) }}</textarea>
                </div>
            </div>

            <div class="card">
                <div class="card-title"><i class="ti ti-receipt"></i> Harga & Stok</div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Harga Jual</label>
                        <div class="input-prefix-group">
                            <span class="input-prefix">Rp</span>
                            <input type="number" name="price" class="form-control"
                                   value="{{ old('price', $product->price) }}"
                                   placeholder="0" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Harga Diskon (opsional)</label>
                        <div class="input-prefix-group">
                            <span class="input-prefix">Rp</span>
                            <input type="number" name="sale_price" class="form-control"
                                   value="{{ old('sale_price', $product->sale_price) }}"
                                   placeholder="0">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Jumlah Stok</label>
                    <input type="number" name="stock" class="form-control"
                           value="{{ old('stock', $product->stock) }}"
                           placeholder="0" required>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN --}}
        <div class="form-right">
            <div class="card">
                <div class="card-title"><i class="ti ti-photo"></i> Foto Produk</div>

                {{-- Preview foto saat ini --}}
                <div class="img-preview-wrapper" id="preview-wrapper">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}"
                             alt="{{ $product->name }}" id="image-preview">
                    @else
                        <div class="img-placeholder" id="img-placeholder">
                            <i class="ti ti-photo-off"></i>
                            <span>Belum ada foto</span>
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <div class="upload-zone" onclick="document.getElementById('file-input').click()">
                        <i class="ti ti-cloud-upload"></i>
                        <span id="upload-text">Ganti Foto Baru</span>
                        <p>Format PNG, JPG, JPEG maks. 2MB</p>
                    </div>
                    <input type="file" id="file-input" name="image"
                           style="display: none;" accept="image/*">
                </div>
            </div>

            <div class="card">
                <div class="card-title"><i class="ti ti-toggle-channels"></i> Visibilitas</div>
                <div class="form-group">
                    <label class="form-label">Status Produk</label>
                    <div class="select-wrapper">
                        <i class="ti ti-circle-check prefix-icon"></i>
                        <select name="is_active" class="form-control">
                            <option value="1" {{ old('is_active', $product->is_active) == 1 ? 'selected' : '' }}>
                                Aktif (Tampil di Toko)
                            </option>
                            <option value="0" {{ old('is_active', $product->is_active) == 0 ? 'selected' : '' }}>
                                Nonaktif (Arsip)
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    <i class="ti ti-device-floppy"></i> Update Produk
                </button>
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
                const wrapper = document.getElementById('preview-wrapper');
                wrapper.innerHTML = `<img src="${e.target.result}" alt="Preview" id="image-preview" style="width:100%; height:200px; object-fit:cover; display:block;">`;
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection