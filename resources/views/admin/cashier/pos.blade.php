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

    /* ==========================================================================
       RESPONSIVE GRID SYSTEM (ANTI PECAH)
       ========================================================================== */
    .pos-layout {
        display: grid;
        grid-template-columns: 240px 1fr 380px;
        gap: 16px;
        min-height: calc(100vh - 120px);
        align-items: start;
    }

    /* Tablet Landscape / Laptop Kecil */
    @media (max-width: 1300px) {
        .pos-layout {
            grid-template-columns: 200px 1fr 350px;
        }
    }

    /* Tablet Portrait Mode */
    @media (max-width: 1024px) {
        .pos-layout {
            grid-template-columns: 1fr 350px; /* Sembunyikan sidebar kategori default */
        }
        .pos-categories {
            display: none !important; /* Disembunyikan atau bisa diakses via dropdown nantinya */
        }
    }

    /* Mobile Phone Mode (Sangat Penting untuk Penggunaan HP) */
    @media (max-width: 768px) {
        .pos-layout {
            grid-template-columns: 1fr; /* Menjadi 1 kolom penuh kebawah */
            gap: 20px;
            height: auto;
            min-height: auto;
        }
        
        .pos-products {
            order: 1; /* Produk muncul pertama */
        }
        
        .pos-cart {
            order: 2; /* Keranjang belanja bergeser ke bawah produk */
            position: sticky;
            bottom: 0;
            z-index: 99;
            box-shadow: 0 -4px 20px rgba(0,0,0,0.08);
            max-height: 500px;
        }

        .product-grid {
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)) !important; /* Grid produk mengecil di HP */
            gap: 10px !important;
        }

        .product-card {
            padding: 10px !important;
        }

        .product-card img {
            height: 100px !important;
        }

        .action-row {
            flex-direction: column; /* Tombol transaksi di HP jadi tumpuk vertikal */
            gap: 8px !important;
        }

        .btn-action {
            width: 100% !important;
            justify-content: center;
        }
    }

    /* Left Sidebar: Categories */
    .pos-categories {
        background: var(--bg-surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 16px;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .category-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 14px;
        border-radius: var(--radius-md);
        cursor: pointer;
        font-size: 13.5px;
        font-weight: 600;
        color: var(--text-sec);
        transition: all 0.2s ease;
    }
    .category-item i { font-size: 18px; }
    .category-item:hover { background: var(--bg-hover); color: var(--accent); }
    .category-item.active { background: var(--accent-light); color: var(--accent-dark); }

    /* Center Section: Products Management */
    .pos-products {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    
    /* Responsive Top Bar */
    .products-header {
        background: var(--bg-surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 14px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap; /* Supaya search box turun rapi kalau di layar sempit */
    }
    .search-box {
        display: flex;
        align-items: center;
        background: var(--bg-hover);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 0 14px;
        gap: 10px;
        height: 40px;
        flex: 1;
        min-width: 200px;
    }
    .search-box input {
        border: none; outline: none; background: transparent;
        font-size: 13px; width: 100%; color: var(--text-main);
    }

    /* Grid Produk Adaptif */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
        gap: 14px;
    }
    .product-card {
        background: var(--bg-surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 12px;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        position: relative;
    }
    .product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(92, 158, 116, 0.1);
        border-color: var(--accent);
    }
    .product-card img {
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-radius: var(--radius-md);
        margin-bottom: 10px;
        background: var(--bg-hover);
    }
    .product-card .title { font-size: 13.5px; font-weight: 700; color: var(--text-main); margin-bottom: 4px; }
    .product-card .stock { font-size: 11px; color: var(--text-sec); margin-bottom: 8px; }
    .product-card .price { font-size: 14px; font-weight: 800; color: var(--accent); margin-top: auto; }

    /* Right Section: Premium Checkout Cart Card */
    .pos-cart {
        background: var(--bg-surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        display: flex;
        flex-direction: column;
        height: calc(100vh - 120px);
        min-height: 500px;
    }
    
    /* Cart Header & Channel Toggle Button */
    .cart-header { padding: 16px; border-bottom: 1px solid var(--border); }
    .channel-toggle {
        display: grid;
        grid-template-columns: 1fr 1fr;
        background: var(--bg-hover);
        padding: 4px;
        border-radius: var(--radius-md);
        border: 1px solid var(--border);
    }
    .btn-channel {
        border: none; background: transparent; padding: 8px;
        font-size: 12.5px; font-weight: 700; color: var(--text-sec);
        cursor: pointer; border-radius: 7px; transition: 0.15s;
        display: flex; align-items: center; justify-content: center; gap: 6px;
    }
    .btn-channel.active { background: var(--accent); color: white; box-shadow: 0 2px 6px rgba(92,158,116,0.2); }

    /* Customer Notes Input Box */
    .customer-box { padding: 10px 16px; border-bottom: 1px solid var(--border); background: #fafbfa; }
    .input-icon-wrapper { position: relative; display: flex; align-items: center; }
    .input-icon-wrapper i { position: absolute; left: 12px; color: var(--text-sec); font-size: 15px; }
    .input-notes {
        width: 100%; height: 36px; padding: 0 12px 0 34px;
        border: 1px solid var(--border); border-radius: 8px;
        font-size: 12px; color: var(--text-main); font-weight: 500; outline: none;
    }
    .input-notes:focus { border-color: var(--accent); background: white; }

    /* Items List Container (Scrollable) */
    .cart-items { flex: 1; overflow-y: auto; padding: 16px; display: flex; flex-direction: column; gap: 12px; }
    .cart-item { display: flex; gap: 12px; align-items: center; padding-bottom: 12px; border-bottom: 1px dashed var(--border); }
    .cart-item:last-child { border-bottom: none; }
    .cart-item img { width: 45px; height: 45px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border); }
    .item-info { flex: 1; }
    .item-name { font-size: 13px; font-weight: 700; color: var(--text-main); }
    .item-price { font-size: 12px; color: var(--text-sec); margin-top: 2px; }
    
    /* Quantity Counter Control */
    .qty-control { display: flex; align-items: center; border: 1px solid var(--border); border-radius: 6px; overflow: hidden; background: var(--bg-surface); }
    .btn-qty { border: none; background: transparent; width: 26px; height: 26px; font-size: 12px; cursor: pointer; color: var(--text-main); }
    .btn-qty:hover { background: var(--bg-hover); color: var(--accent); }
    .qty-val { width: 30px; text-align: center; font-size: 12px; font-weight: 700; border-left: 1px solid var(--border); border-right: 1px solid var(--border); }

    /* Summary & Checkout Button Area */
    .cart-summary { padding: 16px; background: var(--bg-hover); border-top: 1px solid var(--border); border-radius: 0 0 var(--radius-lg) var(--radius-lg); margin-top: auto; }
    .summary-row { display: flex; justify-content: space-between; font-size: 13px; color: var(--text-sec); margin-bottom: 8px; font-weight: 500; }
    .summary-row.total { font-size: 16px; font-weight: 800; color: var(--text-main); border-top: 1px solid #e1e5e0; padding-top: 10px; margin-top: 4px; margin-bottom: 14px; }
    
    .action-row { display: flex; gap: 10px; }
    .btn-action {
        flex: 1; height: 44px; border: none; border-radius: 10px;
        font-size: 13px; font-weight: 700; cursor: pointer;
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        transition: all 0.15s ease;
    }
    .btn-action.hold { background: #ffffff; border: 1px solid var(--border); color: var(--text-sec); }
    .btn-action.hold:hover { background: #f0f2ef; color: var(--text-main); }
    .btn-action.pay { background: var(--accent); color: white; box-shadow: 0 2px 6px rgba(92,158,116,0.2); }
    .btn-action.pay:hover { background: var(--accent-dark); transform: translateY(-1px); }
</style>
@endsection

@section('content')
<div class="pos-layout">

    {{-- KOLOM 1: Kategori Filter Cepat --}}
    <div class="pos-categories">
        <div style="padding: 0 0 10px 0; font-weight: 700; font-size: 14px; border-bottom: 1px solid var(--border);">
            <i class="ti ti-layout-grid"></i> Kategori
        </div>
        <div id="categoryList" style="display:flex; flex-direction:column; gap:8px; padding-top: 10px;">
            </div>
    </div>

    {{-- KOLOM 2: Manajemen Produk --}}
    <div class="pos-products">
        <div class="products-header">
            <div class="search-box">
                <i class="ti ti-search" style="color: var(--text-sec);"></i>
                <input type="text" id="searchProduct" onkeyup="searchProduct()" placeholder="Cari nama furniture atau scan SKU...">
            </div>
            <div style="font-size: 12.5px; font-weight: 700; color: var(--text-sec);" id="product-count">
                Memuat Produk...
            </div>
        </div>

        <div class="product-grid" id="product-list-container">
            </div>
    </div>

    {{-- KOLOM 3: Keranjang Belanja --}}
    <div class="pos-cart">
        <div class="cart-header">
            <div class="channel-toggle">
                <button class="btn-channel active" id="channel-offline" onclick="switchChannel('offline')">
                    <i class="ti ti-device-computer-camera"></i> Toko Offline
                </button>
                <button class="btn-channel" id="channel-online" onclick="switchChannel('online')">
                    <i class="ti ti-world"></i> Order Online
                </button>
            </div>
        </div>

        <div class="customer-box">
            <div class="input-icon-wrapper">
                <i class="ti ti-user" id="notesIcon"></i>
                <input type="text" id="customerNotes" class="input-notes" placeholder="Nama Pelanggan / Catatan Meja (Opsional)">
            </div>
        </div>

        <div class="cart-items" id="cart-items-container">
            </div>

        <div class="cart-summary">
            <div class="summary-row">
                <span>Subtotal Barang</span>
                <span id="subtotal-val">Rp 0</span>
            </div>
            <div class="summary-row" id="row-ongkir" style="display: none; color: #b56e4e;">
                <span>Ongkos Kirim J&T</span>
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
                    <i class="ti ti-circle-check"></i> Bayar [F8]
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

    // Load Data dari Database (Backend Laravel)
    async function loadProducts() {
        try {
            const res = await fetch('/admin/kasir/produk');
            posProducts = await res.json();
            buildCategoryList();
            renderProducts(posProducts);
            renderCart();
        } catch (err) {
            console.error('Gagal load produk:', err);
            // Tangani error jika fetch gagal
        }
    }

    // Bangun Sidebar Kategori Secara Dinamis
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

    // Render Grid Produk
    function renderProducts(products) {
        const grid = document.getElementById('product-list-container');
        grid.innerHTML = '';
        document.getElementById('product-count').innerText = `${products.length} Item Furnitur`;

        if (products.length === 0) {
            grid.innerHTML = `<div style="grid-column: 1/-1; text-align: center; padding: 40px; color: var(--text-sec); font-weight: 500;">Produk tidak ditemukan...</div>`;
            return;
        }

        products.forEach(p => {
            const formattedPrice = 'Rp ' + p.harga.toLocaleString('id-ID');
            grid.innerHTML += `
                <div class="product-card" onclick="addToCart(${p.id})">
                    <img src="${p.img}" alt="${p.nama}">
                    <div class="title">${p.nama}</div>
                    <div class="stock">Stok Tersedia: ${p.stok}</div>
                    <div class="price">${formattedPrice}</div>
                </div>`;
        });
    }

    // Filter Kategori
    function filterCategory(category) {
        currentSelectedCategory = category;
        document.getElementById('searchProduct').value = '';
        document.querySelectorAll('.category-item').forEach(item => item.classList.remove('active'));
        
        const target = document.getElementById('cat-' + category);
        if (target) target.classList.add('active');
        
        renderProducts(category === 'all' ? posProducts : posProducts.filter(p => p.kategori === category));
    }

    // Fitur Pencarian
    function searchProduct() {
        const keyword = document.getElementById('searchProduct').value.toLowerCase();
        let base = currentSelectedCategory === 'all' ? posProducts : posProducts.filter(p => p.kategori === currentSelectedCategory);
        renderProducts(base.filter(p => p.nama.toLowerCase().includes(keyword)));
    }

    // Manajemen Keranjang - Tambah
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

    // Manajemen Keranjang - Ubah Qty
    function updateQty(productId, amount) {
        const product = posProducts.find(p => p.id === productId);
        const targetCartItem = cart.find(item => item.id === productId);
        
        if (targetCartItem) {
            targetCartItem.qty += amount;
            if (targetCartItem.qty > product.stok) {
                if (typeof Swal !== 'undefined') Swal.fire('Stok Tidak Cukup', 'Stok tidak mencukupi!', 'warning');
                targetCartItem.qty = product.stok;
            }
            if (targetCartItem.qty <= 0) { 
                removeFromCart(productId); 
                return; 
            }
        }
        renderCart();
    }

    // Manajemen Keranjang - Hapus
    function removeFromCart(productId) {
        cart = cart.filter(item => item.id !== productId);
        renderCart();
    }

    // Render Keranjang
    function renderCart() {
        const container = document.getElementById('cart-items-container');
        container.innerHTML = '';

        if (cart.length === 0) {
            container.innerHTML = `
                <div style="text-align: center; margin: auto; color: var(--text-muted); font-size: 13px;">
                    <i class="ti ti-shopping-cart" style="font-size: 36px; display: block; margin-bottom: 8px; color: var(--text-sec);"></i>
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
                    <img src="${item.img || 'https://via.placeholder.com/45'}" alt="${item.nama}">
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

    // Ganti Saluran Penjualan
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
            notesInput.placeholder = "Nama Pelanggan / Catatan Meja (Opsional)";
        }
        renderCart();
    }

    // Proses Pembayaran ke Server
    async function checkoutProcess() {
        if (cart.length === 0) {
            if (typeof Swal !== 'undefined') Swal.fire('Gagal', 'Masukkan minimal 1 barang ke keranjang!', 'warning');
            else alert('Masukkan minimal 1 barang ke keranjang!');
            return;
        }

        const notes = document.getElementById('customerNotes').value;
        const grandTotalText = document.getElementById('grand-total').innerText;

        if (typeof Swal !== 'undefined') {
            const { isConfirmed } = await Swal.fire({
                title: 'Proses Pembayaran?',
                text: `Total: ${grandTotalText}`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Proses',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#5c9e74',
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
                if (typeof Swal !== 'undefined') {
                    await Swal.fire('Berhasil!', `Transaksi ${data.order_number} berhasil!\nTotal: Rp ${Number(data.total).toLocaleString('id-ID')}`, 'success');
                } else {
                    alert(`Berhasil! Transaksi ${data.order_number} sukses.`);
                }
                cart = [];
                document.getElementById('customerNotes').value = '';
                renderCart();
                loadProducts(); // Refresh stok dari server
            } else {
                if (typeof Swal !== 'undefined') Swal.fire('Error', 'Transaksi gagal.', 'error');
                else alert('Transaksi gagal.');
            }
        } catch (err) {
            console.error(err);
            if (typeof Swal !== 'undefined') Swal.fire('Error', 'Terjadi kesalahan saat proses transaksi.', 'error');
            else alert('Terjadi kesalahan saat proses transaksi.');
        }
    }

    // Hotkey F8
    document.addEventListener('keydown', function(e) {
        if (e.key === 'F8') { 
            e.preventDefault(); 
            checkoutProcess(); 
        }
    });

    // Jalankan inisiasi saat halaman dimuat
    document.addEventListener('DOMContentLoaded', loadProducts);
</script>
@endsection