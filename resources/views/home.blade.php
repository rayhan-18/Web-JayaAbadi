@extends('layouts.app')

@section('title', 'Beranda - MyHome')

@section('content')

<!-- ============================================================
     GOOGLE FONTS
============================================================ -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500&display=swap" rel="stylesheet">

<!-- ============================================================
     GLOBAL TYPOGRAPHY & CSS CUSTOM PROPERTIES
============================================================ -->
<style>
:root {
    --font-display: 'Cormorant Garamond', Georgia, serif;
    --font-body: 'DM Sans', sans-serif;
    --color-ivory:   #F9F6F1;
    --color-linen:   #EDE8DF;
    --color-warm:    #C8A97A;
    --color-amber:   #92400e;
    --color-charcoal:#1C1A17;
    --color-muted:   #7A736A;
    --color-white:   #FFFFFF;
    --ease-out-expo: cubic-bezier(0.16, 1, 0.3, 1);
}

*, *::before, *::after { box-sizing: border-box; }

body {
    font-family: var(--font-body);
    background: var(--color-ivory);
    color: var(--color-charcoal);
}

/* ===== HERO SLIDER ===== */
.hero-section {
    position: relative;
    height: 100svh;
    min-height: 620px;
    overflow: hidden;
}

.slide {
    position: absolute;
    inset: 0;
    opacity: 0;
    transition: opacity 1.2s ease-in-out;
}
.slide.active { opacity: 1; }

.slide-bg {
    position: absolute;
    inset: 0;
    background-size: cover;
    background-position: center;
    animation: kbZoom 22s ease-in-out infinite alternate;
    transform-origin: center;
}
.slide[style*="opacity: 0"] .slide-bg { animation: none; }

@keyframes kbZoom {
    from { transform: scale(1); }
    to   { transform: scale(1.07); }
}

.slide-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(
        160deg,
        rgba(28,26,23,0.55) 0%,
        rgba(28,26,23,0.25) 60%,
        rgba(28,26,23,0.5) 100%
    );
}

.slide-content {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: flex-end;
    padding: clamp(2.5rem, 6vw, 5rem) clamp(1.5rem, 7vw, 6rem);
}

.slide-inner {
    max-width: 680px;
    opacity: 0;
    transform: translateY(28px);
    transition: opacity 1s var(--ease-out-expo) 0.3s,
                transform 1s var(--ease-out-expo) 0.3s;
}
.slide.active .slide-inner {
    opacity: 1;
    transform: translateY(0);
}

.slide-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    font-family: var(--font-body);
    font-size: 0.7rem;
    font-weight: 400;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: var(--color-warm);
    margin-bottom: 1rem;
}
.slide-eyebrow::before {
    content: '';
    display: block;
    width: 28px;
    height: 1px;
    background: var(--color-warm);
}

.slide-title {
    font-family: var(--font-display);
    font-size: clamp(2.8rem, 6vw, 5.2rem);
    font-weight: 300;
    line-height: 1.08;
    color: var(--color-white);
    margin: 0 0 1.25rem;
    letter-spacing: -0.01em;
}

.slide-subtitle {
    font-family: var(--font-body);
    font-size: clamp(0.85rem, 1.5vw, 1rem);
    font-weight: 300;
    line-height: 1.75;
    color: rgba(255,255,255,0.75);
    max-width: 500px;
    margin: 0 0 2.25rem;
}

.hero-ctas {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
}

.btn-hero-primary {
    background: var(--color-warm);
    color: var(--color-charcoal);
    padding: 0.85rem 2rem;
    font-family: var(--font-body);
    font-size: 0.78rem;
    font-weight: 500;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: background 0.3s ease, transform 0.3s ease;
    display: inline-block;
}
.btn-hero-primary:hover {
    background: #d4b48a;
    transform: translateY(-2px);
}

