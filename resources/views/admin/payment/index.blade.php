@extends('layouts.admin')

@section('title', 'Transaksi Pembayaran')

@section('styles')
<style>
    /* ── Premium Variables (Tema: Royal Blue & Minimalist Slate) ── */
    :root {
        --accent: #2563eb; 
        --accent-dark: #1e40af;
        --accent-light: #eff6ff;
        --border: rgba(15, 23, 42, 0.06);
        --bg-body: #f8fafc;
        --bg-surface: #ffffff;
        --bg-hover: #f1f5f9;
        --text-main: #0f172a;
        --text-sec: #475569;
        --text-muted: #94a3b8;
        --radius-md: 14px;
        --radius-lg: 20px;
        --shadow-card: 0 10px 30px -10px rgba(15, 23, 42, 0.05);
    }

    body { color: var(--text-main); background: var(--bg-body); }

    /* ── Page Header ── */
    .page-header {
        display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; flex-wrap: wrap; gap: 16px;
    }
    .page-title h1 {
        font-size: 24px; font-weight: 700; color: var(--text-main); margin: 0; letter-spacing: -0.02em;
    }
    .page-title .breadcrumb {
        font-size: 13.5px; color: var(--text-sec); margin-top: 4px;
    }

    /* ── Stats Grid ── */
    .stats-row {
        display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px;
    }
    .stat-card {
        background: var(--bg-surface); border-radius: var(--radius-lg); border: 1px solid var(--border);
        padding: 20px 16px; text-align: center; box-shadow: var(--shadow-card); transition: all 0.3s ease;
    }
    .stat-card:hover { transform: translateY(-4px); }
    
    .stat-icon {
        width: 46px; height: 46px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 24px; margin: 0 auto 12px;
    }
    
    .stat-card.total .stat-icon   { background: #f1f5f9; color: #475569; }
    .stat-card.pending .stat-icon { background: #fffbeb; color: #f59e0b; }
    .stat-card.success .stat-icon { background: #eff6ff; color: #3b82f6; }
    .stat-card.failed .stat-icon  { background: #fef2f2; color: #ef4444; }

    .stat-label { font-size: 12px; font-weight: 600; color: var(--text-sec); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.06em; }
    .stat-value { font-size: 26px; font-weight: 700; color: var(--text-main); letter-spacing: -0.02em; line-height: 1.1; }
    
    /* ── Filters ── */
    .filter-bar { display: flex; gap: 12px; margin-bottom: 24px; flex-wrap: wrap; align-items: center; }
    .search-box {
        display: flex; align-items: center; background: var(--bg-surface);
        border: 1px solid var(--border); border-radius: var(--radius-md);
        padding: 0 14px; gap: 10px; flex: 1; max-width: 320px; height: 42px; transition: all 0.2s;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }
    .search-box:focus-within { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15); }
    .search-box i { color: var(--text-sec); font-size: 16px; }
    .search-box input { border: none; outline: none; font-size: 13.5px; width: 100%; color: var(--text-main); background: transparent; }
    
    .select-wrapper { position: relative; display: inline-block; }
    .select-wrapper i.prefix-icon {
        position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--text-sec); font-size: 15px; pointer-events: none;
    }
    .filter-select {
        height: 42px; padding: 0 36px 0 38px; border: 1px solid var(--border); border-radius: var(--radius-md);
        background: var(--bg-surface) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23475569' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E") no-repeat right 12px center;
        appearance: none; font-size: 13.5px; font-weight: 500; color: var(--text-main); cursor: pointer; transition: all 0.2s; min-width: 170px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }
    .filter-select:hover { border-color: #cbd5e1; }
    .filter-select:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15); }

    /* Date Filter Styling */
    .date-filter {
        cursor: pointer; display: flex; align-items: center; gap: 8px; 
        border: 1px solid var(--border); background: var(--bg-surface); 
        border-radius: var(--radius-md); padding: 0 14px; height: 42px; 
        font-size: 13.5px; font-weight: 500; color: var(--text-main);
        box-shadow: 0 2px 10px rgba(0,0,0,0.02); transition: border-color 0.2s;
    }
    .date-filter:hover { border-color: #cbd5e1; }

    /* Export Dropdown */
    .export-dropdown { position: relative; display: inline-block; }
    .btn-export {
        background: var(--bg-surface); color: var(--text-main); border: 1px solid var(--border);
        padding: 0 16px; height: 42px; border-radius: var(--radius-md); font-size: 13.5px; font-weight: 600;
        display: inline-flex; align-items: center; gap: 8px; cursor: pointer; transition: 0.2s; box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }
    .btn-export:hover { border-color: #cbd5e1; color: var(--accent); }
    .export-dropdown-content {
        display: none; position: absolute; right: 0; top: 48px; background: var(--bg-surface);
        min-width: 180px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border-radius: 12px;
        z-index: 10; border: 1px solid var(--border); overflow: hidden;
    }
    .export-dropdown-content a {
        padding: 12px 16px; display: flex; align-items: center; gap: 10px; text-decoration: none;
        color: var(--text-main); font-size: 13.5px; font-weight: 500; border-bottom: 1px solid #f1f5f9;
    }
    .export-dropdown-content a:hover { background: var(--bg-hover); color: var(--accent); }
    .export-dropdown:hover .export-dropdown-content { display: block; }

    /* ── Layout & Table ── */
    .layout-order { display: flex; gap: 24px; align-items: flex-start; min-width: 0; }
    .table-section { flex: 1; min-width: 0; width: 100%; }
    
    .table-wrapper { 
        background: var(--bg-surface); border-radius: var(--radius-lg); border: 1px solid var(--border); 
        box-shadow: var(--shadow-card); width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; padding-bottom: 4px;
    }
    .table-wrapper::-webkit-scrollbar { height: 6px; }
    .table-wrapper::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

    table { width: 100%; border-collapse: collapse; font-size: 13.5px; min-width: 920px; } 
    th {
        text-align: left; padding: 16px 24px; background: rgba(0,0,0,0.015); font-weight: 600;
        color: var(--text-sec); border-bottom: 1px solid var(--border); font-size: 12px;
        text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap;
    }
    td { padding: 16px 24px; border-bottom: 1px solid var(--border); vertical-align: middle; color: var(--text-main); white-space: nowrap; }
    tr:last-child td { border-bottom: none; }
    tbody tr:hover { background: var(--bg-hover); }

    .invoice-link { font-weight: 700; color: var(--text-main); text-decoration: none; transition: color 0.2s; }
    .invoice-link:hover { color: var(--accent); }

    .customer-info { line-height: 1.4; }
    .customer-name { font-weight: 600; color: var(--text-main); font-size: 13.5px; }
    .customer-email { font-size: 11.5px; color: var(--text-muted); margin-top: 2px; }

    /* Badges Status */
    .status-badge {
        display: inline-flex; align-items: center; justify-content: center;
        padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 700; letter-spacing: 0.04em; white-space: nowrap;
    }
    .status-konfirmasi { background: #fffbeb; color: #d97706; }
    .status-konfirmasi::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: #f59e0b; margin-right: 6px; }
    
    .status-berhasil { background: #ecfdf5; color: #059669; }
    .status-berhasil::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: #10b981; margin-right: 6px; }
    
    .status-gagal { background: #fef2f2; color: #dc2626; }
    .status-gagal::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: #ef4444; margin-right: 6px; }

    .action-btn {
        width: 32px; height: 32px; border-radius: 8px; background: var(--bg-surface);
        border: 1px solid #e2e8f0; display: inline-flex; align-items: center; justify-content: center;
        cursor: pointer; color: var(--text-sec); font-size: 16px; transition: 0.2s;
    }
    .action-btn:hover { background: var(--accent-light); color: var(--accent); border-color: var(--accent); }

    /* Pagination */
    .pagination { display: flex; justify-content: space-between; align-items: center; margin-top: 24px; font-size: 13.5px; color: var(--text-sec); font-weight: 500; flex-wrap: wrap; gap: 12px; }
    .pagination-links span, .pagination-links a {
        display: inline-flex; align-items: center; justify-content: center; min-width: 32px; height: 32px; padding: 0 10px;
        border: 1px solid var(--border); border-radius: 8px; text-decoration: none; color: var(--text-main); font-weight: 600; transition: 0.2s; background: var(--bg-surface);
    }
    .pagination-links a:hover { background: var(--bg-hover); border-color: #cbd5e1; }
    .pagination-links .active { background: var(--accent); border-color: var(--accent); color: white; }

    /* Detail Panel Sidebar */
    .detail-panel {
        width: 380px; flex-shrink: 0; background: var(--bg-surface) !important; border-radius: var(--radius-lg);
        border: 1px solid var(--border); display: none; flex-direction: column; box-shadow: var(--shadow-card);
        position: sticky; top: 100px; max-height: calc(100vh - 120px); overflow-y: auto;
    }
    .detail-panel.open { display: flex; animation: slideIn 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    @keyframes slideIn { from { opacity: 0; transform: translateX(30px); } to { opacity: 1; transform: translateX(0); } }

    .dp-header {
        display: flex; justify-content: space-between; align-items: center; padding: 20px 24px;
        border-bottom: 1px solid var(--border); background: var(--bg-surface) !important; position: sticky; top: 0; z-index: 20;
    }
    .dp-header h3 { font-size: 16px; font-weight: 700; margin: 0; color: var(--text-main); }
    .dp-close {
        width: 32px; height: 32px; border-radius: 8px; background: var(--bg-hover); border: 1px solid transparent;
        display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 16px; color: var(--text-sec); transition: 0.2s;
    }
    .dp-close:hover { background: #fef2f2; color: #ef4444; border-color: #fecaca; }
    
    .dp-body { padding: 24px; }
    .dp-invoice-id { font-size: 18px; font-weight: 700; color: var(--text-main); margin-bottom: 18px; letter-spacing: -0.02em; }
    .dp-row { display: flex; gap: 12px; margin-bottom: 14px; align-items: flex-start; }
    .dp-label { font-size: 13px; color: var(--text-sec); font-weight: 500; min-width: 100px; }
    .dp-value { font-size: 13.5px; color: var(--text-main); font-weight: 500; flex: 1; line-height: 1.5; }
    
    .dp-section-title { font-size: 12.5px; font-weight: 700; color: var(--text-sec); margin: 24px 0 16px; text-transform: uppercase; letter-spacing: 0.08em; }
    .dp-divider { border: none; border-top: 1px dashed #cbd5e1; margin: 20px 0; }
    
    /* Frame Foto Bukti Pembayaran */
    .receipt-frame {
        width: 100%; border: 1px solid var(--border); border-radius: var(--radius-md);
        overflow: hidden; background: var(--bg-hover); position: relative; margin-top: 8px;
    }
    .receipt-img { width: 100%; height: auto; max-height: 300px; object-fit: contain; display: block; cursor: zoom-in; }
    .receipt-overlay-tip { padding: 10px; background: var(--bg-hover); border-top: 1px solid var(--border); text-align: center; font-size: 12px; color: var(--text-sec); font-weight: 600; }

    .dp-total-row { display: flex; justify-content: space-between; margin-top: 16px; padding-top: 16px; border-top: 1px solid var(--border); }
    .dp-total-row .lbl { font-weight: 700; color: var(--text-main); font-size: 14.5px; }
    .dp-total-row .val { font-weight: 800; color: var(--accent); font-size: 18px; }

    /* Button Action Panel */
    .btn-payment-action {
        width: 100%; padding: 14px; border-radius: 12px; font-size: 13.5px; font-weight: 600;
        cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;
        outline: none; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .btn-payment-action.approve { background-color: var(--accent) !important; color: #ffffff !important; border: none; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2); margin-top: 24px; }
    .btn-payment-action.approve:hover { background-color: var(--accent-dark) !important; transform: translateY(-2px); box-shadow: 0 6px 15px rgba(30, 64, 175, 0.3); }
    
    .btn-payment-action.reject { background-color: transparent !important; color: var(--text-sec) !important; border: 1px solid var(--border); margin-top: 12px; }
    .btn-payment-action.reject:hover { background-color: #fef2f2 !important; color: #ef4444 !important; border-color: #fecaca; }

    /* ── RESPONSIVE MOBILE & TABLET ── */
    @media (max-width: 1200px) {
        .stats-row { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 1024px) {
        .layout-order { flex-direction: column; }
        .detail-panel { width: 100%; }
        
        /* Ubah Detail Panel jadi Modal Full Screen di HP */
        .detail-panel.open {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            width: 100%; max-height: 100vh; z-index: 1000;
            border-radius: 0; border: none;
            animation: slideUp 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        @keyframes slideUp { from { opacity: 0; transform: translateY(100px); } to { opacity: 1; transform: translateY(0); } }
    }

    @media (max-width: 768px) {
        .filter-bar { flex-direction: column; align-items: stretch; }
        .search-box, .select-wrapper, .filter-select, .date-filter { max-width: 100%; width: 100%; min-width: 100%; }
        .page-header { flex-direction: column; align-items: flex-start; }
        .export-dropdown, .btn-export { width: 100%; }
        
        .stat-card { padding: 16px; border-radius: 16px; }
        .stat-label { font-size: 10.5px; }
        .stat-value { font-size: 20px; }
        .stat-icon { width: 40px; height: 40px; font-size: 20px; }
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
        <div class="breadcrumb">JayaAbadi / Pembayaran</div>
    </div>
    <div style="display: flex; gap: 10px; align-items: center; width: auto;">
        <div class="export-dropdown">
            <button type="button" class="btn-export">
                <i class="ti ti-download"></i> Export
                <i class="ti ti-chevron-down" style="font-size:14px;"></i>
            </button>
            <div class="export-dropdown-content">
                <a href="{{ route('admin.payment.export.pdf', request()->query()) }}" target="_blank">
                    <i class="ti ti-file-type-pdf"></i> Export PDF
                </a>
                <a href="{{ route('admin.payment.export.csv', request()->query()) }}">
                    <i class="ti ti-file-type-csv"></i> Export CSV
                </a>
            </div>
        </div>
    </div>
</div>

<form method="GET" action="{{ route('admin.payment.index') }}" id="filterForm">
<div class="filter-bar">
    <div class="search-box">
        <i class="ti ti-search"></i>
        <input type="text" name="search" placeholder="Cari invoice, pelanggan..." value="{{ request('search') }}" oninput="debounceSubmit()">
    </div>

    <div class="select-wrapper">
        <i class="ti ti-circle-check prefix-icon"></i>
        <select class="filter-select" name="status" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="unpaid"  {{ request('status') === 'unpaid'  ? 'selected' : '' }}>Menunggu Konfirmasi</option>
            <option value="paid"    {{ request('status') === 'paid'    ? 'selected' : '' }}>Berhasil</option>
            <option value="failed"  {{ request('status') === 'failed'  ? 'selected' : '' }}>Gagal</option>
        </select>
    </div>

    <label class="date-filter">
        <i class="ti ti-calendar-event"></i>
        <input type="month" name="month" value="{{ request('month') }}" onchange="this.form.submit()" style="border:none; outline:none; background:transparent; font-size:13.5px; font-weight:500; color:var(--text-main); cursor:pointer; width:130px;">
    </label>

    @if(request('search') || request('status') || request('month'))
        <a href="{{ route('admin.payment.index') }}" style="height:42px; padding:0 16px; border:1px solid #fecaca; color:#ef4444; border-radius:var(--radius-md); display:inline-flex; align-items:center; gap:6px; font-size:13.5px; font-weight:600; text-decoration:none; background:#fef2f2;">
            <i class="ti ti-refresh"></i> Reset
        </a>
    @endif
</div>
</form>

<div class="stats-row">
    <div class="stat-card total">
        <div class="stat-icon"><i class="ti ti-report-money"></i></div>
        <div class="stat-label">Total Masuk</div>
        <div class="stat-value">Rp {{ number_format($stats['total'] ?? 0, 0, ',', '.') }}</div>
    </div>
    <div class="stat-card pending">
        <div class="stat-icon"><i class="ti ti-clock-bolt"></i></div>
        <div class="stat-label">Perlu Konfirmasi</div>
        <div class="stat-value">{{ $stats['pending'] ?? 0 }}</div>
    </div>
    <div class="stat-card success">
        <div class="stat-icon"><i class="ti ti-circle-check"></i></div>
        <div class="stat-label">Pembayaran Sukses</div>
        <div class="stat-value">{{ $stats['success'] ?? 0 }}</div>
    </div>
    <div class="stat-card failed">
        <div class="stat-icon"><i class="ti ti-circle-x"></i></div>
        <div class="stat-label">Pembayaran Gagal</div>
        <div class="stat-value">{{ $stats['failed'] ?? 0 }}</div>
    </div>
</div>

<div class="layout-order">
    <div class="table-section">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>No. Invoice</th>
                        <th>Pelanggan</th>
                        <th>Tanggal Bayar</th>
                        <th>Metode</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $index => $p)
                    <tr>
                        <td><a href="#" class="invoice-link" onclick="showPaymentDetail({{ $index }}); return false;">{{ $p->order_number }}</a></td>
                        <td>
                            <div class="customer-info">
                                <div class="customer-name">{{ $p->user->name ?? 'Guest' }}</div>
                                <div class="customer-email">{{ $p->user->email ?? '-' }}</div>
                            </div>
                        </td>
                        <td style="color: var(--text-sec); font-size: 12.5px;">{{ $p->created_at->format('d M Y H:i') }}</td>
                        <td style="font-weight: 500;">{{ ucfirst($p->payment_method) }}</td>
                        <td style="font-weight: 600;">Rp {{ number_format($p->total_amount, 0, ',', '.') }}</td>
                        <td>
                            @php
                                $cls = match($p->payment_status) {
                                    'unpaid' => 'status-konfirmasi',
                                    'paid'   => 'status-berhasil',
                                    'failed' => 'status-gagal',
                                    default  => ''
                                };
                                $label = match($p->payment_status) {
                                    'unpaid' => 'Menunggu',
                                    'paid'   => 'Berhasil',
                                    'failed' => 'Gagal',
                                    default  => ucfirst($p->payment_status)
                                };
                            @endphp
                            <span class="status-badge {{ $cls }}">{{ $label }}</span>
                        </td>
                        <td style="text-align: center;">
                            <div class="action-btn" onclick="showPaymentDetail({{ $index }})" title="Tinjau Pembayaran">
                                <i class="ti ti-zoom-in"></i>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 48px; color: var(--text-muted);">Belum ada data pembayaran.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="pagination">
            <div>Menampilkan {{ $payments->firstItem() ?? 0 }} - {{ $payments->lastItem() ?? 0 }} dari {{ $payments->total() }} transaksi</div>
            <div class="pagination-links">
                {!! $payments->links() !!}
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
    const payments = @json($paymentsJson);
    const storageUrl = "{{ asset('storage') }}";

    function formatRupiah(n) {
        return 'Rp ' + Number(n).toLocaleString('id-ID');
    }

    function getStatusClass(status) {
        const map = {
            'Menunggu': 'status-konfirmasi',
            'Berhasil': 'status-berhasil',
            'Gagal': 'status-gagal'
        };
        return map[status] || '';
    }

    function showPaymentDetail(index) {
        const p = payments[index];
        const panel = document.getElementById('paymentPanel');
        const body = document.getElementById('paymentBody');

        // Logika menata foto bukti transaksi dari storage lokal
        let receiptHtml = '';
        if (p.metode.toLowerCase() !== 'cash') {
            const imgSrc = p.bukti_foto ? `${storageUrl}/${p.bukti_foto}` : 'https://placehold.co/400x500?text=Bukti+Belum+Diunggah';
            
            receiptHtml = `
                <hr class="dp-divider">
                <div class="dp-section-title">Dokumen Bukti Transaksi</div>
                <div class="receipt-frame">
                    <a href="${imgSrc}" target="_blank" title="Klik untuk memperbesar gambar">
                        <img src="${imgSrc}" class="receipt-img" alt="Bukti Transfer ${p.invoice}" onerror="this.src='https://placehold.co/400x500?text=Error'">
                    </a>
                    <div class="receipt-overlay-tip">
                        <i class="ti ti-maximize"></i> Klik gambar untuk memperbesar
                    </div>
                </div>
            `;
        }

        let actionButtonsHtml = '';
        if (p.status_raw === 'unpaid') {
            actionButtonsHtml = `
                <button class="btn-payment-action approve" onclick="confirmPayment(${p.order_db_id})">
                    <i class="ti ti-circle-check"></i> Terima Pembayaran
                </button>
                <button class="btn-payment-action reject" onclick="rejectPayment(${p.order_db_id})">
                    Tolak Bukti Transfer
                </button>
            `;
        }

        let currentLabel = p.status;
        if (p.status_raw === 'unpaid') currentLabel = 'Menunggu';
        if (p.status_raw === 'paid') currentLabel = 'Berhasil';
        if (p.status_raw === 'failed') currentLabel = 'Gagal';

        body.innerHTML = `
            <div class="dp-invoice-id">${p.invoice}</div>
            <div class="dp-row">
                <div class="dp-label">Status</div>
                <div class="dp-value"><span class="status-badge ${getStatusClass(currentLabel)}">${currentLabel}</span></div>
            </div>
            <div class="dp-row">
                <div class="dp-label">Order Ref.</div>
                <div class="dp-value" style="font-weight:700; color:var(--accent);">${p.order_id}</div>
            </div>
            <div class="dp-row">
                <div class="dp-label">Waktu Bayar</div>
                <div class="dp-value">${p.tanggal}</div>
            </div>
            <div class="dp-row">
                <div class="dp-label">Metode</div>
                <div class="dp-value">${p.metode}</div>
            </div>
            <div class="dp-row">
                <div class="dp-label">Pelanggan</div>
                <div class="dp-value">
                    <div style="font-weight:600;">${p.nama}</div>
                    <div style="color: var(--text-sec); font-size:11.5px;">${p.email}</div>
                </div>
            </div>
            ${receiptHtml}
            <div class="dp-total-row grand">
                <span class="lbl">Total Nominal</span>
                <span class="val">${formatRupiah(p.jumlah)}</span>
            </div>
            ${actionButtonsHtml}
        `;

        panel.classList.add('open');
        
        // Kunci background scroll saat modal di HP terbuka
        if (window.innerWidth <= 1024) {
            document.body.style.overflow = 'hidden';
        }
    }

    function closePaymentDetail() {
        document.getElementById('paymentPanel').classList.remove('open');
        document.body.style.overflow = '';
    }

    let debounceTimer;
    function debounceSubmit() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            document.getElementById('filterForm').submit();
        }, 400);
    }

    function confirmPayment(orderId) {
        Swal.fire({
            title: 'Konfirmasi Pembayaran?',
            text: 'Pembayaran akan ditandai sebagai berhasil.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Terima',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#2563eb',
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin/pembayaran/${orderId}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ payment_status: 'paid' })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Berhasil!', 'Pembayaran telah dikonfirmasi.', 'success')
                            .then(() => location.reload());
                    }
                });
            }
        });
    }

    function rejectPayment(orderId) {
        Swal.fire({
            title: 'Tolak Pembayaran?',
            text: 'Pembayaran akan ditandai sebagai gagal.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Tolak',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#ef4444',
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin/pembayaran/${orderId}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ payment_status: 'failed' })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Ditolak!', 'Pembayaran telah ditolak.', 'success')
                            .then(() => location.reload());
                    }
                });
            }
        });
    }
</script>
@endsection