<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        font-family: 'DejaVu Sans', sans-serif;
        font-size: 11px;
        color: #2d3b32;
        padding: 32px;
        background: #fff;
    }

    /* Header */
    .header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 2px solid #5c9e74;
    }
    .header-left h1 {
        font-size: 18px;
        font-weight: 700;
        color: #2d3b32;
        letter-spacing: -0.02em;
    }
    .header-left p {
        font-size: 11px;
        color: #7a9080;
        margin-top: 4px;
    }
    .header-right {
        text-align: right;
        font-size: 10px;
        color: #9aada2;
    }
    .header-right strong {
        display: block;
        font-size: 13px;
        color: #5c9e74;
        font-weight: 700;
        margin-bottom: 2px;
    }

    /* Filter Info */
    .filter-info {
        background: #f5f7f4;
        border: 1px solid #e6e9e4;
        border-radius: 6px;
        padding: 8px 14px;
        margin-bottom: 20px;
        font-size: 11px;
        color: #7a9080;
        display: flex;
        gap: 20px;
    }
    .filter-info span strong { color: #2d3b32; }

    /* Table */
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 11px;
    }
    thead tr {
        background: #3a5c48;
        color: #ffffff;
    }
    th {
        padding: 10px 12px;
        text-align: left;
        font-weight: 600;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        white-space: nowrap;
    }
    td {
        padding: 9px 12px;
        border-bottom: 1px solid #e6e9e4;
        vertical-align: middle;
        color: #2d3b32;
    }
    tbody tr:nth-child(even) { background: #f9fbf9; }
    tbody tr:last-child td { border-bottom: none; }

    /* Badges */
    .badge {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 10px;
        font-weight: 700;
    }
    .badge-aktif    { background: #e8f0eb; color: #3a5c48; }
    .badge-nonaktif { background: #fdf5f5; color: #c47a7a; }

    /* Footer */
    .footer {
        margin-top: 20px;
        padding-top: 12px;
        border-top: 1px solid #e6e9e4;
        display: flex;
        justify-content: space-between;
        font-size: 10px;
        color: #9aada2;
    }
    .footer strong { color: #5c9e74; }
</style>
</head>
<body>

    <div class="header">
        <div class="header-left">
            <h1>Laporan Data Produk</h1>
            <p>FurniHome — Sistem Manajemen Toko</p>
        </div>
        <div class="header-right">
            <strong>FurniHome</strong>
            Digenerate: {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>

    <div class="filter-info">
        <span>Kategori: <strong>{{ $filterCategory }}</strong></span>
        <span>Status: <strong>{{ $filterStatus }}</strong></span>
        @if($filterSearch)
        <span>Pencarian: <strong>"{{ $filterSearch }}"</strong></span>
        @endif
        <span>Total: <strong>{{ $products->count() }} produk</strong></span>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">#</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th>Harga Jual</th>
                <th style="text-align: center;">Stok</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $i => $product)
            <tr>
                <td style="color: #9aada2;">{{ $i + 1 }}</td>
                <td>
                    <strong>{{ $product->name }}</strong><br>
                    <span style="font-size: 9px; color: #9aada2;">{{ $product->slug }}</span>
                </td>
                <td>{{ $product->category->name ?? '-' }}</td>
                <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                <td style="text-align: center;">
                    <strong style="{{ $product->stock <= 5 ? 'color:#c47a7a;' : '' }}">
                        {{ $product->stock }}
                    </strong>
                </td>
                <td>
                    <span class="badge {{ $product->is_active ? 'badge-aktif' : 'badge-nonaktif' }}">
                        {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 20px; color: #9aada2;">
                    Tidak ada produk ditemukan.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <span>Total Produk: <strong>{{ $products->count() }}</strong> | 
              Total Nilai Stok: <strong>Rp {{ number_format($products->sum(fn($p) => $p->price * $p->stock), 0, ',', '.') }}</strong>
        </span>
        <span>FurniHome © {{ now()->year }}</span>
    </div>

</body>
</html>