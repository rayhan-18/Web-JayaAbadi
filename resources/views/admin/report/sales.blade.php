@extends('layouts.admin')

@section('title', 'Laporan Penjualan')

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
        display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;
    }
    .page-title h1 {
        font-size: 22px; font-weight: 700; color: var(--text-main); margin: 0; letter-spacing: -0.02em;
    }
    .page-title .breadcrumb {
        font-size: 13px; color: var(--text-sec); margin-top: 4px;
    }

    /* Date Filter Premium Button */
    .date-filter {
        display: flex; align-items: center; gap: 8px;
        border: 1px solid var(--border); background: var(--bg-surface);
        border-radius: var(--radius-md); padding: 8px 14px; font-size: 13px; font-weight: 600;
        color: var(--text-main); cursor: pointer; transition: background 0.15s;
    }
    .date-filter:hover { background: var(--bg-hover); border-color: #d1d6cf; }
    .date-filter i { color: var(--accent); font-size: 16px; }

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
        font-size: 26px; margin: 0 auto 12px;
    }
    
    /* Icon Colors - Transparent Backgrounds */
    .stat-card.revenue .stat-icon  { background: transparent; color: var(--accent); }
    .stat-card.orders .stat-icon   { background: transparent; color: #5c7b9e; }
    .stat-card.aov .stat-icon      { background: transparent; color: #b89247; }
    .stat-card.items .stat-icon    { background: transparent; color: #865c9e; }

    .stat-label { font-size: 12px; font-weight: 600; color: var(--text-sec); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em; }
    .stat-value { font-size: 24px; font-weight: 700; color: var(--text-main); letter-spacing: -0.02em; }
    .stat-trend { font-size: 12px; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 4px; margin-top: 8px; }
    .stat-trend.up { color: var(--accent); }
    .stat-trend.down { color: #c47a7a; }

    /* Card Box (Untuk Grafik & Tabel) */
    .card-box {
        background: var(--bg-surface); border-radius: var(--radius-lg);
        border: 1px solid var(--border); margin-bottom: 24px; overflow: hidden;
    }
    .card-header {
        display: flex; justify-content: space-between; align-items: center;
        padding: 16px 20px; border-bottom: 1px solid var(--border);
    }
    .card-title { font-size: 15px; font-weight: 700; color: var(--text-main); }
    
    /* Chart Area */
    .chart-area { padding: 20px; }
    .chart-svg { width: 100%; height: 260px; overflow: visible; }

    /* Table Design */
    .table-wrapper { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; font-size: 13px; min-width: 800px; }
    th {
        text-align: left; padding: 14px 20px; background: var(--bg-hover); font-weight: 600;
        color: var(--text-sec); border-bottom: 1px solid var(--border); font-size: 12px;
        text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap;
    }
    td { padding: 14px 20px; border-bottom: 1px solid var(--border); vertical-align: middle; color: var(--text-main); }
    tr:last-child td { border-bottom: none; }
    tbody tr:hover { background: var(--bg-hover); }

    .val-currency { font-family: monospace; font-size: 13.5px; font-weight: 500; }
    .val-negative { color: #c47a7a; }

    /* Badges */
    .status-badge {
        display: inline-flex; align-items: center; justify-content: center;
        padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; letter-spacing: 0.02em; white-space: nowrap;
    }
    .status-berhasil { background: var(--accent-light); color: var(--accent-dark); }
    .status-refund   { background: #fdf5f5; color: #c47a7a; }
    .status-berhasil::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: var(--accent); margin-right: 6px; }
    .status-refund::before   { content: ''; width: 6px; height: 6px; border-radius: 50%; background: #c47a7a; margin-right: 6px; }

    /* Export Laporan Dropdown */
    .export-dropdown { position: relative; display: inline-block; }
    .btn-export {
        background: var(--bg-surface); color: var(--text-main); border: 1px solid var(--border);
        padding: 0 16px; height: 34px; border-radius: 8px; font-size: 12px; font-weight: 600;
        display: inline-flex; align-items: center; gap: 6px; cursor: pointer; transition: 0.2s;
    }
    .btn-export:hover { background: var(--bg-hover); border-color: #d1d6cf; }
    
    .export-dropdown-content {
        display: none; position: absolute; right: 0; top: 40px; background: var(--bg-surface);
        min-width: 180px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); border-radius: 10px;
        z-index: 10; border: 1px solid var(--border); overflow: hidden;
    }
    .export-dropdown-content a {
        padding: 10px 16px; display: flex; align-items: center; gap: 10px; text-decoration: none;
        color: var(--text-main); font-size: 13px; font-weight: 500; border-bottom: 1px solid #f0f2ef;
    }
    .export-dropdown-content a:hover { background: var(--bg-hover); color: var(--accent); }
    .export-dropdown-content a:hover i { color: var(--accent); }
    .export-dropdown:hover .export-dropdown-content { display: block; }
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
        <button type="button" class="btn-export" style="height: 40px; font-size: 13px; border-radius: var(--radius-md);">
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
    <label class="date-filter" style="cursor:pointer;">
        <i class="ti ti-calendar-event"></i>
        <input 
            type="month" 
            name="month" 
            value="{{ request('month', now()->format('Y-m')) }}"
            onchange="this.form.submit()"
            style="border:none; outline:none; background:transparent; font-size:13px; font-weight:600; color:var(--text-main); cursor:pointer; width:130px;"
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
        <button class="btn-export">Detail Grafik <i class="ti ti-chevron-right"></i></button>
    </div>
    <div class="chart-area">
        <svg class="chart-svg" viewBox="0 0 860 220" preserveAspectRatio="none">
            <text x="0" y="26"  font-size="11" fill="var(--text-muted)">15jt</text>
            <text x="0" y="76"  font-size="11" fill="var(--text-muted)">10jt</text>
            <text x="0" y="126" font-size="11" fill="var(--text-muted)">5jt</text>
            <text x="8" y="176" font-size="11" fill="var(--text-muted)">0</text>
            <line x1="28" y1="20"  x2="860" y2="20"  stroke="var(--bg-hover)" stroke-width="1"/>
            <line x1="28" y1="70"  x2="860" y2="70"  stroke="var(--bg-hover)" stroke-width="1"/>
            <line x1="28" y1="120" x2="860" y2="120" stroke="var(--bg-hover)" stroke-width="1"/>
            <line x1="28" y1="170" x2="860" y2="170" stroke="var(--border)" stroke-width="1"/>
            
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
                fill="none" stroke="var(--accent)" stroke-width="3" stroke-linejoin="round" stroke-linecap="round"/>
            @endif

            @if(isset($chartData))
                @foreach($chartData as $index => $data)
                    @php $x = 40 + ($index * 70); @endphp
                    <circle cx="{{ $x }}" cy="{{ $data['y_pixel'] ?? 170 }}" r="4" fill="var(--bg-surface)" stroke="var(--accent)" stroke-width="2"/>
                @endforeach
                
                @foreach($chartData as $index => $data)
                    @php $x = 40 + ($index * 70); @endphp
                    <text x="{{ $x }}" y="195" font-size="11" fill="var(--text-sec)" text-anchor="middle">{{ $data['tgl'] }} {{ now()->translatedFormat('M') }}</text>
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
                    <td style="color: var(--text-sec); font-size: 12.5px;">{{ $order->created_at->format('d M Y') }}</td>
                    <td style="font-weight: 600; color: var(--text-main);">{{ $order->order_number }}</td>
                    <td>{{ $order->user->name ?? 'Guest' }}</td>
                    <td style="text-align: center;">{{ $order->items->sum('quantity') }}</td>
                    <td class="val-currency">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    <td class="val-currency val-negative">- Rp 0</td>
                    <td class="val-currency" style="color: var(--accent-dark); font-weight: 700;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
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
                    <td colspan="8" style="text-align: center; padding: 40px; color: var(--text-muted);">Tidak ada data transaksi di bulan ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection