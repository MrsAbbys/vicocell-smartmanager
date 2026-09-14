@extends('layouts.admin')

@section('title', 'Reporte Financiero y Operativo - VICOCELL')

@section('content')
<style>
    /* Estilos base del documento de reporte */
    .report-paper {
        background-color: var(--bg-card-custom) !important;
        border: 1px solid var(--border-custom) !important;
        border-radius: 14px;
        padding: 2.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .report-badge {
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        padding: 5px 12px;
        font-weight: 700;
        border-radius: 6px;
    }

    .metric-card-pro {
        border-radius: 10px;
        border: 1px solid var(--border-custom);
        background: rgba(14, 22, 38, 0.4);
        padding: 1.25rem;
    }

    /* Reglas estrictas de impresión profesional A4 / PDF */
    @media print {
        @page {
            size: A4 portrait;
            margin: 1.2cm 1.5cm;
        }

        /* Ocultar elementos interactivos del sistema */
        .no-print, .admin-navbar, footer, .btn, form {
            display: none !important;
        }

        body {
            background-color: #ffffff !important;
            color: #0f172a !important;
            font-size: 11pt;
            margin: 0;
            padding: 0;
        }

        .report-paper {
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
            background: transparent !important;
        }

        .metric-card-pro {
            background-color: #f8fafc !important;
            border: 1px solid #cbd5e1 !important;
        }

        .table {
            border-color: #cbd5e1 !important;
        }

        .table th {
            background-color: #f1f5f9 !important;
            color: #0f172a !important;
            border-bottom: 2px solid #0f172a !important;
            font-size: 9pt !important;
            text-transform: uppercase;
        }

        .table td {
            color: #1e293b !important;
            border-color: #e2e8f0 !important;
            font-size: 9.5pt !important;
            padding: 6px 8px !important;
        }

        .text-info, .text-primary, .text-success {
            color: #0f172a !important;
            font-weight: 700 !important;
        }

        /* Forzar visualización de firmas y pie de auditoría */
        .print-footer-audit {
            display: block !important;
            page-break-inside: avoid;
            margin-top: 4rem;
        }
    }

    .print-footer-audit {
        display: none;
    }
</style>

<!-- Barra Superior de Acciones (No Imprimible) -->
<div class="d-flex justify-content-between align-items-center mb-4 no-print">
    <div>
        <h4 class="fw-bold mb-0"><i class="bi bi-file-earmark-ruled me-2 text-primary"></i>Auditoría Financiera y Operativa</h4>
        <p class="text-muted small mb-0">Consolidación de transacciones comerciales y servicios de laboratorio</p>
    </div>
    <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-primary px-4 fw-semibold shadow-sm">
            <i class="bi bi-printer me-2"></i>Imprimir Reporte Oficial
        </button>
    </div>
</div>

<!-- Filtros de Consulta (No Imprimible) -->
<div class="card shadow-sm border-0 mb-4 no-print">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('reports.index') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-semibold text-muted">Fecha Inicial</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate }}" required>
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold text-muted">Fecha Final</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-calendar-check"></i></span>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate }}" required>
                </div>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100 fw-semibold">
                    <i class="bi bi-funnel me-1"></i> Generar Balance
                </button>
                <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary px-3" title="Mes Actual">
                    Actual
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Hoja de Reporte Ejecutivo Formal -->
<div class="report-paper">

    <!-- Cabecera Institucional del Reporte -->
    <div class="row align-items-center border-bottom pb-4 mb-4" style="border-color: var(--border-custom) !important;">
        <div class="col-sm-7">
            <div class="d-flex align-items-center mb-2">
                <i class="bi bi-shield-check-fill text-primary fs-2 me-2"></i>
                <h3 class="fw-bold tracking-tight mb-0">VICOCELL SMARTMANAGER</h3>
            </div>
            <p class="small text-muted mb-0">Servicio Técnico Especializado, Venta de Telefonía Celular & Accesorios</p>
            <p class="small text-muted mb-0">Sistema Central de Facturación y Control Operativo</p>
        </div>
        <div class="col-sm-5 text-sm-end mt-3 mt-sm-0">
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle report-badge mb-2">Informe Contable Oficial</span>
            <div class="small fw-semibold">Periodo: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} — {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</div>
            <div class="small text-muted">Fecha de Emisión: {{ now()->format('d/m/Y H:i') }}</div>
            <div class="small text-muted">Generado por: {{ Auth::user()->name }} ({{ ucfirst(Auth::user()->role) }})</div>
        </div>
    </div>

    <!-- Indicadores Financieros de Resumen -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="metric-card-pro border-start border-4 border-primary">
                <div class="text-muted small text-uppercase fw-semibold mb-1">Módulo Ventas / POS</div>
                <h4 class="fw-bold mb-0">Bs. {{ number_format($totalSalesIncome, 2) }}</h4>
                <div class="small text-muted mt-1">{{ $sales->count() }} órdenes procesadas</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="metric-card-pro border-start border-4 border-success">
                <div class="text-muted small text-uppercase fw-semibold mb-1">Taller de Reparaciones</div>
                <h4 class="fw-bold mb-0 text-success">Bs. {{ number_format($totalRepairsIncome, 2) }}</h4>
                <div class="small text-muted mt-1">{{ $repairs->count() }} equipos entregados</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="metric-card-pro border-start border-4 border-info">
                <div class="text-muted small text-uppercase fw-semibold mb-1">Balance Consolidado</div>
                <h4 class="fw-bold mb-0 text-info">Bs. {{ number_format($grandTotal, 2) }}</h4>
                <div class="small text-muted mt-1">Recaudación total neta</div>
            </div>
        </div>
    </div>

    <!-- 1. Detalle de Ventas Registradas -->
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="fw-bold text-uppercase tracking-wide mb-0">
                <i class="bi bi-receipt me-2 text-primary"></i>1. Detalle de Ventas Comerciales
            </h6>
            <span class="small text-muted">{{ $sales->count() }} comprobantes emitidos</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 130px;">Nº Comprobante</th>
                        <th>Fecha / Hora</th>
                        <th>Cliente / Titular</th>
                        <th>Canal de Pago</th>
                        <th class="text-end" style="width: 140px;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                        <tr>
                            <td class="fw-bold text-info">{{ $sale->voucher_number }}</td>
                            <td class="small">{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <strong>{{ $sale->client->name ?? 'Cliente Mostrador' }}</strong>
                                @if($sale->client && $sale->client->ci_nit)
                                    <span class="small text-muted ms-1">(NIT/CI: {{ $sale->client->ci_nit }})</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-secondary-subtle text-secondary border small">{{ $sale->payment_method }}</span>
                            </td>
                            <td class="text-end fw-bold">Bs. {{ number_format($sale->total, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No se generaron ventas registradas en el rango de fechas seleccionado.</td>
                        </tr>
                    @endforelse
                </tbody>
                @if($sales->count() > 0)
                    <tfoot>
                        <tr class="fw-bold border-top" style="border-top: 2px solid var(--border-custom) !important;">
                            <td colspan="4" class="text-end text-uppercase small">Subtotal Ventas:</td>
                            <td class="text-end text-primary">Bs. {{ number_format($totalSalesIncome, 2) }}</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

    <!-- 2. Detalle de Servicios Técnicos -->
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="fw-bold text-uppercase tracking-wide mb-0">
                <i class="bi bi-wrench-adjustable me-2 text-success"></i>2. Detalle de Servicios de Taller Entregados
            </h6>
            <span class="small text-muted">{{ $repairs->count() }} órdenes concluidas</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 130px;">Código Orden</th>
                        <th>Fecha Liquidación</th>
                        <th>Cliente</th>
                        <th>Dispositivo / Modelo</th>
                        <th>Técnico Responsable</th>
                        <th class="text-end" style="width: 140px;">Monto Cobrado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($repairs as $repair)
                        <tr>
                            <td class="fw-bold text-info">{{ $repair->tracking_code }}</td>
                            <td class="small">{{ $repair->updated_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $repair->client->name ?? 'Cliente General' }}</td>
                            <td>
                                <strong>{{ $repair->brand }} {{ $repair->model }}</strong>
                                <small class="text-muted d-block">{{ $repair->device_type }}</small>
                            </td>
                            <td class="small">{{ $repair->technician->name ?? 'Laboratorio Central' }}</td>
                            <td class="text-end fw-bold text-success">Bs. {{ number_format($repair->estimated_cost, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No hay servicios técnicos con estado entregado en este periodo.</td>
                        </tr>
                    @endforelse
                </tbody>
                @if($repairs->count() > 0)
                    <tfoot>
                        <tr class="fw-bold border-top" style="border-top: 2px solid var(--border-custom) !important;">
                            <td colspan="5" class="text-end text-uppercase small">Subtotal Servicios Técnicos:</td>
                            <td class="text-end text-success">Bs. {{ number_format($totalRepairsIncome, 2) }}</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

    <!-- Liquidación Final Consolidada -->
    <div class="row justify-content-end mb-4">
        <div class="col-md-5">
            <div class="p-3 rounded border" style="background-color: var(--bg-surface-custom); border-color: var(--border-custom) !important;">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Ingresos Tienda:</span>
                    <span class="fw-semibold">Bs. {{ number_format($totalSalesIncome, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Ingresos Servicio Técnico:</span>
                    <span class="fw-semibold">Bs. {{ number_format($totalRepairsIncome, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between pt-2 border-top fw-bold fs-5" style="border-color: var(--border-custom) !important;">
                    <span>TOTAL GENERAL:</span>
                    <span class="text-info">Bs. {{ number_format($grandTotal, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección de Firmas y Validación Legal (Impresión) -->
    <div class="print-footer-audit">
        <div class="row text-center mt-5 pt-4">
            <div class="col-6">
                <div style="border-top: 1px solid #64748b; width: 70%; margin: 0 auto; padding-top: 6px;">
                    <div class="fw-bold">{{ Auth::user()->name }}</div>
                    <div class="small text-muted">Responsable de Auditoría / Caja</div>
                    <div class="small text-muted">VICOCELL SmartManager</div>
                </div>
            </div>
            <div class="col-6">
                <div style="border-top: 1px solid #64748b; width: 70%; margin: 0 auto; padding-top: 6px;">
                    <div class="fw-bold">Administración General</div>
                    <div class="small text-muted">Firma & Sello de Conformidad</div>
                    <div class="small text-muted">Control Interno de Gestión</div>
                </div>
            </div>
        </div>
        <div class="text-center text-muted small mt-4 pt-3 border-top">
            Documento de control contable generado electrónicamente por VICOCELL SmartManager. Válido para auditoría interna y conciliación de caja.
        </div>
    </div>

</div>
@endsection
