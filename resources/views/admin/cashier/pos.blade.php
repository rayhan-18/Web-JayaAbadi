@extends('layouts.admin')

@section('title', 'Transaksi POS Kasir')

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

    body { color: var(--text-main); background: #fcfdfc; }

    /* POS Three-Column Layout */
    .pos-layout {
        display: grid;
        grid-template-columns: 240px 1fr 380px;
        gap: 16px;
        height: calc(100vh - 100px);
        align-items: start;
    }

    @media (max-width: 1200px) {
        .pos-layout { grid-template-columns: 1fr 380px; }
        .pos-categories { display: none; }
    }

    .pos-card {
        background: var(--bg-surface);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border);
        display: flex;
        flex-direction: column;
        height: 100%;
        overflow: hidden;
    }

    /* COLUMN 1: Kategori Produk */
    .category-list { padding: 12px; display: flex; flex-direction: column; gap: 8px; overflow-y: auto; }
    .cat-item {
        display: flex; align-items: center; gap: 10px; padding: 12px;
        border-radius: var(--radius-md); border: 1px solid var(--border);
        cursor: pointer; font-weight: 600; font-size: 13px; transition: 0.15s;
        color: var(--text-main); background: var(--bg-surface);
    }
    .cat-item:hover { background: var(--bg-hover); border-color: #d1d6cf; }
    .cat-item.active { background: var(--accent-light); color: var(--accent-dark); border-color: var(--accent); }

    /* COLUMN 2: Grid Produk */
    .pos-main-header { padding: 12px 16px; border-bottom: 1px solid var(--border); display: flex; gap: 12px; }
    .search-box {
        display: flex; align-items: center; background: var(--bg-surface);
        border: 1px solid var(--border); border-radius: var(--radius-md);
        padding: 0 14px; gap: 10px; flex: 1; height: 40px;
    }
    .search-box input { border: none; outline: none; font-size: 13px; width: 100%; color: var(--text-main); background: transparent; }
    
    .product-grid {
        display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 14px; padding: 16px; overflow-y: auto; flex: 1;
    }
    .prod-card {
        background: var(--bg-surface); border: 1px solid var(--border);
        border-radius: var(--radius-md); padding: 10px; cursor: pointer;
        transition: all 0.2s; display: flex; flex-direction: column; position: relative;
    }
    .prod-card:hover { transform: translateY(-2px); border-color: var(--accent); box-shadow: 0 4px 12px rgba(92,158,116,0.06); }
    
    .prod-img-wrapper {
        width: 100%; height: 110px; border-radius: 6px; overflow: hidden;
        background: var(--bg-hover); margin-bottom: 10px; border: 1px solid #f0f2ef;
    }
    .prod-img-wrapper img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s; }
    .prod-card:hover .prod-img-wrapper img { transform: scale(1.05); }
    .prod-name { font-size: 12.5px; font-weight: 700; color: var(--text-main); line-height: 1.3; margin-bottom: 4px; }
    .prod-price { font-size: 12px; font-weight: 600; color: var(--accent-dark); }
    
    .stock-badge {
        position: absolute; top: 16px; right: 16px; padding: 2px 6px; z-index: 2;
        border-radius: 4px; font-size: 9px; font-weight: 700; background: rgba(255,255,255,0.9); color: var(--text-main);
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .stock-badge.low { background: #fdf5f5; color: #c47a7a; }

    .no-product-msg { grid-column: 1 / -1; text-align: center; padding: 40px 20px; color: var(--text-sec); }
    .no-product-msg i { font-size: 32px; color: var(--text-muted); display: block; margin-bottom: 8px; }

    /* COLUMN 3: Keranjang & Billing */
    .cart-header { padding: 16px; border-bottom: 1px solid var(--border); }
    .channel-toggle {
        display: grid; grid-template-columns: 1fr 1fr; background: var(--bg-hover);
        padding: 4px; border-radius: 8px; margin-bottom: 12px;
    }
    .btn-toggle {
        border: none; padding: 8px; font-size: 12px; font-weight: 700; border-radius: 6px;
        cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px; background: transparent; color: var(--text-sec);
    }
    .btn-toggle.active { background: #ffffff; color: var(--text-main); box-shadow: 0 2px 4px rgba(0,0,0,0.04); }

    .cart-items { flex: 1; overflow-y: auto; padding: 12px 16px; display: flex; flex-direction: column; gap: 10px; }
    .cart-item { display: flex; align-items: center; gap: 12px; padding-bottom: 10px; border-bottom: 1px solid #f0f2ef; }
    .cart-item-details { flex: 1; }
    .cart-item-name { font-size: 13px; font-weight: 600; color: var(--text-main); }
    .cart-item-price { font-size: 12px; color: var(--text-sec); margin-top: 2px; }
    
    .qty-ctrl { display: flex; align-items: center; gap: 8px; border: 1px solid var(--border); border-radius: 6px; padding: 2px; }
    .qty-btn { width: 24px; height: 24px; border: none; background: transparent; cursor: pointer; font-weight: 700; font-size: 14px; color: var(--text-sec); }
    .qty-btn:hover { color: var(--accent); }
    .qty-num { font-size: 12.5px; font-weight: 700; width: 16px; text-align: center; }
    
    .btn-remove-item { background: transparent; border: none; color: #c47a7a; cursor: pointer; font-size: 16px; margin-left: 4px; }
    .btn-remove-item:hover { color: #a15555; }
    .empty-cart-msg { text-align: center; padding: 40px 0; color: var(--text-muted); font-size: 13px; }

    .cart-summary { padding: 16px; border-top: 1px solid var(--border); background: var(--bg-hover); }
    .summary-row { display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 8px; color: var(--text-sec); }
    .summary-row.total { border-top: 1px solid var(--border); margin-top: 10px; padding-top: 10px; font-weight: 700; color: var(--text-main); font-size: 16px; }
    .summary-row.total .val { color: var(--accent-dark); }
    
    .btn-pay {
        width: 100%; padding: 14px; background-color: #5c9e74 !important; color: #ffffff !important;
        border: none; border-radius: 10px; font-size: 14px; font-weight: 700; cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: 8px; margin-top: 12px;
        box-shadow: 0 4px 12px rgba(92, 158, 116, 0.2); transition: all 0.2s;
    }
    .btn-pay:hover { background-color: #3a5c48 !important; transform: translateY(-1px); }
    .btn-pay:active { transform: scale(0.98); }
</style>
@endsection

@section('content')
<div class="pos-layout">

    {{-- KOLOM 1: Kategori Filter Cepat --}}
    <div class="pos-card pos-categories">
        <div style="padding: 14px 16px; font-weight: 700; font-size: 14px; border-bottom: 1px solid var(--border);"><i class="ti ti-layout-grid"></i> Kategori</div>
        <div class="category-list" id="categoryList">
            <div class="cat-item active" id="cat-all" onclick="filterCategory('all')"><i class="ti ti-box"></i> Semua Barang</div>
            <div class="cat-item" id="cat-sofa" onclick="filterCategory('sofa')"><i class="ti ti-sofa"></i> Sofa & Lounge</div>
            <div class="cat-item" id="cat-kursi" onclick="onclick=filterCategory('kursi')"><i class="ti ti-armchair"></i> Kursi</div>
            <div class="cat-item" id="cat-meja" onclick="filterCategory('meja')"><i class="ti ti-table"></i> Meja Makan</div>
            <div class="cat-item" id="cat-lemari" onclick="filterCategory('lemari')"><i class="ti ti-door"></i> Lemari Baju</div>
        </div>
    </div>

    {{-- KOLOM 2: Katalog Grid Produk --}}
    <div class="pos-card">
        <div class="pos-main-header">
            <div class="search-box">
                <i class="ti ti-search" style="color: var(--text-sec);"></i>
                <input type="text" id="searchInput" oninput="searchProduct()" placeholder="Ketik nama produk atau scan barcode barang...">
            </div>
        </div>
        <div class="product-grid" id="productGrid"></div>
    </div>

    {{-- KOLOM 3: Keranjang & Billing --}}
    <div class="pos-card">
        <div class="cart-header">
            <div class="channel-toggle">
                <button class="btn-toggle active" id="chan-offline" onclick="setChannel('offline')">
                    <i class="ti ti-store"></i> Toko Offline
                </button>
                <button class="btn-toggle" id="chan-online" onclick="setChannel('online')">
                    <i class="ti ti-world"></i> Order Online
                </button>
            </div>
            
            <div class="search-box" style="height: 36px; border-color: #d1d6cf;">
                <i class="ti ti-user" style="color: var(--text-sec); font-size: 14px;"></i>
                <input type="text" id="customerNotes" placeholder="Nama Pelanggan / Catatan Meja (Opsional)" style="font-size: 12px;">
            </div>
        </div>

        {{-- AKTIF: List keranjang belanjaan dinamis menggunakan JavaScript --}}
        <div class="cart-items" id="cartItemsContainer"></div>

        <div class="cart-summary">
            <div class="summary-row">
                <span class="lbl">Subtotal Items</span>
                <span class="val" id="summarySubtotal">Rp 0</span>
            </div>
            <div class="summary-row" id="row-ongkir" style="display: none;">
                <span class="lbl">Ongkos Kirim Paket</span>
                <span class="val" style="color: var(--accent-dark); font-weight: 600;">Rp 50.000</span>
            </div>
            <div class="summary-row">
                <span class="lbl">Diskon / Voucher</span>
                <span class="val" style="color: #c47a7a;">- Rp 0</span>
            </div>
            <div class="summary-row total">
                <span class="lbl">Grand Total</span>
                <span class="val" id="grand-total">Rp 0</span>
            </div>
            <button class="btn-pay" onclick="checkoutProcess()">
                <i class="ti ti-device-floppy"></i> Proses Pembayaran [F8]
            </button>
        </div>
    </div>
</div>

<script>
    let posProducts = [];
    let cart = [];
    let currentSelectedCategory = 'all';
    let currentChannel = 'offline';
    const ongkirValue = 50000;

    async function loadProducts() {
        try {
            const res = await fetch('/admin/kasir/produk');
            posProducts = await res.json();
            buildCategoryList();
            renderProducts(posProducts);
            renderCart();
        } catch (err) {
            console.error('Gagal load produk:', err);
        }
    }

    function buildCategoryList() {
        const categories = [...new Set(posProducts.map(p => p.kategori))];
        const list = document.getElementById('categoryList');
        if (!list) return;
        let html = `<div class="cat-item active" id="cat-all" onclick="filterCategory('all')"><i class="ti ti-box"></i> Semua Barang</div>`;
        categories.forEach(cat => {
            const label = cat.charAt(0).toUpperCase() + cat.slice(1).replace(/-/g, ' ');
            html += `<div class="cat-item" id="cat-${cat}" onclick="filterCategory('${cat}')"><i class="ti ti-tag"></i> ${label}</div>`;
        });
        list.innerHTML = html;
    }

    function renderProducts(products) {
        const grid = document.getElementById('productGrid');
        grid.innerHTML = '';
        if (products.length === 0) {
            grid.innerHTML = `<div class="no-product-msg"><i class="ti ti-package-off"></i>Produk tidak ditemukan</div>`;
            return;
        }
        products.forEach(p => {
            const formattedPrice = 'Rp ' + p.harga.toLocaleString('id-ID');
            const badgeLowClass = p.tag === 'Menipis' ? 'low' : '';
            grid.innerHTML += `
                <div class="prod-card" onclick="addToCart(${p.id})">
                    <span class="stock-badge ${badgeLowClass}">${p.stok} Unit</span>
                    <div class="prod-img-wrapper">
                        <img src="${p.img}" alt="${p.nama}">
                    </div>
                    <div class="prod-name">${p.nama}</div>
                    <div class="prod-price">${formattedPrice}</div>
                </div>`;
        });
    }

    function addToCart(productId) {
        const product = posProducts.find(p => p.id === productId);
        if (!product) return;
        const targetCartItem = cart.find(item => item.id === productId);
        if (targetCartItem) {
            if (targetCartItem.qty >= product.stok) {
                Swal.fire('Stok Habis', 'Batas sisa stok gudang terpenuhi!', 'warning');
                return;
            }
            targetCartItem.qty++;
        } else {
            cart.push({ id: product.id, nama: product.nama, harga: product.harga, qty: 1 });
        }
        renderCart();
    }

    function updateQty(productId, amount) {
        const product = posProducts.find(p => p.id === productId);
        const targetCartItem = cart.find(item => item.id === productId);
        if (targetCartItem) {
            targetCartItem.qty += amount;
            if (targetCartItem.qty > product.stok) {
                Swal.fire('Stok Tidak Cukup', 'Stok tidak mencukupi!', 'warning');
                targetCartItem.qty = product.stok;
            }
            if (targetCartItem.qty <= 0) { removeFromCart(productId); return; }
        }
        renderCart();
    }

    function removeFromCart(productId) {
        cart = cart.filter(item => item.id !== productId);
        renderCart();
    }

    function renderCart() {
        const container = document.getElementById('cartItemsContainer');
        container.innerHTML = '';
        if (cart.length === 0) {
            container.innerHTML = `<div class="empty-cart-msg"><i class="ti ti-shopping-cart-x" style="font-size:24px; display:block; margin-bottom:4px; color:var(--text-muted)"></i>Keranjang masih kosong</div>`;
            document.getElementById('summarySubtotal').innerText = 'Rp 0';
            document.getElementById('grand-total').innerText = currentChannel === 'online' ? 'Rp ' + ongkirValue.toLocaleString('id-ID') : 'Rp 0';
            return;
        }
        let subtotal = 0;
        cart.forEach(item => {
            subtotal += item.harga * item.qty;
            container.innerHTML += `
                <div class="cart-item">
                    <div class="cart-item-details">
                        <div class="cart-item-name">${item.nama}</div>
                        <div class="cart-item-price">Rp ${item.harga.toLocaleString('id-ID')}</div>
                    </div>
                    <div class="qty-ctrl">
                        <button class="qty-btn" onclick="updateQty(${item.id}, -1)">-</button>
                        <span class="qty-num">${item.qty}</span>
                        <button class="qty-btn" onclick="updateQty(${item.id}, 1)">+</button>
                    </div>
                    <button class="btn-remove-item" onclick="removeFromCart(${item.id})" title="Hapus"><i class="ti ti-trash"></i></button>
                </div>`;
        });
        let grandTotal = subtotal + (currentChannel === 'online' ? ongkirValue : 0);
        document.getElementById('summarySubtotal').innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
        document.getElementById('grand-total').innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
    }

    function filterCategory(category) {
        currentSelectedCategory = category;
        document.getElementById('searchInput').value = '';
        document.querySelectorAll('.cat-item').forEach(item => item.classList.remove('active'));
        const target = document.getElementById('cat-' + category);
        if (target) target.classList.add('active');
        renderProducts(category === 'all' ? posProducts : posProducts.filter(p => p.kategori === category));
    }

    function searchProduct() {
        const keyword = document.getElementById('searchInput').value.toLowerCase();
        let base = currentSelectedCategory === 'all' ? posProducts : posProducts.filter(p => p.kategori === currentSelectedCategory);
        renderProducts(base.filter(p => p.nama.toLowerCase().includes(keyword)));
    }

    function setChannel(type) {
        currentChannel = type;
        document.getElementById('chan-offline').classList.toggle('active', type === 'offline');
        document.getElementById('chan-online').classList.toggle('active', type === 'online');
        document.getElementById('row-ongkir').style.display = type === 'online' ? 'flex' : 'none';
        renderCart();
    }

    async function checkoutProcess() {
        if (cart.length === 0) {
            Swal.fire('Gagal', 'Masukkan minimal 1 barang ke keranjang!', 'warning');
            return;
        }
        const notes = document.getElementById('customerNotes').value;
        const { isConfirmed } = await Swal.fire({
            title: 'Proses Pembayaran?',
            text: `Total: ${document.getElementById('grand-total').innerText}`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Proses',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#5c9e74',
        });
        if (!isConfirmed) return;
        try {
            const res = await fetch('/admin/kasir/checkout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    items:   cart.map(i => ({ id: i.id, qty: i.qty, harga: i.harga })),
                    channel: currentChannel,
                    notes:   notes,
                })
            });
            const data = await res.json();
            if (data.success) {
                await Swal.fire('Berhasil!', `Transaksi ${data.order_number} berhasil!\nTotal: Rp ${Number(data.total).toLocaleString('id-ID')}`, 'success');
                cart = [];
                document.getElementById('customerNotes').value = '';
                renderCart();
                loadProducts();
            } else {
                Swal.fire('Error', 'Transaksi gagal.', 'error');
            }
        } catch (err) {
            Swal.fire('Error', 'Terjadi kesalahan saat proses transaksi.', 'error');
        }
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'F8') { e.preventDefault(); checkoutProcess(); }
    });

    document.addEventListener('DOMContentLoaded', loadProducts);
</script>
@endsection