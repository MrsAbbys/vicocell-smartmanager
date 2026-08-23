@extends('layouts.public')

@section('title', 'Mis Compras y Pedidos - VICOCELL')

@section('content')
<div class="container py-5">
    <div class="row align-items-center mb-4">
        <div class="col-md-8">
            <h3 class="fw-bold text-dark mb-0">
                <i class="bi bi-bag-check-fill text-primary me-2"></i>Mis Compras y Pedidos
            </h3>
            <p class="text-muted small mb-0">Revisa el historial y detalle de todos tus productos adquiridos</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="{{ route('store.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left me-1"></i> Seguir Comprando
            </a>
        </div>
    </div>

    @if($orders->isEmpty())
        <div class="card shadow-sm border-0 text-center py-5">
            <div class="card-body">
                <i class="bi bi-cart-x display-3 text-muted mb-3 d-block"></i>
                <h5 class="fw-bold text-secondary">Aún no tienes pedidos registrados</h5>
                <p class="text-muted small">Explora nuestro catálogo y adquiere los mejores celulares y accesorios.</p>
                <a href="{{ route('store.index') }}" class="btn btn-primary mt-2">
                    <i class="bi bi-grid-fill me-1"></i> Ir al Catálogo
                </a>
            </div>
        </div>
    @else
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="card shadow-sm border-0 mb-4 rounded-3 overflow-hidden">
                    <div class="card-header bg-light d-flex flex-wrap justify-content-between align-items-center py-3">
                        <div>
                            <span class="badge bg-primary me-2">{{ $order->voucher_number }}</span>
                            <small class="text-muted">Fecha: {{ $order->created_at->format('d/m/Y H:i') }}</small>
                        </div>
                        <div class="mt-2 mt-sm-0">
                            <span class="text-muted small me-2">Método de pago: <strong>{{ $order->payment_method }}</strong></span>
                            <span class="fs-5 fw-bold text-success">Total: Bs. {{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Producto</th>
                                        <th class="text-center" width="100">Cantidad</th>
                                        <th class="text-end" width="140">Precio Unitario</th>
                                        <th class="text-end" width="140">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->details as $detail)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($detail->product->image)
                                                        <img src="{{ asset('storage/' . $detail->product->image) }}" class="rounded me-3 object-fit-cover" style="width: 45px; height: 45px;" alt="{{ $detail->product->name }}">
                                                    @else
                                                        <div class="rounded bg-light d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                                            <i class="bi bi-phone text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <span class="fw-bold text-dark d-block">{{ $detail->product->name }}</span>
                                                        <small class="text-muted">{{ $detail->product->category->name }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center fw-semibold">{{ $detail->quantity }}</td>
                                            <td class="text-end">Bs. {{ number_format($detail->price, 2) }}</td>
                                            <td class="text-end fw-bold">Bs. {{ number_format($detail->subtotal, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($order->notes)
                        <div class="card-footer bg-white text-muted small py-2">
                            <i class="bi bi-info-circle me-1"></i> <strong>Detalle:</strong> {{ $order->notes }}
                        </div>
                    @endif
                </div>
            @endforeach

            <div class="d-flex justify-content-center">
                {{ $orders->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
