<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Sunset Bridge</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --sb-primary: #C17D3C;
            --sb-primary-dark: #8B4513;
            --sb-primary-light: #E8A96A;
            --sb-bg: #F8F9FB;
            --sb-sidebar-bg: #FFFFFF;
            --sb-text: #1A1A1A;
            --sb-text-muted: #6B6B6B;
            --sb-border: #E8ECF0;
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

        /* SIDEBAR */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: 220px;
            height: 100vh;
            background: var(--sb-sidebar-bg);
            border-right: 1px solid var(--sb-border);
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar-brand {
            padding: 18px 16px 14px;
            border-bottom: 1px solid var(--sb-border);
        }

        .brand-logo { display: flex; align-items: center; gap: 9px; text-decoration: none; }

        .brand-icon {
            width: 36px; height: 36px;
            background: var(--sb-primary);
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 15px;
        }

        .brand-name { font-size: 13px; font-weight: 700; color: var(--sb-text); }
        .brand-sub  { font-size: 10px; color: var(--sb-text-muted); }
        .brand-badge {
            display: inline-block;
            background: #2C1810;
            color: #E8A96A;
            font-size: 9px; font-weight: 700;
            padding: 2px 6px; border-radius: 4px;
            letter-spacing: 0.3px; margin-top: 3px;
        }

        .sidebar-section {
            padding: 14px 14px 3px;
            font-size: 10px; color: var(--sb-text-muted);
            text-transform: uppercase; letter-spacing: 1px; font-weight: 600;
        }

        .sidebar-nav { padding: 3px 8px; }
        .nav-item-sb { list-style: none; margin: 1px 0; }

        .nav-link-sb {
            display: flex; align-items: center; gap: 9px;
            padding: 9px 10px;
            border-radius: 8px;
            color: var(--sb-text-muted);
            text-decoration: none;
            font-size: 13px; font-weight: 500;
            transition: all 0.2s;
        }

        .nav-link-sb:hover { background: #F5F3F0; color: var(--sb-text); }
        .nav-link-sb.active { background: #F5EDE4; color: var(--sb-primary); font-weight: 600; }
        .nav-link-sb i { width: 16px; font-size: 13px; text-align: center; }

        /* MAIN */
        .main-content { margin-left: 220px; min-height: 100vh; display: flex; flex-direction: column; }

        .topnav {
            background: var(--sb-white);
            border-bottom: 1px solid var(--sb-border);
            padding: 0 24px;
            height: 60px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 100;
        }

        .topnav-title { font-size: 16px; font-weight: 700; }

        .topnav-right { display: flex; align-items: center; gap: 12px; }

        .btn-leave {
            background: var(--sb-primary);
            color: white;
            border: none; border-radius: 8px;
            padding: 7px 14px;
            font-size: 13px; font-weight: 600;
            cursor: pointer; font-family: inherit;
            display: flex; align-items: center; gap: 6px;
            text-decoration: none;
        }

        .btn-leave:hover { background: var(--sb-primary-dark); color: white; }

        .user-chip {
            display: flex; align-items: center; gap: 8px;
            padding: 5px 10px;
            border-radius: 8px;
            border: 1px solid var(--sb-border);
            cursor: pointer;
        }

        .user-avatar-sm {
            width: 28px; height: 28px;
            border-radius: 7px;
            background: var(--sb-primary);
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 11px; font-weight: 700;
        }

        .user-name-sm { font-size: 12.5px; font-weight: 600; }
        .user-role-sm { font-size: 10.5px; color: var(--sb-text-muted); }

        /* PAGE */
        .page-content { padding: 22px; flex: 1; }

        /* CARDS */
        .card-sb {
            background: white;
            border: 1px solid var(--sb-border);
            border-radius: 14px;
            padding: 20px;
        }

        .stat-grid {
            display: grid; grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .stat-card-emp {
            background: white;
            border: 1px solid var(--sb-border);
            border-radius: 12px;
            padding: 16px;
        }

        .stat-value { font-size: 28px; font-weight: 800; line-height: 1; }
        .stat-label { font-size: 12px; color: var(--sb-text-muted); margin-top: 3px; }
        .stat-sub   { font-size: 11px; color: var(--sb-text-muted); margin-top: 2px; }

        /* TABLE */
        .table-emp thead th {
            background: #F8F9FB; color: var(--sb-text-muted);
            font-size: 11.5px; font-weight: 600;
            border: none; padding: 10px 12px; text-transform: uppercase; letter-spacing: 0.5px;
        }

        .table-emp tbody td {
            font-size: 13px; padding: 10px 12px;
            vertical-align: middle;
            border-bottom: 1px solid #F0F2F5;
        }

        /* STATUS */
        .badge-status {
            display: inline-flex; align-items: center;
            padding: 4px 10px; border-radius: 20px;
            font-size: 11.5px; font-weight: 600;
        }

        .badge-hadir    { background: #E8F5EE; color: #2D7A51; }
        .badge-terlambat{ background: #FEF3E2; color: #D4860A; }
        .badge-izin     { background: #E3F0FF; color: #1A6B8A; }
        .badge-alpha    { background: #FDEAEA; color: #C0392B; }
        .badge-pending  { background: #FEF3E2; color: #D4860A; }
        .badge-approved { background: #E8F5EE; color: #2D7A51; }
        .badge-rejected { background: #FDEAEA; color: #C0392B; }

        /* ABSEN BUTTONS */
        .btn-absen-masuk {
            width: 100%; padding: 12px;
            border: 1.5px dashed #C8D0D8; border-radius: 10px;
            background: #F8F9FB; color: var(--sb-text-muted);
            font-size: 14px; font-weight: 600;
            cursor: pointer; font-family: inherit;
            transition: all 0.2s;
        }

        .btn-absen-masuk:hover { border-color: var(--sb-primary); color: var(--sb-primary); }
        .btn-absen-masuk.done { background: #E8F5EE; border-color: #2D7A51; color: #2D7A51; border-style: solid; cursor: default; }

        .btn-absen-pulang {
            width: 100%; padding: 12px;
            border: none; border-radius: 10px;
            background: var(--sb-danger); color: white;
            font-size: 14px; font-weight: 700;
            cursor: pointer; font-family: inherit;
            transition: all 0.2s;
        }

        .btn-absen-pulang:hover { background: #A93226; }
        .btn-absen-pulang:disabled { background: #C8D0D8; cursor: not-allowed; }
        .btn-absen-pulang.done { background: #5B4E72; }

        /* ALERTS */
        .alert-sb {
            border-radius: 10px; padding: 11px 15px;
            font-size: 13px; font-weight: 500;
            display: flex; align-items: center; gap: 9px;
            margin-bottom: 14px;
        }
        .alert-success-sb { background: #E8F5EE; color: #2D7A51; }
        .alert-error-sb   { background: #FDEAEA; color: #C0392B; }

        /* FORMS */
        .form-control-sb {
            border: 1.5px solid var(--sb-border);
            border-radius: 9px; padding: 9px 13px;
            font-size: 13.5px; font-family: inherit;
            width: 100%; transition: border-color 0.2s;
        }
        .form-control-sb:focus { border-color: var(--sb-primary); outline: none; box-shadow: 0 0 0 3px rgba(193,125,60,0.1); }
        .form-select-sb { appearance: auto; }
        .form-label-sb { font-size: 13px; font-weight: 600; margin-bottom: 5px; display: block; }

        .btn-primary-emp {
            background: var(--sb-primary); color: white;
            border: none; border-radius: 9px; padding: 10px 20px;
            font-size: 13.5px; font-weight: 600; cursor: pointer; font-family: inherit;
            transition: all 0.2s; display: inline-flex; align-items: center; gap: 7px; text-decoration: none;
        }
        .btn-primary-emp:hover { background: var(--sb-primary-dark); color: white; }
        .btn-outline-emp {
            background: transparent; color: var(--sb-text-muted);
            border: 1.5px solid var(--sb-border); border-radius: 9px; padding: 9px 16px;
            font-size: 13px; font-weight: 500; cursor: pointer; font-family: inherit;
            transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px; text-decoration: none;
        }
        .btn-outline-emp:hover { border-color: var(--sb-text); color: var(--sb-text); }

        /* CALENDAR */
        .cal-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px; }
        .cal-day-header { text-align: center; font-size: 11px; font-weight: 700; color: var(--sb-text-muted); padding: 6px 0; }
        .cal-day {
            aspect-ratio: 1; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 500;
            position: relative; cursor: default;
        }
        .cal-day.has-shift { font-weight: 700; }
        .cal-day.shift-pagi  { background: #DBEAFE; color: #1E40AF; }
        .cal-day.shift-siang { background: #FEF9C3; color: #92400E; }
        .cal-day.shift-libur { background: #FEE2E2; color: #B91C1C; }
        .cal-day.today { ring: 2px solid var(--sb-primary); box-shadow: 0 0 0 2px var(--sb-primary); }
        .cal-day.empty { background: transparent; }

        @media (max-width: 768px) {
            .sidebar { display: none; }
            .main-content { margin-left: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-brand">
        <a href="{{ route('employee.dashboard') }}" class="brand-logo">
            <div class="brand-icon"><i class="fa-solid fa-mug-hot"></i></div>
            <div>
                <div class="brand-name">Sunset Bridge</div>
                <div class="brand-sub">Coffee & Eatry</div>
                <div class="brand-badge">End To End System</div>
            </div>
        </a>
    </div>

    <div class="sidebar-section">Utama</div>
    <ul class="sidebar-nav">
        <li class="nav-item-sb">
            <a href="{{ route('employee.dashboard') }}" class="nav-link-sb {{ request()->routeIs('employee.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-house"></i> Dashboard
            </a>
        </li>
    </ul>

    <div class="sidebar-section">Menu</div>
    <ul class="sidebar-nav">
        <li class="nav-item-sb">
            <a href="{{ route('employee.schedules.index') }}" class="nav-link-sb {{ request()->routeIs('employee.schedules*') ? 'active' : '' }}">
                <i class="fa-solid fa-calendar-days"></i> Jadwal Kerja
            </a>
        </li>
        <li class="nav-item-sb">
            <a href="{{ route('employee.leaves.index') }}" class="nav-link-sb {{ request()->routeIs('employee.leaves*') ? 'active' : '' }}">
                <i class="fa-solid fa-file-circle-check"></i> Riwayat Izin
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

<div class="main-content">
    <nav class="topnav">
        <div class="topnav-title">@yield('page-title', 'Dashboard')</div>
        <div class="topnav-right">
            <a href="{{ route('employee.leaves.create') }}" class="btn-leave">
                <i class="fa-solid fa-plus"></i> Ajukan Izin
            </a>
            <div class="user-chip">
                <div class="user-avatar-sm">{{ substr(auth()->user()->name, 0, 2) }}</div>
                <div>
                    <div class="user-name-sm">{{ auth()->user()->employee->name ?? auth()->user()->name }}</div>
                    <div class="user-role-sm">{{ auth()->user()->employee->position ?? 'Karyawan' }}</div>
                </div>
            </div>
        </div>
    </nav>

    <div class="px-3 pt-3">
        @if(session('success'))
            <div class="alert-sb alert-success-sb">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert-sb alert-error-sb">
                <i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}
            </div>
        @endif
        @if($errors->any())
            <div class="alert-sb alert-error-sb">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <div>@foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>
            </div>
        @endif
    </div>

    <main class="page-content">
        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
