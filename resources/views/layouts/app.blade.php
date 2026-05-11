<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Taquilla CoMotor')</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --sidebar-w: 240px;
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --sidebar-active: #2563eb;
            --accent: #3b82f6;
            --text-muted: #94a3b8;
            --topbar-h: 60px;
        }

        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: #f1f5f9;
            color: #1e293b;
            display: flex;
            min-height: 100vh;
        }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            z-index: 100;
            transition: width 0.25s ease;
            overflow: hidden;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 20px 18px;
            border-bottom: 1px solid #1e293b;
            text-decoration: none;
        }
        .sidebar-brand .brand-icon {
            width: 36px; height: 36px;
            background: var(--accent);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .sidebar-brand .brand-icon svg { width: 20px; height: 20px; fill: #fff; }
        .sidebar-brand .brand-text {
            font-size: 0.95rem;
            font-weight: 700;
            color: #f8fafc;
            white-space: nowrap;
            line-height: 1.2;
        }
        .sidebar-brand .brand-sub {
            font-size: 0.7rem;
            color: var(--text-muted);
            font-weight: 400;
        }

        .sidebar-section {
            padding: 16px 12px 4px;
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--text-muted);
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            margin: 2px 8px;
            border-radius: 8px;
            text-decoration: none;
            color: #cbd5e1;
            font-size: 0.875rem;
            font-weight: 500;
            transition: background 0.15s, color 0.15s;
            white-space: nowrap;
        }
        .nav-item svg { width: 18px; height: 18px; flex-shrink: 0; opacity: 0.8; }
        .nav-item:hover { background: var(--sidebar-hover); color: #f8fafc; }
        .nav-item:hover svg { opacity: 1; }
        .nav-item.active {
            background: var(--sidebar-active);
            color: #fff;
        }
        .nav-item.active svg { opacity: 1; }

        .sidebar-footer {
            margin-top: auto;
            padding: 12px 8px;
            border-top: 1px solid #1e293b;
        }

        /* ── Topbar ── */
        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-w);
            right: 0;
            height: var(--topbar-h);
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            z-index: 90;
        }
        .topbar-title {
            font-size: 1.05rem;
            font-weight: 600;
            color: #0f172a;
        }
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .user-chip {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .user-avatar {
            width: 34px; height: 34px;
            background: var(--accent);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.8rem;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }
        .user-name {
            font-size: 0.875rem;
            font-weight: 600;
            color: #1e293b;
        }
        .btn-logout {
            display: flex;
            align-items: center;
            gap: 6px;
            background: #fee2e2;
            color: #b91c1c;
            border: none;
            padding: 7px 14px;
            border-radius: 7px;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-logout svg { width: 14px; height: 14px; }
        .btn-logout:hover { background: #fecaca; }

        /* ── Main content ── */
        .page-wrapper {
            margin-left: var(--sidebar-w);
            padding-top: var(--topbar-h);
            flex: 1;
            min-width: 0;
        }
        .main-content {
            padding: 28px;
            max-width: 1200px;
        }

        /* ── Alerts ── */
        .alert-success {
            background: #dcfce7;
            color: #166534;
            border-left: 4px solid #22c55e;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.875rem;
        }
        .alert-error ul { padding-left: 16px; margin-top: 6px; }

        /* ── Tabla genérica ── */
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.07);
        }
        th, td { padding: 11px 16px; text-align: left; font-size: 0.875rem; }
        th {
            background: #f8fafc;
            color: #475569;
            font-weight: 600;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            border-bottom: 1px solid #e2e8f0;
        }
        td { border-bottom: 1px solid #f1f5f9; color: #334155; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #f8fafc; }
    </style>
    @stack('styles')
</head>
<body>

    {{-- ── SIDEBAR ── --}}
    <aside class="sidebar">
        <a href="{{ route('viajes.index') }}" class="sidebar-brand">
            <div class="brand-icon">
                <svg viewBox="0 0 24 24"><path d="M4 16c0 1.1.9 2 2 2h12a2 2 0 0 0 2-2V8l-4-4H6a2 2 0 0 0-2 2v10zm10-9 3 3h-3V7zM7 9h5v2H7V9zm0 4h10v2H7v-2z"/></svg>
            </div>
            <div>
                <div class="brand-text">CoMotor</div>
                <div class="brand-sub">Sistema de Taquilla</div>
            </div>
        </a>

        <div class="sidebar-section">Gestión</div>

        @can('viajes.ver')
        <a href="{{ route('viajes.index') }}"
        class="nav-item {{ request()->routeIs('viajes.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17 8C8 10 5.9 16.17 3.82 21L5.71 22l1-2.3A4.49 4.49 0 0 0 8 20C19 20 22 3 22 3c-1 2-8 2-8 2s0-1 3-3zm-2.71 8.15-1.29-1.29A3.93 3.93 0 0 1 15 12a4 4 0 0 1-8 0 4 4 0 0 1 4-4 3.93 3.93 0 0 1 2.86 1.29L12.57 10.6A2 2 0 0 0 11 10a2 2 0 0 0 0 4 2 2 0 0 0 1.57-.69z"/></svg>
            Viajes
        </a>
        @endcan

        @can('tiquetes.ver')
        <a href="{{ route('tiquetes.index') }}"
        class="nav-item {{ request()->routeIs('tiquetes.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M22 10V6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v4c1.1 0 2 .9 2 2s-.9 2-2 2v4c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-4c-1.1 0-2-.9-2-2s.9-2 2-2zm-2-1.46A4 4 0 0 0 18 12a4 4 0 0 0 2 3.46V18H4v-2.54A4 4 0 0 0 6 12a4 4 0 0 0-2-3.46V6h16v2.54zM11 15h2v2h-2zm0-4h2v2h-2zm0-4h2v2h-2z"/></svg>
            Tiquetes
        </a>
        @endcan

        @can('rutas.ver')
        <a href="{{ route('rutas.index') }}"
        class="nav-item {{ request()->routeIs('rutas.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M13.49 5.48c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm-3.6 13.9 1-4.4 2.1 2v6h2v-7.5l-2.1-2 .6-3c1.3 1.5 3.3 2.5 5.5 2.5v-2c-1.9 0-3.5-1-4.3-2.4l-1-1.6c-.4-.6-1-1-1.7-1-.3 0-.5.1-.8.1l-5.2 2.2v4.7h2v-3.4l1.8-.7-1.6 8.1-4.9-1-.4 2 7 1.4z"/></svg>
            Rutas
        </a>
        @endcan

        <div class="sidebar-section">Flota</div>

        @can('buses.ver')
        <a href="{{ route('buses.index') }}"
        class="nav-item {{ request()->routeIs('buses.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M4 16c0 1.1.9 2 2 2h12a2 2 0 0 0 2-2V7H4v9zm10-7h2v2h-2V9zm0 4h2v2h-2v-2zm-4-4h2v2h-2V9zm0 4h2v2h-2v-2zm-4-4h2v2H6V9zm0 4h2v2H6v-2zM20 4H4L2 7h20l-2-3z"/></svg>
            Buses
        </a>
        @endcan

        @can('tipos_bus.ver')
        <a href="{{ route('tipos-bus.index') }}"
        class="nav-item {{ request()->routeIs('tipos-bus.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
            Tipos de Bus
        </a>
        @endcan

        <div class="sidebar-section">Reportes</div>

        @can('reportes.ver')
        <a href="{{ route('reportes.index') }}"
        class="nav-item {{ request()->routeIs('reportes.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
            Reportes
        </a>
        @endcan

        <div class="sidebar-section">Personal</div>

        @can('conductores.ver')
        <a href="{{ route('conductores.index') }}"
        class="nav-item {{ request()->routeIs('conductores.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
            Conductores
        </a>
        @endcan

        @can('propietarios.ver')
        <a href="{{ route('propietarios.index') }}"
        class="nav-item {{ request()->routeIs('propietarios.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
            Propietarios
        </a>
        @endcan

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout" style="width:100%; justify-content:center;">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/></svg>
                    Cerrar sesión
                </button>
            </form>
        </div>
    </aside>

    {{-- ── TOPBAR ── --}}
    <header class="topbar">
        <span class="topbar-title">@yield('page-title', 'Panel de Control')</span>
        <div class="topbar-right">
            <div class="user-chip">
                <div class="user-avatar">
                    {{ strtoupper(substr(Auth::user()->nombre, 0, 1)) }}
                </div>
                <span class="user-name">{{ Auth::user()->nombre }}</span>
            </div>
        </div>
    </header>

    {{-- ── CONTENT ── --}}
    <div class="page-wrapper">
        <div class="main-content">

            @if(session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert-error">{{ session('error') }}</div>
            @endif

            @if($errors->any())
                <div class="alert-error">
                    <strong>Corrige los siguientes errores:</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

@stack('scripts')
</body>
</html>
