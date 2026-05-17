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
    <div style="display: flex; gap: 12px;">
        <div class="export-dropdown">
            <button class="btn-export" style="height: 40px; font-size: 13px; border-radius: var(--radius-md);"><i class="ti ti-download"></i> Export <i class="ti ti-chevron-down" style="font-size: 14px;"></i></button>
            <div class="export-dropdown-content">
                <a href="#"><i class="ti ti-file-spreadsheet"></i> Export Excel</a>
                <a href="#"><i class="ti ti-file-type-csv"></i> Export CSV</a>
            </div>
        </div>
        <div class="date-filter">
            <i class="ti ti-calendar-event"></i>
            <span>Bulan Ini (Mei 2024)</span>
            <i class="ti ti-chevron-down" style="font-size: 14px; color: var(--text-muted);"></i>
        </div>
    </div>
</div>

<div class="stats-row">
    <div class="stat-card revenue">
        <div class="stat-icon"><i class="ti ti-coin"></i></div>
        <div class="stat-label">Total Pendapatan</div>
        <div class="stat-value">Rp 148.500.000</div>
        <div class="stat-trend up"><i class="ti ti-trending-up"></i> +15.2% vs Apr</div>
    </div>
    <div class="stat-card orders">
        <div class="stat-icon"><i class="ti ti-shopping-cart"></i></div>
        <div class="stat-label">Total Pesanan</div>
        <div class="stat-value">345</div>
        <div class="stat-trend up"><i class="ti ti-trending-up"></i> +8.4% vs Apr</div>
    </div>
    <div class="stat-card aov">
        <div class="stat-icon"><i class="ti ti-receipt"></i></div>
        <div class="stat-label">Rata-rata Transaksi</div>
        <div class="stat-value">Rp 430.434</div>
        <div class="stat-trend up"><i class="ti ti-trending-up"></i> +2.1% vs Apr</div>
    </div>
    <div class="stat-card items">
        <div class="stat-icon"><i class="ti ti-packages"></i></div>
        <div class="stat-label">Produk Terjual</div>
        <div class="stat-value">512</div>
        <div class="stat-trend down"><i class="ti ti-trending-down"></i> -3.5% vs Apr</div>
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
            
            <polyline points="40,150 110,130 180,140 250,90 320,60 390,100 460,80 530,30 600,50 670,40 740,70 810,20"
                fill="none" stroke="var(--accent)" stroke-width="3" stroke-linejoin="round" stroke-linecap="round"/>
            @foreach([[40,150],[110,130],[180,140],[250,90],[320,60],[390,100],[460,80],[530,30],[600,50],[670,40],[740,70],[810,20]] as [$x,$y])
            <circle cx="{{ $x }}" cy="{{ $y }}" r="4" fill="var(--bg-surface)" stroke="var(--accent)" stroke-width="2"/>
            @endforeach
            
            @foreach(['01','03','05','07','09','11','13','15','17','19','21','23'] as $i => $tgl)
            <text x="{{ 40+($i*70) }}" y="195" font-size="11" fill="var(--text-sec)" text-anchor="middle">{{ $tgl }} Mei</text>
            @endforeach
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
                    <th>Kuantitas</th>
                    <th>Subtotal</th>
                    <th>Diskon</th>
                    <th>Total Bersih</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $sales = [
                        ['tgl'=>'26 Mei 2024', 'id'=>'#ORD-00123', 'nama'=>'Rayhan Maulana', 'qty'=>1, 'subtotal'=>2800000, 'diskon'=>0, 'total'=>2800000, 'status'=>'Berhasil'],
                        ['tgl'=>'25 Mei 2024', 'id'=>'#ORD-00122', 'nama'=>'Siti Aisyah', 'qty'=>1, 'subtotal'=>1900000, 'diskon'=>50000, 'total'=>1850000, 'status'=>'Berhasil'],
                        ['tgl'=>'25 Mei 2024', 'id'=>'#ORD-00121', 'nama'=>'Budi Santoso', 'qty'=>2, 'subtotal'=>3300000, 'diskon'=>100000, 'total'=>3200000, 'status'=>'Berhasil'],
                        ['tgl'=>'23 Mei 2024', 'id'=>'#ORD-00115', 'nama'=>'Ahmad Fauzi', 'qty'=>1, 'subtotal'=>4500000, 'diskon'=>0, 'total'=>4500000, 'status'=>'Refund'],
                        ['tgl'=>'21 Mei 2024', 'id'=>'#ORD-00112', 'nama'=>'Larasati Putri', 'qty'=>3, 'subtotal'=>1400000, 'diskon'=>150000, 'total'=>1250000, 'status'=>'Berhasil'],
                    ];
                @endphp
                @foreach($sales as $s)
                <tr>
                    <td style="color: var(--text-sec); font-size: 12.5px;">{{ $s['tgl'] }}</td>
                    <td style="font-weight: 600; color: var(--text-main);">{{ $s['id'] }}</td>
                    <td>{{ $s['nama'] }}</td>
                    <td style="text-align: center;">{{ $s['qty'] }}</td>
                    <td class="val-currency">Rp {{ number_format($s['subtotal'], 0, ',', '.') }}</td>
                    <td class="val-currency val-negative">- Rp {{ number_format($s['diskon'], 0, ',', '.') }}</td>
                    <td class="val-currency" style="color: var(--accent-dark); font-weight: 700;">Rp {{ number_format($s['total'], 0, ',', '.') }}</td>
                    <td>
                        @php $cls = $s['status'] == 'Berhasil' ? 'status-berhasil' : 'status-refund'; @endphp
                        <span class="status-badge {{ $cls }}">{{ $s['status'] }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection