@extends('layouts.admin')

@section('title', 'Riwayat Pembayaran')

@section('styles')
<style>
    /* Premium Variables */
    :root {
        --accent: #5c9e74;
        --accent-dark: #3a5c48;
        --accent-light: #e8f0eb;
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
        display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;
    }
    .page-title h1 {
        font-size: 22px; font-weight: 700; color: var(--text-main); margin: 0; letter-spacing: -0.02em;
    }
    .page-title .breadcrumb {
        font-size: 13px; color: var(--text-sec); margin-top: 4px;
    }

    /* Stats Grid */
    .stats-row {
        display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px;
    }
    .stat-card {
        background: var(--bg-surface); border-radius: var(--radius-lg); border: 1px solid var(--border);
        padding: 20px 16px; text-align: center; transition: all 0.2s ease;
    }
    .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(45, 59, 50, 0.04); }
    
    .stat-icon {
        width: 42px; height: 42px; border-radius: var(--radius-md);
        display: flex; align-items: center; justify-content: center;
        font-size: 24px; margin: 0 auto 12px;
    }
    
    .stat-card.total .stat-icon   { background: transparent; color: var(--text-main); }
    .stat-card.pending .stat-icon { background: transparent; color: #b89247; }
    .stat-card.success .stat-icon { background: transparent; color: var(--accent); }
    .stat-card.failed .stat-icon  { background: transparent; color: #c47a7a; }

    .stat-label { font-size: 12.5px; font-weight: 600; color: var(--text-sec); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.02em; }
    .stat-value { font-size: 24px; font-weight: 700; color: var(--text-main); letter-spacing: -0.02em; }
    
    /* Filters */
    .filter-bar { display: flex; gap: 12px; margin-bottom: 24px; flex-wrap: wrap; align-items: center; }
    .search-box {
        display: flex; align-items: center; background: var(--bg-surface);
        border: 1px solid var(--border); border-radius: var(--radius-md);
        padding: 0 14px; gap: 10px; flex: 1; max-width: 320px; height: 40px; transition: all 0.2s;
    }
    .search-box:focus-within { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(92, 158, 116, 0.15); }
    .search-box i { color: var(--text-sec); font-size: 16px; }
    .search-box input { border: none; outline: none; font-size: 13px; width: 100%; color: var(--text-main); background: transparent; }
    .search-box input::placeholder { color: var(--text-muted); }
    
    .select-wrapper { position: relative; display: inline-block; }
    .select-wrapper i.prefix-icon {
        position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-sec); font-size: 15px; pointer-events: none;
    }
    .filter-select {
        height: 40px; padding: 0 36px 0 34px; border: 1px solid var(--border); border-radius: var(--radius-md);
        background: var(--bg-surface) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%237a9080' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E") no-repeat right 12px center;
        appearance: none; font-size: 13px; font-weight: 500; color: var(--text-main); cursor: pointer; transition: all 0.2s; min-width: 160px;
    }
    .filter-select:hover { background: var(--bg-hover); border-color: #d1d6cf; }
    .filter-select:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px rgba(92, 158, 116, 0.15); }

    /* Export Laporan Dropdown */
    .export-dropdown { position: relative; display: inline-block; }
    .btn-export {
        background: var(--bg-surface); color: var(--text-main); border: 1px solid var(--border);
        padding: 0 16px; height: 40px; border-radius: var(--radius-md); font-size: 13px; font-weight: 600;
        display: inline-flex; align-items: center; justify-content: center; gap: 8px; cursor: pointer; transition: 0.2s;
    }
    .btn-export i { font-size: 16px; color: var(--text-sec); }
    .btn-export:hover { background: var(--bg-hover); border-color: #d1d6cf; }
    
    .export-dropdown-content {
        display: none; position: absolute; right: 0; top: 46px; background: var(--bg-surface);
        min-width: 180px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); border-radius: 10px;
        z-index: 10; border: 1px solid var(--border); overflow: hidden;
    }
    .export-dropdown-content a {
        padding: 10px 16px; display: flex; align-items: center; gap: 10px; text-decoration: none;
        color: var(--text-main); font-size: 13px; font-weight: 500; border-bottom: 1px solid #f0f2ef;
    }
    .export-dropdown-content a:last-child { border-bottom: none; }
    .export-dropdown-content a i { color: var(--text-sec); font-size: 15px; }
    .export-dropdown-content a:hover { background: var(--bg-hover); color: var(--accent); }
    .export-dropdown:hover .export-dropdown-content { display: block; }

    /* Layout & Table (Disamakan dengan Halaman Pesanan) */
    .layout-order { display: flex; gap: 20px; align-items: flex-start; }
    .table-section { flex: 1; min-width: 0; }
    
    .table-wrapper { background: var(--bg-surface); border-radius: var(--radius-lg); border: 1px solid var(--border); overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; font-size: 13px; min-width: 960px; }
    th {
        text-align: left; padding: 14px 20px; background: var(--bg-hover); font-weight: 600;
        color: var(--text-sec); border-bottom: 1px solid var(--border); font-size: 12px;
        text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap;
    }
    td { padding: 14px 20px; border-bottom: 1px solid var(--border); vertical-align: middle; color: var(--text-main); }
    tr:last-child td { border-bottom: none; }
    tbody tr:hover { background: var(--bg-hover); }

    .invoice-link { font-weight: 700; color: var(--text-main); text-decoration: none; word-break: break-word; }
    .invoice-link:hover { color: var(--accent); }

    .customer-info { line-height: 1.4; }
    .customer-name { font-weight: 600; color: var(--text-main); font-size: 13.5px; }
    .customer-email { font-size: 11.5px; color: var(--text-muted); margin-top: 2px; } 

    /* Premium Status Muted Badges (Payment Custom) */
    .status-badge {
        display: inline-flex; align-items: center; justify-content: center;
        padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; letter-spacing: 0.02em; white-space: nowrap;
    }
    .status-konfirmasi { background: #fdf5e6; color: #8a5a2e; }
    .status-berhasil   { background: var(--accent-light); color: var(--accent-dark); }
    .status-gagal      { background: #fdf5f5; color: #c47a7a; }
    
    .status-badge::before { content: ''; width: 6px; height: 6px; border-radius: 50%; margin-right: 6px; }
    .status-konfirmasi::before { background: #d99e52; }
    .status-berhasil::before   { background: var(--accent); }
    .status-gagal::before      { background: #c47a7a; }

    .action-btn {
        width: 32px; height: 32px; border-radius: 8px; background: var(--bg-surface);
        border: 1px solid var(--border); display: inline-flex; align-items: center; justify-content: center;
        cursor: pointer; color: var(--text-sec); font-size: 16px; transition: 0.15s; flex-shrink: 0;
    }
    .action-btn:hover { background: var(--bg-hover); color: var(--accent); border-color: #d1d6cf; }

    /* Pagination */
    .pagination { display: flex; justify-content: space-between; align-items: center; margin-top: 24px; font-size: 13px; color: var(--text-sec); flex-wrap: wrap; gap: 12px; }
    .pagination-links { display: flex; gap: 6px; }
    .pagination-links a, .pagination-links span {
        display: inline-flex; align-items: center; justify-content: center; min-width: 30px; height: 30px; padding: 0 10px;
        border: 1px solid var(--border); border-radius: 6px; text-decoration: none; color: var(--text-main); font-weight: 500;
        transition: 0.15s; background: var(--bg-surface);
    }
    .pagination-links a:hover { background: var(--bg-hover); border-color: var(--accent); color: var(--accent); }
    .pagination-links .active { background: var(--accent); border-color: var(--accent); color: white; }

    /* Detail Panel Sidebar (Disamakan dengan Halaman Pesanan) */
    .detail-panel {
        width: 360px; flex-shrink: 0; background: #ffffff !important; border-radius: var(--radius-lg);
        border: 1px solid var(--border); display: none; flex-direction: column;
        position: sticky; top: 80px; max-height: calc(100vh - 100px); overflow-y: auto;
        box-shadow: -4px 0 24px rgba(0, 0, 0, 0.04);
    }
    .detail-panel.open { display: flex; animation: slideIn 0.3s ease-out; }
    @keyframes slideIn { from { opacity: 0; transform: translateX(20px); } to { opacity: 1; transform: translateX(0); } }
    
    .detail-panel::-webkit-scrollbar { width: 5px; }
    .detail-panel::-webkit-scrollbar-track { background: transparent; }
    .detail-panel::-webkit-scrollbar-thumb { background: #d1d6cf; border-radius: 10px; }

    .dp-header {
        display: flex; justify-content: space-between; align-items: center; padding: 18px 20px;
        border-bottom: 1px solid var(--border); background: #ffffff !important;
        position: sticky; top: 0; z-index: 20;
    }
    .dp-header h3 { font-size: 15px; font-weight: 700; margin: 0; color: var(--text-main); }
    .dp-close {
        width: 30px; height: 30px; border-radius: 8px; background: var(--bg-hover); border: 1px solid transparent;
        display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 16px; color: var(--text-sec); transition: 0.2s;
    }
    .dp-close:hover { background: #fdf5f5; color: #c47a7a; border-color: #e8caca; }
    
    .dp-body { padding: 20px; }
    .dp-invoice-id { font-size: 18px; font-weight: 700; color: var(--text-main); margin-bottom: 16px; letter-spacing: -0.02em; word-break: break-all; }
    .dp-row { display: flex; gap: 12px; margin-bottom: 14px; align-items: flex-start; }
    .dp-label { font-size: 12.5px; color: var(--text-sec); font-weight: 500; min-width: 100px; }
    .dp-value { font-size: 13px; color: var(--text-main); font-weight: 500; flex: 1; line-height: 1.4; }
    
    .dp-section-title { font-size: 13px; font-weight: 700; color: var(--text-main); margin: 20px 0 12px; text-transform: uppercase; letter-spacing: 0.02em; }
    .dp-divider { border: none; border-top: 1px dashed var(--border); margin: 16px 0; }
    
    /* Bukti Transfer Image Box */
    .bukti-transfer-box {
        width: 100%; height: 160px; border: 2px dashed var(--border); border-radius: var(--radius-md);
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        gap: 8px; color: var(--text-sec); cursor: pointer; transition: all 0.2s; margin-top: 8px; text-align: center; padding: 10px;
    }
    .bukti-transfer-box:hover { border-color: var(--accent); background: var(--bg-hover); color: var(--accent); }
    .bukti-transfer-box i { font-size: 28px; }
    .bukti-transfer-box span { font-size: 12px; font-weight: 600; }

    /* Button Action Panel */
    .btn-payment-action {
        width: 100%; padding: 12px; color: #ffffff !important;
        border: none; border-radius: 10px; font-size: 13px; font-weight: 600;
        cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;
        outline: none; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); -webkit-tap-highlight-color: transparent;
    }
    .btn-payment-action.approve { background-color: #5c9e74 !important; box-shadow: 0 2px 6px rgba(92, 158, 116, 0.2); margin-top: 24px; }
    .btn-payment-action.approve:hover { background-color: #3a5c48 !important; transform: translateY(-2px); box-shadow: 0 6px 15px rgba(58, 92, 72, 0.3); }
    .btn-payment-action.approve:active { transform: scale(0.97); background-color: #2d4a3a !important; box-shadow: inset 0 2px 4px rgba(0,0,0,0.2); }
    
    .btn-payment-action.reject { background-color: transparent !important; color: var(--text-sec) !important; border: 1px solid var(--border); margin-top: 10px; }
    .btn-payment-action.reject:hover { background-color: #fdf5f5 !important; color: #c47a7a !important; border-color: #e8caca; }
    
    /* =========================================
       SISTEM RESPONSIVE (Disamakan dengan Pesanan)
       ========================================= */
    @media (max-width: 1200px) {
        .stats-row { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 1024px) {
        .layout-order { flex-direction: column; }
        
        /* Modal Panel Mobile */
        .detail-panel.open {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            width: 100%; max-height: 100vh; z-index: 1000;
            border-radius: 0; border: none;
            animation: slideUp 0.3s ease-out;
        }
        @keyframes slideUp { from { opacity: 0; transform: translateY(50px); } to { opacity: 1; transform: translateY(0); } }
    }

    @media (max-width: 768px) {
        .filter-bar { flex-direction: column; align-items: stretch; }
        .search-box, .select-wrapper, .filter-select { max-width: 100%; width: 100%; min-width: 100%; }
        .page-header { flex-direction: column; align-items: flex-start; }
        .export-dropdown, .btn-export { width: 100%; }
    }

    @media (max-width: 480px) {
        .stats-row { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1>Transaksi Pembayaran</h1>
        <div class="breadcrumb">FurniHome / Pembayaran</div>
    </div>
    <div class="export-dropdown">
        <button class="btn-export"><i class="ti ti-download"></i> Export Laporan <i class="ti ti-chevron-down" style="font-size: 14px;"></i></button>
        <div class="export-dropdown-content">
            <a href="#"><i class="ti ti-file-type-pdf"></i> Export PDF</a>
            <a href="#"><i class="ti ti-file-spreadsheet"></i> Export Excel</a>
            <a href="#"><i class="ti ti-file-type-csv"></i> Export CSV</a>
        </div>
    </div>
</div>

<div class="stats-row">
    <div class="stat-card total">
        <div class="stat-icon"><i class="ti ti-report-money"></i></div>
        <div class="stat-label">Total Masuk</div>
        <div class="stat-value">Rp 48.210.000</div>
    </div>
    <div class="stat-card pending">
        <div class="stat-icon"><i class="ti ti-clock-bolt"></i></div>
        <div class="stat-label">Perlu Konfirmasi</div>
        <div class="stat-value">5</div>
    </div>
    <div class="stat-card success">
        <div class="stat-icon"><i class="ti ti-circle-check"></i></div>
        <div class="stat-label">Pembayaran Sukses</div>
        <div class="stat-value">112</div>
    </div>
    <div class="stat-card failed">
        <div class="stat-icon"><i class="ti ti-circle-x"></i></div>
        <div class="stat-label">Pembayaran Gagal</div>
        <div class="stat-value">3</div>
    </div>
</div>

<div class="filter-bar">
    <div class="search-box">
        <i class="ti ti-search"></i>
        <input type="text" placeholder="Cari No. Invoice, Nama...">
    </div>
    <div class="select-wrapper">
        <i class="ti ti-credit-card prefix-icon"></i>
        <select class="filter-select">
            <option>Semua Metode</option>
            <option>Transfer Bank Manual</option>
            <option>E-Wallet (OVO/DANA)</option>
            <option>Credit Card</option>
        </select>
    </div>
    <div class="select-wrapper">
        <i class="ti ti-adjustments-horizontal prefix-icon"></i>
        <select class="filter-select">
            <option>Semua Status</option>
            <option>Menunggu Konfirmasi</option>
            <option>Berhasil</option>
            <option>Gagal</option>
        </select>
    </div>
</div>

<div class="layout-order">
    <div class="table-section">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>No. Invoice</th>
                        <th>Order ID</th>
                        <th>Pelanggan</th>
                        <th>Tanggal Bayar</th>
                        <th>Metode</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $payments = [
                            ['invoice'=>'INV/240526/FH/089', 'order_id'=>'#ORD-00123', 'nama'=>'Rayhan Maulana', 'email'=>'rayhan@gmail.com', 'tanggal'=>'26 Mei 2024 10:32', 'metode'=>'Transfer Bank Mandiri', 'jumlah'=>2800000, 'status'=>'Menunggu Konfirmasi', 'bank_sender'=>'Rayhan M', 'rek_num'=>'157000xxxx890'],
                            ['invoice'=>'INV/240525/FH/088', 'order_id'=>'#ORD-00122', 'nama'=>'Siti Aisyah', 'email'=>'aisyah@gmail.com', 'tanggal'=>'25 Mei 2024 15:46', 'metode'=>'E-Wallet OVO', 'jumlah'=>1900000, 'status'=>'Berhasil', 'bank_sender'=>'-', 'rek_num'=>'-'],
                            ['invoice'=>'INV/240525/FH/087', 'order_id'=>'#ORD-00121', 'nama'=>'Budi Santoso', 'email'=>'budi@gmail.com', 'tanggal'=>'25 Mei 2024 11:21', 'metode'=>'Transfer Bank Mandiri', 'jumlah'=>3275000, 'status'=>'Berhasil', 'bank_sender'=>'Budi Santoso', 'rek_num'=>'123000xxxx567'],
                            ['invoice'=>'INV/240524/FH/086', 'order_id'=>'#ORD-00119', 'nama'=>'Ahmad Fauzi', 'email'=>'ahmad@gmail.com', 'tanggal'=>'24 Mei 2024 16:55', 'metode'=>'Credit Card VISA', 'jumlah'=>4600000, 'status'=>'Berhasil', 'bank_sender'=>'-', 'rek_num'=>'-'],
                            ['invoice'=>'INV/240523/FH/085', 'order_id'=>'#ORD-00118', 'nama'=>'Nina Karlina', 'email'=>'nina@gmail.com', 'tanggal'=>'23 Mei 2024 12:12', 'metode'=>'E-Wallet DANA', 'jumlah'=>1300000, 'status'=>'Berhasil', 'bank_sender'=>'-', 'rek_num'=>'-'],
                            ['invoice'=>'INV/240522/FH/084', 'order_id'=>'#ORD-00116', 'nama'=>'Larasati Putri', 'email'=>'larasati@gmail.com', 'tanggal'=>'22 Mei 2024 14:40', 'metode'=>'Credit Card Mastercard', 'jumlah'=>3870000, 'status'=>'Gagal', 'bank_sender'=>'-', 'rek_num'=>'-'],
                        ];
                    @endphp
                    @foreach($payments as $index => $p)
                    <tr>
                        <td><a href="#" class="invoice-link">{{ $p['invoice'] }}</a></td>
                        <td style="font-weight: 600;">{{ $p['order_id'] }}</td>
                        <td>
                            <div class="customer-info">
                                <div class="customer-name">{{ $p['nama'] }}</div>
                                <div class="customer-email">{{ $p['email'] }}</div>
                            </div>
                        </td>
                        <td style="color: var(--text-sec); font-size: 12.5px;">{{ $p['tanggal'] }}</td>
                        <td style="color: var(--text-sec); font-size: 12.5px;">{{ $p['metode'] }}</td>
                        <td style="font-weight: 500;">Rp {{ number_format($p['jumlah'], 0, ',', '.') }}</td>
                        <td>
                            @php
                                $cls = match($p['status']) {
                                    'Menunggu Konfirmasi' => 'status-konfirmasi',
                                    'Berhasil' => 'status-berhasil',
                                    'Gagal' => 'status-gagal',
                                    default => ''
                                };
                            @endphp
                            <span class="status-badge {{ $cls }}">{{ $p['status'] }}</span>
                        </td>
                        <td style="text-align: center;">
                            <div class="action-btn" onclick="showPaymentDetail({{ $index }})" title="Lihat Detail">
                                <i class="ti ti-eye"></i>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="pagination">
            <div>Menampilkan 1 - 6 dari 120 transaksi</div>
            <div class="pagination-links">
                <span class="active">1</span>
                <a href="#">2</a>
                <a href="#">3</a>
                <a href="#">→</a>
            </div>
        </div>
    </div>

    <div class="detail-panel" id="paymentPanel">
        <div class="dp-header">
            <h3>Rincian Pembayaran</h3>
            <div class="dp-close" onclick="closePaymentDetail()"><i class="ti ti-x"></i></div>
        </div>
        <div class="dp-body" id="paymentBody"></div>
    </div>
</div>

<script>
    var paymentsData = @json($payments);

    window.formatRupiah = function(n) {
        return 'Rp ' + n.toLocaleString('id-ID');
    };

    window.getStatusClass = function(status) {
        var statusMap = {
            'Menunggu Konfirmasi': 'status-konfirmasi',
            'Berhasil': 'status-berhasil',
            'Gagal': 'status-gagal'
        };
        return statusMap[status] || '';
    };

    window.showPaymentDetail = function(index) {
        var p = paymentsData[index];
        var panel = document.getElementById('paymentPanel');
        var body = document.getElementById('paymentBody');

        if (!panel || !body) return;

        var manualTransferHtml = '';
        if(p.metode.includes('Transfer Bank')) {
            manualTransferHtml = `
                <hr class="dp-divider">
                <div class="dp-section-title">Verifikasi Transfer Manual</div>
                <div class="dp-row">
                    <div class="dp-label">Nama Pengirim</div>
                    <div class="dp-value">${p.bank_sender}</div>
                </div>
                <div class="dp-row">
                    <div class="dp-label">No. Rekening</div>
                    <div class="dp-value">${p.rek_num}</div>
                </div>
                <div class="dp-label" style="margin-bottom:6px;">Bukti Transfer</div>
                <div class="bukti-transfer-box" onclick="alert('Buka lampiran gambar asli...')">
                    <i class="ti ti-file-search"></i>
                    <span>Lihat Lampiran Gambar</span>
                    <p style="font-size:11px; color: var(--text-muted); margin:0;">bukti_transfer_id_${index}.jpg</p>
                </div>
            `;
        }

        var actionButtonsHtml = '';
        if(p.status === 'Menunggu Konfirmasi') {
            actionButtonsHtml = `
                <button class="btn-payment-action approve" onclick="event.stopPropagation(); alert('Pembayaran ${p.invoice} berhasil disetujui!')"><i class="ti ti-circle-check"></i> Terima Pembayaran</button>
                <button class="btn-payment-action reject" onclick="event.stopPropagation(); alert('Pembayaran ditolak.')">Tolak Bukti Transfer</button>
            `;
        }

        body.innerHTML = `
            <div class="dp-invoice-id">${p.invoice}</div>
            <div class="dp-row">
                <div class="dp-label">Status</div>
                <div class="dp-value"><span class="status-badge ${window.getStatusClass(p.status)}">${p.status}</span></div>
            </div>
            <div class="dp-row">
                <div class="dp-label">Order Reference</div>
                <div class="dp-value" style="font-weight:700; color:var(--accent-dark);">${p.order_id}</div>
            </div>
            <div class="dp-row">
                <div class="dp-label">Waktu Bayar</div>
                <div class="dp-value">${p.tanggal}</div>
            </div>
            <div class="dp-row">
                <div class="dp-label">Metode Sistem</div>
                <div class="dp-value">${p.metode}</div>
            </div>
            <div class="dp-row">
                <div class="dp-label">Pelanggan</div>
                <div class="dp-value">
                    <div style="font-weight:600; margin-bottom: 2px;">${p.nama}</div>
                    <div style="color: var(--text-sec); font-size:11.5px;">${p.email}</div>
                </div>
            </div>
            
            ${manualTransferHtml}
            
            <hr class="dp-divider">
            <div class="dp-total-row grand" style="display:flex; justify-content:space-between; margin-top:14px; padding-top:14px; border-top:1px solid var(--border);">
                <span class="lbl" style="font-weight:700; color:var(--text-main); font-size:14px;">Total Nominal</span>
                <span class="val" style="font-weight:700; color:var(--accent); font-size:18px;">${window.formatRupiah(p.jumlah)}</span>
            </div>
            
            ${actionButtonsHtml}
        `;

        panel.classList.add('open');
    };

    window.closePaymentDetail = function() {
        var panel = document.getElementById('paymentPanel');
        if (panel) panel.classList.remove('open');
    };
</script>
@endsection