@extends('layouts.admin')

@section('title', 'Transaksi POS Kasir')

@section('styles')
<style>
    /* ── Premium Variables (Tema: Monochrome Slate dengan Blue CTA) ── */
    :root {
        --accent: #0f172a;        /* Diubah jadi Hitam Pekat */
        --accent-dark: #000000;   /* Hitam absolut */
        --accent-light: #f1f5f9;  /* Abu-abu super terang untuk background */
        --border: rgba(15, 23, 42, 0.08);
        --bg-body: #f8fafc;
        --bg-surface: #ffffff;
        --bg-hover: #f1f5f9;
        --text-main: #0f172a;
        --text-sec: #475569;
        --text-muted: #94a3b8;
        --radius-md: 14px;
        --radius-lg: 20px;
        --shadow-card: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        --shadow-hover: 0 10px 20px -5px rgba(15, 23, 42, 0.1);
    }

    body { color: var(--text-main); background: var(--bg-body); }

    /* ==========================================================================
       RESPONSIVE GRID SYSTEM (ANTI PECAH)
       ========================================================================== */
    .pos-layout {
        display: grid;
        grid-template-columns: 240px 1fr 380px;
        gap: 24px;
        min-height: calc(100vh - 100px);
        align-items: start;
    }

    /* ── KIRI: Kategori Sidebar ── */
    .pos-categories {
        background: var(--bg-surface); border: 1px solid var(--border);
        border-radius: var(--radius-lg); padding: 20px; box-shadow: var(--shadow-card);
        display: flex; flex-direction: column; gap: 8px;
        position: sticky; top: 20px;
    }
    .cat-title {
        font-weight: 700; font-size: 14px; padding-bottom: 12px; margin-bottom: 8px;
        border-bottom: 1px solid var(--border); color: var(--text-main); display: flex; align-items: center; gap: 8px;
    }
    .cat-title i { color: var(--text-main); font-size: 18px; opacity: 0.8; }
    
    #categoryList { display: flex; flex-direction: column; gap: 6px; }
    
    .category-item {
        display: flex; align-items: center; gap: 12px; padding: 12px 14px;
        border-radius: 10px; cursor: pointer; font-size: 13.5px; font-weight: 600;
        color: var(--text-sec); transition: all 0.2s ease; border: 1px solid transparent;
    }
    .category-item i { font-size: 18px; opacity: 0.7; }
    .category-item:hover { background: var(--bg-hover); color: var(--text-main); }
    
    /* Active Category: Monochrome */
    .category-item.active { background: var(--bg-hover); color: var(--text-main); border-color: #cbd5e1; }
    .category-item.active i { opacity: 1; color: var(--text-main); }

    /* ── TENGAH: Manajemen Produk ── */
    .pos-products { display: flex; flex-direction: column; gap: 20px; min-width: 0; }
    
    .products-header {
        background: var(--bg-surface); border: 1px solid var(--border); border-radius: var(--radius-lg);
        padding: 16px 20px; display: flex; justify-content: space-between; align-items: center; gap: 16px;
        box-shadow: var(--shadow-card);
    }
    .search-box {
        display: flex; align-items: center; background: var(--bg-hover); border: 1px solid transparent;
        border-radius: var(--radius-md); padding: 0 16px; gap: 10px; height: 44px; flex: 1; transition: all 0.2s;
    }
    .search-box:focus-within { border-color: var(--text-main); background: var(--bg-surface); box-shadow: 0 0 0 3px rgba(15, 23, 42, 0.1); }
    .search-box i { color: var(--text-sec); font-size: 18px; }
    .search-box input { border: none; outline: none; background: transparent; font-size: 13.5px; width: 100%; color: var(--text-main); font-weight: 500; }
    .search-box input::placeholder { color: var(--text-muted); font-weight: 400; }
    
    .product-count { font-size: 13px; font-weight: 700; color: var(--text-main); background: var(--bg-hover); padding: 8px 14px; border-radius: 10px; white-space: nowrap; border: 1px solid var(--border); }

    /* Grid Produk Adaptif */
    .product-grid {
        display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 16px;
    }
    .product-card {
        background: var(--bg-surface); border: 1px solid var(--border); border-radius: var(--radius-md);
        padding: 12px; cursor: pointer; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex; flex-direction: column; box-shadow: var(--shadow-card); position: relative;
    }
    .product-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-hover); border-color: var(--text-main); }
    .product-card img {
        width: 100%; height: 130px; object-fit: cover; border-radius: 10px; margin-bottom: 12px;
        background: var(--bg-hover); border: 1px solid rgba(0,0,0,0.03);
    }
    .product-card .title { font-size: 13.5px; font-weight: 700; color: var(--text-main); margin-bottom: 4px; line-height: 1.3; }
    .product-card .stock { font-size: 11.5px; color: var(--text-sec); margin-bottom: 10px; font-weight: 500; }
    .product-card .price { font-size: 14.5px; font-weight: 800; color: var(--text-main); margin-top: auto; }

    /* ── KANAN: Keranjang Belanja ── */
    .pos-cart {
        background: var(--bg-surface); border: 1px solid var(--border); border-radius: var(--radius-lg);
        display: flex; flex-direction: column; height: calc(100vh - 40px); position: sticky; top: 20px;
        box-shadow: var(--shadow-card); overflow: hidden;
    }
    
    .cart-header { padding: 16px; border-bottom: 1px solid var(--border); background: var(--bg-surface); z-index: 10; }
    .channel-toggle {
        display: grid; grid-template-columns: 1fr 1fr; background: var(--bg-hover);
        padding: 4px; border-radius: 10px; border: 1px solid var(--border);
    }
    .btn-channel {
        border: none; background: transparent; padding: 10px; font-size: 12.5px; font-weight: 700;
        color: var(--text-sec); cursor: pointer; border-radius: 8px; transition: 0.2s;
        display: flex; align-items: center; justify-content: center; gap: 6px;
    }
    /* Active Channel: Monochrome */
    .btn-channel.active { background: var(--bg-surface); color: var(--text-main); box-shadow: 0 2px 8px rgba(0,0,0,0.08); border: 1px solid rgba(0,0,0,0.05); }

    .customer-box { padding: 12px 16px; border-bottom: 1px solid var(--border); background: #f8fafc; }
    .input-icon-wrapper { position: relative; display: flex; align-items: center; }
    .input-icon-wrapper i { position: absolute; left: 14px; color: var(--text-sec); font-size: 16px; }
    .input-notes {
        width: 100%; height: 40px; padding: 0 14px 0 38px; border: 1px solid #cbd5e1;
        border-radius: 10px; font-size: 13px; color: var(--text-main); font-weight: 500; transition: 0.2s;
    }
    .input-notes:focus { border-color: var(--text-main); background: white; box-shadow: 0 0 0 3px rgba(15, 23, 42, 0.1); outline: none; }

    /* Cart Items list */
    .cart-items { flex: 1; overflow-y: auto; padding: 16px; display: flex; flex-direction: column; gap: 14px; }
    .cart-items::-webkit-scrollbar { width: 6px; }
    .cart-items::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    
    .cart-item { display: flex; gap: 12px; align-items: center; padding-bottom: 14px; border-bottom: 1px dashed #cbd5e1; }
    .cart-item:last-child { border-bottom: none; padding-bottom: 0; }
    .cart-item img { width: 50px; height: 50px; object-fit: cover; border-radius: 10px; border: 1px solid var(--border); }
    .item-info { flex: 1; }
    .item-name { font-size: 13.5px; font-weight: 700; color: var(--text-main); margin-bottom: 2px; line-height: 1.2; }
    .item-price { font-size: 12.5px; font-weight: 600; color: var(--text-sec); }
    
    .qty-control { display: flex; align-items: center; border: 1px solid #cbd5e1; border-radius: 8px; overflow: hidden; background: var(--bg-surface); }
    .btn-qty { border: none; background: transparent; width: 28px; height: 28px; font-size: 14px; font-weight: 600; cursor: pointer; color: var(--text-main); transition: 0.2s; }
    .btn-qty:hover { background: var(--bg-hover); color: var(--text-main); }
    .qty-val { width: 32px; text-align: center; font-size: 13px; font-weight: 700; border-left: 1px solid #cbd5e1; border-right: 1px solid #cbd5e1; display: flex; align-items: center; justify-content: center; background: #f8fafc; }

    /* Summary & Buttons */
    .cart-summary { padding: 20px 16px; background: var(--bg-hover); border-top: 1px solid var(--border); z-index: 10; }
    .summary-row { display: flex; justify-content: space-between; font-size: 13.5px; color: var(--text-sec); margin-bottom: 10px; font-weight: 600; }
    .summary-row.total { font-size: 18px; font-weight: 800; color: var(--text-main); border-top: 2px dashed #cbd5e1; padding-top: 14px; margin-top: 6px; margin-bottom: 16px; }
    .summary-row.total span:last-child { color: var(--text-main); }
    
    .action-row { display: flex; gap: 12px; }
    .btn-action {
        flex: 1; height: 48px; border: none; border-radius: 12px; font-size: 14px; font-weight: 700;
        cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    /* Tombol Hold: Monochrome */
    .btn-action.hold { background: var(--bg-surface); border: 1px solid #cbd5e1; color: var(--text-sec); }
    .btn-action.hold:hover { background: #e2e8f0; color: var(--text-main); border-color: #94a3b8; }
    
    /* 🔵 TOMBOL BAYAR: ROYAL BLUE (SATU-SATUNYA YANG BIRU) 🔵 */
    .btn-action.pay { background: #2563eb; color: white; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2); }
    .btn-action.pay:hover { background: #1e40af; transform: translateY(-2px); box-shadow: 0 6px 15px rgba(30, 64, 175, 0.3); }
    .btn-action.pay:active { transform: scale(0.97); box-shadow: 0 2px 8px rgba(37, 99, 235, 0.15); }

    /* ==========================================================================
       MEDIA QUERIES (RESPONSIVE TABLET & MOBILE)
       ========================================================================== */
    @media (max-width: 1200px) {
        .pos-layout { grid-template-columns: 200px 1fr 340px; }
    }

    @media (max-width: 1024px) {
        .pos-layout { grid-template-columns: 1fr 350px; }
        
        .pos-categories {
            grid-column: 1 / -1; flex-direction: row; padding: 14px;
            overflow-x: auto; -webkit-overflow-scrolling: touch;
            position: relative; top: 0; z-index: 5; margin-bottom: -4px;
        }
        .cat-title { border: none; padding: 0; margin: 0; padding-right: 16px; border-right: 2px solid var(--border); }
        #categoryList { flex-direction: row; gap: 10px; padding-left: 12px; }
        .category-item { flex: 0 0 auto; white-space: nowrap; padding: 10px 16px; }
    }

    @media (max-width: 768px) {
        .pos-layout { grid-template-columns: 1fr; gap: 16px; }
        .pos-categories { padding: 12px; border-radius: 16px; }
        
        .products-header { flex-direction: column; align-items: stretch; border-radius: 16px; }
        .search-box { width: 100%; max-width: 100%; }
        .product-count { text-align: center; }
        
        .product-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
        .product-card { border-radius: 14px; padding: 10px; }
        .product-card img { height: 110px; border-radius: 8px; margin-bottom: 10px; }
        .product-card .title { font-size: 12.5px; }
        .product-card .price { font-size: 13.5px; }

        .pos-cart { height: auto; min-height: 400px; max-height: none; position: relative; top: 0; margin-top: 10px; border-radius: 16px; }
        .cart-items { max-height: 400px; } 
    }
</style>
@endsection

@section('content')
<div class="pos-layout">

    {{-- KOLOM 1: Kategori Filter Cepat --}}
    <div class="pos-categories">
        <div class="cat-title"><i class="ti ti-layout-grid"></i> Kategori</div>
        <div id="categoryList"></div>
    </div>

    {{-- KOLOM 2: Manajemen Produk --}}
    <div class="pos-products">
        <div class="products-header">
            <div class="search-box">
                <i class="ti ti-search"></i>
                <input type="text" id="searchProduct" onkeyup="searchProduct()" placeholder="Cari nama furnitur atau SKU...">
            </div>
            <div class="product-count" id="product-count">Memuat Produk...</div>
        </div>

        <div class="product-grid" id="product-list-container"></div>
    </div>

    {{-- KOLOM 3: Keranjang Belanja --}}
    <div class="pos-cart">
        <div class="cart-header">
            <div class="channel-toggle">
                <button class="btn-channel active" id="channel-offline" onclick="switchChannel('offline')">
                    <i class="ti ti-building-store"></i> Toko Offline
                </button>
                <button class="btn-channel" id="channel-online" onclick="switchChannel('online')">
                    <i class="ti ti-world"></i> Order Online
                </button>
            </div>
        </div>

        <div class="customer-box">
            <div class="input-icon-wrapper">
                <i class="ti ti-user" id="notesIcon"></i>
                <input type="text" id="customerNotes" class="input-notes" placeholder="Nama Pelanggan / Catatan Meja">
            </div>
        </div>

        <div class="cart-items" id="cart-items-container"></div>

        <div class="cart-summary">
            <div class="summary-row">
                <span>Subtotal Barang</span>
                <span id="subtotal-val">Rp 0</span>
            </div>
            <div class="summary-row" id="row-ongkir" style="display: none; color: #0f172a;">
                <span>Ongkos Kirim</span>
                <span>Rp 50.000</span>
            </div>
            <div class="summary-row total">
                <span>Total Akhir</span>
                <span id="grand-total">Rp 0</span>
            </div>

            <div class="action-row">
                <button class="btn-action hold" onclick="alert('Pesanan berhasil disimpan sebagai Draft/Hold!')">
                    <i class="ti ti-folder-open"></i> Hold
                </button>
                <button class="btn-action pay" onclick="checkoutProcess()">
                    <i class="ti ti-cash"></i> Bayar
                </button>
            </div>
        </div>
    </div>

</div>

<script>
    // State Variables
    let posProducts = [];
    let cart = [];
    let currentChannel = 'offline';
    let currentSelectedCategory = 'all';
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
        
        let html = `<div class="category-item active" id="cat-all" onclick="filterCategory('all')"><i class="ti ti-box"></i> Semua Barang</div>`;
        categories.forEach(cat => {
            const label = cat.charAt(0).toUpperCase() + cat.slice(1).replace(/-/g, ' ');
            html += `<div class="category-item" id="cat-${cat}" onclick="filterCategory('${cat}')"><i class="ti ti-tag"></i> ${label}</div>`;
        });
        list.innerHTML = html;
    }

    function renderProducts(products) {
        const grid = document.getElementById('product-list-container');
        grid.innerHTML = '';
        document.getElementById('product-count').innerText = `${products.length} Item Furnitur`;

        if (products.length === 0) {
            grid.innerHTML = `<div style="grid-column: 1/-1; text-align: center; padding: 60px 20px; color: var(--text-sec); font-weight: 600;"><i class="ti ti-ghost" style="font-size: 40px; display: block; margin-bottom: 10px; opacity: 0.5;"></i>Produk tidak ditemukan...</div>`;
            return;
        }

        products.forEach(p => {
            const formattedPrice = 'Rp ' + p.harga.toLocaleString('id-ID');
            grid.innerHTML += `
                <div class="product-card" onclick="addToCart(${p.id})">
                    <img src="${p.img}" alt="${p.nama}" onerror="this.src='https://placehold.co/200x200?text=No+Image'">
                    <div class="title">${p.nama}</div>
                    <div class="stock">Sisa Stok: ${p.stok}</div>
                    <div class="price">${formattedPrice}</div>
                </div>`;
        });
    }

    function filterCategory(category) {
        currentSelectedCategory = category;
        document.getElementById('searchProduct').value = '';
        document.querySelectorAll('.category-item').forEach(item => item.classList.remove('active'));
        
        const target = document.getElementById('cat-' + category);
        if (target) target.classList.add('active');
        
        renderProducts(category === 'all' ? posProducts : posProducts.filter(p => p.kategori === category));
    }

    function searchProduct() {
        const keyword = document.getElementById('searchProduct').value.toLowerCase();
        let base = currentSelectedCategory === 'all' ? posProducts : posProducts.filter(p => p.kategori === currentSelectedCategory);
        renderProducts(base.filter(p => p.nama.toLowerCase().includes(keyword)));
    }

    function addToCart(productId) {
        const product = posProducts.find(p => p.id === productId);
        if (!product) return;
        
        const targetCartItem = cart.find(item => item.id === productId);
        if (targetCartItem) {
            if (targetCartItem.qty >= product.stok) {
                if (typeof Swal !== 'undefined') Swal.fire('Stok Habis', 'Batas sisa stok gudang terpenuhi!', 'warning');
                else alert('Batas sisa stok gudang terpenuhi!');
                return;
            }
            targetCartItem.qty++;
        } else {
            cart.push({ id: product.id, nama: product.nama, harga: product.harga, qty: 1, img: product.img });
        }
        renderCart();
    }

    function updateQty(productId, amount) {
        const product = posProducts.find(p => p.id === productId);
        const targetCartItem = cart.find(item => item.id === productId);
        
        if (targetCartItem) {
            targetCartItem.qty += amount;
            if (targetCartItem.qty > product.stok) {
                if (typeof Swal !== 'undefined') Swal.fire('Stok Terbatas', 'Jumlah melebihi stok yang ada!', 'warning');
                targetCartItem.qty = product.stok;
            }
            if (targetCartItem.qty <= 0) { 
                removeFromCart(productId); 
                return; 
            }
        }
        renderCart();
    }

    function removeFromCart(productId) {
        cart = cart.filter(item => item.id !== productId);
        renderCart();
    }

    function renderCart() {
        const container = document.getElementById('cart-items-container');
        container.innerHTML = '';

        if (cart.length === 0) {
            container.innerHTML = `
                <div style="text-align: center; margin: auto; color: var(--text-muted); font-size: 13px; font-weight: 500;">
                    <i class="ti ti-shopping-cart" style="font-size: 48px; display: block; margin-bottom: 12px; color: #cbd5e1;"></i>
                    Keranjang masih kosong
                </div>`;
            document.getElementById('subtotal-val').innerText = 'Rp 0';
            document.getElementById('grand-total').innerText = currentChannel === 'online' ? 'Rp ' + ongkirValue.toLocaleString('id-ID') : 'Rp 0';
            return;
        }

        let subtotal = 0;
        cart.forEach(item => {
            subtotal += item.harga * item.qty;
            container.innerHTML += `
                <div class="cart-item">
                    <img src="${item.img || 'https://placehold.co/50x50'}" alt="${item.nama}" onerror="this.src='https://placehold.co/50x50'">
                    <div class="item-info">
                        <div class="item-name">${item.nama}</div>
                        <div class="item-price">Rp ${item.harga.toLocaleString('id-ID')}</div>
                    </div>
                    <div class="qty-control">
                        <button class="btn-qty" onclick="updateQty(${item.id}, -1)">-</button>
                        <div class="qty-val">${item.qty}</div>
                        <button class="btn-qty" onclick="updateQty(${item.id}, 1)">+</button>
                    </div>
                </div>`;
        });

        let grandTotal = subtotal + (currentChannel === 'online' ? ongkirValue : 0);
        document.getElementById('subtotal-val').innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
        document.getElementById('grand-total').innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
    }

    function switchChannel(type) {
        currentChannel = type;
        const btnOffline = document.getElementById('channel-offline');
        const btnOnline = document.getElementById('channel-online');
        const rowOngkir = document.getElementById('row-ongkir');
        const notesInput = document.getElementById('customerNotes');

        if(type === 'online') {
            btnOnline.classList.add('active');
            btnOffline.classList.remove('active');
            rowOngkir.style.display = 'flex';
            notesInput.placeholder = "Nama Pelanggan / Alamat Kirim / No HP";
        } else {
            btnOffline.classList.add('active');
            btnOnline.classList.remove('active');
            rowOngkir.style.display = 'none';
            notesInput.placeholder = "Nama Pelanggan / Catatan Meja";
        }
        renderCart();
    }

async function checkoutProcess() {
    if (cart.length === 0) {
        if (typeof Swal !== 'undefined') Swal.fire('Gagal', 'Keranjang belanja kosong!', 'warning');
        else alert('Keranjang belanja kosong!');
        return;
    }

    const notes = document.getElementById('customerNotes').value;
    const grandTotalText = document.getElementById('grand-total').innerText;

    if (typeof Swal !== 'undefined') {
        const { isConfirmed } = await Swal.fire({
            title: 'Proses Pembayaran?',
            text: `Total tagihan: ${grandTotalText}`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Bayar Sekarang',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#2563eb',
        });
        if (!isConfirmed) return;
    } else {
        if (!confirm(`Proses pembayaran dengan Total ${grandTotalText}?`)) return;
    }

    try {
        const res = await fetch('/admin/kasir/checkout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                items: cart.map(i => ({ id: i.id, qty: i.qty, harga: i.harga })),
                channel: currentChannel,
                notes: notes,
            })
        });
        const data = await res.json();

        if (data.success) {
            window.location.href = `/admin/orders/${data.order_id}/invoice`;
            return;
        } else {
            if (typeof Swal !== 'undefined') Swal.fire('Error', 'Transaksi gagal.', 'error');
            else alert('Transaksi gagal.');
        }
    } catch (err) {
        console.error(err);
        if (typeof Swal !== 'undefined') Swal.fire('Error', 'Terjadi kesalahan sistem saat proses transaksi.', 'error');
        else alert('Terjadi kesalahan sistem saat proses transaksi.');
    }
}

    // Hotkey F8 untuk Bayar Cepat
    document.addEventListener('keydown', function(e) {
        if (e.key === 'F8') { 
            e.preventDefault(); 
            checkoutProcess(); 
        }
    });

    document.addEventListener('DOMContentLoaded', loadProducts);
</script>
@endsection