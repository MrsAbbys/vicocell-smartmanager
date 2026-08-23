@extends('layouts.admin')

@section('title', 'Actualizar Orden ' . $repair->tracking_code . ' - VICOCELL')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col-md-6">
        <h3 class="fw-bold text-dark mb-0"><i class="bi bi-pencil-square me-2 text-primary"></i>Actualizar Orden de Servicio</h3>
        <p class="text-muted small mb-0">Código: <span class="badge bg-primary">{{ $repair->tracking_code }}</span> | Cliente: <strong>{{ $repair->client->name }}</strong></p>
    </div>
    <div class="col-md-6 text-md-end mt-3 mt-md-0">
        <a href="{{ route('repairs.show', $repair) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Ver Comprobante
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

<form action="{{ route('repairs.update', $repair) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row g-3">
        <!-- Panel Izquierdo: Diagnóstico y Estado Técnico -->
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-gear-wide-connected me-1 text-primary"></i> Estado Técnico y Trabajo Realizado</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Estado de la Reparación <span class="text-danger">*</span></label>
                            <select name="status" class="form-select fw-bold border-primary" required>
                                <option value="Recibido" {{ old('status', $repair->status) == 'Recibido' ? 'selected' : '' }}>Recibido</option>
                                <option value="En Revision" {{ old('status', $repair->status) == 'En Revision' ? 'selected' : '' }}>En Revisión</option>
                                <option value="En Reparacion" {{ old('status', $repair->status) == 'En Reparacion' ? 'selected' : '' }}>En Reparación</option>
                                <option value="Listo para Entrega" {{ old('status', $repair->status) == 'Listo para Entrega' ? 'selected' : '' }}>Listo para Entrega</option>
                                <option value="Entregado" {{ old('status', $repair->status) == 'Entregado' ? 'selected' : '' }}>Entregado</option>
                                <option value="Cancelado" {{ old('status', $repair->status) == 'Cancelado' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Técnico Asignado</label>
                            <select name="technician_id" class="form-select">
                                <option value="">-- Sin asignar --</option>
                                @foreach($technicians as $tech)
                                    <option value="{{ $tech->id }}" {{ old('technician_id', $repair->technician_id) == $tech->id ? 'selected' : '' }}>
                                        {{ $tech->name }} ({{ ucfirst($tech->role) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small">Diagnóstico Técnico y Solución Aplicada</label>
                            <textarea name="diagnosis" class="form-control" rows="4" placeholder="Detalla los componentes cambiados, pruebas efectuadas o motivo de imposibilidad de reparación...">{{ old('diagnosis', $repair->diagnosis) }}</textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small">Falla Reportada Original</label>
                            <textarea name="reported_fault" class="form-control bg-light" rows="2" required>{{ old('reported_fault', $repair->reported_fault) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel Derecho: Datos del Equipo y Liquidación de Pagos -->
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-phone me-1 text-primary"></i> Datos del Equipo</h5>
                </div>
                <div class="card-body">
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label fw-semibold small">Tipo Dispositivo</label>
                            <input type="text" name="device_type" class="form-control form-control-sm" value="{{ old('device_type', $repair->device_type) }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold small">Marca</label>
                            <input type="text" name="brand" class="form-control form-control-sm" value="{{ old('brand', $repair->brand) }}" required>
                        </div>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label fw-semibold small">Modelo</label>
                            <input type="text" name="model" class="form-control form-control-sm" value="{{ old('model', $repair->model) }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold small">IMEI / Serie</label>
                            <input type="text" name="imei_serial" class="form-control form-control-sm" value="{{ old('imei_serial', $repair->imei_serial) }}">
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Fecha Prometida de Entrega</label>
                        <input type="date" name="promised_date" class="form-control form-control-sm" value="{{ old('promised_date', optional($repair->promised_date)->format('Y-m-d')) }}">
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-cash-coin me-1 text-primary"></i> Liquidación de Costos</h5>
                </div>
                <div class="card-body">
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold small">Costo Total (Bs.)</label>
                            <input type="number" step="0.01" name="estimated_cost" class="form-control fw-bold text-primary" value="{{ old('estimated_cost', $repair->estimated_cost) }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold small">Anticipo / Pagado (Bs.)</label>
                            <input type="number" step="0.01" name="advance_payment" class="form-control text-success fw-bold" value="{{ old('advance_payment', $repair->advance_payment) }}" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm">
                        <i class="bi bi-check2-circle me-1"></i> Guardar Cambios
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
