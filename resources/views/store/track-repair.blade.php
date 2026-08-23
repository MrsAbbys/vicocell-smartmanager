@extends('layouts.public')

@section('title', 'Consultar Estado de Reparación - VICOCELL')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7 text-center mb-4">
            <h2 class="fw-bold text-dark"><i class="bi bi-tools text-primary me-2"></i>Rastreo de Servicio Técnico</h2>
            <p class="text-muted">Ingresa el código que figura en tu boleta de recepción para verificar el estado de tu equipo.</p>

            <form action="{{ route('store.track') }}" method="GET" class="mt-3">
                <div class="input-group input-group-lg shadow-sm">
                    <input type="text" name="code" class="form-control text-uppercase text-center fw-bold" placeholder="Ej: REP-A1B2C" value="{{ $code }}" required>
                    <button class="btn btn-primary px-4 fw-bold" type="submit">
                        <i class="bi bi-search me-1"></i> Consultar
                    </button>
                </div>
            </form>
        </div>

        @if($code)
            <div class="col-lg-8">
                @if($repair)
                    <div class="card shadow border-0 rounded-3 overflow-hidden">
                        <div class="card-header bg-dark text-white p-3 d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge bg-primary fs-6 me-2">{{ $repair->tracking_code }}</span>
                                <strong>{{ $repair->brand }} {{ $repair->model }}</strong>
                            </div>
                            <small class="text-light opacity-75">Ingreso: {{ $repair->created_at->format('d/m/Y') }}</small>
                        </div>
                        <div class="card-body p-4">
                            <!-- Indicador de Estado -->
                            <div class="text-center py-3 mb-4 bg-light rounded-3">
                                <span class="text-muted small text-uppercase d-block mb-1">Estado Actual del Dispositivo</span>
                                @php
                                    $statusColors = [
                                        'Recibido' => 'badge bg-secondary fs-5',
                                        'En Revision' => 'badge bg-info text-dark fs-5',
                                        'En Reparacion' => 'badge bg-warning text-dark fs-5',
                                        'Listo para Entrega' => 'badge bg-primary fs-5',
                                        'Entregado' => 'badge bg-success fs-5',
                                        'Cancelado' => 'badge bg-danger fs-5',
                                    ];
                                @endphp
                                <span class="{{ $statusColors[$repair->status] ?? 'badge bg-secondary fs-5' }} px-3 py-2">
                                    <i class="bi bi-info-circle me-1"></i> {{ $repair->status }}
                                </span>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-sm-6">
                                    <h6 class="fw-bold text-secondary text-uppercase small mb-1">Dispositivo</h6>
                                    <p class="mb-0">{{ $repair->device_type }} - {{ $repair->brand }} {{ $repair->model }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <h6 class="fw-bold text-secondary text-uppercase small mb-1">Fecha Prometida de Entrega</h6>
                                    <p class="mb-0">{{ $repair->promised_date ? $repair->promised_date->format('d/m/Y') : 'Por confirmar con técnico' }}</p>
                                </div>
                            </div>

                            <div class="mb-3">
                                <h6 class="fw-bold text-secondary text-uppercase small mb-1">Falla Reportada</h6>
                                <p class="bg-light p-2 rounded small text-dark mb-0">{{ $repair->reported_fault }}</p>
                            </div>

                            @if($repair->diagnosis)
                                <div class="mb-4">
                                    <h6 class="fw-bold text-secondary text-uppercase small mb-1">Diagnóstico del Laboratorio</h6>
                                    <p class="bg-light p-2 rounded small text-dark mb-0">{{ $repair->diagnosis }}</p>
                                </div>
                            @endif

                            <div class="card bg-light border-0 p-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">Costo Estimado:</span>
                                    <strong>Bs. {{ number_format($repair->estimated_cost, 2) }}</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">Anticipo Cancelado:</span>
                                    <strong class="text-success">- Bs. {{ number_format($repair->advance_payment, 2) }}</strong>
                                </div>
                                <hr class="my-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold fs-6">Saldo a Pagar al Retirar:</span>
                                    <span class="fw-bold fs-5 text-danger">Bs. {{ number_format($repair->balance, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning text-center shadow-sm py-4">
                        <i class="bi bi-exclamation-triangle display-6 d-block mb-2"></i>
                        <h5 class="fw-bold mb-1">No se encontró ninguna orden</h5>
                        <p class="small text-muted mb-0">No existe ningún servicio técnico asociado al código <strong>"{{ $code }}"</strong>. Por favor verifica los datos en tu comprobante.</p>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
