<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - VICOCELL SmartManager')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --admin-dark: #0f172a;
            --admin-accent: #2563eb;
        }
        body {
            background-color: #f8fafc;
            color: #1e293b;
        }
        .admin-navbar {
            background-color: var(--admin-dark);
            border-bottom: 2px solid #334155;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark admin-navbar shadow-sm mb-4">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-bold text-white" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-gear-wide-connected text-primary me-1"></i> VICOCELL <span class="badge bg-secondary ms-1">Panel Interno</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navAdmin">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navAdmin">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-1"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('products.index') }}"><i class="bi bi-box-seam me-1"></i> Inventario</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    <span class="text-light small"><i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name ?? 'Administrador' }}</span>
                    <a href="{{ url('/') }}" target="_blank" class="btn btn-sm btn-outline-info" title="Ver Tienda">
                        <i class="bi bi-shop"></i> Tienda
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-box-arrow-right"></i> Salir</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="container-fluid px-4 py-2">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
