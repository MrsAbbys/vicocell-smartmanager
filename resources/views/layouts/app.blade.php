<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'VICOCELL SmartManager')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --vico-primary: #0f172a;
            --vico-accent: #2563eb;
        }
        body {
            background-color: #f8fafc;
            color: #1e293b;
        }
        .navbar-custom {
            background-color: var(--vico-primary);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow-sm mb-4">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                <i class="bi bi-phone-fill text-primary"></i> VICOCELL <span class="fw-light fs-6 text-secondary">SmartManager</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMain">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}"><i class="bi bi-house-door"></i> Inicio</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-2">
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
