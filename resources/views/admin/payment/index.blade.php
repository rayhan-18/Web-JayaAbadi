@extends('layouts.admin')

@section('title', 'Transaksi Pembayaran')

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
    .filter-select:hover { background-color: var(--bg-hover); border-color: #d1d6cf; }
    .filter-select:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px rgba(92, 158, 116, 0.15); }

    /* Layout & Table */
    .layout-order { display: flex; gap: 20px; align-items: flex-start; }
    .table-section { flex: 1; min-width: 0; }
    
    .table-wrapper { background: var(--bg-surface); border-radius: var(--radius-lg); border: 1px solid var(--border); overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; font-size: 13px; min-width: 920px; } 
    th {
        text-align: left; padding: 14px 20px; background: var(--bg-hover); font-weight: 600;
        color: var(--text-sec); border-bottom: 1px solid var(--border); font-size: 12px;
        text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap;
    }
    td { padding: 14px 20px; border-bottom: 1px solid var(--border); vertical-align: middle; color: var(--text-main); }
    tr:last-child td { border-bottom: none; }
    tbody tr:hover { background: var(--bg-hover); }

    .invoice-link { font-weight: 700; color: var(--text-main); text-decoration: none; }
    .invoice-link:hover { color: var(--accent); }

    .customer-info { line-height: 1.4; }
    .customer-name { font-weight: 600; color: var(--text-main); font-size: 13.5px; }
    .customer-email { font-size: 11.5px; color: var(--text-muted); margin-top: 2px; }

    /* Badges Status */
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
        cursor: pointer; color: var(--text-sec); font-size: 16px; transition: 0.15s;
    }
    .action-btn:hover { background: var(--bg-hover); color: var(--accent); border-color: #d1d6cf; }

    /* Detail Panel Sidebar */
    .detail-panel {
        width: 360px; flex-shrink: 0; background: #ffffff !important; border-radius: var(--radius-lg);
        border: 1px solid var(--border); display: none; flex-direction: column;
        position: sticky; top: 80px; max-height: calc(100vh - 100px); overflow-y: auto;
        box-shadow: -4px 0 24px rgba(0, 0, 0, 0.04);
    }
    .detail-panel.open { display: flex; animation: slideIn 0.3s ease-out; }
    @keyframes slideIn { from { opacity: 0; transform: translateX(20px); } to { opacity: 1; transform: translateX(0); } }

    .dp-header {
        display: flex; justify-content: space-between; align-items: center; padding: 18px 20px;
        border-bottom: 1px solid var(--border); background: #ffffff !important; position: sticky; top: 0; z-index: 20;
    }
    .dp-header h3 { font-size: 15px; font-weight: 700; margin: 0; color: var(--text-main); }
    .dp-close {
        width: 30px; height: 30px; border-radius: 8px; background: var(--bg-hover); border: 1px solid transparent;
        display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 16px; color: var(--text-sec); transition: 0.2s;
    }
    .dp-close:hover { background: #fdf5f5; color: #c47a7a; border-color: #e8caca; }
    
    .dp-body { padding: 20px; }
    .dp-invoice-id { font-size: 18px; font-weight: 700; color: var(--text-main); margin-bottom: 16px; letter-spacing: -0.02em; }
    .dp-row { display: flex; gap: 12px; margin-bottom: 14px; align-items: flex-start; }
    .dp-label { font-size: 12.5px; color: var(--text-sec); font-weight: 500; min-width: 100px; }
    .dp-value { font-size: 13px; color: var(--text-main); font-weight: 500; flex: 1; line-height: 1.4; }
    
    .dp-section-title { font-size: 13px; font-weight: 700; color: var(--text-main); margin: 20px 0 12px; text-transform: uppercase; letter-spacing: 0.02em; }
    .dp-divider { border: none; border-top: 1px dashed var(--border); margin: 16px 0; }
    
    /* Frame Foto Bukti Pembayaran */
    .receipt-frame {
        width: 100%; border: 1px solid var(--border); border-radius: var(--radius-md);
        overflow: hidden; background: var(--bg-hover); position: relative; margin-top: 8px;
        box-shadow: inset 0 2px 8px rgba(0,0,0,0.02);
    }
    .receipt-img { width: 100%; height: auto; max-height: 380px; object-fit: contain; display: block; cursor: zoom-in; }
    .receipt-overlay-tip { padding: 8px; background: var(--bg-hover); border-top: 1px solid var(--border); text-align: center; font-size: 11px; color: var(--text-sec); font-weight: 600; }

    /* Button Action Panel */
    .btn-payment-action {
        width: 100%; padding: 12px; color: #ffffff !important;
        border: none; border-radius: 10px; font-size: 13px; font-weight: 600;
        cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;
        outline: none; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .btn-payment-action.approve { background-color: #5c9e74 !important; box-shadow: 0 2px 6px rgba(92, 158, 116, 0.2); margin-top: 20px; }
    .btn-payment-action.approve:hover { background-color: #3a5c48 !important; transform: translateY(-2px); box-shadow: 0 6px 15px rgba(58, 92, 72, 0.3); }
    .btn-payment-action.approve:active { transform: scale(0.97); background-color: #2d4a3a !important; box-shadow: inset 0 2px 4px rgba(0,0,0,0.2); }
    
    .btn-payment-action.reject { background-color: transparent !important; color: var(--text-sec) !important; border: 1px solid var(--border); margin-top: 10px; }
    .btn-payment-action.reject:hover { background-color: #fdf5f5 !important; color: #c47a7a !important; border-color: #e8caca; }

    .export-dropdown { position: relative; display: inline-block; }
    .btn-export {
        background: var(--bg-surface); color: var(--text-main); border: 1px solid var(--border);
        padding: 0 16px; height: 40px; border-radius: var(--radius-md); font-size: 13px; font-weight: 600;
        display: inline-flex; align-items: center; gap: 8px; cursor: pointer; transition: 0.2s;
    }
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
    .export-dropdown-content a:hover { background: var(--bg-hover); color: var(--accent); }
    .export-dropdown:hover .export-dropdown-content { display: block; }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1>Transaksi Pembayaran</h1>
        <div class="breadcrumb">FurniHome / Pembayaran</div>
    </div>
    <div style="display: flex; gap: 10px; align-items: center;">
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
        <input type="text" name="search" placeholder="Cari invoice, pelanggan..."
            value="{{ request('search') }}"
            oninput="debounceSubmit()">
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

    <label class="date-filter" style="cursor:pointer; display:flex; align-items:center; gap:8px; border:1px solid var(--border); background:var(--bg-surface); border-radius:var(--radius-md); padding:0 14px; height:40px; font-size:13px; font-weight:600; color:var(--text-main);">
        <i class="ti ti-calendar-event" style="color:var(--accent);"></i>
        <input type="month" name="month"
            value="{{ request('month') }}"
            onchange="this.form.submit()"
            style="border:none; outline:none; background:transparent; font-size:13px; font-weight:600; color:var(--text-main); cursor:pointer; width:130px;">
    </label>

    @if(request('search') || request('status') || request('month'))
        <a href="{{ route('admin.payment.index') }}"
           style="height:40px; padding:0 16px; border:1px solid #e8caca; color:#c47a7a; border-radius:var(--radius-md); display:inline-flex; align-items:center; gap:6px; font-size:13px; font-weight:600; text-decoration:none; background:var(--bg-surface);">
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
                    @foreach($payments as $index => $p)
                    <tr>
                        <td><a href="#" class="invoice-link" onclick="showPaymentDetail({{ $index }}); return false;">{{ $p->order_number }}</a></td>
                        <td>
                            <div class="customer-info">
                                <div class="customer-name">{{ $p->user->name ?? 'Guest' }}</div>
                                <div class="customer-email">{{ $p->user->email ?? '-' }}</div>
                            </div>
                        </td>
                        <td style="color: var(--text-sec); font-size: 12.5px;">{{ $p->created_at->format('d M Y H:i') }}</td>
                        <td style="color: var(--text-sec); font-size: 12.5px;">{{ ucfirst($p->payment_method) }}</td>
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
                                    'unpaid' => 'Menunggu Konfirmasi',
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
                    @endforeach
                </tbody>
            </table>
        </div>
        
        {{-- Pagination terikat ke variabel $payments --}}
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
    // FIX UTAMA: Menangkap variabel JSON $paymentsJson dari Controller
    const payments = @json($paymentsJson);
    const storageUrl = "{{ asset('storage') }}";

    function formatRupiah(n) {
        return 'Rp ' + Number(n).toLocaleString('id-ID');
    }

    function getStatusClass(status) {
        const map = {
            'Menunggu Konfirmasi': 'status-konfirmasi',
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
                        <img src="${imgSrc}" class="receipt-img" alt="Bukti Transfer ${p.invoice}">
                    </a>
                    <div class="receipt-overlay-tip">
                        <i class="ti ti-maximize"></i> Klik gambar untuk memperbesar
                    </div>
                </div>
            `;
        }

        // Memunculkan tombol Verifikasi Terima / Tolak jika status_raw bernilai 'unpaid'
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

        body.innerHTML = `
            <div class="dp-invoice-id">${p.invoice}</div>
            <div class="dp-row">
                <div class="dp-label">Status</div>
                <div class="dp-value"><span class="status-badge ${getStatusClass(p.status)}">${p.status}</span></div>
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
            <hr class="dp-divider">
            <div class="dp-total-row grand">
                <span class="lbl">Total Nominal</span>
                <span class="val">${formatRupiah(p.jumlah)}</span>
            </div>
            ${actionButtonsHtml}
        `;

        panel.classList.add('open');
    }

    function closePaymentDetail() {
        document.getElementById('paymentPanel').classList.remove('open');
    }

    function confirmPayment(orderId) {
        Swal.fire({
            title: 'Konfirmasi Pembayaran?',
            text: 'Pembayaran akan ditandai sebagai berhasil.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Terima',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#5c9e74',
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
            confirmButtonColor: '#c47a7a',
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