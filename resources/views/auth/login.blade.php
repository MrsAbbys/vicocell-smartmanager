@extends('layouts.public')

@section('title', 'Iniciar Sesión - VICOCELL')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-dark text-white text-center py-4 rounded-top-3">
                    <i class="bi bi-person-circle text-primary display-6 mb-2"></i>
                    <h4 class="fw-bold mb-0">Iniciar Sesión</h4>
                    <small class="text-light opacity-75">Accede a tu cuenta de cliente o administración</small>
                </div>
                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger py-2 small mb-3">
                            <i class="bi bi-exclamation-triangle me-1"></i> {{ $errors->first() }}
                        </div>
                    @endif

                    <form action="{{ route('login.post') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Correo Electrónico</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="correo@ejemplo.com" required autofocus>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-key"></i></span>
                                <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label small text-muted" for="remember">Recordar sesión</label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold shadow-sm">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Ingresar
                        </button>
                    </form>
                </div>
                <div class="card-footer bg-light text-center py-3">
                    <span class="small text-muted">¿No tienes cuenta?</span>
                    <a href="{{ route('register') }}" class="small fw-bold text-decoration-none ms-1">Regístrate gratis</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
