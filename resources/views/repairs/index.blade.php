@extends('layouts.admin')

@section('title', 'Taller de Servicio Técnico - VICOCELL SmartManager')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col-md-6">
        <h3 class="fw-bold mb-0"><i class="bi bi-tools me-2 text-primary"></i>Órdenes de Servicio Técnico</h3>
        <p class="text-muted small mb-0">Control de reparaciones, diagnósticos y entregas de taller</p>
    </div>
    <div class="col-md-6 text-md-end mt-3 mt-md-0">
        <a href="{{ route('repairs.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Recibir Dispositivo
        </a>
    </div>
</div>

<!-- Filtros y Búsqueda -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('repairs.index') }}" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Buscar por código, cliente, teléfono, IMEI o modelo..." value="{{ $search }}">
                </div>
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="">Todos los Estados</option>
                    @foreach(['Recibido', 'En Revision', 'En Reparacion', 'Listo para Entrega', 'Entregado', 'Cancelado'] as $st)
                        <option value="{{ $st }}" {{ $status == $st ? 'selected' : '' }}>{{ $st }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-filter me-1"></i> Filtrar
                </button>
                @if($search || $status)
                    <a href="{{ route('repairs.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Tabla de Reparaciones -->
<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Cliente</th>
                    <th>Dispositivo / Modelo</th>
                    <th>Falla</th>
                    <th>Técnico</th>
                    <th>Estado</th>
                    <th class="text-end">Saldo</th>
                    <th class="text-center" width="130">Acciones</th>
                </tr>
            </thead>
            <tbody>
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
                @forelse($repairOrders as $item)
                    <tr>
                        <td class="fw-bold text-info">{{ $item->tracking_code }}</td>
                        <td>
                            <strong>{{ $item->client->name }}</strong>
                            @if($item->client && $item->client->phone)
                                <small class="text-muted d-block"><i class="bi bi-whatsapp text-success me-1"></i>{{ $item->client->phone }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="fw-semibold">{{ $item->brand }} {{ $item->model }}</span>
                            <small class="text-muted d-block">{{ $item->device_type }}</small>
                        </td>
                        <td class="small text-muted" style="max-width: 220px;">
                            {{ Str::limit($item->reported_fault, 45) }}
                        </td>
                        <td class="small">{{ $item->technician->name ?? 'Sin asignar' }}</td>
                        <td>
                            <span class="badge {{ $badgeClasses[$item->status] ?? 'bg-secondary' }}">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td class="text-end fw-semibold {{ $item->balance > 0 ? 'text-danger' : 'text-success' }}">
                            Bs. {{ number_format($item->balance, 2) }}
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('repairs.show', $item) }}" class="btn btn-outline-info" title="Ver Comprobante">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                                <a href="{{ route('repairs.edit', $item) }}" class="btn btn-outline-primary" title="Editar Estado / Diagnóstico">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                            <i class="bi bi-tools display-6 d-block mb-2"></i>
                            No hay órdenes de reparación registradas
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($repairOrders->hasPages())
        <div class="card-footer d-flex justify-content-center py-3">
            {{ $repairOrders->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection


