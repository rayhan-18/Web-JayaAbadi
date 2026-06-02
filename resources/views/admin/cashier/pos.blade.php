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
    
    <div class="pos-categories">
        <div class="category-item active" onclick="filterCategory('semua', this)">
            <i class="ti ti-layout-grid"></i> Semua Produk
        </div>
        <div class="category-item" onclick="filterCategory('Kursi', this)">
            <i class="ti ti-armchair"></i> Kursi
        </div>
        <div class="category-item" onclick="filterCategory('Meja', this)">
            <i class="ti ti-table"></i> Meja
        </div>
        <div class="category-item" onclick="filterCategory('Lemari', this)">
            <i class="ti ti-door"></i> Lemari
        </div>
        <div class="category-item" onclick="filterCategory('Sofa', this)">
            <i class="ti ti-sofa"></i> Sofa
        </div>
        <div class="category-item" onclick="filterCategory('Rak', this)">
            <i class="ti ti-books"></i> Rak
        </div>
    </div>

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
    // Database dummy produk terintegrasi gambar Unsplash premium
    const rawProducts = [
        {id: 1, name: 'Kursi Minimalis Kayu', category: 'Kursi', price: 750000, stock: 45, img: 'https://images.unsplash.com/photo-1505797149-43b0069ec26b?w=200&auto=format&fit=crop&q=60'},
        {id: 2, name: 'Meja Makan Jati Set', category: 'Meja', price: 2500000, stock: 38, img: 'https://images.unsplash.com/photo-1615066390971-03e4e1c36ddf?w=200&auto=format&fit=crop&q=60'},
        {id: 3, name: 'Lemari Pakaian 3 Pintu', category: 'Lemari', price: 3200000, stock: 30, img: 'https://images.unsplash.com/photo-1595428774223-ef52624120d2?w=200&auto=format&fit=crop&q=60'},
        {id: 4, name: 'Sofa Minimalis Grey', category: 'Sofa', price: 4500000, stock: 28, img: 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=200&auto=format&fit=crop&q=60'},
        {id: 5, name: 'Rak Buku Kayu Minimalis', category: 'Rak', price: 850000, stock: 25, img: 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=200&auto=format&fit=crop&q=60'},
        {id: 6, name: 'Meja Samping Walnut', category: 'Meja', price: 650000, stock: 40, img: 'https://images.unsplash.com/photo-1532372320978-9b4d7a92b24d?w=200&auto=format&fit=crop&q=60'},
        {id: 7, name: 'Sofa Velvet Emerald Luxury', category: 'Sofa', price: 3750000, stock: 15, img: 'https://images.unsplash.com/photo-1493663284031-b7e3aefcae8e?w=200&auto=format&fit=crop&q=60'},
        {id: 8, name: 'Kursi Cafe Industrial', category: 'Kursi', price: 1350000, stock: 33, img: 'https://images.unsplash.com/photo-1567538096630-e0c55bd6374c?w=200&auto=format&fit=crop&q=60'}
    ];

    let cart = [];
    let currentChannel = 'offline';
    let selectedCategory = 'semua';

    function formatRupiah(number) {
        return 'Rp ' + number.toLocaleString('id-ID');
    }

    // Render Produk ke Grid HTML
    function renderProducts(filterList = rawProducts) {
        const container = document.getElementById('product-list-container');
        container.innerHTML = '';
        
        document.getElementById('product-count').innerText = `${filterList.length} Item Furnitur`;

        if(filterList.length === 0) {
            container.innerHTML = `<div style="grid-column: 1/-1; text-align: center; padding: 40px; color: var(--text-sec); font-weight: 500;">Produk tidak ditemukan...</div>`;
            return;
        }

        filterList.forEach(p => {
            const card = document.createElement('div');
            card.className = 'product-card';
            card.onclick = () => addToCart(p.id);
            card.innerHTML = `
                <img src="${p.img}" alt="${p.name}">
                <div class="title">${p.name}</div>
                <div class="stock">Stok Tersedia: ${p.stock}</div>
                <div class="price">${formatRupiah(p.price)}</div>
            `;
            container.appendChild(card);
        });
    }

    // Filter Kategori Sidebar Klik
    function filterCategory(cat, element) {
        selectedCategory = cat;
        document.querySelectorAll('.category-item').forEach(el => el.classList.remove('active'));
        element.classList.add('active');
        applyFilter();
    }

    // Live Search Box Filter
    function searchProduct() {
        applyFilter();
    }

    // Gabungkan Filter Kategori + Search Keyword
    function applyFilter() {
        const keyword = document.getElementById('searchProduct').value.toLowerCase();
        const filtered = rawProducts.filter(p => {
            const matchCategory = (selectedCategory === 'semua' || p.category === selectedCategory);
            const matchKeyword = p.name.toLowerCase().includes(keyword);
            return matchCategory && matchKeyword;
        });
        renderProducts(filtered);
    }

    // Tambah Item ke Keranjang Belanja
    function addToCart(productId) {
        const product = rawProducts.find(p => p.id === productId);
        const exist = cart.find(item => item.id === productId);

        if(exist) {
            exist.qty += 1;
        } else {
            cart.push({ ...product, qty: 1 });
        }
        renderCart();
    }

    // Ubah Kuantitas Item (+ / -)
    function changeQty(id, delta) {
        const item = cart.find(i => i.id === id);
        if(!item) return;

        item.qty += delta;
        if(item.qty <= 0) {
            cart = cart.filter(i => i.id !== id);
        }
        renderCart();
    }

    // Render Ulang List Keranjang & Hitung Total Akhir
    function renderCart() {
        const container = document.getElementById('cart-items-container');
        container.innerHTML = '';

        if(cart.length === 0) {
            container.innerHTML = `
                <div style="text-align: center; margin: auto; color: var(--text-muted); font-size: 13px;">
                    <i class="ti ti-shopping-cart" style="font-size: 36px; display: block; margin-bottom: 8px; color: var(--text-sec);"></i>
                    Keranjang masih kosong
                </div>`;
            document.getElementById('subtotal-val').innerText = 'Rp 0';
            document.getElementById('grand-total').innerText = 'Rp 0';
            return;
        }

        let subtotal = 0;
        cart.forEach(item => {
            subtotal += (item.price * item.qty);
            const el = document.createElement('div');
            el.className = 'cart-item';
            el.innerHTML = `
                <img src="${item.img}" alt="${item.name}">
                <div class="item-info">
                    <div class="item-name">${item.name}</div>
                    <div class="item-price">${formatRupiah(item.price)}</div>
                </div>
                <div class="qty-control">
                    <button class="btn-qty" onclick="changeQty(${item.id}, -1)">-</button>
                    <div class="qty-val">${item.qty}</div>
                    <button class="btn-qty" onclick="changeQty(${item.id}, 1)">+</button>
                </div>
            `;
            container.appendChild(el);
        });

        let ongkir = currentChannel === 'online' ? 50000 : 0;
        let grandTotal = subtotal + ongkir;

        document.getElementById('subtotal-val').innerText = formatRupiah(subtotal);
        document.getElementById('grand-total').innerText = formatRupiah(grandTotal);
    }

    // Switch Metode: Offline Store vs Order Online
    function switchChannel(channel) {
        currentChannel = channel;
        const btnOffline = document.getElementById('channel-offline');
        const btnOnline = document.getElementById('channel-online');
        const rowOngkir = document.getElementById('row-ongkir');
        const notesInput = document.getElementById('customerNotes');

        if(channel === 'online') {
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

    // Cetak Transaksi Rangkuman Akhir
    function checkoutProcess() {
        if (cart.length === 0) {
            alert('Gagal: Masukkan minimal 1 barang ke dalam keranjang belanja!');
            return;
        }
        const notes = document.getElementById('customerNotes').value;
        const infoCatatan = notes.trim() !== '' ? `\nCatatan Pembeli: ${notes}` : '';
        
        alert(`✨ Transaksi Berhasil Diproses! ✨\nSaluran: Toko ${currentChannel.toUpperCase()}${infoCatatan}\nTotal Nota: ${document.getElementById('grand-total').innerText}\n\nStruk siap dicetak.`);
        
        cart = [];
        document.getElementById('customerNotes').value = '';
        renderCart();
    }

    // Global Key Listener Shortcut F8 untuk Bayar Cepat
    document.addEventListener('keydown', function(e) {
        if (e.key === 'F8') {
            e.preventDefault();
            checkoutProcess();
        }
    });

    // Booting Inisialisasi Pertama Kali
    document.addEventListener("DOMContentLoaded", function() {
        renderProducts();
        renderCart();
    });
</script>
@endsection