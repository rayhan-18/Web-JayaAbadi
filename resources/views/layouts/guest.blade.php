<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sanctuari')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: #F8FAFC;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .split-container {
            display: flex;
            width: 100%;
            max-width: 1280px;
            height: 90vh;
            background: white;
            border-radius: 2rem;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        .split-left {
            flex: 1;
            background: linear-gradient(135deg, #1E1B2E 0%, #2D2A3E 100%);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem;
            position: relative;
            overflow: hidden;
        }
        .split-left::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
            pointer-events: none;
        }
        .split-left h1 {
            font-size: 3rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            margin-bottom: 1rem;
        }
        .split-left p {
            font-size: 1rem;
            line-height: 1.6;
            opacity: 0.8;
            max-width: 80%;
        }
        .split-right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2.5rem;
            background: white;
        }
        .auth-card {
            width: 100%;
            max-width: 400px;
        }
        .input-group {
            position: relative;
            margin-bottom: 1.25rem;
        }
        .input-group label {
            display: block;
            font-size: 0.75rem;
            font-weight: 500;
            color: #334155;
            margin-bottom: 0.25rem;
        }
        .input-group input {
            width: 100%;
            padding: 0.75rem 0;
            border: none;
            border-bottom: 1.5px solid #E2E8F0;
            font-size: 0.875rem;
            outline: none;
            transition: border-color 0.2s;
            background: transparent;
        }
        .input-group input:focus {
            border-bottom-color: #F59E0B;
        }
        .input-group .toggle-password {
            position: absolute;
            right: 0;
            bottom: 0.75rem;
            cursor: pointer;
            color: #94A3B8;
            font-size: 0.9rem;
        }
        .input-group .toggle-password:hover {
            color: #F59E0B;
        }
        .auth-btn {
            width: 100%;
            background: #0F172A;
            color: white;
            padding: 0.75rem;
            border: none;
            border-radius: 9999px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 0.5rem;
        }
        .auth-btn:hover {
            background: #F59E0B;
        }
        .strength-bars {
            display: flex;
            gap: 4px;
            margin-top: 6px;
        }
        .strength-bar {
            height: 3px;
            flex: 1;
            border-radius: 2px;
            background: #E2E8F0;
            transition: background 0.2s;
        }
        .auth-footer {
            text-align: center;
            font-size: 0.8rem;
            color: #64748B;
            margin-top: 1.5rem;
        }
        .auth-footer a {
            color: #F59E0B;
            font-weight: 500;
            text-decoration: none;
        }
        .invalid-feedback {
            color: #EF4444;
            font-size: 0.7rem;
            margin-top: 0.25rem;
        }
        @media (max-width: 768px) {
            .split-left { display: none; }
            .split-right { flex: 1; }
            .split-container { width: 90%; height: auto; border-radius: 1.5rem; }
        }
    </style>
</head>
<body>
    <div class="split-container">
        <div class="split-left">
            <h1>Sanctuari</h1>
            <p>Menciptakan ruang tenang melalui desain furnitur yang berkelanjutan dan estetika modern.</p>
            <div style="margin-top: 2rem;">
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                    <span style="display: inline-block; width: 8px; height: 8px; background: #F59E0B; border-radius: 50%;"></span>
                    <span style="font-size: 0.875rem;">+50 Pengrajin Ahli</span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <span style="display: inline-block; width: 8px; height: 8px; background: #F59E0B; border-radius: 50%;"></span>
                    <span style="font-size: 0.875rem;">100% Material Lokal</span>
                </div>
            </div>
        </div>
        <div class="split-right">
            @yield('content')
        </div>
    </div>
</body>
</html>