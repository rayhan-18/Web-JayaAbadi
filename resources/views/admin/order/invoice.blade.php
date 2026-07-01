<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $order->order_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f8fafc;
            color: #0f172a;
            padding: 30px;
        }

        :root {
            --accent: #0f172a;
            --border: rgba(15, 23, 42, 0.1);
            --text-main: #0f172a;
            --text-sec: #475569;
            --text-muted: #94a3b8;
        }

        .invoice-wrap {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        }

        .invoice-toolbar {
            max-width: 800px;
            margin: 0 auto 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }
        .toolbar-right {
            display: flex;
            gap: 10px;
        }
        .btn-toolbar {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 10px 18px; border-radius: 10px; font-weight: 700; font-size: 13.5px;
            text-decoration: none; border: 1px solid var(--border); cursor: pointer;
            font-family: inherit;
        }
        .btn-print { background: #0f172a; color: #fff; border: none; }
        .btn-pdf   { background: #fff; color: var(--text-main); }
        .btn-back  { background: #fff; color: var(--text-sec); }

        .invoice-header {
            display: flex; justify-content: space-between; align-items: flex-start;
            border-bottom: 2px solid var(--accent); padding-bottom: 24px; margin-bottom: 24px;
        }
        .brand-name { font-size: 22px; font-weight: 800; color: var(--accent); }
        .brand-sub { font-size: 12.5px; color: var(--text-sec); margin-top: 4px; }

        .invoice-title { text-align: right; }
        .invoice-title h2 { font-size: 20px; font-weight: 800; margin-bottom: 4px; }
        .invoice-number { font-size: 13.5px; color: var(--text-sec); font-weight: 600; }

        .badge {
            display: inline-block; padding: 4px 12px; border-radius: 20px;
            font-size: 11.5px; font-weight: 700; margin-top: 8px;
        }
        .badge-paid   { background: #dcfce7; color: #166534; }
        .badge-unpaid { background: #fef3c7; color: #92400e; }
        .badge-failed { background: #fee2e2; color: #991b1b; }

        .info-grid {
            display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 28px;
        }
        .info-box .label { font-size: 11.5px; color: var(--text-muted); font-weight: 700; text-transform: uppercase; margin-bottom: 6px; }
        .info-box .value { font-size: 13.5px; color: var(--text-main); font-weight: 600; line-height: 1.5; }

        table.items { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        table.items th {
            text-align: left; font-size: 11.5px; text-transform: uppercase; color: var(--text-muted);
            border-bottom: 2px solid var(--border); padding: 10px 8px; font-weight: 700;
        }
        table.items td { padding: 12px 8px; border-bottom: 1px solid var(--border); font-size: 13.5px; color: var(--text-main); }
        table.items td.num, table.items th.num { text-align: right; }

        .summary { margin-left: auto; width: 280px; }
        .summary-row { display: flex; justify-content: space-between; padding: 8px 0; font-size: 13.5px; color: var(--text-sec); font-weight: 600; }
        .summary-row.total { border-top: 2px solid var(--accent); margin-top: 6px; padding-top: 14px; font-size: 17px; font-weight: 800; color: var(--accent); }

        .footer-note { margin-top: 40px; padding-top: 20px; border-top: 1px dashed var(--border); font-size: 12px; color: var(--text-muted); text-align: center; }

        @media print {
            .invoice-toolbar { display: none !important; }
            body { background: #fff; padding: 0; }
            .invoice-wrap { border: none; box-shadow: none; padding: 0; }
        }
    </style>
</head>
<body>

    <div class="invoice-toolbar">
        <a href="{{ url('/admin/kasir/transaksi') }}" class="btn-toolbar btn-back">
            <i class="ti ti-arrow-left"></i> Kembali ke POS
        </a>
        <div class="toolbar-right">
            <button class="btn-toolbar btn-print" onclick="window.print()">
                <i class="ti ti-printer"></i> Print
            </button>
            <a href="{{ route('admin.orders.invoice.pdf', $order->id) }}" class="btn-toolbar btn-pdf" target="_blank">
                <i class="ti ti-download"></i> Download PDF
            </a>
        </div>
    </div>

    <div class="invoice-wrap">
        <div class="invoice-header">
            <div>
                <div class="brand-name">JayaAbadi</div>
                <div class="brand-sub">Toko Furnitur & Perabot Rumah<br>Invoice resmi transaksi</div>
            </div>
            <div class="invoice-title">
                <h2>INVOICE</h2>
                <div class="invoice-number">{{ $order->order_number }}</div>
                @php
                    $badgeClass = match($order->payment_status) {
                        'paid' => 'badge-paid',
                        'failed' => 'badge-failed',
                        default => 'badge-unpaid',
                    };
                    $badgeText = match($order->payment_status) {
                        'paid' => 'LUNAS',
                        'failed' => 'GAGAL',
                        default => 'BELUM DIBAYAR',
                    };
                @endphp
                <div class="badge {{ $badgeClass }}">{{ $badgeText }}</div>
            </div>
        </div>

        <div class="info-grid">
            <div class="info-box">
                <div class="label">Kasir Yang Bertugas</div>
                <div class="value">
                    {{ $order->user->name ?? 'Guest' }}<br>
                    {{ $order->user->email ?? '-' }}<br>
                    {{ $order->phone !== '-' ? $order->phone : '' }}
                </div>
            </div>
            <div class="info-box">
                <div class="label">Detail Transaksi</div>
                <div class="value">
                    Tanggal: {{ $order->created_at->format('d M Y, H:i') }}<br>
                    Saluran: {{ $channel }}<br>
                    Metode Bayar: {{ ucfirst($order->payment_method) }}
                </div>
            </div>
        </div>

        @if($order->shipping_address && $order->shipping_address !== 'Toko Offline')
        <div class="info-box" style="margin-bottom: 24px;">
            <div class="label">Alamat / Catatan</div>
            <div class="value">{{ $order->shipping_address }}</div>
        </div>
        @endif

        <table class="items">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th class="num">Qty</th>
                    <th class="num">Harga Satuan</th>
                    <th class="num">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product->name ?? 'Produk Dihapus' }}</td>
                    <td class="num">{{ $item->quantity }}</td>
                    <td class="num">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="num">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="summary">
            <div class="summary-row">
                <span>Subtotal</span>
                <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>
            @if($extra > 0)
            <div class="summary-row">
                <span>Ongkir & Biaya Lain</span>
                <span>Rp {{ number_format($extra, 0, ',', '.') }}</span>
            </div>
            @endif
            <div class="summary-row total">
                <span>Total</span>
                <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="footer-note">
            Terima kasih telah berbelanja di JayaAbadi.<br>
            Invoice ini dihasilkan otomatis oleh sistem — {{ now()->format('d M Y H:i') }}
        </div>
    </div>

</body>
</html>