@extends('layouts.admin')
@section('title', 'Dashboard')

@section('styles')
<style>
    /* Reset & Variables */
    :root {
        --accent: #4a7c5e;
        --border: #e5e7eb;
        --text-secondary: #6b7280;
        --text-muted: #9ca3af;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 28px;
    }
    .stat-card {
        background: #fff;
        border-radius: 16px;
        padding: 20px;
        border: 1px solid var(--border);
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        transition: all 0.2s;
    }
    .stat-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .stat-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
    }
    .stat-info {
        flex: 1;
    }
    .stat-label {
        font-size: 13px;
        font-weight: 500;
        color: var(--text-secondary);
        margin-bottom: 6px;
    }
    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
        line-height: 1.2;
    }
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
    .stat-icon.green  { background: #e8f5ee; color: #2e7d64; }
    .stat-icon.orange { background: #fef3e6; color: #e68a2e; }
    .stat-icon.teal   { background: #e0f2fe; color: #0f7b6e; }
    .stat-icon.gray   { background: #f3f4f6; color: #6b7280; }
    .stat-change {
        font-size: 12px;
        display: flex;
        align-items: center;
        gap: 4px;
        margin-top: 8px;
    }
    .stat-change.up   { color: #10b981; }
    .stat-change.down { color: #ef4444; }
    .stat-change span { color: var(--text-muted); font-weight: normal; }

    /* Two Column Layout */
    .two-col {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 24px;
    }
    .left-col, .right-col {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    /* Chart Card */
    .card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid var(--border);
        overflow: hidden;
    }
    .card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 20px;
        border-bottom: 1px solid var(--border);
        background: #fff;
    }
    .card-title {
        font-size: 16px;
        font-weight: 600;
        color: #1f2937;
    }
    .dropdown-btn {
        display: flex;
        align-items: center;
        gap: 6px;
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 5px 12px;
        font-size: 12px;
        color: var(--text-secondary);
        cursor: pointer;
        background: #fff;
    }
    .chart-legend {
        display: flex;
        gap: 20px;
        padding: 12px 20px 0;
    }
    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        color: var(--text-secondary);
    }
    .legend-line {
        width: 24px;
        height: 2px;
        border-radius: 2px;
    }
    .legend-line.solid  { background: #4a7c5e; }
    .legend-line.dashed { background: repeating-linear-gradient(90deg, #9ca3af 0 4px, transparent 4px 8px); }
    .chart-area {
        padding: 8px 20px 20px;
    }
    .chart-svg {
        width: 100%;
        height: 200px;
        overflow: visible;
    }

    /* Table */
    .overflow-x-auto {
        overflow-x: auto;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }
    th {
        text-align: left;
        padding: 12px 16px;
        background: #f9fafb;
        font-weight: 600;
        color: #4b5563;
        border-bottom: 1px solid var(--border);
    }
    td {
        padding: 12px 16px;
        border-bottom: 1px solid #f3f4f6;
        color: #374151;
    }
    .badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
    .badge-green  { background: #dcfce7; color: #15803d; }
    .badge-orange { background: #ffedd5; color: #c2410c; }
    .badge-blue   { background: #dbeafe; color: #1d4ed8; }
    .badge-gray   { background: #f3f4f6; color: #4b5563; }
    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 8px;
        background: #f3f4f6;
        text-decoration: none;
        color: #6b7280;
        transition: all 0.2s;
    }
    .action-btn:hover { background: #e5e7eb; color: #374151; }

    /* Product List */
    .product-list {
        padding: 0 16px 16px;
    }
    .product-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 0;
        border-bottom: 1px solid #f3f4f6;
    }
    .product-item:last-child { border-bottom: none; }
    .product-thumb {
        width: 44px;
        height: 44px;
        background: #f3f4f6;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }
    .product-info {
        flex: 1;
    }
    .product-name {
        font-weight: 600;
        color: #1f2937;
        font-size: 14px;
    }
    .product-sold {
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 2px;
    }
    .product-price {
        font-weight: 600;
        color: #1f2937;
        font-size: 14px;
        white-space: nowrap;
    }

    /* Revenue List */
    .revenue-list {
        padding: 0 20px 20px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .revenue-item {
        display: flex;
        align-items: center;
        gap: 14px;
        background: #f9fafb;
        border-radius: 12px;
        padding: 14px 16px;
    }
    .revenue-icon {
        width: 40px;
        height: 40px;
        background: #e8f5ee;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    .revenue-info {
        flex: 1;
    }
    .revenue-label {
        font-size: 12px;
        color: var(--text-secondary);
    }
    .revenue-value {
        font-size: 16px;
        font-weight: 700;
        color: var(--accent);
    }
    .link-btn {
        font-size: 12px;
        color: var(--accent);
        text-decoration: none;
        font-weight: 500;
    }
    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 20px;
        border-bottom: 1px solid var(--border);
    }
    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 28px;
    }
    .page-title h1 {
        font-size: 24px;
        font-weight: 700;
        color: #1f2937;
    }
    .page-title p {
        font-size: 14px;
        color: var(--text-secondary);
        margin-top: 4px;
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1>Dashboard</h1>
        <p>Selamat datang kembali, Admin!</p>
    </div>
    <div style="display:flex;align-items:center;gap:8px;border:1px solid var(--border);background:#fff;border-radius:10px;padding:7px 14px;font-size:13px;cursor:pointer;">
        📅 20 Mei – 26 Mei 2024 ▾
    </div>
</div>

{{-- 4 Stat Cards (ikon di kanan, sesuai gambar) --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-top">
            <div class="stat-info">
                <div class="stat-label">Total Produk</div>
                <div class="stat-value">120</div>
            </div>
            <div class="stat-icon green">📦</div>
        </div>
        <div class="stat-change up">↑ 12.5% <span>dari bulan lalu</span></div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <div class="stat-info">
                <div class="stat-label">Total Pesanan</div>
                <div class="stat-value">80</div>
            </div>
            <div class="stat-icon orange">🛒</div>
        </div>
        <div class="stat-change down">↓ 8.2% <span>dari bulan lalu</span></div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <div class="stat-info">
                <div class="stat-label">Total Pendapatan</div>
                <div class="stat-value" style="font-size:22px;">Rp 25.430.000</div>
            </div>
            <div class="stat-icon teal">💵</div>
        </div>
        <div class="stat-change up">↑ 15.7% <span>dari bulan lalu</span></div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <div class="stat-info">
                <div class="stat-label">User Aktif</div>
                <div class="stat-value">230</div>
            </div>
            <div class="stat-icon gray">👤</div>
        </div>
        <div class="stat-change up">↑ 9.1% <span>dari bulan lalu</span></div>
    </div>
</div>

{{-- Two Column Layout --}}
<div class="two-col">
    <div class="left-col">
        {{-- Chart Card --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">Grafik Penjualan</span>
                <button class="dropdown-btn">Tahun Ini ▾</button>
            </div>
            <div class="chart-legend">
                <div class="legend-item"><div class="legend-line solid"></div> Pendapatan (Rp)</div>
                <div class="legend-item"><div class="legend-line dashed"></div> Pesanan</div>
            </div>
            <div class="chart-area">
                <svg class="chart-svg" viewBox="0 0 860 200" preserveAspectRatio="none">
                    <text x="0" y="16"  font-size="11" fill="#9ca3af">40jt</text>
                    <text x="0" y="66"  font-size="11" fill="#9ca3af">30jt</text>
                    <text x="0" y="116" font-size="11" fill="#9ca3af">20jt</text>
                    <text x="0" y="166" font-size="11" fill="#9ca3af">10jt</text>
                    <text x="4" y="196" font-size="11" fill="#9ca3af">0</text>
                    <line x1="28" y1="10"  x2="860" y2="10"  stroke="#f0f0f0" stroke-width="1"/>
                    <line x1="28" y1="60"  x2="860" y2="60"  stroke="#f0f0f0" stroke-width="1"/>
                    <line x1="28" y1="110" x2="860" y2="110" stroke="#f0f0f0" stroke-width="1"/>
                    <line x1="28" y1="160" x2="860" y2="160" stroke="#f0f0f0" stroke-width="1"/>
                    <polyline points="50,155 120,145 190,90 260,42 330,130 400,155 470,120 540,125 610,120 680,110 750,100 820,30"
                        fill="none" stroke="#4a7c5e" stroke-width="2.5" stroke-linejoin="round" stroke-linecap="round"/>
                    @foreach([[50,155],[120,145],[190,90],[260,42],[330,130],[400,155],[470,120],[540,125],[610,120],[680,110],[750,100],[820,30]] as [$x,$y])
                    <circle cx="{{ $x }}" cy="{{ $y }}" r="4" fill="#4a7c5e"/>
                    @endforeach
                    <polyline points="50,165 120,162 190,158 260,155 330,160 400,156 470,158 540,156 610,157 680,155 750,156 820,154"
                        fill="none" stroke="#b0b0b0" stroke-width="2" stroke-dasharray="6,4" stroke-linejoin="round" stroke-linecap="round"/>
                    @foreach([[50,165],[120,162],[190,158],[260,155],[330,160],[400,156],[470,158],[540,156],[610,157],[680,155],[750,156],[820,154]] as [$x,$y])
                    <circle cx="{{ $x }}" cy="{{ $y }}" r="4" fill="#b0b0b0"/>
                    @endforeach
                    @foreach(['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'] as $i => $bln)
                    <text x="{{ 44+($i*70) }}" y="210" font-size="11" fill="#9ca3af" text-anchor="middle">{{ $bln }}</text>
                    @endforeach
                </svg>
            </div>
        </div>

        {{-- Orders Table --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">Pesanan Terbaru</span>
                <a href="#" class="link-btn">Lihat semua</a>
            </div>
            <div class="overflow-x-auto">
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th><th>Pelanggan</th><th>Tanggal</th>
                            <th>Total</th><th>Status</th><th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $pesananTerbaru = [
                                ['id'=>'ORD-00123','pelanggan'=>'Rayhan Maulana','tanggal'=>'26 Mei 2024','total'=>2750000,'status'=>'Dikirim'],
                                ['id'=>'ORD-00122','pelanggan'=>'Siti Aisyah',   'tanggal'=>'25 Mei 2024','total'=>1850000,'status'=>'Diproses'],
                                ['id'=>'ORD-00121','pelanggan'=>'Budi Santoso',  'tanggal'=>'25 Mei 2024','total'=>3200000,'status'=>'Pending'],
                                ['id'=>'ORD-00120','pelanggan'=>'Dewi Anggraini','tanggal'=>'24 Mei 2024','total'=> 950000,'status'=>'Selesai'],
                                ['id'=>'ORD-00119','pelanggan'=>'Ahmad Fauzi',   'tanggal'=>'24 Mei 2024','total'=>4500000,'status'=>'Dikirim'],
                            ];
                        @endphp
                        @foreach($pesananTerbaru as $o)
                        <tr>
                            <td>#{{ $o['id'] }}</td>
                            <td>{{ $o['pelanggan'] }}</td>
                            <td>{{ $o['tanggal'] }}</td>
                            <td>Rp {{ number_format($o['total'],0,',','.') }}</td>
                            <td>
                                @php
                                    $cls = match($o['status']) {
                                        'Dikirim' => 'badge-green',
                                        'Diproses' => 'badge-orange',
                                        'Pending' => 'badge-blue',
                                        'Selesai' => 'badge-gray',
                                        default => 'badge-gray'
                                    };
                                @endphp
                                <span class="badge {{ $cls }}">{{ $o['status'] }}</span>
                            </td>
                            <td><a href="#" class="action-btn">👁️</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="right-col">
        {{-- Best Sellers --}}
        <div class="card">
            <div class="section-header">
                <span class="card-title">Produk Terlaris</span>
                <a href="#" class="link-btn">Lihat semua</a>
            </div>
            <div class="product-list">
                @php
                    $produkTerlaris = [
                        ['icon'=>'🪑','nama'=>'Kursi Minimalis Kayu', 'terjual'=>45,'harga'=>750000],
                        ['icon'=>'🍽️','nama'=>'Meja Makan Jati',      'terjual'=>38,'harga'=>2500000],
                        ['icon'=>'🚪','nama'=>'Lemari Pakaian 3 Pintu','terjual'=>30,'harga'=>3200000],
                        ['icon'=>'🛋️','nama'=>'Sofa Minimalis Abu',   'terjual'=>28,'harga'=>4500000],
                        ['icon'=>'📚','nama'=>'Rak Buku Minimalis',   'terjual'=>25,'harga'=>850000],
                    ];
                @endphp
                @foreach($produkTerlaris as $p)
                <div class="product-item">
                    <div class="product-thumb">{{ $p['icon'] }}</div>
                    <div class="product-info">
                        <div class="product-name">{{ $p['nama'] }}</div>
                        <div class="product-sold">Terjual {{ $p['terjual'] }}</div>
                    </div>
                    <div class="product-price">Rp {{ number_format($p['harga'],0,',','.') }}</div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Revenue Summary --}}
        <div class="card">
            <div class="section-header">
                <span class="card-title">Ringkasan Pendapatan</span>
                <a href="#" class="link-btn">Lihat laporan</a>
            </div>
            <div class="revenue-list">
                <div class="revenue-item">
                    <div class="revenue-icon">💰</div>
                    <div class="revenue-info">
                        <div class="revenue-label">Pendapatan Hari Ini</div>
                        <div class="revenue-value">Rp 2.450.000</div>
                    </div>
                </div>
                <div class="revenue-item">
                    <div class="revenue-icon">📅</div>
                    <div class="revenue-info">
                        <div class="revenue-label">Pendapatan Minggu Ini</div>
                        <div class="revenue-value">Rp 12.750.000</div>
                    </div>
                </div>
                <div class="revenue-item">
                    <div class="revenue-icon">🗓️</div>
                    <div class="revenue-info">
                        <div class="revenue-label">Pendapatan Bulan Ini</div>
                        <div class="revenue-value">Rp 25.430.000</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection