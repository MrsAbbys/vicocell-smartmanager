@extends('layouts.admin')

@section('title', 'Nueva Orden de Reparación - VICOCELL SmartManager')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col-md-6">
        <h3 class="fw-bold text-dark mb-0"><i class="bi bi-tools me-2 text-primary"></i>Recepción de Dispositivo</h3>
        <p class="text-muted small mb-0">Registra el ingreso de un equipo al taller de servicio técnico</p>
    </div>
    <div class="col-md-6 text-md-end mt-3 mt-md-0">
        <a href="{{ route('repairs.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver a Órdenes
        </a>
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger py-2 small mb-3">
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('repairs.store') }}" method="POST">
    @csrf
    <div class="row g-3">
        <!-- Panel Izquierdo: Datos del Dispositivo y Falla -->
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-phone me-1 text-primary"></i> Información del Dispositivo</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Código de Rastreo</label>
                            <input type="text" name="tracking_code" class="form-control fw-bold bg-light" value="{{ $trackingCode }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Tipo de Dispositivo <span class="text-danger">*</span></label>
                            <input type="text" name="device_type" class="form-control" value="{{ old('device_type', 'Celular') }}" placeholder="Ej: Celular, Tablet, Reloj" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Marca <span class="text-danger">*</span></label>
                            <input type="text" name="brand" class="form-control" value="{{ old('brand') }}" placeholder="Ej: Samsung, Xiaomi, Apple" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Modelo Comercial <span class="text-danger">*</span></label>
                            <input type="text" name="model" class="form-control" value="{{ old('model') }}" placeholder="Ej: Galaxy A54, iPhone 11" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small">IMEI / Nº de Serie / Contraseña de Desbloqueo (Opcional)</label>
                            <input type="text" name="imei_serial" class="form-control" value="{{ old('imei_serial') }}" placeholder="IMEI o patrón/código de desbloqueo">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small">Falla Reportada por el Cliente <span class="text-danger">*</span></label>
                            <textarea name="reported_fault" class="form-control" rows="3" placeholder="Describe el problema: pantalla rota, cambio de batería, no da señal..." required>{{ old('reported_fault') }}</textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small">Diagnóstico Inicial / Observaciones Físicas</label>
                            <textarea name="diagnosis" class="form-control" rows="2" placeholder="Detalles de golpes, rayones, piezas faltantes...">{{ old('diagnosis') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel Derecho: Cliente, Costos y Asignación -->
        <div class="col-lg-5">
            <!-- Datos del Cliente -->
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-person me-1 text-primary"></i> Datos del Cliente</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Cliente Registrado</label>
                        <select name="client_id" id="client_id" class="form-select">
                            <option value="">-- Cliente Nuevo / Mostrador --</option>
                            @foreach($clients as $cli)
                                <option value="{{ $cli->id }}" {{ old('client_id') == $cli->id ? 'selected' : '' }}>
                                    {{ $cli->name }} ({{ $cli->phone ?? 'Sin teléfono' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div id="quickClientSection">
                        <div class="mb-2">
                            <label class="form-label fw-semibold small">Nombre del Cliente <span class="text-danger">*</span></label>
                            <input type="text" name="client_name" id="client_name" class="form-control" value="{{ old('client_name') }}" placeholder="Ej: Mariana López">
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="form-label fw-semibold small">Teléfono / WhatsApp</label>
                                <input type="text" name="client_phone" class="form-control" value="{{ old('client_phone') }}" placeholder="Ej: 71234567">
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold small">CI / NIT</label>
                                <input type="text" name="client_ci" class="form-control" value="{{ old('client_ci') }}" placeholder="Ej: 6543210">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Costos y Planificación -->
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-cash-stack me-1 text-primary"></i> Costos y Asignación</h5>
                </div>
                <div class="card-body">
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold small">Costo Estimado (Bs.) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="estimated_cost" id="estimated_cost" class="form-control fw-bold" value="{{ old('estimated_cost', '0.00') }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold small">Anticipo (Bs.)</label>
                            <input type="number" step="0.01" name="advance_payment" id="advance_payment" class="form-control" value="{{ old('advance_payment', '0.00') }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Técnico Asignado</label>
                        <select name="technician_id" class="form-select">
                            <option value="">-- Sin asignar --</option>
                            @foreach($technicians as $tech)
                                <option value="{{ $tech->id }}" {{ old('technician_id') == $tech->id ? 'selected' : '' }}>
                                    {{ $tech->name }} ({{ ucfirst($tech->role) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold small">Estado Inicial</label>
                            <select name="status" class="form-select">
                                <option value="Recibido">Recibido</option>
                                <option value="En Revision">En Revisión</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold small">Fecha Prometida</label>
                            <input type="date" name="promised_date" class="form-control" value="{{ old('promised_date') }}">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm">
                        <i class="bi bi-check2-circle me-1"></i> Registrar Ingreso al Taller
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    const clientSelect = document.getElementById('client_id');
    const quickSection = document.getElementById('quickClientSection');

    clientSelect.addEventListener('change', function() {
        quickSection.style.display = this.value ? 'none' : 'block';
    });
</script>
@endsection
