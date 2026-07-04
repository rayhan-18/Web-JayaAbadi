@extends('layouts.app')

@section('title', 'Invoice Pembayaran | Jaya Abadi')

@section('content')
<div class="ja-invoice-wrapper">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        .ja-invoice-wrapper {
            --text-main: #0F172A;      /* Dark navy/black for main text */
            --text-muted: #64748B;     /* Gray for secondary text */
            --text-label: #94A3B8;     /* Light blue-gray for uppercase labels */
            --border-light: #F1F5F9;   /* Very light gray for table rows */
            --border-dark: #0F172A;    /* Dark navy for main divider */
            --bg-page: #F8FAFC;        /* Light background for the whole page */
            --bg-card: #FFFFFF;        /* White background for the invoice card */
            --badge-bg: #DCFCE7;       /* Light green for LUNAS badge */
            --badge-text: #166534;     /* Dark green text for LUNAS badge */
            --badge-unpaid-bg: #FEE2E2;
            --badge-unpaid-text: #991B1B;

            background-color: var(--bg-page);
            padding: 4rem 1.25rem;
            font-family: 'Inter', sans-serif;
            color: var(--text-main);
        }

        .ja-card {
            max-width: 800px;
            margin: 0 auto;
            background: var(--bg-card);
            border-radius: 12px;
            padding: 3.5rem 4rem;
            box-shadow: 0 10px 40px -10px rgba(0,0,0,0.08);
            border: 1px solid #E2E8F0;
        }

        @media (max-width: 640px) {
            .ja-card { padding: 2rem 1.5rem; }
        }

        /* HEADER SECTION */
        .ja-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 1.5rem;
        }

        .ja-brand-name {
            font-weight: 800;
            font-size: 1.8rem;
            letter-spacing: -0.02em;
            color: var(--text-main);
            line-height: 1.2;
        }

        .ja-brand-sub {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-top: 0.25rem;
        }

        .ja-head-right {
            text-align: right;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .ja-invoice-title {
            font-weight: 800;
            font-size: 1.5rem;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: var(--text-main);
        }

        .ja-order-no {
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--text-muted);
            margin-top: 0.25rem;
            margin-bottom: 0.75rem;
        }

        .ja-badge {
            display: inline-block;
            padding: 0.35rem 1rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.05em;
        }

        .ja-badge.is-paid {
            background-color: var(--badge-bg);
            color: var(--badge-text);
        }

        .ja-badge.is-unpaid {
            background-color: var(--badge-unpaid-bg);
            color: var(--badge-unpaid-text);
        }

        /* DIVIDER */
        .ja-divider-thick {
            height: 2px;
            background-color: var(--border-dark);
            margin: 2.5rem 0;
        }

        /* META SECTION */
        .ja-meta-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
            margin-bottom: 3rem;
        }
        @media (min-width: 640px) {
            .ja-meta-grid { grid-template-columns: 1fr 1fr; }
        }

        .ja-meta-label {
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: var(--text-label);
            margin-bottom: 0.75rem;
        }

        .ja-meta-val {
            font-size: 0.95rem;
            color: var(--text-main);
            margin-bottom: 0.35rem;
        }
        .ja-meta-val b { font-weight: 600; }

        /* TABLE SECTION */
        table.ja-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
        }

        table.ja-table th {
            text-align: left;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: var(--text-label);
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-light);
        }

        table.ja-table th.center { text-align: center; }
        table.ja-table th.right { text-align: right; }

        table.ja-table td {
            padding: 1.25rem 0;
            font-size: 0.95rem;
            color: var(--text-main);
            border-bottom: 1px solid var(--border-light);
            vertical-align: middle;
        }

        table.ja-table td.center { text-align: center; }
        table.ja-table td.right { text-align: right; }

        /* TOTALS SECTION */
        .ja-totals-wrapper {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 4rem;
        }

        .ja-totals-box {
            width: 100%;
            max-width: 350px;
        }

        .ja-totals-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.95rem;
            color: var(--text-main);
            margin-bottom: 1rem;
        }

        .ja-totals-row-final {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1.25rem;
            margin-top: 0.5rem;
            border-top: 2px solid var(--border-dark);
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--text-main);
        }

        /* FOOTER */
        .ja-footer {
            text-align: center;
            border-top: 1px dotted #CBD5E1;
            padding-top: 2rem;
        }

        .ja-footer p {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-bottom: 0.25rem;
        }

        /* BUTTONS (Outside Card) */
        .ja-actions {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1rem;
            margin-top: 2.5rem;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .ja-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.85rem 2rem;
            border-radius: 8px;
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            font-size: 0.85rem;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .ja-btn-primary {
            background: var(--text-main);
            color: #FFFFFF;
        }
        .ja-btn-primary:hover {
            background: #000000;
            transform: translateY(-2px);
        }

        .ja-btn-secondary {
            background: transparent;
            color: var(--text-main);
            border: 1px solid #CBD5E1;
        }
        .ja-btn-secondary:hover {
            background: #F1F5F9;
        }
    </style>

    <div class="ja-card">
        
        <div class="ja-head">
            <div>
                <div class="ja-brand-name">JayaAbadi</div>
                <div class="ja-brand-sub">Toko Furnitur & Perabot Rumah<br>Invoice resmi transaksi</div>
            </div>
            
            @php
                // Logika status pembayaran berdasarkan metode pembayaran
                $isPaid = in_array(strtolower($order->payment_method), ['ewallet', 'transfer']);
            @endphp
            
            <div class="ja-head-right">
                <div class="ja-invoice-title">INVOICE</div>
                <div class="ja-order-no">{{ $order->order_number }}</div>
                
                <div class="ja-badge {{ $isPaid ? 'is-paid' : 'is-unpaid' }}">
                    {{ $isPaid ? 'LUNAS' : 'BELUM LUNAS' }}
                </div>
            </div>
        </div>

        <div class="ja-divider-thick"></div>

        <div class="ja-meta-grid">
            <div>
                <div class="ja-meta-label">PELANGGAN</div>
                <div class="ja-meta-val">{{ auth()->user()->name }}</div>
                <div class="ja-meta-val">{{ auth()->user()->email }}</div>
            </div>
            <div>
                <div class="ja-meta-label">DETAIL TRANSAKSI</div>
                <div class="ja-meta-val">Tanggal: {{ $order->created_at->format('d M Y, H:i') }}</div>
                <div class="ja-meta-val">Saluran: Website (Online)</div>
                <div class="ja-meta-val">Metode Bayar: <span style="text-transform:capitalize;">{{ $order->payment_method ?? 'Cash' }}</span></div>
            </div>
        </div>

        <table class="ja-table">
            <thead>
                <tr>
                    <th>PRODUK</th>
                    <th class="center">QTY</th>
                    <th class="right">HARGA SATUAN</th>
                    <th class="right">SUBTOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td class="center">{{ $item->quantity }}</td>
                    <td class="right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="right">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @php
            $subtotal = $order->items->sum(fn($i) => $i->price * $i->quantity);
            $shipping = 150000;
            $tax = round($subtotal * 0.11);
            $total = $subtotal + $shipping + $tax;
        @endphp

        <div class="ja-totals-wrapper">
            <div class="ja-totals-box">
                <div class="ja-totals-row">
                    <span>Subtotal Produk</span>
                    <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="ja-totals-row">
                    <span>Ongkos Kirim</span>
                    <span>Rp {{ number_format($shipping, 0, ',', '.') }}</span>
                </div>
                <div class="ja-totals-row">
                    <span>Pajak (PPN 11%)</span>
                    <span>Rp {{ number_format($tax, 0, ',', '.') }}</span>
                </div>
                
                <div class="ja-totals-row-final">
                    <span>Total</span>
                    <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="ja-footer">
            <p>Terima kasih telah berbelanja di JayaAbadi.</p>
            <p>Invoice ini dihasilkan otomatis oleh sistem &mdash; {{ now()->format('d M Y H:i') }}</p>
        </div>

    </div>

    <div class="ja-actions">
        <a href="{{ route('orders.index') }}" class="ja-btn ja-btn-primary">Lihat Pesanan Saya</a>
        <a href="{{ route('home') }}" class="ja-btn ja-btn-secondary">Kembali ke Beranda</a>
    </div>

</div>
@endsection