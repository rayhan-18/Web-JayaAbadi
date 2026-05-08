@extends('layouts.admin')

@section('title', 'Pesanan')

@section('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }
    .page-title h1 {
        font-size: 24px;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }
    .page-title .breadcrumb {
        font-size: 13px;
        color: #6b7280;
        margin-top: 4px;
    }
    .stats-row {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 16px;
        margin-bottom: 28px;
    }
    .stat-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        padding: 20px 16px;
        text-align: center;
        transition: 0.2s;
    }
    .stat-card .stat-icon {
        font-size: 28px;
        margin-bottom: 8px;
    }
    .stat-card .stat-label {
        font-size: 14px;
        font-weight: 500;
        color: #6b7280;
        margin-bottom: 8px;
    }
    .stat-card .stat-value {
        font-size: 32px;
        font-weight: 700;
        letter-spacing: -0.02em;
    }
    .stat-card.all .stat-value { color: #1f2937; }
    .stat-card.pending .stat-value { color: #f59e0b; }
    .stat-card.proses .stat-value { color: #3b82f6; }
    .stat-card.kirim .stat-value { color: #8b5cf6; }
    .stat-card.selesai .stat-value { color: #10b981; }
    .filter-bar {
        display: flex;
        gap: 12px;
        margin-bottom: 24px;
        flex-wrap: wrap;
        align-items: center;
    }
    .search-box {
        display: flex;
        align-items: center;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 6px 12px;
        gap: 8px;
        flex: 1;
        max-width: 300px;
    }
    .search-box input {
        border: none;
        outline: none;
        font-size: 13px;
        width: 100%;
    }
    .filter-select {
        padding: 6px 12px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background: #fff;
        font-size: 13px;
        cursor: pointer;
    }
    .btn-export {
        background: #fff;
        border: 1px solid #e5e7eb;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: 0.2s;
    }
    .btn-export:hover { background: #f9fafb; }
    .export-dropdown {
        position: relative;
        display: inline-block;
    }
    .export-dropdown-content {
        display: none;
        position: absolute;
        right: 0;
        background: #fff;
        min-width: 160px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        border-radius: 8px;
        z-index: 1;
        border: 1px solid #e5e7eb;
    }
    .export-dropdown-content a {
        padding: 8px 16px;
        display: block;
        text-decoration: none;
        color: #374151;
        font-size: 13px;
    }
    .export-dropdown-content a:hover { background: #f3f4f6; }
    .export-dropdown:hover .export-dropdown-content { display: block; }

    /* LAYOUT TABEL + DETAIL PANEL */
    .layout-order {
        display: flex;
        gap: 20px;
    }
    .table-section {
        flex: 1;
        min-width: 0;
    }
    .detail-panel {
        width: 340px;
        flex-shrink: 0;
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        display: none;
        flex-direction: column;
        position: sticky;
        top: 90px;
        max-height: calc(100vh - 110px);
        overflow-y: auto;
    }
    .detail-panel.open { display: flex; }
    .dp-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 20px;
        border-bottom: 1px solid #f3f4f6;
        background: #fff;
        position: sticky;
        top: 0;
        z-index: 1;
    }
    .dp-header h3 { font-size: 16px; font-weight: 700; }
    .dp-close {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 14px;
        color: #6b7280;
    }
    .dp-body { padding: 16px 20px; }
    .dp-order-id { font-size: 16px; font-weight: 700; margin-bottom: 4px; }
    .dp-row { display: flex; gap: 12px; margin-bottom: 12px; }
    .dp-label { font-size: 12px; color: #6b7280; font-weight: 500; min-width: 100px; }
    .dp-value { font-size: 13px; color: #1f2937; font-weight: 500; flex: 1; }
    .dp-section-title { font-size: 13px; font-weight: 700; margin: 16px 0 10px; }
    .dp-divider { border: none; border-top: 1px solid #f3f4f6; margin: 12px 0; }
    .dp-product {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
    }
    .dp-product-img {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }
    .dp-product-name { font-size: 13px; font-weight: 600; color: #1f2937; }
    .dp-product-qty { font-size: 11px; color: #6b7280; margin-top: 2px; }
    .dp-product-price { margin-left: auto; font-size: 13px; font-weight: 600; white-space: nowrap; }
    .dp-total-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: 13px;
    }
    .dp-total-row .lbl { color: #6b7280; }
    .dp-total-row .val { font-weight: 500; }
    .dp-total-row.grand .lbl { font-weight: 700; color: #1f2937; }
    .dp-total-row.grand .val { font-weight: 700; color: #1a2e22; font-size: 16px; }
    .btn-update {
        width: 100%;
        padding: 10px;
        background: #1a2e22;
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        margin-top: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    .btn-update:hover { background: #2d4a35; }

    .table-wrapper {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        overflow-x: auto;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }
    th {
        text-align: left;
        padding: 14px 16px;
        background: #f9fafb;
        font-weight: 600;
        color: #4b5563;
        border-bottom: 1px solid #e5e7eb;
        white-space: nowrap;
    }
    td {
        padding: 14px 16px;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
    }
    tbody tr:hover { background: #f9fafb; }

    .customer-info { line-height: 1.4; }
    .customer-name { font-weight: 500; color: #1f2937; }
    .customer-email { font-size: 11px; color: #9ca3af; margin-top: 2px; }
    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
    }
    .status-pending { background: #fef3c7; color: #b45309; }
    .status-diproses { background: #dbeafe; color: #1d4ed8; }
    .status-dikirim { background: #e0e7ff; color: #4338ca; }
    .status-selesai { background: #dcfce7; color: #15803d; }
    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #6b7280;
        font-size: 16px;
        transition: 0.15s;
    }
    .action-btn:hover { background: #f3f4f6; color: #374151; }

    .pagination {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 24px;
        font-size: 13px;
        color: #6b7280;
    }
    .pagination-links {
        display: flex;
        gap: 8px;
    }
    .pagination-links a, .pagination-links span {
        padding: 6px 10px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        text-decoration: none;
        color: #4b5563;
    }
    .pagination-links a:hover { background: #f3f4f6; }
    .pagination-links .active {
        background: #4a7c5e;
        border-color: #4a7c5e;
        color: white;
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1>Pesanan</h1>
        <div class="breadcrumb">Dashboard / Pesanan</div>
    </div>
    <div class="export-dropdown">
        <button class="btn-export">📥 Export Laporan ▾</button>
        <div class="export-dropdown-content">
            <a href="#">Export PDF</a>
            <a href="#">Export Excel</a>
            <a href="#">Export CSV</a>
        </div>
    </div>
</div>

<!-- Statistik Cards -->
<div class="stats-row">
    <div class="stat-card all"><div class="stat-icon">📦</div><div class="stat-label">Semua Pesanan</div><div class="stat-value">120</div></div>
    <div class="stat-card pending"><div class="stat-icon">⏳</div><div class="stat-label">Pending</div><div class="stat-value">12</div></div>
    <div class="stat-card proses"><div class="stat-icon">⚙️</div><div class="stat-label">Diproses</div><div class="stat-value">25</div></div>
    <div class="stat-card kirim"><div class="stat-icon">🚚</div><div class="stat-label">Dikirim</div><div class="stat-value">45</div></div>
    <div class="stat-card selesai"><div class="stat-icon">✅</div><div class="stat-label">Selesai</div><div class="stat-value">38</div></div>
</div>

<!-- Filter -->
<div class="filter-bar">
    <div class="search-box"><span>🔍</span><input type="text" placeholder="Cari pesanan..."></div>
    <select class="filter-select"><option>Semua Status</option><option>Pending</option><option>Diproses</option><option>Dikirim</option><option>Selesai</option></select>
</div>

<!-- Layout Tabel + Detail Panel -->
<div class="layout-order">
    <div class="table-section">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Pelanggan</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Metode Pembayaran</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $orders = [
                            ['id'=>'#ORD-00123','nama'=>'Rayhan Maulana','email'=>'rayhan@gmail.com','tanggal'=>'26 Mei 2024 10:30 WIB','total'=>2750000,'metode'=>'E-Wallet OVO','status'=>'Diproses','alamat'=>'Jl. Merdeka No. 123, Bandung','items'=>[['nama'=>'Kursi Minimalis Kayu','qty'=>1,'harga'=>2750000,'emoji'=>'🪑']],'subtotal'=>2750000,'ongkir'=>50000,'total2'=>2800000,'hp'=>'081234567890'],
                            ['id'=>'#ORD-00122','nama'=>'Siti Aisyah','email'=>'aisyah@gmail.com','tanggal'=>'25 Mei 2024 15:45 WIB','total'=>1850000,'metode'=>'OVO','status'=>'Diproses','alamat'=>'Jl. Kenanga No. 5, Bandung','items'=>[['nama'=>'Meja Samping Walnut','qty'=>1,'harga'=>1850000,'emoji'=>'🪵']],'subtotal'=>1850000,'ongkir'=>50000,'total2'=>1900000,'hp'=>'081298765432'],
                            ['id'=>'#ORD-00121','nama'=>'Budi Santoso','email'=>'budi@gmail.com','tanggal'=>'25 Mei 2024 11:20 WIB','total'=>3200000,'metode'=>'Transfer Bank Mandiri ****5678','status'=>'Diproses','alamat'=>'Jl. Sudirman No. 88, Jakarta Pusat','items'=>[['nama'=>'Sofa 3 Seater Premium','qty'=>1,'harga'=>3200000,'emoji'=>'🛋️']],'subtotal'=>3200000,'ongkir'=>75000,'total2'=>3275000,'hp'=>'085612345678'],
                            ['id'=>'#ORD-00120','nama'=>'Dewi Anggraini','email'=>'dewii@gmail.com','tanggal'=>'24 Mei 2024 09:15 WIB','total'=>950000,'metode'=>'COD Bayar di tempat','status'=>'Pending','alamat'=>'Jl. Mawar No. 12, Surabaya','items'=>[['nama'=>'Rak Buku Minimalis','qty'=>1,'harga'=>950000,'emoji'=>'📚']],'subtotal'=>950000,'ongkir'=>0,'total2'=>950000,'hp'=>'087700011234'],
                            ['id'=>'#ORD-00119','nama'=>'Ahmad Fauzi','email'=>'ahmad@gmail.com','tanggal'=>'24 Mei 2024 16:50 WIB','total'=>4500000,'metode'=>'Credit Card VISA ****4567','status'=>'Dikirim','alamat'=>'Jl. Gatot Subroto No. 45, Semarang','items'=>[['nama'=>'Tempat Tidur King Size','qty'=>1,'harga'=>3500000,'emoji'=>'🛏️'],['nama'=>'Nakas Minimalis','qty'=>2,'harga'=>500000,'emoji'=>'🗄️']],'subtotal'=>4500000,'ongkir'=>100000,'total2'=>4600000,'hp'=>'082155556666'],
                            ['id'=>'#ORD-00118','nama'=>'Nina Karlina','email'=>'nina@gmail.com','tanggal'=>'23 Mei 2024 12:10 WIB','total'=>1250000,'metode'=>'E-Wallet DANA','status'=>'Selesai','alamat'=>'Jl. Pahlawan No. 3, Yogyakarta','items'=>[['nama'=>'Kursi Makan Set 4','qty'=>1,'harga'=>1250000,'emoji'=>'🪑']],'subtotal'=>1250000,'ongkir'=>50000,'total2'=>1300000,'hp'=>'081288889999'],
                            ['id'=>'#ORD-00117','nama'=>'Rizky Pratama','email'=>'rizky@gmail.com','tanggal'=>'23 Mei 2024 10:05 WIB','total'=>2150000,'metode'=>'Transfer Bank BNI ****7890','status'=>'Selesai','alamat'=>'Jl. Diponegoro No. 77, Malang','items'=>[['nama'=>'Lemari Hias Minimalis','qty'=>1,'harga'=>1650000,'emoji'=>'🚪'],['nama'=>'Cermin Dinding Oval','qty'=>1,'harga'=>500000,'emoji'=>'🪞']],'subtotal'=>2150000,'ongkir'=>50000,'total2'=>2200000,'hp'=>'085677778888'],
                            ['id'=>'#ORD-00116','nama'=>'Larasati Putri','email'=>'larasati@gmail.com','tanggal'=>'22 Mei 2024 14:35 WIB','total'=>3750000,'metode'=>'Credit Card Mastercard ****3210','status'=>'Dikirim','alamat'=>'Jl. Ahmad Yani No. 20, Medan','items'=>[['nama'=>'Meja Kerja Ergonomis','qty'=>1,'harga'=>3750000,'emoji'=>'🖥️']],'subtotal'=>3750000,'ongkir'=>120000,'total2'=>3870000,'hp'=>'081344443333'],
                        ];
                    @endphp
                    @foreach($orders as $index => $o)
                    <tr>
                        <td style="font-weight:600;">{{ $o['id'] }}</td>
                        <td>
                            <div class="customer-info">
                                <div class="customer-name">{{ $o['nama'] }}</div>
                                <div class="customer-email">{{ $o['email'] }}</div>
                            </div>
                        </td>
                        <td>{{ $o['tanggal'] }}</td>
                        <td>Rp {{ number_format($o['total'], 0, ',', '.') }}</td>
                        <td>{{ $o['metode'] }}</td>
                        <td>
                            @php
                                $statusClass = match($o['status']) {
                                    'Pending' => 'status-pending',
                                    'Diproses' => 'status-diproses',
                                    'Dikirim' => 'status-dikirim',
                                    'Selesai' => 'status-selesai',
                                    default => ''
                                };
                            @endphp
                            <span class="status-badge {{ $statusClass }}">{{ $o['status'] }}</span>
                        </td>
                        <td>
                            <div class="action-btn" onclick="showDetail({{ $index }})">👁️</div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pagination">
            <div>Menampilkan 1 - 8 dari 120 pesanan</div>
            <div class="pagination-links">
                <span class="active">1</span>
                <a href="#">2</a>
                <a href="#">3</a>
                <a href="#">4</a>
                <span>...</span>
                <a href="#">15</a>
                <a href="#">→</a>
            </div>
        </div>
    </div>

    <!-- Detail Panel (sebelah kanan) -->
    <div class="detail-panel" id="detailPanel">
        <div class="dp-header">
            <h3>Detail Pesanan</h3>
            <div class="dp-close" onclick="closeDetail()">✕</div>
        </div>
        <div class="dp-body" id="detailBody"></div>
    </div>
</div>

<script>
    // Data orders dari Blade (diencode ke JavaScript)
    const orders = @json($orders);

    function formatRupiah(n) {
        return 'Rp ' + n.toLocaleString('id-ID');
    }

    function getStatusClass(status) {
        const map = {
            'Pending': 'status-pending',
            'Diproses': 'status-diproses',
            'Dikirim': 'status-dikirim',
            'Selesai': 'status-selesai'
        };
        return map[status] || '';
    }

    function showDetail(index) {
        const o = orders[index];
        const panel = document.getElementById('detailPanel');
        const body = document.getElementById('detailBody');

        const itemsHtml = o.items.map(item => `
            <div class="dp-product">
                <div class="dp-product-img">${item.emoji}</div>
                <div>
                    <div class="dp-product-name">${item.nama}</div>
                    <div class="dp-product-qty">Qty: ${item.qty}</div>
                </div>
                <div class="dp-product-price">${formatRupiah(item.harga)}</div>
            </div>
        `).join('');

        body.innerHTML = `
            <div class="dp-order-id">${o.id}</div>
            <div class="dp-row">
                <div class="dp-label">Status</div>
                <div class="dp-value"><span class="status-badge ${getStatusClass(o.status)}">${o.status}</span></div>
            </div>
            <div class="dp-row">
                <div class="dp-label">Tanggal</div>
                <div class="dp-value">${o.tanggal}</div>
            </div>
            <div class="dp-row">
                <div class="dp-label">Pelanggan</div>
                <div class="dp-value">
                    ${o.nama}<br>
                    <span style="color:#9ca3af;font-size:11px;">${o.email}</span><br>
                    <span style="color:#9ca3af;font-size:11px;">${o.hp || '-'}</span>
                </div>
            </div>
            <div class="dp-row">
                <div class="dp-label">Alamat</div>
                <div class="dp-value" style="font-size:12px;">${o.alamat}</div>
            </div>
            <div class="dp-row">
                <div class="dp-label">Metode Bayar</div>
                <div class="dp-value">${o.metode}</div>
            </div>
            <hr class="dp-divider">
            <div class="dp-section-title">Produk (${o.items.length})</div>
            ${itemsHtml}
            <hr class="dp-divider">
            <div class="dp-total-row">
                <span class="lbl">Subtotal</span>
                <span class="val">${formatRupiah(o.subtotal)}</span>
            </div>
            <div class="dp-total-row">
                <span class="lbl">Ongkos Kirim</span>
                <span class="val">${formatRupiah(o.ongkir)}</span>
            </div>
            <hr class="dp-divider">
            <div class="dp-total-row grand">
                <span class="lbl">Total</span>
                <span class="val">${formatRupiah(o.total2)}</span>
            </div>
            <button class="btn-update" onclick="event.stopPropagation(); alert('Update status untuk ${o.id}')">Update Status</button>
        `;

        panel.classList.add('open');
    }

    function closeDetail() {
        document.getElementById('detailPanel').classList.remove('open');
    }
</script>
@endsection