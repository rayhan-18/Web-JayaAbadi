@extends('layouts.admin')

@section('title', 'Super Admin Dashboard')

@section('styles')
<style>
    :root {
        --accent: #2563eb; 
        --accent-dark: #1e40af;
        --border: rgba(15, 23, 42, 0.06);
        --bg-surface: #ffffff;
        --bg-hover: #f1f5f9;
        --text-main: #0f172a;
        --text-sec: #475569;
        --text-muted: #94a3b8;
        --radius-md: 14px;
        --radius-lg: 20px;
        --shadow-card: 0 10px 30px -10px rgba(15, 23, 42, 0.05);
    }
    .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 28px; }
    .page-title h1 { font-size: 24px; font-weight: 700; color: var(--text-main); margin: 0; }
    .sa-badge { display: inline-block; background: #1e293b; color: white; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; margin-left: 10px; }
    .date-filter { display: flex; align-items: center; gap: 8px; border: 1px solid var(--border); background: var(--bg-surface); padding: 10px 16px; border-radius: var(--radius-md); font-size: 13px; font-weight: 500; }
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 24px; }
    .stat-card { background: var(--bg-surface); border-radius: var(--radius-lg); padding: 22px; border: 1px solid var(--border); box-shadow: var(--shadow-card); }
    .stat-top { display: flex; justify-content: space-between; margin-bottom: 16px; }
    .stat-label { font-size: 12px; font-weight: 600; color: var(--text-sec); margin-bottom: 8px; text-transform: uppercase; }
    .stat-value { font-size: 26px; font-weight: 700; color: var(--text-main); }
    .stat-icon { width: 46px; height: 46px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px; }
    .icon-green { background: #ecfdf5; color: #10b981; } .icon-purple { background: #f3e8ff; color: #8b5cf6; }
    .icon-accent { background: #eff6ff; color: var(--accent); } .icon-orange { background: #fffbeb; color: #f59e0b; }
    .two-col { display: grid; grid-template-columns: 1fr 360px; gap: 20px; }
    .card { background: var(--bg-surface); border-radius: var(--radius-lg); border: 1px solid var(--border); box-shadow: var(--shadow-card); overflow: hidden; margin-bottom: 20px; }
    .card-header { padding: 20px 24px; border-bottom: 1px solid var(--border); font-weight: 700; font-size: 16px; display: flex; justify-content: space-between; }
    .chart-svg { width: 100%; height: 220px; min-width: 600px; }
    table { width: 100%; border-collapse: collapse; font-size: 13px; }
    th { text-align: left; padding: 14px 24px; background: rgba(0,0,0,0.015); border-bottom: 1px solid var(--border); color: var(--text-sec); font-size: 12px; }
    td { padding: 14px 24px; border-bottom: 1px solid var(--border); }
    .status-badge { padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; }
    .status-delivered { background: #ecfdf5; color: #059669; } .status-pending { background: #fffbeb; color: #d97706; }
    .list-item { display: flex; align-items: center; gap: 16px; padding: 12px 24px; border-bottom: 1px solid #f1f5f9; }
    .prod-thumb { width: 48px; height: 48px; border-radius: 12px; background: var(--bg-hover); display: flex; align-items: center; justify-content: center; overflow: hidden; }
    .prod-thumb img { width: 100%; height: 100%; object-fit: cover; }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1>Dashboard <span class="sa-badge">SUPER ADMIN</span></h1>
        <p>Ikhtisar performa toko dan kontrol data hari ini.</p>
    </div>
    <div class="date-filter"><i class="ti ti-calendar-event"></i> Hari Ini: {{ date('d M Y') }}</div>
</div>

<div class="stats-grid">
    <div class="stat-card"><div class="stat-top"><div class="stat-info"><div class="stat-label">Total Produk</div><div class="stat-value">{{ number_format($stats['products'] ?? 0, 0, ',', '.') }}</div></div><div class="stat-icon icon-green"><i class="ti ti-box"></i></div></div></div>
    <div class="stat-card"><div class="stat-top"><div class="stat-info"><div class="stat-label">Total Pesanan</div><div class="stat-value">{{ number_format($stats['orders'] ?? 0, 0, ',', '.') }}</div></div><div class="stat-icon icon-purple"><i class="ti ti-shopping-cart"></i></div></div></div>
    <div class="stat-card"><div class="stat-top"><div class="stat-info"><div class="stat-label">Total Omset</div><div class="stat-value">Rp {{ number_format($stats['revenue'] ?? 0, 0, ',', '.') }}</div></div><div class="stat-icon icon-accent"><i class="ti ti-wallet"></i></div></div></div>
    <div class="stat-card"><div class="stat-top"><div class="stat-info"><div class="stat-label">User Terdaftar</div><div class="stat-value">{{ number_format($stats['users'] ?? 0, 0, ',', '.') }}</div></div><div class="stat-icon icon-orange"><i class="ti ti-users"></i></div></div></div>
</div>

<div class="two-col">
    <div>
        <div class="card">
            <div class="card-header">Grafik Penjualan</div>
            <div style="padding: 16px 24px; overflow-x: auto;">
                <svg class="chart-svg" viewBox="0 0 860 200" preserveAspectRatio="none">
                    <polyline points="50,155 120,145 190,90 260,42 330,130 400,155 470,120 540,125 610,120 680,110 750,100 820,30" fill="none" stroke="#2563eb" stroke-width="3.5" stroke-linejoin="round" stroke-linecap="round"/>
                    @foreach([[50,155],[120,145],[190,90],[260,42],[330,130],[400,155],[470,120],[540,125],[610,120],[680,110],[750,100],[820,30]] as [$x,$y])
                    <circle cx="{{ $x }}" cy="{{ $y }}" r="4.5" fill="#fff" stroke="#2563eb" stroke-width="2.5"/>
                    @endforeach
                    @foreach(['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'] as $i => $bln)
                    <text x="{{ 48+($i*70) }}" y="195" font-size="11.5" fill="#475569" text-anchor="middle">{{ $bln }}</text>
                    @endforeach
                </svg>
            </div>
        </div>
        <div class="card">
            <div class="card-header">Pesanan Terbaru</div>
            <div style="overflow-x: auto;">
                <table>
                    <thead><tr><th>Order ID</th><th>Pelanggan</th><th>Tanggal</th><th>Total</th><th>Status</th></tr></thead>
                    <tbody>
                        @foreach($recentOrders ?? [] as $order)
                        <tr>
                            <td style="font-weight:700;">#{{ $order->order_number }}</td>
                            <td>{{ $order->user->name ?? 'Guest' }}<br><span style="font-size:11px;color:gray;">{{ $order->user->email ?? '-' }}</span></td>
                            <td>{{ $order->created_at->format('d M Y') }}</td>
                            <td style="font-weight:700;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td><span class="status-badge {{ strtolower($order->status) == 'pending' ? 'status-pending' : 'status-delivered' }}">{{ ucfirst($order->status) }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div>
        <div class="card">
            <div class="card-header">Produk Terlaris</div>
            <div>
                @foreach($topProducts ?? [] as $p)
                <div class="list-item">
                    <div class="prod-thumb"><img src="{{ $p['img'] ?? '' }}" alt=""></div>
                    <div style="flex:1;"><div style="font-weight:600;font-size:14px;">{{ $p['nama'] ?? 'Produk' }}</div><div style="font-size:12px;color:gray;">Terjual {{ $p['terjual'] ?? 0 }} unit</div></div>
                    <div style="font-weight:700;font-size:14px;">Rp {{ number_format($p['harga'] ?? 0, 0, ',', '.') }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection