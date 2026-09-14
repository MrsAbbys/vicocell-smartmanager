<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - VICOCELL SmartManager')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Chart.js para el Dashboard -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* MODO OSCURO (Default) */
        [data-bs-theme="dark"] {
            --bg-body-custom: #080c16;
            --bg-surface-custom: #0e1626;
            --bg-card-custom: #111a2e;
            --border-custom: rgba(56, 189, 248, 0.12);
            --text-main-custom: #f1f5f9;
            --text-muted-custom: #94a3b8;
            --nav-text-color: #f1f5f9;
            --nav-bg: #0b111e;
        }

        /* MODO CLARO */
        [data-bs-theme="light"] {
            --bg-body-custom: #f8fafc;
            --bg-surface-custom: #ffffff;
            --bg-card-custom: #ffffff;
            --border-custom: #e2e8f0;
            --text-main-custom: #0f172a;
            --text-muted-custom: #64748b;
            --nav-text-color: #0f172a;
            --nav-bg: #ffffff;
        }

        body {
            background-color: var(--bg-body-custom) !important;
            color: var(--text-main-custom) !important;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            transition: background-color 0.3s, color 0.3s;
        }

        /* Navbar & Sidebar adaptables */
        .admin-navbar {
            background-color: var(--nav-bg) !important;
            border-bottom: 1px solid var(--border-custom);
        }

        /* Forzar color de texto adaptativo en enlaces de navegación */
        .admin-navbar .nav-link, .admin-navbar .navbar-brand {
            color: var(--nav-text-color) !important;
        }

        .card {
            background-color: var(--bg-card-custom) !important;
            border-color: var(--border-custom) !important;
            color: var(--text-main-custom) !important;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .card-header, .table th {
            background-color: var(--bg-surface-custom) !important;
            color: var(--text-main-custom) !important;
            border-bottom-color: var(--border-custom) !important;
        }

        .table td {
            color: var(--text-main-custom) !important;
            border-bottom-color: var(--border-custom) !important;
        }

        .form-control, .form-select {
            background-color: var(--bg-surface-custom) !important;
            color: var(--text-main-custom) !important;
            border-color: var(--border-custom) !important;
        }

        /* Limitar tamaño de flechas e iconos en paginación */
        .pagination svg {
            width: 16px !important;
            height: 16px !important;
        }

        nav[role="navigation"] svg {
            width: 18px !important;
            height: 18px !important;
        }

        /* Estilos limpios para la barra de paginación */
        .pagination {
            gap: 4px;
        }

        .pagination .page-link {
            background-color: var(--bg-surface-custom) !important;
            border-color: var(--border-custom) !important;
            color: var(--text-main-custom) !important;
            border-radius: 6px !important;
            padding: 6px 12px;
        }

        .pagination .page-item.active .page-link {
            background-color: #2563eb !important;
            border-color: #2563eb !important;
            color: #ffffff !important;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg admin-navbar sticky-top py-2 shadow-sm">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-shield-lock-fill text-primary me-2 fs-4"></i> Panel Admin
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminMenu">
                <i class="bi bi-list fs-3" style="color: var(--nav-text-color);"></i>
            </button>

            <div class="collapse navbar-collapse" id="adminMenu">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-3">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'fw-bold text-primary' : '' }}" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2 me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('products.*') ? 'fw-bold text-primary' : '' }}" href="{{ route('products.index') }}">
                            <i class="bi bi-box-seam me-1"></i> Inventario
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('sales.*') ? 'fw-bold text-primary' : '' }}" href="{{ route('sales.index') }}">
                            <i class="bi bi-cart-check me-1"></i> Ventas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('repairs.*') ? 'fw-bold text-primary' : '' }}" href="{{ route('repairs.index') }}">
                            <i class="bi bi-tools me-1"></i> Taller
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('reports.*') ? 'fw-bold text-primary' : '' }}" href="{{ route('reports.index') }}">
                            <i class="bi bi-file-earmark-bar-graph me-1"></i> Reportes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('store.index') }}" target="_blank">
                            <i class="bi bi-shop me-1 text-success"></i> Ver Tienda
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-sm btn-outline-secondary rounded-circle" style="width: 35px; height: 35px;" onclick="toggleTheme()">
                        <i id="themeIcon" class="bi bi-sun-fill text-warning"></i>
                    </button>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary border-0 dropdown-toggle" type="button" data-bs-toggle="dropdown" style="color: var(--nav-text-color);">
                            <i class="bi bi-person-circle me-1 text-primary"></i> {{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item text-danger fw-bold" type="submit"><i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="container-fluid py-4 px-lg-5">
        @if(session('success'))
            <div class="alert alert-success shadow-sm border-0"><i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}</div>
        @endif
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const savedTheme = localStorage.getItem('vicocell-theme') || 'dark';
        document.documentElement.setAttribute('data-bs-theme', savedTheme);

        function toggleTheme() {
            const currentTheme = document.documentElement.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('vicocell-theme', newTheme);
            updateThemeIcon(newTheme);
        }

        function updateThemeIcon(theme) {
            const icon = document.getElementById('themeIcon');
            if (icon) {
                icon.className = theme === 'dark' ? 'bi bi-sun-fill text-warning' : 'bi bi-moon-stars-fill text-primary';
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            updateThemeIcon(document.documentElement.getAttribute('data-bs-theme'));
        });
    </script>
</body>
</html>
