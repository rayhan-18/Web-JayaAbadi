@extends('layouts.admin')

@section('title', 'Laporan Penjualan')

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
        --shadow-hover: 0 20px 40px -12px rgba(15, 23, 42, 0.09);
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

    /* Date Filter Premium Button */
    .date-filter {
        display: flex; align-items: center; gap: 8px;
        border: 1px solid var(--border); background: var(--bg-surface);
        border-radius: var(--radius-md); padding: 0 16px; height: 42px; font-size: 13.5px; font-weight: 600;
        color: var(--text-main); cursor: pointer; transition: all 0.2s; box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }
    .date-filter:hover { border-color: #cbd5e1; }
    .date-filter i { color: var(--accent); font-size: 18px; }

    /* ── Stats Grid ── */
    .stats-row {
        display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px;
    }
    .stat-card {
        background: var(--bg-surface); border-radius: var(--radius-lg); border: 1px solid var(--border);
        padding: 20px 16px; text-align: center; box-shadow: var(--shadow-card); transition: all 0.3s ease;
    }
    .stat-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-hover); }
    
    .stat-icon {
        width: 46px; height: 46px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 24px; margin: 0 auto 12px;
    }
    
    /* Icon Colors */
    .stat-card.revenue .stat-icon  { background: var(--accent-light); color: var(--accent); }
    .stat-card.orders .stat-icon   { background: #f3e8ff; color: #8b5cf6; }
    .stat-card.aov .stat-icon      { background: #fffbeb; color: #f59e0b; }
    .stat-card.items .stat-icon    { background: #ecfdf5; color: #10b981; }

    .stat-label { font-size: 12px; font-weight: 600; color: var(--text-sec); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em; }
    .stat-value { font-size: 24px; font-weight: 700; color: var(--text-main); letter-spacing: -0.02em; }
    .stat-trend { font-size: 12.5px; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 4px; margin-top: 8px; }
    .stat-trend.up { color: #059669; }
    .stat-trend.down { color: #dc2626; }

    /* ── Card Box (Untuk Grafik & Tabel) ── */
    .card-box {
        background: var(--bg-surface); border-radius: var(--radius-lg); box-shadow: var(--shadow-card);
        border: 1px solid var(--border); margin-bottom: 24px; overflow: hidden;
    }
    .card-header {
        display: flex; justify-content: space-between; align-items: center;
        padding: 20px 24px; border-bottom: 1px solid var(--border);
    }
    .card-title { font-size: 16px; font-weight: 700; color: var(--text-main); }
    
    /* Chart Area */
    .chart-area { padding: 24px; overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .chart-svg { width: 100%; height: 260px; overflow: visible; min-width: 600px; }

    /* ── Table Design ── */
    .table-wrapper { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .table-wrapper::-webkit-scrollbar { height: 6px; }
    .table-wrapper::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

    table { width: 100%; border-collapse: collapse; font-size: 13.5px; min-width: 850px; }
    th {
        text-align: left; padding: 16px 24px; background: rgba(0,0,0,0.015); font-weight: 600;
        color: var(--text-sec); border-bottom: 1px solid var(--border); font-size: 12px;
        text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap;
    }
    td { padding: 16px 24px; border-bottom: 1px solid var(--border); vertical-align: middle; color: var(--text-main); white-space: nowrap; }
    tr:last-child td { border-bottom: none; }
    tbody tr:hover { background: var(--bg-hover); }

    .val-currency { font-family: monospace; font-size: 14px; font-weight: 600; }
    .val-negative { color: #dc2626; }

    /* ── Badges ── */
    .status-badge {
        display: inline-flex; align-items: center; justify-content: center;
        padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 700; letter-spacing: 0.04em; white-space: nowrap;
    }
    .status-berhasil { background: #ecfdf5; color: #059669; }
    .status-refund   { background: #fef2f2; color: #dc2626; }
    .status-berhasil::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: #10b981; margin-right: 6px; }
    .status-refund::before   { content: ''; width: 6px; height: 6px; border-radius: 50%; background: #ef4444; margin-right: 6px; }

    /* ── Export Dropdown ── */
    .export-dropdown { position: relative; display: inline-block; }
    .btn-export {
        background: var(--bg-surface); color: var(--text-main); border: 1px solid var(--border);
        padding: 0 16px; height: 42px; border-radius: var(--radius-md); font-size: 13.5px; font-weight: 600;
        display: inline-flex; align-items: center; gap: 8px; cursor: pointer; transition: 0.2s; box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }
    .btn-export:hover { border-color: #cbd5e1; color: var(--accent); }
    .btn-export:hover i { color: var(--accent); }
    
    .export-dropdown-content {
        display: none; position: absolute; right: 0; top: 48px; background: var(--bg-surface);
        min-width: 180px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border-radius: 12px;
        z-index: 10; border: 1px solid var(--border); overflow: hidden;
    }
    .export-dropdown-content a {
        padding: 12px 16px; display: flex; align-items: center; gap: 10px; text-decoration: none;
        color: var(--text-main); font-size: 13.5px; font-weight: 500; border-bottom: 1px solid #f1f5f9; transition: background 0.2s;
    }
    .export-dropdown-content a:last-child { border-bottom: none; }
    .export-dropdown-content a:hover { background: var(--bg-hover); color: var(--accent); }
    .export-dropdown:hover .export-dropdown-content { display: block; }

    /* ── RESPONSIVE MOBILE & TABLET ── */
    @media (max-width: 1024px) {
        .stats-row { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 768px) {
        .page-header { flex-direction: column; align-items: flex-start; gap: 16px; margin-bottom: 20px; }
        .page-header form { width: 100%; }
        .page-header form > div { flex-direction: column; width: 100%; }
        .export-dropdown, .btn-export, .date-filter { width: 100%; justify-content: center; }
        .stats-row { grid-template-columns: repeat(2, 1fr); gap: 12px; }
        .stat-card { padding: 16px; border-radius: 16px; }
        .stat-label { font-size: 10.5px; }
        .stat-value { font-size: 20px; }
        .card-header { flex-direction: column; align-items: flex-start; gap: 12px; }
        .card-header .btn-export { width: 100%; }
    }
    @media (max-width: 480px) {
        .stats-row { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1>Laporan Penjualan</h1>
        <div class="breadcrumb">FurniHome / Laporan / Penjualan</div>
    </div>
    <form method="GET" action="{{ route('admin.report.sales') }}" id="filter-form">
        <div style="display: flex; gap: 12px;">
            <div class="export-dropdown">
                <button type="button" class="btn-export">
                    <i class="ti ti-download"></i> Export <i class="ti ti-chevron-down" style="font-size: 14px;"></i>
                </button>
                <div class="export-dropdown-content">
                    <a href="{{ route('admin.report.sales.export', ['format' => 'csv', 'month' => request('month', now()->format('Y-m'))]) }}">
                        <i class="ti ti-file-type-csv"></i> Export CSV
                    </a>
                    <a href="{{ route('admin.report.sales.export', ['format' => 'pdf', 'month' => request('month', now()->format('Y-m'))]) }}">
                        <i class="ti ti-file-type-pdf"></i> Export PDF
                    </a>
                </div>
            </div>
            <label class="date-filter">
                <i class="ti ti-calendar-event"></i>
                <input 
                    type="month" 
                    name="month" 
                    value="{{ request('month', now()->format('Y-m')) }}"
                    onchange="this.form.submit()"
                    style="border:none; outline:none; background:transparent; font-size:13.5px; font-weight:600; color:var(--text-main); cursor:pointer; width:130px;"
                >
                <i class="ti ti-chevron-down" style="font-size: 14px; color: var(--text-muted);"></i>
            </label>
        </div>
    </form>
</div>

<div class="stats-row">
    <div class="stat-card revenue">
        <div class="stat-icon"><i class="ti ti-coin"></i></div>
        <div class="stat-label">Total Pendapatan</div>
        <div class="stat-value">Rp {{ number_format($stats['revenue'] ?? 0, 0, ',', '.') }}</div>
        <div class="stat-trend {{ ($stats['trend_revenue'] ?? 0) >= 0 ? 'up' : 'down' }}">
            <i class="ti ti-trending-{{ ($stats['trend_revenue'] ?? 0) >= 0 ? 'up' : 'down' }}"></i> 
            {{ number_format(abs($stats['trend_revenue'] ?? 0), 1) }}% vs Bulan Lalu
        </div>
    </div>
    <div class="stat-card orders">
        <div class="stat-icon"><i class="ti ti-shopping-cart"></i></div>
        <div class="stat-label">Total Pesanan</div>
        <div class="stat-value">{{ number_format($stats['orders'] ?? 0, 0, ',', '.') }}</div>
        <div class="stat-trend up"><i class="ti ti-trending-up"></i> Terhubung</div>
    </div>
    <div class="stat-card aov">
        <div class="stat-icon"><i class="ti ti-receipt"></i></div>
        <div class="stat-label">Rata-rata Transaksi</div>
        <div class="stat-value">Rp {{ number_format($stats['aov'] ?? 0, 0, ',', '.') }}</div>
        <div class="stat-trend up"><i class="ti ti-trending-up"></i> Dinamis</div>
    </div>
    <div class="stat-card items">
        <div class="stat-icon"><i class="ti ti-packages"></i></div>
        <div class="stat-label">Produk Terjual</div>
        <div class="stat-value">{{ number_format($stats['items'] ?? 0, 0, ',', '.') }}</div>
        <div class="stat-trend up"><i class="ti ti-trending-up"></i> Real-time</div>
    </div>
</div>

<div class="card-box">
    <div class="card-header">
        <div class="card-title">Grafik Pendapatan Harian</div>
    </div>
    <div class="chart-area">
        <svg class="chart-svg" viewBox="0 0 860 220" preserveAspectRatio="none">
            <text x="0" y="26"  font-size="11" font-weight="500" fill="var(--text-muted)">15jt</text>
            <text x="0" y="76"  font-size="11" font-weight="500" fill="var(--text-muted)">10jt</text>
            <text x="0" y="126" font-size="11" font-weight="500" fill="var(--text-muted)">5jt</text>
            <text x="8" y="176" font-size="11" font-weight="500" fill="var(--text-muted)">0</text>
            <line x1="28" y1="20"  x2="860" y2="20"  stroke="#f1f5f9" stroke-width="1"/>
            <line x1="28" y1="70"  x2="860" y2="70"  stroke="#f1f5f9" stroke-width="1"/>
            <line x1="28" y1="120" x2="860" y2="120" stroke="#f1f5f9" stroke-width="1"/>
            <line x1="28" y1="170" x2="860" y2="170" stroke="#cbd5e1" stroke-width="1"/>
            
            @php
                $points = [];
                if(isset($chartData) && count($chartData) > 0) {
                    foreach($chartData as $index => $data) {
                        $x = 40 + ($index * 70);
                        $points[] = "$x," . ($data['y_pixel'] ?? 170);
                    }
                }
                $pointsString = implode(' ', $points);
            @endphp

            @if(!empty($pointsString))
            <polyline points="{{ $pointsString }}"
                fill="none" stroke="var(--accent)" stroke-width="3.5" stroke-linejoin="round" stroke-linecap="round"/>
            @endif

            @if(isset($chartData))
                @foreach($chartData as $index => $data)
                    @php $x = 40 + ($index * 70); @endphp
                    <circle cx="{{ $x }}" cy="{{ $data['y_pixel'] ?? 170 }}" r="4.5" fill="var(--bg-surface)" stroke="var(--accent)" stroke-width="2.5"/>
                @endforeach
                
                @foreach($chartData as $index => $data)
                    @php $x = 40 + ($index * 70); @endphp
                    <text x="{{ $x }}" y="195" font-size="11.5" font-weight="600" fill="var(--text-sec)" text-anchor="middle">{{ $data['tgl'] }} {{ now()->translatedFormat('M') }}</text>
                @endforeach
            @endif
        </svg>
    </div>
</div>

<div class="card-box">
    <div class="card-header">
        <div class="card-title">Rincian Transaksi</div>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Order ID</th>
                    <th>Pelanggan</th>
                    <th style="text-align: center;">Kuantitas</th>
                    <th>Subtotal</th>
                    <th>Diskon</th>
                    <th>Total Bersih</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentSales ?? [] as $order)
                <tr>
                    <td style="color: var(--text-sec);">{{ $order->created_at->format('d M Y') }}</td>
                    <td style="font-weight: 700; color: var(--text-main);">{{ $order->order_number }}</td>
                    <td>{{ $order->user->name ?? 'Guest' }}</td>
                    <td style="text-align: center; font-weight: 600;">{{ $order->items->sum('quantity') }}</td>
                    <td class="val-currency">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    <td class="val-currency val-negative">- Rp 0</td>
                    
                    <td class="val-currency" style="color: var(--text-main); font-weight: 800;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    
                    <td>
                        @php 
                            $isSuccess = in_array($order->status, ['paid', 'shipping', 'delivered', 'Selesai']);
                            $cls = $isSuccess ? 'status-berhasil' : 'status-refund';
                            $statusLabel = match($order->status) {
                                'pending'   => 'Pending',
                                'paid'      => 'Diproses',
                                'shipping'  => 'Dikirim',
                                'delivered' => 'Berhasil',
                                'cancelled' => 'Batal',
                                default     => ucfirst($order->status)
                            };
                        @endphp
                        <span class="status-badge {{ $cls }}">{{ $statusLabel }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 48px; color: var(--text-muted);">
                        <i class="ti ti-inbox" style="font-size: 32px; display: block; margin-bottom: 8px; opacity: 0.5;"></i>
                        Tidak ada data transaksi di bulan ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection