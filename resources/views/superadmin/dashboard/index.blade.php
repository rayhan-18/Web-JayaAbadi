@extends('layouts.admin')

@section('title', 'Super Admin Dashboard')

@section('styles')
<style>
    /* ── Reset & Premium Variables (Tema: Royal Blue & Minimalist Slate) ── */
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
        display: flex; align-items: center; justify-content: space-between; margin-bottom: 28px; flex-wrap: wrap; gap: 16px;
    }
    .page-title h1 {
        font-size: 24px; font-weight: 700; color: var(--text-main); letter-spacing: -0.02em; margin: 0;
    }
    .page-title p { font-size: 13.5px; color: var(--text-sec); margin-top: 4px; }
    
    /* Super Admin Badge di Header */
    .sa-badge {
        display: inline-block; background: #1e293b; color: white; padding: 4px 10px; border-radius: 6px;
        font-size: 11px; font-weight: 700; letter-spacing: 0.05em; vertical-align: middle; margin-left: 10px;
    }

    .date-filter {
        display: flex; align-items: center; gap: 8px; border: 1px solid var(--border); background: var(--bg-surface);
        border-radius: var(--radius-md); padding: 10px 16px; font-size: 13px; font-weight: 500; color: var(--text-sec);
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }
    .date-filter i { color: var(--accent); font-size: 18px; }

    /* ── Stats Grid ── */
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 24px; }
    .stat-card {
        background: var(--bg-surface); border-radius: var(--radius-lg); padding: 22px; 
        border: 1px solid var(--border); box-shadow: var(--shadow-card);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .stat-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-hover); }
    .stat-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px; }
    .stat-info { flex: 1; }
    .stat-label { font-size: 12px; font-weight: 600; color: var(--text-sec); margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.06em; }
    .stat-value { font-size: 26px; font-weight: 700; color: var(--text-main); line-height: 1.1; letter-spacing: -0.02em; }
    .stat-icon {
        width: 46px; height: 46px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0;
    }
    
    /* Warna Ikon Stat */
    .icon-green  { background: #ecfdf5; color: #10b981; }
    .icon-purple { background: #f3e8ff; color: #8b5cf6; }
    .icon-accent { background: var(--accent-light); color: var(--accent); }
    .icon-orange { background: #fffbeb; color: #f59e0b; }
    .stat-time-tip { font-size: 12px; font-weight: 500; color: var(--text-muted); display: block; }

    /* ── Two Column Layout ── */
    .two-col { display: grid; grid-template-columns: 1fr 360px; gap: 20px; min-width: 0; }
    .left-col, .right-col { display: flex; flex-direction: column; gap: 20px; min-width: 0; }

    /* ── Cards Design Premium ── */
    .card { 
        background: var(--bg-surface); border-radius: var(--radius-lg); 
        border: 1px solid var(--border); box-shadow: var(--shadow-card); overflow: hidden; 
        min-width: 0; width: 100%;
    }
    .card-header { 
        display: flex; align-items: center; justify-content: space-between; 
        padding: 20px 24px; border-bottom: 1px solid var(--border); 
    }
    .card-title { font-size: 16px; font-weight: 700; color: var(--text-main); letter-spacing: -0.01em; display: flex; align-items: center; gap: 8px;}
    .card-title::before { content: ''; display: block; width: 4px; height: 14px; background: var(--accent); border-radius: 4px; }
    
    .dropdown-btn {
        display: flex; align-items: center; gap: 6px; border: 1px solid var(--border); border-radius: 8px; padding: 6px 14px;
        font-size: 12px; font-weight: 600; color: var(--text-sec); background: var(--bg-hover); cursor: pointer; transition: all 0.2s;
    }
    .dropdown-btn:hover { border-color: #cbd5e1; color: var(--text-main); }
    .link-btn { font-size: 13px; font-weight: 600; color: var(--accent); text-decoration: none; transition: color 0.2s; }
    .link-btn:hover { color: var(--accent-dark); }

    /* ── Chart Area ── */
    .chart-legend { display: flex; gap: 24px; padding: 20px 24px 0; }
    .legend-item { display: flex; align-items: center; gap: 8px; font-size: 12.5px; color: var(--text-sec); font-weight: 500; }
    .legend-line { width: 16px; height: 3px; border-radius: 2px; }
    .legend-line.solid  { background: var(--accent); }
    .legend-line.dashed { background: repeating-linear-gradient(90deg, var(--text-muted) 0 4px, transparent 4px 8px); height: 2px; }
    .chart-area { padding: 16px 24px 24px; overflow-x: auto; }
    .chart-area::-webkit-scrollbar { display: none; }
    .chart-svg { width: 100%; height: 220px; overflow: visible; min-width: 600px; }

    /* ── Table Design ── */
    .table-wrapper { 
        width: 100%; display: block; overflow-x: auto; -webkit-overflow-scrolling: touch; padding-bottom: 4px; 
    }
    .table-wrapper::-webkit-scrollbar { height: 6px; }
    .table-wrapper::-webkit-scrollbar-track { background: transparent; }
    .table-wrapper::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .table-wrapper::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    
    table { width: 100%; border-collapse: collapse; font-size: 13px; min-width: 850px; }
    th {
        text-align: left; padding: 14px 24px; background: rgba(0,0,0,0.015); font-weight: 600;
        color: var(--text-sec); border-bottom: 1px solid var(--border); font-size: 12px;
        text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap;
    }
    td { padding: 14px 24px; border-bottom: 1px solid var(--border); vertical-align: middle; color: var(--text-main); white-space: nowrap; }
    tr:last-child td { border-bottom: none; }
    tbody tr:hover { background: var(--bg-hover); }

    .invoice-link { font-weight: 700; color: var(--text-main); text-decoration: none; transition: color 0.2s; }
    .invoice-link:hover { color: var(--accent); }

    .customer-info { line-height: 1.4; }
    .customer-name { font-weight: 600; color: var(--text-main); font-size: 13.5px; }
    .customer-email { font-size: 11.5px; color: var(--text-muted); margin-top: 2px; }

    /* ── Badges Status ── */
    .status-badge {
        display: inline-flex; align-items: center; justify-content: center;
        padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; letter-spacing: 0.02em; white-space: nowrap;
    }
    .status-badge::before { content: ''; width: 6px; height: 6px; border-radius: 50%; margin-right: 6px; }

    .status-pending { background: #fffbeb; color: #d97706; }
    .status-pending::before { background: #f59e0b; }
    .status-paid { background: var(--accent-light); color: var(--accent-dark); }
    .status-paid::before { background: var(--accent); }
    .status-delivered { background: #ecfdf5; color: #059669; }
    .status-delivered::before { background: #10b981; }
    .status-cancelled { background: #fef2f2; color: #dc2626; }
    .status-cancelled::before { background: #ef4444; }

    /* Status Akun Admin */
    .acc-active { background: #ecfdf5; color: #059669; }
    .acc-inactive { background: #fef2f2; color: #dc2626; }

    /* Button Actions Admin */
    .btn-toggle { 
        padding: 6px 14px; border-radius: 6px; font-size: 11px; font-weight: 600; 
        cursor: pointer; border: none; transition: 0.2s; display: inline-flex; align-items: center; gap: 4px;
    }
    .btn-danger { background: #fee2e2; color: #dc2626; }
    .btn-danger:hover { background: #fca5a5; color: #991b1b; }
    .btn-success { background: #d1fae5; color: #059669; }
    .btn-success:hover { background: #6ee7b7; color: #065f46; }

    .action-btn {
        width: 32px; height: 32px; border-radius: 8px; background: var(--bg-surface);
        border: 1px solid #e2e8f0; display: inline-flex; align-items: center; justify-content: center;
        cursor: pointer; color: var(--text-sec); font-size: 16px; transition: 0.15s; text-decoration: none;
    }
    .action-btn:hover { background: var(--bg-hover); color: var(--accent); border-color: #cbd5e1; }

    /* ── Lists (Terlaris & Revenue) ── */
    .list-wrapper { padding: 8px 24px 24px; display: flex; flex-direction: column; gap: 4px; }
    .list-item { display: flex; align-items: center; gap: 16px; padding: 12px 0; border-bottom: 1px solid #f1f5f9; transition: transform 0.2s; }
    .list-item:hover { transform: translateX(4px); }
    .list-item:last-child { border-bottom: none; padding-bottom: 0; }
    
    .item-thumb {
        width: 46px; height: 46px; background: var(--bg-hover); border-radius: 12px; display: flex; align-items: center;
        justify-content: center; font-size: 20px; color: var(--text-sec); border: 1px solid #e2e8f0; flex-shrink: 0;
    }
    .prod-thumb { width: 48px; height: 48px; background: var(--bg-hover); border-radius: 12px; display: flex; align-items: center; justify-content: center; overflow: hidden; border: 1px solid var(--border); flex-shrink: 0; }
    .prod-thumb img { width: 100%; height: 100%; object-fit: cover; }

    .item-info { flex: 1; min-width: 0; }
    .item-name { font-weight: 600; color: var(--text-main); font-size: 14px; margin-bottom: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .item-sub { font-size: 12px; font-weight: 500; color: var(--text-muted); }
    .item-value { font-weight: 700; color: var(--text-main); font-size: 14px; white-space: nowrap; }
    
    .revenue-item { background: var(--bg-surface); border-radius: 12px; padding: 12px 0; border: none; align-items: center; }
    .revenue-item .item-thumb { background: #f1f5f9; color: var(--text-sec); border: 1px solid var(--border); }
    .revenue-item .item-value { color: #0f172a !important; font-size: 16px; margin-left: auto; letter-spacing: -0.02em; font-weight: 700; }
    
    /* ── RESPONSIVE MOBILE & APPS ── */
    @media (max-width: 1024px) { 
        .stats-grid { grid-template-columns: repeat(2, 1fr); } 
        .two-col { grid-template-columns: 1fr; } 
    }
    @media (max-width: 768px) { 
        .page-header { flex-direction: column; align-items: flex-start; gap: 12px; margin-bottom: 20px; }
        .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; margin-bottom: 20px; }
        .stat-card { padding: 16px; border-radius: 16px; }
        .stat-label { font-size: 10.5px; margin-bottom: 4px; }
        .stat-value { font-size: 20px; }
        .stat-icon { width: 38px; height: 38px; font-size: 18px; border-radius: 10px; }
        
        .card-header { padding: 16px 20px; }
        .chart-legend, .chart-area, .list-wrapper { padding-left: 20px; padding-right: 20px; }
        th, td { padding: 14px 16px; }
    }
    @media (max-width: 480px) {
        .stats-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1>Dashboard <span class="sa-badge">SUPER ADMIN</span></h1>
        <p>Ikhtisar performa toko dan kontrol admin hari ini.</p>
    </div>
    <div class="date-filter">
        <i class="ti ti-calendar-event"></i>
        <span>Hari Ini: {{ date('d M Y') }}</span>
    </div>
</div>

{{-- 4 Stat Cards --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-top">
            <div class="stat-info">
                <div class="stat-label">Total Produk</div>
                <div class="stat-value">{{ number_format($stats['products'] ?? 0, 0, ',', '.') }}</div>
            </div>
            <div class="stat-icon icon-green"><i class="ti ti-box"></i></div>
        </div>
        <span class="stat-time-tip">Keseluruhan sku aktif</span>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <div class="stat-info">
                <div class="stat-label">Total Pesanan</div>
                <div class="stat-value">{{ number_format($stats['orders'] ?? 0, 0, ',', '.') }}</div>
            </div>
            <div class="stat-icon icon-purple"><i class="ti ti-shopping-cart"></i></div>
        </div>
        <span class="stat-time-tip">Akumulasi invoice masuk</span>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <div class="stat-info">
                <div class="stat-label">Total Omset</div>
                <div class="stat-value">Rp {{ number_format($stats['revenue'] ?? 0, 0, ',', '.') }}</div>
            </div>
            <div class="stat-icon icon-accent"><i class="ti ti-wallet"></i></div>
        </div>
        <span class="stat-time-tip">Dari transaksi lunas</span>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <div class="stat-info">
                <div class="stat-label">User Terdaftar</div>
                <div class="stat-value">{{ number_format($stats['users'] ?? 0, 0, ',', '.') }}</div>
            </div>
            <div class="stat-icon icon-orange"><i class="ti ti-users"></i></div>
        </div>
        <span class="stat-time-tip">Total akun pembeli</span>
    </div>
</div>

<div class="two-col">
    <div class="left-col">
        {{-- Grafik Penjualan --}}
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
                    <text x="0" y="16" font-size="11" font-weight="500" fill="var(--text-muted)">40jt</text>
                    <text x="0" y="66" font-size="11" font-weight="500" fill="var(--text-muted)">30jt</text>
                    <text x="0" y="116" font-size="11" font-weight="500" fill="var(--text-muted)">20jt</text>
                    <text x="0" y="166" font-size="11" font-weight="500" fill="var(--text-muted)">10jt</text>
                    <text x="4" y="196" font-size="11" font-weight="500" fill="var(--text-muted)">0</text>
                    <line x1="32" y1="10" x2="860" y2="10" stroke="#f1f5f9" stroke-width="1"/>
                    <line x1="32" y1="60" x2="860" y2="60" stroke="#f1f5f9" stroke-width="1"/>
                    <line x1="32" y1="110" x2="860" y2="110" stroke="#f1f5f9" stroke-width="1"/>
                    <line x1="32" y1="160" x2="860" y2="160" stroke="#f1f5f9" stroke-width="1"/>
                    <polyline points="50,155 120,145 190,90 260,42 330,130 400,155 470,120 540,125 610,120 680,110 750,100 820,30" fill="none" stroke="var(--accent)" stroke-width="3.5" stroke-linejoin="round" stroke-linecap="round"/>
                    @foreach([[50,155],[120,145],[190,90],[260,42],[330,130],[400,155],[470,120],[540,125],[610,120],[680,110],[750,100],[820,30]] as [$x,$y])
                    <circle cx="{{ $x }}" cy="{{ $y }}" r="4.5" fill="var(--bg-surface)" stroke="var(--accent)" stroke-width="2.5"/>
                    @endforeach
                    @foreach(['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'] as $i => $bln)
                    <text x="{{ 48+($i*70) }}" y="210" font-size="11.5" font-weight="600" fill="var(--text-sec)" text-anchor="middle">{{ $bln }}</text>
                    @endforeach
                </svg>
            </div>
        </div>

        {{-- Tabel Pesanan Terbaru --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">Pesanan Terbaru</span>
                <a href="{{ route('admin.order.index') }}" class="link-btn">Lihat semua</a>
            </div>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Pelanggan</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th style="text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders ?? [] as $order)
                        <tr>
                            <td><a href="{{ route('admin.order.index') }}" class="invoice-link">#{{ $order->order_number }}</a></td>
                            <td>
                                <div class="customer-info">
                                    <div class="customer-name">{{ $order->user->name ?? 'Guest' }}</div>
                                    <div class="customer-email">{{ $order->user->email ?? '-' }}</div>
                                </div>
                            </td>
                            <td style="color: var(--text-sec); font-size: 12.5px;">{{ $order->created_at->format('d M Y, H:i') }}</td>
                            <td style="font-weight: 600;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td>
                                @php
                                    $statusOrder = strtolower($order->status ?? 'pending');
                                    $badgeCls = match($statusOrder) {
                                        'delivered', 'selesai' => 'status-delivered',
                                        'paid', 'diproses'     => 'status-paid',
                                        'pending'              => 'status-pending',
                                        'cancelled', 'batal'   => 'status-cancelled',
                                        default                => 'status-pending'
                                    };
                                    $lblText = match($statusOrder) {
                                        'delivered' => 'Selesai',
                                        'paid'      => 'Diproses',
                                        'pending'   => 'Pending',
                                        'cancelled' => 'Batal',
                                        default     => ucfirst($statusOrder)
                                    };
                                @endphp
                                <span class="status-badge {{ $badgeCls }}">{{ $lblText }}</span>
                            </td>
                            <td style="text-align: center;">
                                <a href="{{ route('admin.order.index') }}" class="action-btn" title="Lihat Detail">
                                    <i class="ti ti-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 32px;">
                                <i class="ti ti-inbox" style="font-size: 32px; display: block; margin-bottom: 8px; opacity: 0.5;"></i>
                                Belum ada pesanan masuk hari ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- FITUR EKSKLUSIF SUPER ADMIN: Manajemen Admin --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">Manajemen Akun Admin</span>
                <span class="dropdown-btn" style="cursor:default; border:none; background:transparent;"><i class="ti ti-shield-lock" style="color:var(--accent);"></i> Akses Super Admin</span>
            </div>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Nama Admin</th>
                            <th>Email</th>
                            <th>Status Akun</th>
                            <th style="text-align: center;">Aksi Kontrol</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Looping data admin dari Controller. Pastikan passing variabel $admins --}}
                        @forelse($admins ?? [] as $admin)
                        <tr>
                            <td>
                                <div class="customer-info">
                                    <div class="customer-name">{{ $admin->name }}</div>
                                </div>
                            </td>
                            <td style="color: var(--text-sec); font-size: 12.5px;">{{ $admin->email }}</td>
                            <td>
                                {{-- Asumsi ada kolom 'is_active' (boolean) di tabel users --}}
                                @if($admin->is_active)
                                    <span class="status-badge acc-active">Aktif</span>
                                @else
                                    <span class="status-badge acc-inactive">Nonaktif</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                {{-- Form untuk toggle status admin. Arahkan route ini ke controller lu --}}
                                <form action="{{ route('superadmin.admin.toggle', $admin->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('PATCH')
                                    
                                    @if($admin->is_active)
                                        <button type="submit" class="btn-toggle btn-danger" onclick="return confirm('Yakin ingin menonaktifkan admin ini? Mereka tidak akan bisa login.')">
                                            <i class="ti ti-user-off"></i> Nonaktifkan
                                        </button>
                                    @else
                                        <button type="submit" class="btn-toggle btn-success" onclick="return confirm('Aktifkan kembali admin ini?')">
                                            <i class="ti ti-user-check"></i> Aktifkan
                                        </button>
                                    @endif
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 32px;">
                                <i class="ti ti-users" style="font-size: 32px; display: block; margin-bottom: 8px; opacity: 0.5;"></i>
                                Belum ada admin lain yang terdaftar.
                            </td>
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
            <div class="list-wrapper" style="padding-top: 12px;">
                @forelse($topProducts ?? [] as $p)
                <div class="list-item">
                    <div class="prod-thumb"><img src="{{ $p['img'] ?? '' }}" alt="{{ $p['nama'] ?? 'Produk' }}"></div>
                    <div class="item-info">
                        <div class="item-name">{{ $p['nama'] ?? 'Nama Produk' }}</div>
                        <div class="item-sub">Terjual {{ $p['terjual'] ?? 0 }} unit</div>
                    </div>
                    <div class="item-value">Rp {{ number_format($p['harga'] ?? 0, 0, ',', '.') }}</div>
                </div>
                @empty
                <div style="text-align: center; color: var(--text-muted); padding: 20px 0; font-size: 13px;">Belum ada data produk.</div>
                @endforelse
            </div>
        </div>

        {{-- Real Revenue Summary --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">Rangkuman Pendapatan</span>
            </div>
            <div class="list-wrapper" style="gap: 0; padding-top: 12px;">
                <div class="list-item revenue-item">
                    <div class="item-thumb"><i class="ti ti-coin"></i></div>
                    <div class="item-info">
                        <div class="item-sub">Hari Ini</div>
                    </div>
                    <div class="item-value">Rp {{ number_format($revenueSummary['today'] ?? 0, 0, ',', '.') }}</div>
                </div>
                <div class="list-item revenue-item">
                    <div class="item-thumb"><i class="ti ti-calendar-week"></i></div>
                    <div class="item-info">
                        <div class="item-sub">Minggu Ini</div>
                    </div>
                    <div class="item-value">Rp {{ number_format($revenueSummary['week'] ?? 0, 0, ',', '.') }}</div>
                </div>
                <div class="list-item revenue-item">
                    <div class="item-thumb"><i class="ti ti-calendar-stats"></i></div>
                    <div class="item-info">
                        <div class="item-sub">Bulan Ini</div>
                    </div>
                    <div class="item-value">Rp {{ number_format($revenueSummary['month'] ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection