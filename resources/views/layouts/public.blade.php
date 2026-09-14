<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'VICOCELL SmartManager')</title>

    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        /* ===================================================
           SISTEMA DE VARIABLES DE TEMA
        =================================================== */

        /* 1. MODO OSCURO (Neo-Dark Glassmorphism) */
        [data-bs-theme="dark"] {
            --bg-body: #060911;
            --bg-nav: #0c1220;
            --nav-text: #e2e8f0;
            --nav-text-hover: #38bdf8;
            --bg-card: #0f172a;
            --border-color: rgba(56, 189, 248, 0.15);
            --text-title: #f8fafc;
            --text-body: #94a3b8;
            --input-bg: #0b111e;
            --banner-bg: radial-gradient(circle at 50% 30%, #1e1b4b 0%, #0e1626 60%, #060911 100%);
            --banner-title: #ffffff;
            --banner-subtitle: #94a3b8;
        }

        /* 2. MODO CLARO (Fondo Blanco, Texto de Alto Contraste) */
        [data-bs-theme="light"] {
            --bg-body: #ffffff;
            --bg-nav: #f8fafc;
            --nav-text: #1e293b;
            --nav-text-hover: #0284c7;
            --bg-card: #ffffff;
            --border-color: #e2e8f0;
            --text-title: #0f172a;
            --text-body: #475569;
            --input-bg: #f8fafc;
            --banner-bg: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);
            --banner-title: #ffffff;
            --banner-subtitle: #cbd5e1;
        }

        body {
            background-color: var(--bg-body) !important;
            color: var(--text-body) !important;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: background-color 0.3s ease, color 0.3s ease;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        /* Navbar Dinámico */
        .custom-navbar {
            background-color: var(--bg-nav) !important;
            border-bottom: 1px solid var(--border-color);
        }

        .custom-navbar .nav-link,
        .custom-navbar .navbar-brand,
        .custom-navbar .dropdown-toggle {
            color: var(--nav-text) !important;
            font-weight: 500;
        }

        .custom-navbar .nav-link:hover,
        .custom-navbar .nav-link.active {
            color: var(--nav-text-hover) !important;
        }

        /* Hero Banner con texto siempre legible */
        .hero-banner {
            background: var(--banner-bg) !important;
            border-bottom: 1px solid var(--border-color);
        }

        .hero-banner h1 {
            color: var(--banner-title) !important;
            text-shadow: 0 2px 10px rgba(0,0,0,0.5);
        }

        .hero-banner p {
            color: var(--banner-subtitle) !important;
        }

        /* Tarjetas (Cards) */
        .card {
            background-color: var(--bg-card) !important;
            border: 1px solid var(--border-color) !important;
            color: var(--text-body) !important;
            border-radius: 12px;
        }

        .card-title, h1, h2, h3, h4, h5, h6 {
            color: var(--text-title) !important;
        }

        /* Inputs y Formularios */
        .form-control, .form-select, .input-group-text {
            background-color: var(--input-bg) !important;
            border-color: var(--border-color) !important;
            color: var(--text-title) !important;
        }

        .form-control::placeholder {
            color: var(--text-body) !important;
            opacity: 0.7;
        }

        /* Botón de Filtro / Acentos Neón */
        .btn-theme {
            background: linear-gradient(135deg, #0284c7, #06b6d4) !important;
            color: #ffffff !important;
            border: none !important;
            font-weight: 600;
        }

        .btn-theme:hover {
            box-shadow: 0 0 15px rgba(6, 182, 212, 0.4);
            color: #ffffff !important;
        }

        /* Dropdown */
        .dropdown-menu {
            background-color: var(--bg-card) !important;
            border: 1px solid var(--border-color) !important;
        }
        .dropdown-item {
            color: var(--text-title) !important;
        }
        .dropdown-item:hover {
            background-color: var(--input-bg) !important;
        }
        /* Corrección de tamaño para flechas de paginación */
.pagination svg {
    width: 16px !important;
    height: 16px !important;
}

nav[role="navigation"] svg {
    width: 18px !important;
    height: 18px !important;
}
    </style>
</head>
<body>

    <!-- Barra de Navegación -->
    <nav class="navbar navbar-expand-lg custom-navbar sticky-top py-2 shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ route('store.index') }}">
                <i class="bi bi-phone-vibrate-fill fs-4 me-2 text-info"></i>
                <span>VICOCELL</span>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
                <i class="bi bi-list fs-3" style="color: var(--nav-text);"></i>
            </button>

            <div class="collapse navbar-collapse" id="navContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('store.index') ? 'active fw-bold' : '' }}" href="{{ route('store.index') }}">
                            <i class="bi bi-grid-fill me-1"></i> Catálogo
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('store.track') ? 'active fw-bold text-warning' : '' }}" href="{{ route('store.track') }}">
                            <i class="bi bi-search me-1"></i> Seguir Reparación
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-2">
                    <!-- Selector de Modo Oscuro / Claro -->
                    <button class="btn btn-sm btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;" onclick="toggleTheme()" title="Cambiar tema">
                        <i id="themeIcon" class="bi bi-sun-fill text-warning"></i>
                    </button>

                    @auth
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle d-flex align-items-center" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1 text-info"></i> {{ Auth::user()->name }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                @if(in_array(Auth::user()->role, ['admin', 'tecnico']))
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Panel Administrativo</a></li>
                                    <li><hr class="dropdown-divider border-secondary opacity-25"></li>
                                @endif
                                <li><a class="dropdown-item" href="{{ route('customer.orders') }}"><i class="bi bi-bag-check me-2"></i>Mis Compras</a></li>
                                <li><hr class="dropdown-divider border-secondary opacity-25"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button class="dropdown-item text-danger fw-semibold" type="submit"><i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-box-arrow-in-right me-1"></i> Ingresar</a>
                        <a href="{{ route('register') }}" class="btn btn-theme btn-sm"><i class="bi bi-person-plus me-1"></i> Registrarse</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <main class="flex-grow-1">
        @if(session('success'))
            <div class="container mt-3">
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="container mt-3">
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="py-4 mt-5 text-center small border-top" style="border-color: var(--border-color) !important;">
        <div class="container">
            <p class="mb-0 text-muted">VICOCELL SmartManager &copy; 2026 — Soluciones Tecnológicas & Servicio Técnico Especializado</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script de Persistencia de Tema -->
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
    <!-- BOTÓN FLOTANTE DEL CHATBOT -->
<div class="position-fixed bottom-0 end-0 m-3 m-md-4" style="z-index: 1060;">
    <button class="btn btn-primary rounded-circle shadow-lg d-flex align-items-center justify-content-center p-0"
            style="width: 58px; height: 58px; background: linear-gradient(135deg, #0284c7, #06b6d4); border: none;"
            onclick="toggleAiChat()"
            title="Asistente Virtual VICOCELL">
        <i class="bi bi-robot fs-3 text-white"></i>
    </button>
</div>

<!-- VENTANA DEL CHATBOT -->
<div id="aiChatBox" class="card position-fixed bottom-0 end-0 m-3 m-md-4 shadow-lg d-none border-0"
     style="width: 350px; height: 480px; z-index: 1070; border-radius: 16px; overflow: hidden; background-color: var(--bg-card);">

    <!-- Encabezado -->
    <div class="card-header py-2 px-3 d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #0284c7, #06b6d4); color: #fff;">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-robot fs-4"></i>
            <div>
                <h6 class="mb-0 fw-bold small">Asistente Virtual VICOCELL</h6>
                <small style="font-size: 0.72rem; opacity: 0.9;">Recomendaciones & Taller IA</small>
            </div>
        </div>
        <button type="button" class="btn-close btn-close-white btn-sm" onclick="toggleAiChat()"></button>
    </div>

    <!-- Mensajes -->
    <div class="card-body p-3 overflow-auto small d-flex flex-column gap-2" id="aiChatMessages" style="height: 360px; background-color: var(--input-bg);">
        <div class="p-2 rounded align-self-start shadow-sm" style="max-width: 85%; background-color: var(--bg-card); border: 1px solid var(--border-color); color: var(--text-title);">
            👋 ¡Hola! Soy tu asistente de **VICOCELL**. ¿Buscas recomendación de algún celular según tu presupuesto, cotizar un repuesto o consultar tu reparación?
        </div>
    </div>

    <!-- Input -->
    <div class="card-footer p-2" style="background-color: var(--bg-card); border-top: 1px solid var(--border-color);">
        <form id="aiChatForm" onsubmit="sendAiMessage(event)" class="d-flex gap-1">
            <input type="text" id="aiUserInput" class="form-control form-control-sm" placeholder="Escribe tu consulta aquí..." autocomplete="off" required>
            <button class="btn btn-primary btn-sm px-3" type="submit" style="background: linear-gradient(135deg, #0284c7, #06b6d4); border: none;">
                <i class="bi bi-send-fill"></i>
            </button>
        </form>
    </div>
</div>

<script>
    function toggleAiChat() {
        const box = document.getElementById('aiChatBox');
        box.classList.toggle('d-none');
        if (!box.classList.contains('d-none')) {
            document.getElementById('aiUserInput').focus();
        }
    }

    async function sendAiMessage(e) {
        e.preventDefault();
        const input = document.getElementById('aiUserInput');
        const text = input.value.trim();
        if (!text) return;

        const chatBox = document.getElementById('aiChatMessages');

        // Mensaje del usuario
        const userMsg = document.createElement('div');
        userMsg.className = 'p-2 rounded text-white align-self-end shadow-sm';
        userMsg.style.cssText = 'max-width: 85%; background: linear-gradient(135deg, #0284c7, #06b6d4); word-wrap: break-word;';
        userMsg.textContent = text;
        chatBox.appendChild(userMsg);

        input.value = '';
        chatBox.scrollTop = chatBox.scrollHeight;

        // Indicador de "Escribiendo..."
        const typingMsg = document.createElement('div');
        typingMsg.className = 'p-2 rounded align-self-start shadow-sm text-muted fst-italic';
        typingMsg.style.cssText = 'max-width: 85%; background-color: var(--bg-card); border: 1px solid var(--border-color);';
        typingMsg.id = 'botTyping';
        typingMsg.innerHTML = '<i class="bi bi-three-dots"></i> Analizando consulta...';
        chatBox.appendChild(typingMsg);
        chatBox.scrollTop = chatBox.scrollHeight;

        try {
            const response = await fetch("{{ route('ai.chat') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ message: text })
            });

            const data = await response.json();
            const botWait = document.getElementById('botTyping');
            if (botWait) botWait.remove();

            // Mensaje del bot
            const botMsg = document.createElement('div');
            botMsg.className = 'p-2 rounded align-self-start shadow-sm';
            botMsg.style.cssText = 'max-width: 85%; background-color: var(--bg-card); border: 1px solid var(--border-color); color: var(--text-title); white-space: pre-line; word-wrap: break-word;';
            botMsg.innerHTML = data.reply.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
            chatBox.appendChild(botMsg);
            chatBox.scrollTop = chatBox.scrollHeight;

        } catch (err) {
            const botWait = document.getElementById('botTyping');
            if (botWait) botWait.remove();

            const errorMsg = document.createElement('div');
            errorMsg.className = 'p-2 rounded align-self-start shadow-sm text-danger';
            errorMsg.style.cssText = 'max-width: 85%; background-color: var(--bg-card); border: 1px solid var(--border-color);';
            errorMsg.textContent = 'Ocurrió un error al conectar con el servidor.';
            chatBox.appendChild(errorMsg);
        }
    }
</script>
</body>
</html>
