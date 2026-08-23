<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'VICOCELL - Tienda & Servicios Técnicos')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --vico-dark: #0f172a;
            --vico-blue: #0284c7;
        }
        body {
            background-color: #f1f5f9;
            color: #334155;
        }
        .public-navbar {
            background-color: var(--vico-dark);
        }
        .hero-banner {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);
            color: white;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark public-navbar sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('store.index') }}">
                <i class="bi bi-phone-vibrate text-info"></i> VICOCELL
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navPublic">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navPublic">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('store.index') ? 'active' : '' }}" href="{{ route('store.index') }}">
                            <i class="bi bi-grid-fill me-1"></i> Catálogo
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-2">
                    @auth
                        @if(Auth::user()->isAdmin() || Auth::user()->isTecnico())
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-warning">
                                <i class="bi bi-speedometer2 me-1"></i> Panel Admin
                            </a>
                        @endif

                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                <li class="dropdown-item-text text-muted small">
                                    Rol: <span class="badge bg-primary text-capitalize">{{ Auth::user()->role }}</span>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('customer.orders') }}">
                                        <i class="bi bi-bag-check me-1 text-primary"></i> Mis Compras y Pedidos
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-1"></i> Cerrar Sesión
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-sm btn-outline-light">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Iniciar Sesión
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-person-plus me-1"></i> Registrarse
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    @if(session('success'))
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <main>
        @yield('content')
    </main>

    <footer class="bg-dark text-white text-center py-4 mt-5 border-top border-secondary">
        <div class="container">
            <p class="mb-1 fw-bold">VICOCELL SmartManager</p>
            <p class="small text-muted mb-0">Venta de celulares, repuestos, accesorios y servicio técnico especializado.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
