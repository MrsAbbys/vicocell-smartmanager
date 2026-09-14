@extends('layouts.admin')

@section('title', 'Historial de Ventas - VICOCELL SmartManager')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col-md-6">
        <h3 class="fw-bold mb-0"><i class="bi bi-cart-check me-2 text-primary"></i>Historial de Ventas</h3>
        <p class="text-muted small mb-0">Registro y auditoría de transacciones comerciales</p>
    </div>
    <div class="col-md-6 text-md-end mt-3 mt-md-0">
        <a href="{{ route('sales.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Nueva Venta (POS)
        </a>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('sales.index') }}" class="row g-2 align-items-center">
            <div class="col-md-8 col-lg-6">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Buscar por Nº comprobante, cliente o CI..." value="{{ $search }}">
                </div>
            </div>
            <div class="col-md-4 col-lg-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-filter me-1"></i> Buscar
                </button>
                @if($search)
                    <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Comprobante</th>
                    <th>Fecha y Hora</th>
                    <th>Cliente</th>
                    <th>Vendedor</th>
                    <th>Método</th>
                    <th class="text-end">Total</th>
                    <th class="text-center" width="100">Acción</th>
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
                                <small class="text-muted d-block">CI: {{ $sale->client->ci_nit }}</small>
                            @endif
                        </td>
                        <td class="small">{{ $sale->user->name }}</td>
                        <td>
                            <span class="badge bg-secondary-subtle text-secondary border">{{ $sale->payment_method }}</span>
                        </td>
                        <td class="text-end fw-bold fs-6">Bs. {{ number_format($sale->total, 2) }}</td>
                        <td class="text-center">
                            <a href="{{ route('sales.show', $sale) }}" class="btn btn-sm btn-outline-info" title="Ver Comprobante">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">
                            <i class="bi bi-inbox display-6 d-block mb-2"></i>
                            No se encontraron ventas registradas
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($sales->hasPages())
        <div class="card-footer d-flex justify-content-center py-3">
            {{ $sales->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
