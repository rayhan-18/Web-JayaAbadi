{{-- guest.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Jaya Abadi')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <style>
        :root {
            --amber: #F59E0B;
            --amber-light: #FBBF24;
            --amber-dark: #D97706;
            --primary: #1A1625;
            --primary-light: #2C2541;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #0F172A;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .split-container {
            display: flex;
            width: 100%;
            max-width: 1280px;
            min-height: 90vh;
            max-height: 95vh;
            background: white;
            border-radius: 2.5rem;
            overflow: hidden;
            box-shadow: 0 40px 80px -20px rgba(0, 0, 0, 0.6);
            position: relative;
        }

        /* ===== LEFT PANEL ===== */
        .split-left {
            flex: 1.2;
            background: linear-gradient(160deg, #1A1625 0%, #2C2541 50%, #3D2E5C 100%);
            color: white;
            padding: 4rem 3.5rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .split-left::before {
            content: '';
            position: absolute;
            top: -30%;
            right: -20%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(245, 158, 11, 0.08) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .split-left::after {
            content: '';
            position: absolute;
            bottom: -20%;
            left: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(245, 158, 11, 0.05) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .left-header {
            position: relative;
            z-index: 1;
        }

        .brand-logo {
            font-family: 'Playfair Display', serif;
            font-size: 2.8rem;
            font-weight: 700;
            color: #E8C58F;
            letter-spacing: 1px;
        }

        .brand-tagline {
            font-size: 1.6rem;
            font-weight: 300;
            color: rgba(255, 255, 255, 0.9);
            margin-top: 0.5rem;
            line-height: 1.4;
        }

        .brand-tagline strong {
            color: #E8C58F;
            font-weight: 600;
        }

        .left-middle {
            position: relative;
            z-index: 1;
            margin: 2rem 0;
        }

        .left-middle p {
            font-size: 1.05rem;
            line-height: 1.8;
            opacity: 0.85;
            max-width: 90%;
            color: rgba(255, 255, 255, 0.8);
        }

        .trust-badges {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.8rem 1.5rem;
            margin-top: 1.5rem;
            position: relative;
            z-index: 1;
        }

        .badge {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.85);
            padding: 0.5rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        .badge i {
            color: var(--amber);
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }

        .left-footer {
            position: relative;
            z-index: 1;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .left-footer .rating {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .left-footer .stars {
            color: var(--amber);
            letter-spacing: 2px;
            font-size: 1.1rem;
        }

        .left-footer .rating-text {
            font-size: 0.9rem;
            opacity: 0.7;
        }

        /* ===== RIGHT PANEL ===== */
        .split-right {
            flex: 1;
            background: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2.5rem 3rem;
            position: relative;
        }

        .auth-card {
            width: 100%;
            max-width: 400px;
        }

        .auth-card .auth-header {
            margin-bottom: 2.5rem;
            text-align: center;
        }

        .auth-card .auth-header .icon-wrapper {
            width: 64px;
            height: 64px;
            margin: 0 auto 1.2rem;
            background: linear-gradient(135deg, #FEF3C7, #FDE68A);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            box-shadow: 0 8px 24px rgba(245, 158, 11, 0.2);
        }

        .auth-card .auth-header h2 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #0F172A;
            letter-spacing: -0.5px;
        }

        .auth-card .auth-header p {
            color: #64748B;
            font-size: 0.95rem;
            margin-top: 0.25rem;
        }

        /* ===== INPUT STYLES ===== */
        .input-group {
            margin-bottom: 1.5rem;
        }

        .input-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 0.4rem;
            letter-spacing: 0.3px;
        }

        .input-group label .required {
            color: #EF4444;
            margin-left: 2px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper input {
            width: 100%;
            padding: 0.9rem 1.25rem;
            border: 2px solid #E2E8F0;
            border-radius: 14px;
            font-size: 0.95rem;
            background: #F8FAFC;
            transition: all 0.3s ease;
            color: #0F172A;
            font-family: 'Inter', sans-serif;
        }

        .input-wrapper input:focus {
            border-color: var(--amber);
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.15);
            background: #FFFFFF;
            outline: none;
        }

        .input-wrapper input::placeholder {
            color: #94A3B8;
            font-weight: 400;
        }

        .input-wrapper input.input-error {
            border-color: #EF4444;
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
        }

        .input-wrapper .input-icon {
            position: absolute;
            left: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94A3B8;
            font-size: 1rem;
            pointer-events: none;
        }

        .input-wrapper .input-icon ~ input {
            padding-left: 3.2rem;
        }

        .toggle-password {
            position: absolute;
            right: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94A3B8;
            cursor: pointer;
            background: none;
            border: none;
            padding: 0;
            font-size: 1rem;
            transition: color 0.2s;
        }

        .toggle-password:hover {
            color: #475569;
        }

        .input-error-text {
            color: #EF4444;
            font-size: 0.8rem;
            margin-top: 0.35rem;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* ===== PASSWORD STRENGTH ===== */
        .strength-container {
            margin-top: 0.6rem;
        }

        .strength-bars {
            display: flex;
            gap: 4px;
        }

        .strength-bar {
            flex: 1;
            height: 4px;
            background: #E2E8F0;
            border-radius: 4px;
            transition: background 0.3s ease;
        }

        .strength-text {
            font-size: 0.7rem;
            color: #94A3B8;
            margin-top: 0.25rem;
            font-weight: 500;
        }

        /* ===== CHECKBOX ===== */
        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.75rem;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .form-options .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            font-size: 0.9rem;
            color: #475569;
        }

        .form-options .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--amber);
            border-radius: 4px;
            cursor: pointer;
        }

        .form-options .forgot-link {
            font-size: 0.9rem;
            color: var(--amber-dark);
            font-weight: 500;
            text-decoration: none;
            transition: color 0.2s;
        }

        .form-options .forgot-link:hover {
            color: var(--amber);
            text-decoration: underline;
        }

        /* ===== BUTTON ===== */
        .auth-btn {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, var(--amber), var(--amber-dark));
            color: white;
            border: none;
            border-radius: 14px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 8px 24px rgba(245, 158, 11, 0.35);
            transition: all 0.3s ease;
            font-family: 'Inter', sans-serif;
            letter-spacing: 0.3px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }

        .auth-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(245, 158, 11, 0.45);
        }

        .auth-btn:active {
            transform: translateY(0);
        }

        .auth-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .auth-footer {
            text-align: center;
            margin-top: 1.75rem;
            font-size: 0.9rem;
            color: #64748B;
        }

        .auth-footer a {
            color: var(--amber-dark);
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s;
        }

        .auth-footer a:hover {
            color: var(--amber);
            text-decoration: underline;
        }

        /* ===== DIVIDER ===== */
        .divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 1.5rem 0;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #E2E8F0;
        }

        .divider span {
            color: #94A3B8;
            font-size: 0.8rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* ===== SOCIAL BUTTONS ===== */
        .social-btns {
            display: flex;
            gap: 0.75rem;
        }

        .social-btn {
            flex: 1;
            padding: 0.7rem;
            border: 2px solid #E2E8F0;
            border-radius: 12px;
            background: white;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-size: 0.85rem;
            font-weight: 500;
            color: #334155;
            font-family: 'Inter', sans-serif;
        }

        .social-btn:hover {
            border-color: var(--amber);
            background: #FFFBEB;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.1);
        }

        .social-btn i {
            font-size: 1.1rem;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1024px) {
            .split-left {
                padding: 3rem 2.5rem;
            }
            .brand-logo { font-size: 2.2rem; }
            .brand-tagline { font-size: 1.3rem; }
            .trust-badges { grid-template-columns: 1fr; }
        }

        @media (max-width: 820px) {
            .split-container {
                flex-direction: column;
                max-height: none;
                min-height: auto;
                border-radius: 1.5rem;
            }

            .split-left {
                padding: 2.5rem 2rem;
                min-height: 280px;
            }

            .split-left::before,
            .split-left::after {
                display: none;
            }

            .split-right {
                padding: 2rem 1.5rem;
            }

            .auth-card { max-width: 100%; }

            .trust-badges {
                grid-template-columns: 1fr 1fr;
            }

            .left-footer .rating {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.25rem;
            }
        }

        @media (max-width: 480px) {
            body { padding: 0.75rem; }
            .split-container { border-radius: 1rem; }
            .split-left { padding: 1.75rem 1.25rem; min-height: 220px; }
            .brand-logo { font-size: 1.8rem; }
            .brand-tagline { font-size: 1rem; }
            .trust-badges { grid-template-columns: 1fr; gap: 0.3rem; }
            .split-right { padding: 1.5rem 1rem; }
            .auth-card .auth-header h2 { font-size: 1.4rem; }
            .input-wrapper input { padding: 0.75rem 1rem; font-size: 0.9rem; }
            .form-options { flex-direction: column; align-items: flex-start; }
            .social-btns { flex-direction: column; }
        }
    </style>
</head>
<body>
    <div class="split-container">
        <!-- LEFT PANEL -->
        <div class="split-left">
            <div class="left-header">
                <div class="brand-logo">Jaya Abadi</div>
                <div class="brand-tagline">
                    Desain yang Menenangkan,<br>
                    <strong>Ruang yang Bermakna</strong>
                </div>
            </div>

            <div class="left-middle">
                <p>Furnitur berkelanjutan dengan sentuhan estetika modern untuk rumah impian Anda.</p>
                <div class="trust-badges">
                    <div class="badge"><i class="fa-solid fa-leaf"></i> Material Ramah Lingkungan</div>
                    <div class="badge"><i class="fa-solid fa-hands-holding-circle"></i> Pengrajin Lokal</div>
                    <div class="badge"><i class="fa-solid fa-award"></i> Garansi 5 Tahun</div>
                    <div class="badge"><i class="fa-solid fa-truck-fast"></i> Gratis Ongkir</div>
                </div>
            </div>

            <div class="left-footer">
                <div class="rating">
                    <div class="stars">★★★★★</div>
                    <span class="rating-text">4.9 dari 1.200+ ulasan</span>
                </div>
            </div>
        </div>

        <!-- RIGHT PANEL -->
        <div class="split-right">
            @yield('content')
        </div>
    </div>
</body>
</html>