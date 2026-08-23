@extends('layouts.public')

@section('title', 'Crear Cuenta - VICOCELL')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-dark text-white text-center py-4 rounded-top-3">
                    <i class="bi bi-person-plus-fill text-info display-6 mb-2"></i>
                    <h4 class="fw-bold mb-0">Crea tu Cuenta</h4>
                    <small class="text-light opacity-75">Únete a VICOCELL y accede a seguimiento y beneficios</small>
                </div>
                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger py-2 small mb-3">
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('register.post') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Nombre Completo <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" placeholder="Ej: Juan Pérez" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Correo Electrónico <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="correo@ejemplo.com" required>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label fw-semibold">Teléfono / WhatsApp (Opcional)</label>
                            <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}" placeholder="Ej: 70012345">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Contraseña <span class="text-danger">*</span></label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Mínimo 6 caracteres" required>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label fw-semibold">Confirmar Contraseña <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Repite tu contraseña" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold shadow-sm mt-2">
                            <i class="bi bi-check-circle me-1"></i> Registrarme
                        </button>
                    </form>
                </div>
                <div class="card-footer bg-light text-center py-3">
                    <span class="small text-muted">¿Ya tienes una cuenta?</span>
                    <a href="{{ route('login') }}" class="small fw-bold text-decoration-none ms-1">Inicia sesión aquí</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
