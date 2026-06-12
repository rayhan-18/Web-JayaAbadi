@extends('layouts.admin')

@section('title', 'Dashboard')

@section('styles')
<style>
    /* Reset & Premium Variables based on Sidebar & Topbar */
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
        display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;
    }
    .page-title h1 {
        font-size: 22px; font-weight: 700; color: var(--text-main); letter-spacing: -0.02em; margin: 0;
    }
    .page-title p { font-size: 13px; color: var(--text-sec); margin-top: 4px; }

    .date-filter {
        display: flex; align-items: center; gap: 8px; border: 1px solid var(--border); background: var(--bg-surface);
        border-radius: var(--radius-md); padding: 8px 14px; font-size: 12.5px; color: var(--text-sec);
    }
    .date-filter i { color: var(--accent); font-size: 16px; }

    /* Stats Grid */
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 24px; }
    .stat-card {
        background: var(--bg-surface); border-radius: var(--radius-lg); padding: 20px; border: 1px solid var(--border);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(45, 59, 50, 0.04); }
    .stat-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 14px; }
    .stat-info { flex: 1; }
    .stat-label { font-size: 12.5px; font-weight: 600; color: var(--text-sec); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.04em; }
    .stat-value { font-size: 24px; font-weight: 700; color: var(--text-main); line-height: 1.2; letter-spacing: -0.02em; }
    .stat-icon {
        width: 42px; height: 42px; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0;
    }
    
    .icon-green { background: transparent; color: var(--accent); }
    .icon-blue  { background: transparent; color: #5c7b9e; }
    .icon-gold  { background: transparent; color: #b89247; }
    .icon-gray  { background: transparent; color: var(--text-sec); }
    .stat-time-tip { font-size: 11.5px; color: var(--text-muted); margin-top: 6px; display: block; }

    /* Two Column Layout */
    .two-col { display: grid; grid-template-columns: 1fr 340px; gap: 20px; }
    .left-col, .right-col { display: flex; flex-direction: column; gap: 20px; }

    /* Cards */
    .card { background: var(--bg-surface); border-radius: var(--radius-lg); border: 1px solid var(--border); overflow: hidden; }
    .card-header { display: flex; align-items: center; justify-content: space-between; padding: 18px 20px; border-bottom: 1px solid var(--border); }
    .card-title { font-size: 15px; font-weight: 700; color: var(--text-main); letter-spacing: -0.01em; }
    .dropdown-btn {
        display: flex; align-items: center; gap: 6px; border: 1px solid var(--border); border-radius: 6px; padding: 6px 12px;
        font-size: 12px; font-weight: 500; color: var(--text-sec); background: var(--bg-surface);
    }
    .link-btn { font-size: 12.5px; font-weight: 600; color: var(--accent); text-decoration: none; }
    .link-btn:hover { color: var(--accent-dark); }

    /* Chart Area */
    .chart-legend { display: flex; gap: 20px; padding: 16px 20px 0; }
    .legend-item { display: flex; align-items: center; gap: 8px; font-size: 12px; color: var(--text-sec); font-weight: 500; }
    .legend-line { width: 16px; height: 3px; border-radius: 2px; }
    .legend-line.solid  { background: var(--accent); }
    .legend-line.dashed { background: repeating-linear-gradient(90deg, var(--text-muted) 0 4px, transparent 4px 8px); height: 2px; }
    .chart-area { padding: 12px 20px 24px; overflow-x: auto; }
    .chart-svg { width: 100%; height: 220px; overflow: visible; min-width: 600px; }

    /* Table Design */
    .overflow-x-auto { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; font-size: 13px; min-width: 600px; }
    th {
        text-align: left; padding: 14px 20px; background: var(--bg-hover); font-weight: 600;
        color: var(--text-sec); border-bottom: 1px solid var(--border); font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em;
    }
    td { padding: 14px 20px; border-bottom: 1px solid var(--border); color: var(--text-main); font-weight: 500; }
    tr:last-child td { border-bottom: none; }
    
    /* Muted Premium Badges */
    .badge { display: inline-flex; align-items: center; justify-content: center; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; letter-spacing: 0.02em; }
    .badge-green  { background: var(--accent-light); color: var(--accent-dark); }
    .badge-orange { background: #fdf5e6; color: #8a5a2e; }
    .badge-blue   { background: #f0f4f8; color: #4a6b8c; }
    .badge-gray   { background: var(--bg-hover); color: var(--text-sec); }
    
    .action-btn {
        display: inline-flex; align-items: center; justify-content: center; width: 30px; height: 30px; border-radius: 8px;
        background: var(--bg-surface); border: 1px solid var(--border); color: var(--text-sec); text-decoration: none; font-size: 15px;
    }
    .action-btn:hover { background: var(--bg-hover); color: var(--accent); border-color: #d1d6cf; }

    /* Lists */
    .list-wrapper { padding: 8px 20px 20px; }
    .list-item { display: flex; align-items: center; gap: 14px; padding: 12px 0; border-bottom: 1px solid #f0f2ef; }
    .list-item:last-child { border-bottom: none; padding-bottom: 0; }
    
    .item-thumb {
        width: 42px; height: 42px; background: var(--bg-hover); border-radius: 10px; display: flex; align-items: center;
        justify-content: center; font-size: 20px; color: var(--text-sec); border: 1px solid var(--border); flex-shrink: 0;
    }
    .prod-thumb { width: 44px; height: 44px; background: var(--bg-hover); border-radius: 8px; display: flex; align-items: center; justify-content: center; overflow: hidden; border: 1px solid var(--border); flex-shrink: 0; }
    .prod-thumb img { width: 100%; height: 100%; object-fit: cover; }

    .item-info { flex: 1; }
    .item-name { font-weight: 600; color: var(--text-main); font-size: 13.5px; margin-bottom: 2px; }
    .item-sub { font-size: 12px; color: var(--text-muted); }
    .item-value { font-weight: 700; color: var(--text-main); font-size: 13.5px; }
    
    .revenue-item { background: var(--bg-hover); border-radius: 10px; padding: 14px 16px; border: 1px solid transparent; display: flex; align-items: center; }
    .revenue-item .item-thumb { background: var(--accent-light); color: var(--accent); border: none; }
    .revenue-item .item-value { color: var(--accent); font-size: 15px; margin-left: auto; }

    @media (max-width: 1024px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } .two-col { grid-template-columns: 1fr; } }
    @media (max-width: 768px) { .stats-grid { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1>Dashboard</h1>
        <p>Ikhtisar performa toko Anda hari ini.</p>
    </div>
    <div class="date-filter">
        <i class="ti ti-calendar-event"></i>
        <span>Hari Ini: {{ date('d M Y') }}</span>
    </div>
</div>

{{-- 4 Stat Cards Berjalan Otomatis --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-top">
            <div class="stat-info">
                <div class="stat-label">Total Produk</div>
                <div class="stat-value">{{ number_format($stats['products'], 0, ',', '.') }}</div>
            </div>
            <div class="stat-icon icon-green"><i class="ti ti-box"></i></div>
        </div>
        <span class="stat-time-tip">Keseluruhan sku produk aktif</span>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <div class="stat-info">
                <div class="stat-label">Total Pesanan</div>
                <div class="stat-value">{{ number_format($stats['orders'], 0, ',', '.') }}</div>
            </div>
            <div class="stat-icon icon-blue"><i class="ti ti-shopping-cart"></i></div>
        </div>
        <span class="stat-time-tip">Akumulasi seluruh invoice masuk</span>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <div class="stat-info">
                <div class="stat-label">Total Omset</div>
                <div class="stat-value">Rp {{ number_format($stats['revenue'], 0, ',', '.') }}</div>
            </div>
            <div class="stat-icon icon-gold"><i class="ti ti-wallet"></i></div>
        </div>
        <span class="stat-time-tip">Dari transaksi berstatus lunas</span>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <div class="stat-info">
                <div class="stat-label">User Terdaftar</div>
                <div class="stat-value">{{ number_format($stats['users'], 0, ',', '.') }}</div>
            </div>
            <div class="stat-icon icon-gray"><i class="ti ti-users"></i></div>
        </div>
        <span class="stat-time-tip">Total akun pembeli saat ini</span>
    </div>
</div>

<div class="two-col">
    <div class="left-col">
        {{-- Grafik Penjualan (Statis Sampling Desain SVG) --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">Grafik Penjualan</span>
                <button class="dropdown-btn">Tahun Ini</button>
            </div>
            <div class="chart-legend">
                <div class="legend-item"><div class="legend-line solid"></div> Pendapatan</div>
                <div class="legend-item"><div class="legend-line dashed"></div> Pesanan</div>
            </div>
            <div class="chart-area">
                <svg class="chart-svg" viewBox="0 0 860 200" preserveAspectRatio="none">
                    <text x="0" y="16" font-size="11" fill="var(--text-muted)">40jt</text>
                    <text x="0" y="66" font-size="11" fill="var(--text-muted)">30jt</text>
                    <text x="0" y="116" font-size="11" fill="var(--text-muted)">20jt</text>
                    <text x="0" y="166" font-size="11" fill="var(--text-muted)">10jt</text>
                    <text x="4" y="196" font-size="11" fill="var(--text-muted)">0</text>
                    <line x1="28" y1="10" x2="860" y2="10" stroke="var(--bg-hover)" stroke-width="1"/>
                    <line x1="28" y1="60" x2="860" y2="60" stroke="var(--bg-hover)" stroke-width="1"/>
                    <line x1="28" y1="110" x2="860" y2="110" stroke="var(--bg-hover)" stroke-width="1"/>
                    <line x1="28" y1="160" x2="860" y2="160" stroke="var(--bg-hover)" stroke-width="1"/>
                    <polyline points="50,155 120,145 190,90 260,42 330,130 400,155 470,120 540,125 610,120 680,110 750,100 820,30" fill="none" stroke="var(--accent)" stroke-width="3" stroke-linejoin="round" stroke-linecap="round"/>
                    @foreach([[50,155],[120,145],[190,90],[260,42],[330,130],[400,155],[470,120],[540,125],[610,120],[680,110],[750,100],[820,30]] as [$x,$y])
                    <circle cx="{{ $x }}" cy="{{ $y }}" r="4" fill="var(--bg-surface)" stroke="var(--accent)" stroke-width="2"/>
                    @endforeach
                    @foreach(['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'] as $i => $bln)
                    <text x="{{ 44+($i*70) }}" y="210" font-size="11" fill="var(--text-sec)" text-anchor="middle">{{ $bln }}</text>
                    @endforeach
                </svg>
            </div>
        </div>

        {{-- Real-Time Orders Table --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">Pesanan Terbaru</span>
                <a href="{{ route('admin.order.index') }}" class="link-btn">Lihat semua</a>
            </div>
            <div class="overflow-x-auto">
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th><th>Pelanggan</th><th>Tanggal</th>
                            <th>Total</th><th>Status</th><th style="text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                        <tr>
                            <td style="font-weight: 700;">#{{ $order->order_number }}</td>
                            <td>{{ $order->user->name ?? 'Guest' }}</td>
                            <td style="color: var(--text-sec);">{{ $order->created_at->format('d M Y H:i') }}</td>
                            <td style="font-weight: 600;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td>
                                @php
                                    $badgeCls = match(strtolower($order->status)) {
                                        'delivered' => 'badge-green',
                                        'paid'      => 'badge-orange',
                                        'pending'   => 'badge-blue',
                                        'cancelled' => 'badge-gray',
                                        default     => 'badge-gray'
                                    };
                                    $lblText = match(strtolower($order->status)) {
                                        'delivered' => 'Selesai',
                                        'paid'      => 'Diproses',
                                        'pending'   => 'Pending',
                                        'cancelled' => 'Batal',
                                        default     => ucfirst($order->status)
                                    };
                                @endphp
                                <span class="badge {{ $badgeCls }}">{{ $lblText }}</span>
                            </td>
                            <td style="text-align: center;">
                                <a href="{{ route('admin.order.index') }}" class="action-btn" title="Lihat Detail"><i class="ti ti-eye"></i></a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 24px;">Belum ada pesanan masuk.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="right-col">
        {{-- Real Best Sellers --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">Produk Terlaris</span>
            </div>
            <div class="list-wrapper">
                @foreach($topProducts as $p)
                <div class="list-item">
                    <div class="prod-thumb"><img src="{{ $p['img'] }}" alt="{{ $p['nama'] }}"></div>
                    <div class="item-info">
                        <div class="item-name">{{ $p['nama'] }}</div>
                        <div class="item-sub">Terjual {{ $p['terjual'] }} unit</div>
                    </div>
                    <div class="item-value">Rp {{ number_format($p['harga'], 0, ',', '.') }}</div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Real Revenue Summary --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">Rangkuman Pendapatan</span>
            </div>
            <div class="list-wrapper" style="display: flex; flex-direction: column; gap: 10px; padding-top: 16px;">
                <div class="list-item revenue-item">
                    <div class="item-thumb"><i class="ti ti-coin"></i></div>
                    <div class="item-info">
                        <div class="item-sub">Hari Ini</div>
                        <div class="item-value">Rp {{ number_format($revenueSummary['today'], 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="list-item revenue-item">
                    <div class="item-thumb"><i class="ti ti-calendar-week"></i></div>
                    <div class="item-info">
                        <div class="item-sub">Minggu Ini</div>
                        <div class="item-value">Rp {{ number_format($revenueSummary['week'], 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="list-item revenue-item">
                    <div class="item-thumb"><i class="ti ti-calendar-stats"></i></div>
                    <div class="item-info">
                        <div class="item-sub">Bulan Ini</div>
                        <div class="item-value">Rp {{ number_format($revenueSummary['month'], 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection