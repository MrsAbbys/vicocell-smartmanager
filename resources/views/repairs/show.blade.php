@extends('layouts.admin')

@section('title', 'Orden ' . $repair->tracking_code . ' - VICOCELL')

@section('content')
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #printableTicket, #printableTicket * {
            visibility: visible;
        }
        #printableTicket {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 20px;
        }
        .no-print {
            display: none !important;
        }
    }
</style>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <!-- Barra de Acciones Superior -->
        <div class="d-flex justify-content-between align-items-center mb-3 no-print">
            <a href="{{ route('repairs.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Volver al Listado
            </a>
            <div class="d-flex gap-2">
                <button onclick="window.print()" class="btn btn-dark">
                    <i class="bi bi-printer-fill me-1"></i> Imprimir Comprobante
                </button>
                <a href="{{ route('repairs.edit', $repair) }}" class="btn btn-primary">
                    <i class="bi bi-pencil-square me-1"></i> Actualizar Diagnóstico / Estado
                </a>
            </div>
        </div>

        <!-- Documento de Recepción Técnica -->
        <div class="card shadow-sm border-0 rounded-3 p-4 bg-white" id="printableTicket">
            <div class="row border-bottom pb-3 mb-3">
                <div class="col-sm-6">
                    <h3 class="fw-bold text-dark mb-0"><i class="bi bi-tools text-primary"></i> VICOCELL</h3>
                    <p class="text-muted small mb-0">Laboratorio Técnico & Soluciones Digitales</p>
                    <small class="text-secondary d-block">Servicio técnico garantizado y venta de repuestos</small>
                </div>
                <div class="col-sm-6 text-sm-end mt-2 mt-sm-0">
                    <span class="badge bg-primary fs-6 mb-1">ORDEN DE SERVICIO TÉCNICO</span>
                    <h4 class="fw-bold text-dark mb-0">{{ $repair->tracking_code }}</h4>
                    <small class="text-muted">Fecha Ingreso: {{ $repair->created_at->format('d/m/Y H:i') }}</small>
                </div>
            </div>

            <!-- Estado y Responsable -->
            <div class="row g-3 mb-4 bg-light p-3 rounded">
                <div class="col-md-4">
                    <span class="small text-muted d-block">Estado de la Reparación:</span>
                    @php
                        $badgeClasses = [
                            'Recibido' => 'bg-secondary',
                            'En Revision' => 'bg-info text-dark',
                            'En Reparacion' => 'bg-warning text-dark',
                            'Listo para Entrega' => 'bg-primary',
                            'Entregado' => 'bg-success',
                            'Cancelado' => 'bg-danger'
                        ];
                    @endphp
                    <span class="badge {{ $badgeClasses[$repair->status] ?? 'bg-secondary' }} fs-6">
                        {{ $repair->status }}
                    </span>
                </div>
                <div class="col-md-4">
                    <span class="small text-muted d-block">Técnico Responsable:</span>
                    <strong class="text-dark">{{ $repair->technician->name ?? 'No asignado' }}</strong>
                </div>
                <div class="col-md-4">
                    <span class="small text-muted d-block">Fecha Prometida de Entrega:</span>
                    <strong class="text-dark">{{ $repair->promised_date ? $repair->promised_date->format('d/m/Y') : 'Por coordinar' }}</strong>
                </div>
            </div>

            <!-- Datos del Cliente y Dispositivo -->
            <div class="row g-3 mb-4">
                <div class="col-md-6 border-end">
                    <h6 class="fw-bold text-secondary text-uppercase small mb-2"><i class="bi bi-person me-1"></i> Datos del Cliente</h6>
                    <p class="mb-1"><strong>Nombre:</strong> {{ $repair->client->name }}</p>
                    <p class="mb-1"><strong>Teléfono / WhatsApp:</strong> {{ $repair->client->phone ?? 'S/N' }}</p>
                    <p class="mb-0"><strong>CI / NIT:</strong> {{ $repair->client->ci_nit ?? 'S/N' }}</p>
                </div>
                <div class="col-md-6 ps-md-4">
                    <h6 class="fw-bold text-secondary text-uppercase small mb-2"><i class="bi bi-phone me-1"></i> Datos del Dispositivo</h6>
                    <p class="mb-1"><strong>Equipo:</strong> {{ $repair->device_type }} - {{ $repair->brand }} {{ $repair->model }}</p>
                    <p class="mb-0"><strong>IMEI / Serie / Patrón:</strong> {{ $repair->imei_serial ?? 'No registrado' }}</p>
                </div>
            </div>

            <!-- Falla Reportada y Diagnóstico -->
            <div class="mb-4">
                <div class="p-3 border rounded mb-2 bg-light">
                    <strong class="small text-secondary text-uppercase d-block mb-1">Motivo de Ingreso / Falla Reportada:</strong>
                    <p class="mb-0 text-dark">{{ $repair->reported_fault }}</p>
                </div>
                @if($repair->diagnosis)
                    <div class="p-3 border rounded bg-white">
                        <strong class="small text-secondary text-uppercase d-block mb-1">Diagnóstico / Trabajo Realizado:</strong>
                        <p class="mb-0 text-dark">{{ $repair->diagnosis }}</p>
                    </div>
                @endif
            </div>

            <!-- Resumen Financiero -->
            <div class="row justify-content-end mb-4">
                <div class="col-md-6">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <tbody>
                                <tr>
                                    <th class="bg-light">Costo Estimado / Total:</th>
                                    <td class="text-end fw-bold">Bs. {{ number_format($repair->estimated_cost, 2) }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Anticipo Pagado:</th>
                                    <td class="text-end text-success fw-bold">- Bs. {{ number_format($repair->advance_payment, 2) }}</td>
                                </tr>
                                <tr class="table-warning">
                                    <th class="fw-bold fs-6">SALDO PENDIENTE:</th>
                                    <td class="text-end fw-bold fs-6 text-danger">Bs. {{ number_format($repair->balance, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Términos y Firmas -->
            <div class="row pt-4 mt-2 border-top text-center small text-muted">
                <div class="col-6 pt-5">
                    <div class="border-top border-dark mx-auto" style="width: 200px;"></div>
                    <p class="mt-1 mb-0">Firma del Cliente</p>
                </div>
                <div class="col-6 pt-5">
                    <div class="border-top border-dark mx-auto" style="width: 200px;"></div>
                    <p class="mt-1 mb-0">Firma Responsable Taller</p>
                </div>
                <div class="col-12 mt-4">
                    <p class="mb-0 text-secondary">Presentar este comprobante o el código <strong>{{ $repair->tracking_code }}</strong> para el retiro de su equipo.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
