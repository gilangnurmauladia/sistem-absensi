<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Sunset Bridge Admin</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --sb-primary: #C17D3C;
            --sb-primary-dark: #8B4513;
            --sb-primary-light: #E8A96A;
            --sb-accent: #F5A623;
            --sb-bg: #FAF7F2;
            --sb-sidebar: #2C1810;
            --sb-sidebar-hover: #3D2316;
            --sb-text: #1A1A1A;
            --sb-text-muted: #6B6B6B;
            --sb-border: #E8E0D5;
            --sb-white: #FFFFFF;
            --sb-success: #2D7A51;
            --sb-warning: #D4860A;
            --sb-danger: #C0392B;
            --sb-info: #1A6B8A;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--sb-bg);
            color: var(--sb-text);
            min-height: 100vh;
        }

        /* === SIDEBAR === */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: 240px;
            height: 100vh;
            background: var(--sb-sidebar);
            z-index: 1000;
            overflow-y: auto;
            transition: transform 0.3s ease;
        }

        .sidebar-brand {
            padding: 20px 20px 16px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .sidebar-brand .brand-logo {
            display: flex; align-items: center; gap: 10px;
            text-decoration: none;
        }

        .sidebar-brand .brand-icon {
            width: 38px; height: 38px;
            background: var(--sb-primary);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 16px; color: white;
        }

        .sidebar-brand .brand-name {
            font-size: 14px; font-weight: 700;
            color: white; line-height: 1.2;
        }

        .sidebar-brand .brand-sub {
            font-size: 10px; color: var(--sb-primary-light);
            font-weight: 400; letter-spacing: 0.5px;
        }

        .sidebar-badge {
            display: inline-block;
            background: var(--sb-primary);
            color: white;
            font-size: 9px;
            font-weight: 600;
            padding: 2px 6px;
            border-radius: 4px;
            letter-spacing: 0.3px;
            margin-top: 4px;
        }

        .sidebar-section {
            padding: 16px 12px 4px;
            font-size: 10px;
            color: rgba(255,255,255,0.35);
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }

        .sidebar-nav { padding: 4px 8px; }

        .nav-item-sb {
            list-style: none;
            margin: 2px 0;
        }

        .nav-link-sb {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 8px;
            color: rgba(255,255,255,0.65);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .nav-link-sb:hover, .nav-link-sb.active {
            background: var(--sb-sidebar-hover);
            color: white;
        }

        .nav-link-sb.active {
            background: var(--sb-primary) !important;
            color: white;
        }

        .nav-link-sb i {
            width: 18px;
            font-size: 14px;
            text-align: center;
        }

        /* === MAIN CONTENT === */
        .main-content {
            margin-left: 240px;
            min-height: 100vh;
            display: flex; flex-direction: column;
        }

        /* === TOP NAV === */
        .topnav {
            background: var(--sb-white);
            border-bottom: 1px solid var(--sb-border);
            padding: 0 24px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topnav-left {
            display: flex; align-items: center; gap: 12px;
        }

        .topnav-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--sb-text);
        }

        .topnav-right {
            display: flex; align-items: center; gap: 16px;
        }

        .topnav-bell {
            width: 38px; height: 38px;
            border-radius: 10px;
            background: var(--sb-bg);
            border: 1px solid var(--sb-border);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            color: var(--sb-text-muted);
            transition: all 0.2s;
            text-decoration: none;
            position: relative;
        }

        .topnav-bell:hover { background: var(--sb-border); color: var(--sb-text); }

        .bell-badge {
            position: absolute;
            top: -4px; right: -4px;
            background: var(--sb-danger);
            color: white;
            font-size: 9px;
            font-weight: 700;
            width: 16px; height: 16px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
        }

        .user-info {
            display: flex; align-items: center; gap: 10px;
        }

        .user-avatar {
            width: 36px; height: 36px;
            border-radius: 10px;
            background: var(--sb-primary);
            display: flex; align-items: center; justify-content: center;
            color: white;
            font-size: 13px;
            font-weight: 700;
        }

        .user-name {
            font-size: 13px; font-weight: 600; color: var(--sb-text);
        }

        .user-role {
            font-size: 11px; color: var(--sb-text-muted);
        }

        /* === PAGE CONTENT === */
        .page-content { padding: 24px; flex: 1; }

        .page-header {
            margin-bottom: 20px;
        }

        .page-header h1 {
            font-size: 22px;
            font-weight: 800;
            color: var(--sb-text);
            margin-bottom: 2px;
        }

        .page-header p {
            font-size: 13px;
            color: var(--sb-text-muted);
        }

        /* === CARDS === */
        .card-sb {
            background: white;
            border: 1px solid var(--sb-border);
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }

        .stat-card {
            background: white;
            border: 1px solid var(--sb-border);
            border-radius: 14px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        }

        .stat-icon {
            width: 50px; height: 50px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .stat-icon.green  { background: #E8F5EE; color: var(--sb-success); }
        .stat-icon.amber  { background: #FEF3E2; color: var(--sb-warning); }
        .stat-icon.red    { background: #FDEAEA; color: var(--sb-danger); }
        .stat-icon.blue   { background: #E3F0FF; color: var(--sb-info); }
        .stat-icon.brown  { background: #F5EDE4; color: var(--sb-primary); }

        .stat-value {
            font-size: 26px; font-weight: 800; color: var(--sb-text);
            line-height: 1;
        }

        .stat-label {
            font-size: 12.5px; color: var(--sb-text-muted);
            margin-top: 3px;
        }

        /* === TABLE === */
        .table-sb thead th {
            background: #F5EDE4;
            color: var(--sb-primary-dark);
            font-weight: 600;
            font-size: 12.5px;
            border: none;
            padding: 12px 14px;
        }

        .table-sb tbody td {
            font-size: 13px;
            padding: 12px 14px;
            vertical-align: middle;
            border-bottom: 1px solid var(--sb-border);
            border-left: none;
            border-right: none;
            border-top: none;
        }

        .table-sb tbody tr:hover { background: #FAF5F0; }

        /* === STATUS BADGES === */
        .badge-hadir    { background: #E8F5EE; color: #2D7A51; }
        .badge-terlambat{ background: #FEF3E2; color: #D4860A; }
        .badge-izin     { background: #E3F0FF; color: #1A6B8A; }
        .badge-alpha    { background: #FDEAEA; color: #C0392B; }
        .badge-libur    { background: #F0EEF5; color: #5B4E72; }
        .badge-pending  { background: #FEF3E2; color: #D4860A; }
        .badge-approved { background: #E8F5EE; color: #2D7A51; }
        .badge-rejected { background: #FDEAEA; color: #C0392B; }

        .status-badge {
            display: inline-flex; align-items: center;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11.5px;
            font-weight: 600;
        }

        /* Shift color chips */
        .shift-pagi  { background: #DBEAFE; color: #1E40AF; }
        .shift-siang { background: #FEF9C3; color: #92400E; }
        .shift-libur { background: #FEE2E2; color: #B91C1C; }
        .shift-chip {
            display: inline-flex; align-items: center; justify-content: center;
            width: 28px; height: 22px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
        }

        /* === FORMS === */
        .form-control-sb, .form-select-sb {
            border: 1.5px solid var(--sb-border);
            border-radius: 9px;
            padding: 9px 13px;
            font-size: 13.5px;
            font-family: inherit;
            background: white;
            color: var(--sb-text);
            transition: border-color 0.2s;
            width: 100%;
        }

        .form-control-sb:focus, .form-select-sb:focus {
            border-color: var(--sb-primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(193,125,60,0.12);
        }

        .form-label-sb {
            font-size: 13px;
            font-weight: 600;
            color: var(--sb-text);
            margin-bottom: 6px;
            display: block;
        }

        /* === BUTTONS === */
        .btn-primary-sb {
            background: var(--sb-primary);
            color: white;
            border: none;
            border-radius: 9px;
            padding: 9px 18px;
            font-size: 13.5px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            font-family: inherit;
            display: inline-flex; align-items: center; gap: 7px;
            text-decoration: none;
        }

        .btn-primary-sb:hover {
            background: var(--sb-primary-dark);
            color: white;
            transform: translateY(-1px);
        }

        .btn-outline-sb {
            background: transparent;
            color: var(--sb-primary);
            border: 1.5px solid var(--sb-primary);
            border-radius: 9px;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            font-family: inherit;
            display: inline-flex; align-items: center; gap: 6px;
            text-decoration: none;
        }

        .btn-outline-sb:hover {
            background: var(--sb-primary);
            color: white;
        }

        .btn-danger-sb {
            background: var(--sb-danger);
            color: white;
            border: none;
            border-radius: 9px;
            padding: 8px 14px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            font-family: inherit;
            display: inline-flex; align-items: center; gap: 6px;
            text-decoration: none;
        }

        .btn-danger-sb:hover { background: #A93226; color: white; }

        /* === ALERTS === */
        .alert-sb {
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 13.5px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            border: none;
        }

        .alert-success-sb { background: #E8F5EE; color: #2D7A51; }
        .alert-error-sb   { background: #FDEAEA; color: #C0392B; }
        .alert-info-sb    { background: #E3F0FF; color: #1A6B8A; }
        .alert-warning-sb { background: #FEF3E2; color: #D4860A; }

        /* === RESPONSIVE === */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }

        /* === SCROLLBAR === */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #D4C4B0; border-radius: 10px; }
    </style>
    @stack('styles')
</head>
<body>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <a href="{{ route('admin.dashboard') }}" class="brand-logo">
            <div class="brand-icon"><i class="fa-solid fa-mug-hot"></i></div>
            <div>
                <div class="brand-name">Sunset Bridge</div>
                <div class="brand-sub">Coffee & Eatry</div>
                <div class="sidebar-badge">Admin Panel</div>
            </div>
        </a>
    </div>

    <div class="sidebar-section">Utama</div>
    <ul class="sidebar-nav">
        <li class="nav-item-sb">
            <a href="{{ route('admin.dashboard') }}" class="nav-link-sb {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-gauge"></i> Dashboard
            </a>
        </li>
    </ul>

    <div class="sidebar-section">Manajemen</div>
    <ul class="sidebar-nav">
        <li class="nav-item-sb">
            <a href="{{ route('admin.employees.index') }}" class="nav-link-sb {{ request()->routeIs('admin.employees*') ? 'active' : '' }}">
                <i class="fa-solid fa-users"></i> Data Karyawan
            </a>
        </li>
        <li class="nav-item-sb">
            <a href="{{ route('admin.schedules.index') }}" class="nav-link-sb {{ request()->routeIs('admin.schedules*') ? 'active' : '' }}">
                <i class="fa-solid fa-calendar-days"></i> Jadwal Shift
            </a>
        </li>
        <li class="nav-item-sb">
            <a href="{{ route('admin.attendances.index') }}" class="nav-link-sb {{ request()->routeIs('admin.attendances*') ? 'active' : '' }}">
                <i class="fa-solid fa-clock-rotate-left"></i> Absensi
            </a>
        </li>
        <li class="nav-item-sb">
            <a href="{{ route('admin.performances.index') }}" class="nav-link-sb {{ request()->routeIs('admin.performances*') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-bar"></i> Penilaian Kinerja
            </a>
        </li>
        <li class="nav-item-sb">
            <a href="{{ route('admin.leaves.index') }}" class="nav-link-sb {{ request()->routeIs('admin.leaves*') ? 'active' : '' }}">
                <i class="fa-solid fa-file-circle-check"></i> Manajemen Izin
                @php $pendingLeaves = \App\Models\Leave::where('status','pending')->count(); @endphp
                @if($pendingLeaves > 0)
                    <span class="ms-auto badge bg-danger rounded-pill" style="font-size:10px;">{{ $pendingLeaves }}</span>
                @endif
            </a>
        </li>
    </ul>

    <div class="sidebar-section">Akun</div>
    <ul class="sidebar-nav">
        <li class="nav-item-sb">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-link-sb w-100 border-0" style="cursor:pointer; background:none; text-align:left;">
                    <i class="fa-solid fa-right-from-bracket"></i> Keluar
                </button>
            </form>
        </li>
    </ul>
</aside>

<!-- Main Content -->
<div class="main-content">
    <!-- Top Nav -->
    <nav class="topnav">
        <div class="topnav-left">
            <button class="topnav-bell d-md-none" onclick="toggleSidebar()">
                <i class="fa-solid fa-bars"></i>
            </button>
            <span class="topnav-title">@yield('page-title', 'Dashboard')</span>
        </div>
        <div class="topnav-right">
            <a href="{{ route('admin.leaves.index') }}" class="topnav-bell" title="Notifikasi Izin">
                <i class="fa-regular fa-bell"></i>
                @if(isset($pendingLeaves) && $pendingLeaves > 0)
                    <span class="bell-badge">{{ $pendingLeaves }}</span>
                @endif
            </a>
            <div class="user-info">
                <div class="user-avatar">{{ substr(auth()->user()->name, 0, 2) }}</div>
                <div>
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-role">Administrator</div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <div class="px-4 pt-3">
        @if(session('success'))
            <div class="alert-sb alert-success-sb mb-0">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert-sb alert-error-sb mb-0">
                <i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}
            </div>
        @endif
        @if($errors->any())
            <div class="alert-sb alert-error-sb mb-0">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <div>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Page Content -->
    <main class="page-content">
        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('open');
    }
</script>
@stack('scripts')
</body>
</html>
