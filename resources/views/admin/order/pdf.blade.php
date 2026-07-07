<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        font-family: 'DejaVu Sans', sans-serif;
        font-size: 11px;
        color: #2d3b32;
        padding: 32px;
        background: #fff;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 2px solid #5c9e74;
    }
    .header-left h1 { font-size: 18px; font-weight: 700; color: #2d3b32; letter-spacing: -0.02em; }
    .header-left p  { font-size: 11px; color: #7a9080; margin-top: 4px; }
    .header-right   { text-align: right; font-size: 10px; color: #9aada2; }
    .header-right strong { display: block; font-size: 13px; color: #5c9e74; font-weight: 700; margin-bottom: 2px; }

    .filter-info {
        background: #f5f7f4; border: 1px solid #e6e9e4; border-radius: 6px;
        padding: 8px 14px; margin-bottom: 20px; font-size: 11px; color: #7a9080;
        display: flex; gap: 20px; flex-wrap: wrap;
    }
    .filter-info span strong { color: #2d3b32; }

    table { width: 100%; border-collapse: collapse; font-size: 11px; }
    thead tr { background: #3a5c48; color: #ffffff; }
    th {
        padding: 10px 12px; text-align: left; font-weight: 600;
        font-size: 10px; text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap;
    }
    td { padding: 9px 12px; border-bottom: 1px solid #e6e9e4; vertical-align: middle; }
    tbody tr:nth-child(even) { background: #f9fbf9; }
    tbody tr:last-child td { border-bottom: none; }

    .badge {
        display: inline-block; padding: 2px 8px; border-radius: 4px;
        font-size: 10px; font-weight: 700;
    }
    .badge-pending   { background: #fdf5e6; color: #8a5a2e; }
    .badge-diproses  { background: #f0f4f8; color: #4a6b8c; }
    .badge-dikirim   { background: #f3f0f8; color: #6b4a8c; }
    .badge-selesai   { background: #e8f0eb; color: #3a5c48; }
    .badge-batal     { background: #fdf5f5; color: #c47a7a; }

    .badge-online    { background: #eef7f2; color: #3b7a54; }
    .badge-offline   { background: #f1f3f5; color: #495057; }

    .footer {
        margin-top: 20px; padding-top: 12px; border-top: 1px solid #e6e9e4;
        display: flex; justify-content: space-between; font-size: 10px; color: #9aada2;
    }
    .footer strong { color: #5c9e74; }
</style>
</head>
<body>

<div class="header">
    <div class="header-left">
        <h1>Laporan Data Pesanan</h1>
        <p>JayaAbadi — Sistem Manajemen Toko</p>
    </div>
    <div class="header-right">
        <strong>JayaAbadi</strong>
        Digenerate: {{ now()->format('d/m/Y H:i') }}
    </div>
</div>

<div class="filter-info">
    <span>Saluran: <strong>{{ $filterChannel }}</strong></span>
    <span>Status: <strong>{{ $filterStatus }}</strong></span>
    @if($filterSearch)
        <span>Pencarian: <strong>"{{ $filterSearch }}"</strong></span>
    @endif
    <span>Total: <strong>{{ $orders->count() }} pesanan</strong></span>
</div>

<table>
    <thead>
        <tr>
            <th style="width: 25px;">#</th>
            <th>Tanggal</th>
            <th>Order ID</th>
            <th>Sumber</th>
            <th>Pelanggan</th>
            <th>Metode Bayar</th>
            <th>Total</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($orders as $i => $order)
        @php
            $statusClass = match($order->status) {
                'pending'   => 'badge-pending',
                'paid'      => 'badge-diproses',
                'shipping'  => 'badge-dikirim',
                'delivered' => 'badge-selesai',
                'cancelled' => 'badge-batal',
                default     => ''
            };
            $statusLabel = match($order->status) {
                'pending'   => 'Pending',
                'paid'      => 'Diproses',
                'shipping'  => 'Dikirim',
                'delivered' => 'Selesai',
                'cancelled' => 'Dibatalkan',
                default     => ucfirst($order->status)
            };
            $isPos = $order->payment_method === 'cash';
        @endphp
        <tr>
            <td style="color: #9aada2;">{{ $i + 1 }}</td>
            <td style="color: #7a9080;">{{ $order->created_at->format('d/m/Y H:i') }}</td>
            <td><strong>{{ $order->order_number }}</strong></td>
            <td>
                <span class="badge {{ $isPos ? 'badge-offline' : 'badge-online' }}">
                    {{ $isPos ? 'Kasir POS' : 'Website' }}
                </span>
            </td>
            <td>
                <strong>{{ $order->user->name ?? 'Guest' }}</strong><br>
                <span style="font-size:9px; color:#9aada2;">{{ $order->user->email ?? '-' }}</span>
            </td>
            <td>{{ ucfirst($order->payment_method) }}</td>
            <td><strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></td>
            <td><span class="badge {{ $statusClass }}">{{ $statusLabel }}</span></td>
        </tr>
        @empty
        <tr>
            <td colspan="8" style="text-align:center; padding:20px; color:#9aada2;">
                Tidak ada pesanan ditemukan.
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="footer">
    <span>
        Total Pesanan: <strong>{{ $orders->count() }}</strong> &nbsp;|&nbsp;
        Total Nilai: <strong>Rp {{ number_format($orders->sum('total_amount'), 0, ',', '.') }}</strong>
    </span>
    <span>JayaAbadi © {{ now()->year }}</span>
</div>

</body>
</html>