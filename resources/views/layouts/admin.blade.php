<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <title>@yield('title', 'Dashboard') — FurniHome Admin</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --sidebar-bg:     #1a3129;
            --sidebar-hover:  #243d33;
            --sidebar-active: #2d5040;
            --accent:         #4a7c5e;
            --accent-light:   #6aaa82;
            --text-primary:   #1a1a1a;
            --text-secondary: #6b7280;
            --text-muted:     #9ca3af;
            --bg-main:        #f5f5f5;
            --border:         #e5e7eb;
            --font: 'Segoe UI', system-ui, sans-serif;
        }

        body {
            font-family: var(--font);
            background: var(--bg-main);
            display: flex;
            min-height: 100vh;
            color: var(--text-primary);
        }

        /* Desktop: .main geser kanan sejauh lebar sidebar */
        .main {
             margin-left: 218px;
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        /* Mobile: .main full width, sidebar jadi overlay */
        @media (max-width: 767px) {
            .main {
                margin-left: 0 !important;
            }
        }

        .content {
            padding: 24px;
            flex: 1;
            min-width: 0;
            overflow-x: hidden;
        }

        /* ── GLOBAL COMPONENTS ── */
        .card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid var(--border);
        }

        .card-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
        }

        .card-title { font-size: 15px; font-weight: 700; }

        .badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
        .badge-green  { background: #dcf5e7; color: #27ae60; }
        .badge-orange { background: #fff3dc; color: #d4820a; }
        .badge-blue   { background: #dce8ff; color: #2563eb; }
        .badge-gray   { background: #f0f0f0; color: #6b7280; }
        .badge-red    { background: #ffe0e0; color: #e05c5c; }

        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 16px; border-radius: 8px;
            font-size: 13px; font-weight: 600; cursor: pointer;
            border: none; text-decoration: none; transition: opacity 0.15s;
        }
        .btn:hover { opacity: 0.88; }
        .btn-primary { background: var(--accent); color: #fff; }
        .btn-outline { background: #fff; color: var(--text-secondary); border: 1px solid var(--border); }
        .btn-danger  { background: #ffe0e0; color: #e05c5c; }

        table { width: 100%; border-collapse: collapse; }
        thead th {
            text-align: left; font-size: 12px; font-weight: 600;
            color: var(--text-muted); padding: 10px 20px;
            border-bottom: 1px solid var(--border); background: #fafafa;
        }
        tbody td { padding: 12px 20px; font-size: 13px; border-bottom: 1px solid #f3f4f6; }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover { background: #fafafa; }

        .action-btn {
            display: inline-flex; align-items: center; justify-content: center;
            width: 30px; height: 30px; border-radius: 8px;
            background: #f5f5f5; border: 1px solid var(--border);
            cursor: pointer; font-size: 13px; text-decoration: none;
            color: var(--text-secondary);
        }

        .page-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 20px;
        }
        .page-title h1 { font-size: 22px; font-weight: 700; }
        .page-title p  { font-size: 13px; color: var(--text-secondary); margin-top: 2px; }

        .link-btn { font-size: 12px; color: var(--accent); text-decoration: none; font-weight: 500; }

        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; }
        .form-control {
            width: 100%; padding: 9px 12px;
            border: 1px solid var(--border); border-radius: 8px;
            font-size: 13px; outline: none; font-family: var(--font);
            transition: border-color 0.15s;
        }
        .form-control:focus { border-color: var(--accent); }

        .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

        .search-filter {
            display: flex; align-items: center; gap: 10px;
            padding: 14px 20px; border-bottom: 1px solid var(--border);
        }
        .search-input {
            display: flex; align-items: center; gap: 8px;
            background: #f9fafb; border: 1px solid var(--border);
            border-radius: 8px; padding: 7px 12px; flex: 1; max-width: 300px;
        }
        .search-input input { border: none; background: transparent; outline: none; font-size: 13px; width: 100%; }

        .table-footer { padding: 14px 20px; display: flex; align-items: center; justify-content: space-between; border-top: 1px solid #f3f4f6; }
        .table-footer span { font-size: 12px; color: var(--text-muted); }

    </style>

    {{-- FIX: @yield('styles') dipindah ke LUAR <style> tag agar <style> dan <script>
         dari child view tidak nyangkut di dalam blok CSS dan gagal dieksekusi --}}
    @yield('styles')

</head>
<body>

    @include('admin.partials.sidebar')

    <div class="main">
        @include('admin.partials.topbar')

        <div class="content">
            @if(session('success'))
                <div style="background:#dcf5e7;color:#27ae60;padding:10px 16px;border-radius:8px;margin-bottom:16px;font-size:13px;">
                    ✅ {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div style="background:#ffe0e0;color:#e05c5c;padding:10px 16px;border-radius:8px;margin-bottom:16px;font-size:13px;">
                    ❌ {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </div>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>
</html>