.btn-hero-ghost {
    background: transparent;
    color: var(--color-white);
    padding: 0.85rem 2rem;
    font-family: var(--font-body);
    font-size: 0.78rem;
    font-weight: 400;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    text-decoration: none;
    border: 1px solid rgba(255,255,255,0.45);
    cursor: pointer;
    transition: background 0.3s ease, border-color 0.3s ease;
    display: inline-block;
}
.btn-hero-ghost:hover {
    background: rgba(255,255,255,0.08);
    border-color: rgba(255,255,255,0.7);
}

/* Hero nav arrows */
.hero-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 10;
    background: none;
    border: none;
    cursor: pointer;
    padding: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
}
.hero-arrow-left  { left: 1.5rem; }
.hero-arrow-right { right: 1.5rem; }

.arrow-line {
    display: block;
    width: 36px;
    height: 1px;
    background: rgba(255,255,255,0.5);
    position: relative;
    transition: width 0.3s ease, background 0.3s ease;
}
.arrow-line::after {
    content: '';
    position: absolute;
    top: 50%;
    width: 8px;
    height: 8px;
    border-right: 1px solid rgba(255,255,255,0.5);
    border-top: 1px solid rgba(255,255,255,0.5);
    transition: border-color 0.3s ease;
}
.hero-arrow-right .arrow-line::after {
    right: 0;
    transform: translateY(-50%) rotate(45deg);
}
.hero-arrow-left  .arrow-line::after {
    left: 0;
    transform: translateY(-50%) rotate(-135deg);
}
.hero-arrow:hover .arrow-line {
    width: 48px;
    background: rgba(255,255,255,0.9);
}
.hero-arrow:hover .arrow-line::after {
    border-color: rgba(255,255,255,0.9);
}

/* Hero dots */
.hero-dots {
    position: absolute;
    bottom: 2rem;
    right: clamp(1.5rem, 6vw, 5rem);
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    z-index: 10;
}
.hero-dot {
    display: block;
    width: 1px;
    height: 20px;
    background: rgba(255,255,255,0.35);
    border: none;
    cursor: pointer;
    padding: 0;
    transition: height 0.4s ease, background 0.4s ease;
}
.hero-dot.active {
    height: 36px;
    background: var(--color-warm);
}

/* Slide counter */
.slide-counter {
    position: absolute;
    bottom: 2.2rem;
    left: clamp(1.5rem, 7vw, 6rem);
    font-family: var(--font-body);
    font-size: 0.7rem;
    letter-spacing: 0.15em;
    color: rgba(255,255,255,0.45);
    z-index: 10;
}
.slide-counter .current { color: rgba(255,255,255,0.85); }

/* ===== SCROLL REVEAL ===== */
.reveal {
    opacity: 0;
    transform: translateY(36px);
    transition: opacity 1s var(--ease-out-expo),
                transform 1s var(--ease-out-expo);
}
.reveal.visible {
    opacity: 1;
    transform: none;
}
.d1 { transition-delay: 0.05s; }
.d2 { transition-delay: 0.12s; }
.d3 { transition-delay: 0.19s; }
.d4 { transition-delay: 0.26s; }
.d5 { transition-delay: 0.33s; }
.d6 { transition-delay: 0.40s; }

/* ===== SECTION HEADERS ===== */
.section-label {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    font-size: 0.68rem;
    font-weight: 400;
    letter-spacing: 0.22em;
    text-transform: uppercase;
    color: var(--color-warm);
    margin-bottom: 0.75rem;
}
.section-label::before {
    content: '';
    display: block;
    width: 24px;
    height: 1px;
    background: var(--color-warm);
}

.section-title {
    font-family: var(--font-display);
    font-weight: 300;
    color: var(--color-charcoal);
    line-height: 1.1;
}

/* ===== DIVIDER ===== */
.ornament-divider {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin: 0 auto 3.5rem;
    max-width: 200px;
}
.ornament-divider::before,
.ornament-divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--color-linen);
}
.ornament-divider span {
    width: 5px;
    height: 5px;
    background: var(--color-warm);
    transform: rotate(45deg);
    flex-shrink: 0;
}

