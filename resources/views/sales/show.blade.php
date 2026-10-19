@extends('layouts.admin')

@section('title', 'Comprobante ' . $sale->voucher_number . ' - VICOCELL')

@section('content')
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #printableVoucher, #printableVoucher * {
            visibility: visible;
        }
        #printableVoucher {
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
    <div class="col-lg-8">
        <!-- Acciones superiores -->
        <div class="d-flex justify-content-between align-items-center mb-3 no-print">
            <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Volver a Ventas
            </a>
            <div class="d-flex gap-2">
                <button onclick="window.print()" class="btn btn-dark">
                    <i class="bi bi-printer-fill me-1"></i> Imprimir Recibo
                </button>
                <a href="{{ route('sales.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1"></i> Nueva Venta
                </a>
            </div>
        </div>

        <!-- Tarjeta de Comprobante -->
        <div class="card shadow-sm border-0 rounded-3 p-4 bg-white" id="printableVoucher">
            <div class="row border-bottom pb-3 mb-3">
                <div class="col-sm-6">
                    <h3 class="fw-bold text-dark mb-0"><i class="bi bi-phone-vibrate text-primary"></i> VICOCELL</h3>
                    <p class="text-muted small mb-0">SmartManager - Soluciones Tecnológicas</p>
                    <small class="text-secondary d-block">Venta de celulares, accesorios y servicio técnico</small>
                </div>
                <div class="col-sm-6 text-sm-end mt-2 mt-sm-0">
                    <span class="badge bg-primary fs-6 mb-1">COMPROBANTE DE VENTA</span>
                    <h5 class="fw-bold text-dark mb-0">{{ $sale->voucher_number }}</h5>
                    <small class="text-muted">Fecha: {{ $sale->created_at->format('d/m/Y H:i') }}</small>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-sm-6">
                    <h6 class="fw-bold text-secondary text-uppercase small mb-2">Datos del Cliente</h6>
                    <p class="mb-1"><strong>Nombre:</strong> {{ $sale->client->name ?? 'Cliente de Mostrador' }}</p>
                    <p class="mb-1"><strong>CI / NIT:</strong> {{ $sale->client->ci_nit ?? 'S/N' }}</p>
                    <p class="mb-0"><strong>Teléfono:</strong> {{ $sale->client->phone ?? 'S/N' }}</p>
                </div>
                <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                    <h6 class="fw-bold text-secondary text-uppercase small mb-2">Detalles de la Transacción</h6>
                    <p class="mb-1"><strong>Atendido por:</strong> {{ $sale->user->name }}</p>
                    <p class="mb-1"><strong>Método de Pago:</strong> <span class="badge bg-secondary">{{ $sale->payment_method }}</span></p>
                </div>
            </div>

            <div class="table-responsive mb-4">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="50" class="text-center">#</th>
                            <th>Descripción del Producto</th>
                            <th width="120" class="text-end">Precio Unit.</th>
                            <th width="80" class="text-center">Cant.</th>
                            <th width="130" class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale->details as $index => $detail)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>
                                    <strong>{{ $detail->product->name }}</strong>
                                    <small class="text-muted d-block">{{ $detail->product->category->name }}</small>
                                </td>
                                <td class="text-end">Bs. {{ number_format($detail->price, 2) }}</td>
                                <td class="text-center">{{ $detail->quantity }}</td>
                                <td class="text-end fw-semibold">Bs. {{ number_format($detail->subtotal, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end fw-bold fs-5">TOTAL A PAGAR:</td>
                            <td class="text-end fw-bold fs-5 text-primary">Bs. {{ number_format($sale->total, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            @if($sale->notes)
                <div class="bg-light p-3 rounded mb-3">
                    <strong class="small text-secondary text-uppercase">Notas / Observaciones:</strong>
                    <p class="small mb-0 mt-1">{{ $sale->notes }}</p>
                </div>
            @endif

            <!-- Comprobante de Pago Adjunto (QR / Transferencia) -->
            @if($sale->payment_proof)
                <div class="card border-info-subtle bg-light-subtle p-3 mb-3 no-print">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <strong class="small text-secondary text-uppercase">
                            <i class="bi bi-receipt-cutoff text-info me-1"></i> Comprobante de Pago Subido por el Cliente:
                        </strong>
                        <a href="{{ asset('storage/' . $sale->payment_proof) }}" target="_blank" class="btn btn-outline-info btn-sm">
                            <i class="bi bi-box-arrow-up-right me-1"></i> Ver en pantalla completa
                        </a>
                    </div>
                    <div>
                        <a href="{{ asset('storage/' . $sale->payment_proof) }}" target="_blank">
                            <img src="{{ asset('storage/' . $sale->payment_proof) }}"
                                 alt="Comprobante de pago"
                                 class="img-thumbnail rounded shadow-sm"
                                 style="max-height: 180px; object-fit: contain;">
                        </a>
                    </div>
                </div>
            @endif

            <div class="text-center text-muted small mt-4 pt-3 border-top">
                <p class="mb-0">¡Gracias por su preferencia!</p>
                <p class="mb-0">VICOCELL SmartManager - Garantía sujeta a términos y condiciones.</p>
            </div>
        </div>
    </div>
</div>
@endsection
