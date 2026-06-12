<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    body { font-family: sans-serif; font-size: 12px; color: #2d3b32; }
    h2   { font-size: 16px; margin-bottom: 4px; }
    p    { font-size: 11px; color: #7a9080; margin: 0 0 16px; }
    table { width: 100%; border-collapse: collapse; }
    th {
        background: #f5f7f4; padding: 8px 12px; text-align: left;
        font-size: 11px; color: #7a9080; border-bottom: 1px solid #e6e9e4;
        text-transform: uppercase; letter-spacing: 0.05em;
    }
    td { padding: 8px 12px; border-bottom: 1px solid #e6e9e4; }
    .total { font-weight: 700; color: #3a5c48; }
    .footer { margin-top: 24px; font-size: 11px; color: #9aada2; text-align: right; }
</style>
</head>
<body>
    <h2>Laporan Penjualan — {{ \Carbon\Carbon::parse($month . '-01')->translatedFormat('F Y') }}</h2>
    <p>Digenerate pada {{ now()->format('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Tanggal</th>
                <th>Order ID</th>
                <th>Pelanggan</th>
                <th>Qty</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $i => $order)
            <tr>
                <td style="color:#9aada2;">{{ $i + 1 }}</td>
                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                <td style="font-weight:600;">{{ $order->order_number }}</td>
                <td>{{ $order->user->name ?? 'Guest' }}</td>
                <td>{{ $order->items->sum('quantity') }}</td>
                <td class="total">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                <td>{{ ucfirst($order->status) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center; padding:20px; color:#9aada2;">
                    Tidak ada data transaksi.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Total: Rp {{ number_format($orders->sum('total_amount'), 0, ',', '.') }} 
        dari {{ $orders->count() }} transaksi
    </div>
</body>
</html>