/* ===== CATEGORY CARDS ===== */
.categories-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
}
@media (max-width: 900px) {
    .categories-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 500px) {
    .categories-grid { grid-template-columns: 1fr 1fr; gap: 0.6rem; }
}

.cat-card {
    position: relative;
    overflow: hidden;
    aspect-ratio: 3/4;
    display: block;
    text-decoration: none;
    background: #1a1815;
}
.cat-card::after {
    content: '';
    position: absolute;
    inset: 0;
    border: 1px solid transparent;
    transition: border-color 0.5s ease;
    pointer-events: none;
    z-index: 3;
}
.cat-card:hover::after {
    border-color: var(--color-warm);
}

.cat-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.9s var(--ease-out-expo),
                opacity 0.5s ease;
    opacity: 0.88;
}
.cat-card:hover .cat-img {
    transform: scale(1.07);
    opacity: 0.75;
}

.cat-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(28,26,23,0.75) 0%, transparent 55%);
    z-index: 1;
}

.cat-info {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 1.75rem 1.5rem;
    z-index: 2;
    transform: translateY(0);
    transition: transform 0.5s var(--ease-out-expo);
}

.cat-name {
    font-family: var(--font-display);
    font-size: 1.45rem;
    font-weight: 300;
    color: var(--color-white);
    display: block;
    margin-bottom: 0.5rem;
    letter-spacing: 0.01em;
}

.cat-cta {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.68rem;
    font-weight: 400;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    color: var(--color-warm);
    opacity: 0;
    transform: translateY(8px);
    transition: opacity 0.4s ease 0.1s, transform 0.4s var(--ease-out-expo) 0.1s;
}
.cat-cta::after {
    content: '';
    display: block;
    width: 20px;
    height: 1px;
    background: var(--color-warm);
    transition: width 0.3s ease;
}
.cat-card:hover .cat-cta {
    opacity: 1;
    transform: translateY(0);
}
.cat-card:hover .cat-cta::after { width: 32px; }

/* ===== PRODUCT CARDS ===== */
.products-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.5rem;
}
@media (max-width: 1024px) {
    .products-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 540px) {
    .products-grid { grid-template-columns: 1fr 1fr; gap: 0.75rem; }
}

.product-card {
    background: var(--color-white);
    display: flex;
    flex-direction: column;
    position: relative;
    transition: box-shadow 0.4s ease;
}
.product-card:hover {
    box-shadow: 0 20px 50px -10px rgba(28,26,23,0.14);
}

.product-img-wrap {
    position: relative;
    overflow: hidden;
    aspect-ratio: 1/1;
    background: var(--color-linen);
}
.product-img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.8s var(--ease-out-expo);
}
.product-card:hover .product-img-wrap img {
    transform: scale(1.06);
}

.product-badge {
    position: absolute;
    top: 0.9rem;
    left: 0.9rem;
    background: var(--color-charcoal);
    color: var(--color-ivory);
    font-size: 0.62rem;
    font-weight: 500;
    letter-spacing: 0.16em;
    text-transform: uppercase;
    padding: 0.3rem 0.7rem;
    z-index: 2;
}

.product-body {
    padding: 1.25rem 1.25rem 1.5rem;
    display: flex;
    flex-direction: column;
    flex: 1;
}

.product-name {
    font-family: var(--font-display);
    font-size: 1.15rem;
    font-weight: 400;
    color: var(--color-charcoal);
    margin-bottom: 0.35rem;
    line-height: 1.3;
}

.product-stars {
    display: flex;
    align-items: center;
    gap: 3px;
    margin-bottom: 0.75rem;
}
.product-stars i {
    font-size: 0.7rem;
    color: var(--color-warm);
}
.product-stars .empty { color: var(--color-linen); }
.product-stars span {
    font-size: 0.72rem;
    color: var(--color-muted);
    margin-left: 4px;
}

