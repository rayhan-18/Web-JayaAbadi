@extends('layouts.app')

@section('title', 'Invoice Pembayaran | Jaya Abadi')

@section('content')
<div class="ja-invoice">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,500;0,9..144,600;0,9..144,700;0,9..144,900;1,9..144,500&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        .ja-invoice {
            --ink: #2B2420;
            --paper: #F3EEE2;
            --paper-dim: #ECE5D3;
            --brass: #A9752F;
            --brass-dark: #8A5F24;
            --stamp-green: #3F5D46;
            --stamp-red: #8B3A3A;
            --line: #D9CFB8;
            --muted: #8C8171;

            background: var(--ink);
            background-image:
                radial-gradient(ellipse at 20% 10%, rgba(169,117,47,0.10), transparent 55%),
                radial-gradient(ellipse at 85% 90%, rgba(169,117,47,0.08), transparent 50%);
            padding: 4.5rem 1.25rem;
            font-family: 'Inter', sans-serif;
            color: var(--ink);
        }

        .ja-invoice .mono { font-family: 'IBM Plex Mono', monospace; }
        .ja-invoice .display { font-family: 'Fraunces', serif; }

        .ja-card {
            max-width: 780px;
            margin: 0 auto;
            background: var(--paper);
            background-image: radial-gradient(rgba(43,36,32,0.035) 1px, transparent 1px);
            background-size: 3px 3px;
            border-radius: 6px;
            position: relative;
            box-shadow: 0 30px 60px -20px rgba(0,0,0,0.55), 0 0 0 1px rgba(169,117,47,0.15);
        }

        /* torn ticket edge at very top */
        .ja-tag-strip {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 1.1rem 2rem;
            border-bottom: 1px dashed var(--line);
            position: relative;
        }
        .ja-tag-hole {
            width: 16px; height: 16px;
            border-radius: 50%;
            background: var(--ink);
            box-shadow: inset 0 0 0 2px var(--paper), 0 0 0 1px var(--line);
            flex-shrink: 0;
        }
        .ja-tag-strip span {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 0.65rem;
            letter-spacing: 0.28em;
            text-transform: uppercase;
            color: var(--muted);
        }

        .ja-body { padding: 2.75rem 2rem 3rem; }
        @media (min-width: 640px) { .ja-body { padding: 3rem 3.5rem 3.5rem; } }

        .ja-head {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1.5rem;
            margin-bottom: 2.75rem;
            position: relative;
        }
        .ja-brand-name {
            font-family: 'Fraunces', serif;
            font-weight: 700;
            font-size: 1.9rem;
            letter-spacing: -0.01em;
            color: var(--ink);
            line-height: 1;
        }
        .ja-brand-sub {
            font-size: 0.68rem;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--brass-dark);
            margin-top: 0.5rem;
            font-weight: 600;
        }
        .ja-invoice-label {
            font-family: 'Fraunces', serif;
            font-size: 2.1rem;
            font-weight: 600;
            letter-spacing: -0.02em;
            color: var(--ink);
            text-align: right;
        }
        .ja-order-no {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 0.85rem;
            color: var(--muted);
            text-align: right;
            margin-top: 0.15rem;
        }

        /* ink stamp — signature element */
        .ja-stamp {
            position: absolute;
            top: -8px;
            right: -6px;
            transform: rotate(-9deg);
            width: 112px;
            height: 112px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
            filter: url(#ja-rough);
            mix-blend-mode: multiply;
        }
        .ja-stamp::before {
            content: "";
            position: absolute;
            inset: 0;
            border: 3px solid currentColor;
            border-radius: 50%;
        }
        .ja-stamp::after {
            content: "";
            position: absolute;
            inset: 8px;
            border: 1px solid currentColor;
            border-radius: 50%;
        }
        .ja-stamp span {
            font-family: 'IBM Plex Mono', monospace;
            font-weight: 700;
            font-size: 0.78rem;
            letter-spacing: 0.08em;
            text-align: center;
            line-height: 1.25;
            color: currentColor;
        }
        .ja-stamp.is-paid  { color: var(--stamp-green); }
        .ja-stamp.is-unpaid{ color: var(--stamp-red); }

        .ja-rule { height: 1px; background: var(--line); margin-bottom: 2.5rem; }

        .ja-meta-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
            margin-bottom: 2.75rem;
        }
        @media (min-width: 640px) { .ja-meta-grid { grid-template-columns: 1fr 1fr; } }
        .ja-meta-label {
            font-size: 0.62rem;
            font-weight: 700;
            letter-spacing: 0.24em;
            text-transform: uppercase;
            color: var(--brass-dark);
            margin-bottom: 0.6rem;
        }
        .ja-meta-primary { font-size: 1.05rem; font-weight: 600; color: var(--ink); }
        .ja-meta-secondary { font-size: 0.85rem; color: var(--muted); margin-top: 0.2rem; }
        .ja-meta-row { font-size: 0.88rem; color: var(--ink); }
        .ja-meta-row + .ja-meta-row { margin-top: 0.3rem; }
        .ja-meta-row b { font-weight: 600; }

        table.ja-ledger { width: 100%; border-collapse: collapse; margin-bottom: 2.5rem; }
        table.ja-ledger thead th {
            text-align: left;
            font-size: 0.62rem;
            font-weight: 700;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: var(--brass-dark);
            padding-bottom: 0.85rem;
            border-bottom: 1.5px solid var(--ink);
        }
        table.ja-ledger thead th.num { text-align: right; }
        table.ja-ledger thead th.qty { text-align: center; }
        table.ja-ledger tbody td {
            padding: 1.1rem 0;
            border-bottom: 1px dashed var(--line);
            vertical-align: top;
        }
        table.ja-ledger tbody tr:last-child td { border-bottom: none; }
        .ja-item-name { font-size: 0.92rem; font-weight: 600; color: var(--ink); }
        .ja-qty { text-align: center; font-family: 'IBM Plex Mono', monospace; font-size: 0.85rem; color: var(--muted); }
        .ja-price { text-align: right; font-family: 'IBM Plex Mono', monospace; font-size: 0.85rem; color: var(--muted); }
        .ja-line-total { text-align: right; font-family: 'IBM Plex Mono', monospace; font-size: 0.92rem; font-weight: 600; color: var(--ink); }

        .ja-totals { display: flex; justify-content: flex-end; margin-bottom: 3rem; }
        .ja-totals-box { width: 100%; max-width: 280px; }
        .ja-subtotal-row {
            display: flex; justify-content: space-between;
            font-size: 0.85rem; color: var(--muted); margin-bottom: 0.75rem;
        }
        .ja-subtotal-row span.mono { font-family: 'IBM Plex Mono', monospace; }
        .ja-total-row {
            display: flex; justify-content: space-between; align-items: baseline;
            padding-top: 1rem; border-top: 2px solid var(--ink);
        }
        .ja-total-row .lbl {
            font-size: 0.68rem; font-weight: 700; letter-spacing: 0.2em;
            text-transform: uppercase; color: var(--ink);
        }
        .ja-total-row .val {
            font-family: 'Fraunces', serif; font-weight: 700; font-size: 1.6rem; color: var(--ink);
        }

        .ja-footer {
            text-align: center;
            border-top: 1px dashed var(--line);
            padding-top: 1.75rem;
        }
        .ja-footer p {
            font-size: 0.68rem; letter-spacing: 0.22em; text-transform: uppercase;
            color: var(--muted);
        }

        .ja-actions {
            display: flex; flex-wrap: wrap; justify-content: center; gap: 0.85rem;
            margin-top: 2.25rem; max-width: 780px; margin-left: auto; margin-right: auto;
        }
        .ja-btn {
            display: inline-flex; align-items: center; justify-content: center;
            padding: 0.95rem 2.1rem;
            border-radius: 8px;
            font-family: 'Inter', sans-serif;
            font-weight: 700;
            font-size: 0.78rem;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            text-decoration: none;
            transition: transform 0.15s ease, background 0.15s ease, border-color 0.15s ease;
        }
        .ja-btn:hover { transform: translateY(-1px); }
        .ja-btn-primary { background: var(--brass); color: #FFF8EC; }
        .ja-btn-primary:hover { background: var(--brass-dark); }
        .ja-btn-secondary {
            background: transparent; color: var(--paper);
            border: 1px solid rgba(243,238,226,0.35);
        }
        .ja-btn-secondary:hover { border-color: var(--paper); }
    </style>

    <!-- rough ink-edge filter for the stamp -->
    <svg width="0" height="0" style="position:absolute">
        <filter id="ja-rough">
            <feTurbulence type="fractalNoise" baseFrequency="0.75" numOctaves="2" result="noise" seed="4"/>
            <feDisplacementMap in="SourceGraphic" in2="noise" scale="2.6"/>
        </filter>
    </svg>

    <div class="ja-card">

        <div class="ja-tag-strip">
            <div class="ja-tag-hole"></div>
            <span>Nota Pengiriman &middot; Jaya Abadi Furnitur</span>
        </div>

        <div class="ja-body">

            <div class="ja-head">
                <div>
                    <div class="ja-brand-name">Jaya&nbsp;Abadi</div>
                    <div class="ja-brand-sub">Toko Furnitur &amp; Perabot Rumah</div>
                </div>
                <div>
                    <div class="ja-invoice-label">Invoice</div>
                    <div class="ja-order-no">{{ $order->order_number }}</div>
                </div>

                <div class="ja-stamp {{ $order->status == 'paid' ? 'is-paid' : 'is-unpaid' }}">
                    <span>{{ $order->status == 'paid' ? 'LUNAS' : 'BELUM LUNAS' }}</span>
                </div>
            </div>

            <div class="ja-rule"></div>

            <div class="ja-meta-grid">
                <div>
                    <div class="ja-meta-label">Ditagihkan Kepada</div>
                    <div class="ja-meta-primary">{{ auth()->user()->name }}</div>
                    <div class="ja-meta-secondary">{{ auth()->user()->email }}</div>
                </div>
                <div class="sm:text-right">
                    <div class="ja-meta-label">Detail Transaksi</div>
                    <div class="ja-meta-row">Tanggal &nbsp;<b>{{ $order->created_at->format('d M Y, H:i') }}</b></div>
                    <div class="ja-meta-row">Metode &nbsp;<b class="mono" style="text-transform:uppercase;">{{ $order->payment_method ?? 'Cash' }}</b></div>
                </div>
            </div>

            <table class="ja-ledger">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th class="qty">Qty</th>
                        <th class="num">Harga</th>
                        <th class="num">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td class="ja-item-name">{{ $item->product->name }}</td>
                        <td class="ja-qty">{{ $item->quantity }}</td>
                        <td class="ja-price">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="ja-line-total">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="ja-totals">
                <div class="ja-totals-box">
                    <div class="ja-subtotal-row">
                        <span>Subtotal</span>
                        <span class="mono">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="ja-total-row">
                        <span class="lbl">Total</span>
                        <span class="val">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="ja-footer">
                <p>Terima kasih atas kepercayaan Anda</p>
            </div>

        </div>
    </div>

    <div class="ja-actions">
        <a href="{{ route('orders.index') }}" class="ja-btn ja-btn-primary">Lihat Pesanan Saya</a>
        <a href="{{ route('home') }}" class="ja-btn ja-btn-secondary">Kembali ke Beranda</a>
    </div>

</div>
@endsection