.product-price {
    font-family: var(--font-body);
    font-size: 1rem;
    font-weight: 400;
    color: var(--color-amber);
    margin-bottom: auto;
    letter-spacing: 0.02em;
}

.product-price-label {
    font-size: 0.7rem;
    color: var(--color-muted);
    letter-spacing: 0.08em;
    text-transform: uppercase;
    display: block;
    margin-bottom: 0.2rem;
}

.btn-add-cart {
    display: block;
    width: 100%;
    margin-top: 1.25rem;
    padding: 0.75rem;
    background: var(--color-charcoal);
    color: var(--color-ivory);
    font-family: var(--font-body);
    font-size: 0.72rem;
    font-weight: 400;
    letter-spacing: 0.16em;
    text-transform: uppercase;
    border: 1px solid var(--color-charcoal);
    cursor: pointer;
    text-align: center;
    text-decoration: none;
    transition: background 0.3s ease, color 0.3s ease, border-color 0.3s ease;
}
.btn-add-cart:hover {
    background: var(--color-amber);
    border-color: var(--color-amber);
    color: var(--color-white);
}

/* ===== PHILOSOPHY SECTION ===== */
.philosophy-section {
    display: grid;
    grid-template-columns: 1fr 1fr;
    min-height: 560px;
}
@media (max-width: 768px) {
    .philosophy-section { grid-template-columns: 1fr; }
}

.philosophy-img-wrap {
    position: relative;
    overflow: hidden;
}
.philosophy-img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.9s var(--ease-out-expo);
    display: block;
}
.philosophy-img-wrap:hover img {
    transform: scale(1.04);
}

.philosophy-img-wrap::after {
    content: '';
    position: absolute;
    inset: 1.5rem;
    border: 1px solid rgba(200,169,122,0.3);
    pointer-events: none;
    transition: inset 0.5s ease;
}
.philosophy-img-wrap:hover::after {
    inset: 2rem;
}

.philosophy-content {
    background: var(--color-charcoal);
    padding: clamp(3rem, 7vw, 6rem) clamp(2rem, 6vw, 5rem);
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.philosophy-title {
    font-family: var(--font-display);
    font-size: clamp(2rem, 4vw, 3.2rem);
    font-weight: 300;
    color: var(--color-ivory);
    line-height: 1.15;
    margin-bottom: 1.5rem;
    letter-spacing: -0.01em;
}
.philosophy-title em {
    font-style: italic;
    color: var(--color-warm);
}

.philosophy-text {
    font-size: 0.9rem;
    font-weight: 300;
    line-height: 1.85;
    color: rgba(249,246,241,0.62);
    margin-bottom: 2.5rem;
    max-width: 440px;
}

.stats-row {
    display: flex;
    gap: 2.5rem;
    padding-top: 2rem;
    border-top: 1px solid rgba(200,169,122,0.2);
}

.stat-item {}
.stat-number {
    font-family: var(--font-display);
    font-size: 2.2rem;
    font-weight: 300;
    color: var(--color-warm);
    line-height: 1;
    display: block;
    margin-bottom: 0.3rem;
}
.stat-label {
    font-size: 0.68rem;
    font-weight: 400;
    letter-spacing: 0.16em;
    text-transform: uppercase;
    color: rgba(249,246,241,0.38);
}

/* ===== SECTION WRAPPERS ===== */
.section-pad {
    padding: clamp(4rem, 8vw, 7rem) clamp(1.25rem, 6vw, 5rem);
}
.bg-ivory  { background: var(--color-ivory); }
.bg-white  { background: var(--color-white); }
.bg-linen  { background: var(--color-linen); }

.section-header {
    margin-bottom: 3rem;
}
.section-header-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 3rem;
}

.link-subtle {
    font-size: 0.72rem;
    font-weight: 400;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: var(--color-amber);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    border-bottom: 1px solid transparent;
    padding-bottom: 2px;
    transition: border-color 0.3s ease, gap 0.3s ease;
}
.link-subtle::after {
    content: '→';
    transition: transform 0.3s ease;
}
.link-subtle:hover {
    border-color: var(--color-amber);
}
.link-subtle:hover::after {
    transform: translateX(4px);
}
</style>

<!-- ============================================================
     HERO SLIDER
============================================================ -->
<section class="hero-section">
    @php
        $slides = [
            [
                'image'    => 'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?q=80&w=2070&auto=format',
                'title'    => 'Temukan Ketenangan di Setiap Sudut Rumah',
                'subtitle' => 'Koleksi furnitur yang dikurasi khusus untuk menghadirkan harmoni antara estetika modern dan kenyamanan organik.',
            ],
            [
                'image'    => 'https://images.unsplash.com/photo-1583847268964-b28dc8f51f92?q=80&w=2070&auto=format',
                'title'    => 'Dibuat dengan Tangan, Dirancang untuk Jiwa',
                'subtitle' => 'Setiap potongan adalah hasil dedikasi pengrajin lokal dengan material berkelanjutan.',
            ],
            [
                'image'    => 'https://images.unsplash.com/photo-1560185009-5bf9f2849488?q=80&w=2070&auto=format',
                'title'    => 'Ruang Tamu yang Harmonis',
                'subtitle' => 'Kurasi furnitur modern dengan kenyamanan tak tertandingi untuk pusat rumah Anda.',
            ],
        ];
    @endphp

    @foreach($slides as $index => $slide)
    <div class="slide {{ $index == 0 ? 'active' : '' }}">
        <div class="slide-bg" style="background-image: url('{{ $slide['image'] }}');"></div>
        <div class="slide-overlay"></div>
        <div class="slide-content">
            <div class="slide-inner">
                <span class="slide-eyebrow">MyHome Collection</span>
                <h1 class="slide-title">{{ $slide['title'] }}</h1>
                <p class="slide-subtitle">{{ $slide['subtitle'] }}</p>
                <div class="hero-ctas">
                    <a href="{{ route('products.index') }}" class="btn-hero-primary">Jelajahi Koleksi</a>
                    <a href="#" class="btn-hero-ghost">Lihat Lookbook 2024</a>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <button id="prevSlide" class="hero-arrow hero-arrow-left" aria-label="Slide sebelumnya">
        <span class="arrow-line"></span>
    </button>
    <button id="nextSlide" class="hero-arrow hero-arrow-right" aria-label="Slide berikutnya">
        <span class="arrow-line"></span>
    </button>

    <div class="hero-dots">
        @foreach($slides as $index => $slide)
        <button class="hero-dot {{ $index == 0 ? 'active' : '' }}" data-index="{{ $index }}" aria-label="Slide {{ $index + 1 }}"></button>
        @endforeach
    </div>

    <div class="slide-counter">
        <span class="current">01</span> / 0{{ count($slides) }}
    </div>
</section>

<!-- ============================================================
     KATEGORI
============================================================ -->
<section class="section-pad bg-ivory">
    <div class="max-w-screen-xl mx-auto">
        <div class="section-header text-center reveal">
            <span class="section-label">Eksplorasi</span>
            <h2 class="section-title" style="font-size: clamp(2rem, 4vw, 3rem); max-width: 500px; margin: 0 auto 0.75rem;">Ruang yang Terinspirasi</h2>
            <p style="font-size: 0.9rem; color: var(--color-muted); max-width: 480px; margin: 0 auto; line-height: 1.8;">Pilih berdasarkan fungsi dan ciptakan ekosistem hidup yang selaras dengan gaya personal Anda.</p>
        </div>
        <div class="ornament-divider reveal"><span></span></div>

        @php
            $categories = [
                ['name' => 'Kamar Tidur',  'image' => 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?q=80&w=2071&auto=format',  'slug' => 'kamar-tidur'],
                ['name' => 'Ruang Tamu',   'image' => 'https://images.unsplash.com/photo-1560185009-5bf9f2849488?q=80&w=2070&auto=format',  'slug' => 'ruang-tamu'],
                ['name' => 'Ruang Makan',  'image' => 'https://images.unsplash.com/photo-1617806118233-18e1de247200?q=80&w=2070&auto=format', 'slug' => 'ruang-makan'],
                ['name' => 'Koleksi Baru', 'image' => 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?q=80&w=2070&auto=format', 'slug' => 'koleksi-baru'],
            ];
        @endphp

        <div class="categories-grid">
            @foreach($categories as $index => $cat)
            <a href="{{ route('products.category', $cat['slug']) }}"
               class="cat-card reveal d{{ $index + 1 }}">
                <img src="{{ $cat['image'] }}" alt="{{ $cat['name'] }}" class="cat-img" loading="lazy">
                <div class="cat-overlay"></div>
                <div class="cat-info">
                    <span class="cat-name">{{ $cat['name'] }}</span>
                    <span class="cat-cta">Jelajahi</span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

<!-- ============================================================
     PRODUK TERPOPULER
============================================================ -->
<section class="section-pad bg-white">
    <div class="max-w-screen-xl mx-auto">
        <div class="section-header-row reveal">
            <div>
                <span class="section-label">Pilihan Utama</span>
                <h2 class="section-title" style="font-size: clamp(1.8rem, 3.5vw, 2.6rem);">Produk Terpopuler</h2>
            </div>
            <a href="{{ route('products.index') }}" class="link-subtle">Semua Produk</a>
        </div>

        @php
            $placeholderImages = [
                'https://images.unsplash.com/photo-1567538096630-e0c55bd6374c?w=600&h=600&fit=crop',
                'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=600&h=600&fit=crop',
                'https://images.unsplash.com/photo-1583847268964-b28dc8f51f92?w=600&h=600&fit=crop',
                'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?w=600&h=600&fit=crop',
            ];
            $popularProducts = \App\Models\Product::where('is_active', true)->latest()->take(4)->get();
        @endphp

        <div class="products-grid">
            @forelse($popularProducts as $index => $product)
            <div class="product-card reveal d{{ ($index % 4) + 1 }}">
                <a href="{{ route('products.show', $product->slug) }}" class="product-img-wrap" tabindex="-1">
                    @if($index === 0)
                    <span class="product-badge">Terlaris</span>
                    @endif
                    @if($product->image && file_exists(public_path('storage/'.$product->image)))
                        <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" loading="lazy">
                    @else
                        <img src="{{ $placeholderImages[$index % count($placeholderImages)] }}" alt="Furniture" loading="lazy">
                    @endif
                </a>
                <div class="product-body">
                    <a href="{{ route('products.show', $product->slug) }}" style="text-decoration:none;">
                        <span class="product-name">{{ $product->name }}</span>
                    </a>

                    @php $rating = $product->average_rating ?? 4.5; @endphp
                    <div class="product-stars">
                        @for($i = 1; $i <= 5; $i++)
                        <i class="fa-solid fa-star {{ $i <= round($rating) ? '' : 'empty' }}"></i>
                        @endfor
                        <span>{{ number_format($rating, 1) }}</span>
                    </div>

                    <span class="product-price-label">Harga</span>
                    <span class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>

                    @auth
                    <form action="{{ route('cart.add', $product->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn-add-cart">Tambah ke Keranjang</button>
                    </form>
                    @else
                    <button onclick="showLoginAlert()" class="btn-add-cart">Tambah ke Keranjang</button>
                    @endauth
                </div>
            </div>
            @empty
            <div style="grid-column: 1/-1; text-align:center; padding: 4rem; color: var(--color-muted); font-size: 0.9rem; letter-spacing: 0.06em;">
                Belum ada produk tersedia.
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- ============================================================
     FILOSOFI KAMI
============================================================ -->
<section class="philosophy-section reveal">
    <div class="philosophy-img-wrap">
        <img src="https://images.unsplash.com/photo-1583847268964-b28dc8f51f92?q=80&w=2070&auto=format" alt="Pengrajin Sanctuari" loading="lazy">
    </div>
    <div class="philosophy-content">
        <span class="section-label" style="color: var(--color-warm);">Tentang Kami</span>
        <h2 class="philosophy-title">
            Dibuat dengan Tangan,<br>
            <em>Dirancang untuk Jiwa</em>
        </h2>
        <p class="philosophy-text">
            Setiap potongan adalah hasil dari dedikasi pengrajin lokal yang memadukan teknik tradisional dengan visi desain kontemporer. Kami menggunakan material berkelanjutan untuk menciptakan furnitur yang tidak hanya indah, tetapi juga bertanggung jawab.
        </p>
        <div class="stats-row">
            <div class="stat-item">
                <span class="stat-number">50<sup style="font-size:1rem; color: var(--color-warm);">+</sup></span>
                <span class="stat-label">Pengrajin Ahli</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">100%</span>
                <span class="stat-label">Material Lokal</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">5</span>
                <span class="stat-label">Tahun Garansi</span>
            </div>
        </div>
    </div>
</section>


<!-- ============================================================
     JAVASCRIPT
============================================================ -->
<script>
(function () {
    // ========== HERO SLIDER ==========
    const slides    = document.querySelectorAll('.slide');
    const dots      = document.querySelectorAll('.hero-dot');
    const counter   = document.querySelector('.slide-counter .current');
    const prevBtn   = document.getElementById('prevSlide');
    const nextBtn   = document.getElementById('nextSlide');
    let current     = 0;
    let timer;

    function pad(n) { return n < 10 ? '0' + n : '' + n; }

    function goTo(idx) {
        if (idx < 0) idx = slides.length - 1;
        if (idx >= slides.length) idx = 0;

        slides[current].classList.remove('active');
        dots[current].classList.remove('active');

        current = idx;

        slides[current].classList.add('active');
        dots[current].classList.add('active');
        if (counter) counter.textContent = pad(current + 1);

        // Re-trigger zoom on active slide bg
        const bg = slides[current].querySelector('.slide-bg');
        if (bg) {
            bg.style.animation = 'none';
            bg.offsetHeight;
            bg.style.animation = '';
        }
    }

    function next() { goTo(current + 1); resetTimer(); }
    function prev() { goTo(current - 1); resetTimer(); }

    function resetTimer() {
        clearInterval(timer);
        timer = setInterval(next, 5500);
    }

    if (prevBtn) prevBtn.addEventListener('click', prev);
    if (nextBtn) nextBtn.addEventListener('click', next);
    dots.forEach((dot, i) => dot.addEventListener('click', () => { goTo(i); resetTimer(); }));
    resetTimer();

    // ========== SCROLL REVEAL ==========
    const revealEls = document.querySelectorAll('.reveal');
    const io = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.classList.add('visible');
                io.unobserve(e.target);
            }
        });
    }, { threshold: 0.08, rootMargin: '0px 0px -30px 0px' });

    revealEls.forEach(el => io.observe(el));

    // Immediate check for elements already in view
    function checkVisible() {
        revealEls.forEach(el => {
            const r = el.getBoundingClientRect();
            if (r.top < window.innerHeight - 40) {
                el.classList.add('visible');
                io.unobserve(el);
            }
        });
    }
    window.addEventListener('load', checkVisible);
    window.addEventListener('resize', checkVisible);
})();

function showLoginAlert() {
    Swal.fire({
        title: 'Login Diperlukan',
        text: 'Kamu harus login terlebih dahulu untuk menambahkan produk ke keranjang.',
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Login Sekarang',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#92400e',
        cancelButtonColor: '#6b7280',
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '{{ route("login") }}';
        }
    });
}
</script>

@endsection