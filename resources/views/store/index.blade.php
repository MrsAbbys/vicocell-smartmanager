@extends('layouts.public')

@section('title', 'Catálogo Digital - VICOCELL')

@section('content')
<section class="hero-banner py-5 mb-4 shadow-sm">
    <div class="container text-center">
        <h1 class="display-5 fw-bold mb-2">VICOCELL SmartManager</h1>
        <p class="lead mb-0 opacity-75">Celulares, accesorios, repuestos y servicio técnico especializado</p>
    </div>
</section>

<div class="container">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('store.index') }}" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Buscar celulares o accesorios..." value="{{ $search }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <select name="category" class="form-select">
                        <option value="">Todas las categorías</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-theme w-100"><i class="bi bi-filter me-1"></i> Filtrar</button>
                    @if($search || $categoryId)
                        <a href="{{ route('store.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4 mb-4">
        @forelse($products as $product)
            <div class="col">
                <div class="card h-100 shadow-sm border-0 rounded-3 overflow-hidden d-flex flex-column">
                    <!-- Contenedor con ajuste proporcional de imagen -->
                    <div class="d-flex align-items-center justify-content-center p-3" style="height: 220px; background-color: rgba(125, 125, 125, 0.05);">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}"
                                 class="img-fluid"
                                 style="max-height: 100%; max-width: 100%; object-fit: contain;"
                                 alt="{{ $product->name }}">
                        @else
                            <div class="text-center text-muted">
                                <i class="bi bi-phone fs-1 d-block opacity-50"></i>
                                <small class="small">Sin imagen</small>
                            </div>
                        @endif
                    </div>

                    <div class="card-body d-flex flex-column pb-3">
                        <span class="badge bg-secondary mb-2 align-self-start">{{ $product->category->name }}</span>
                        <h6 class="card-title fw-bold mb-1">{{ $product->name }}</h6>
                        <p class="card-text small opacity-75 flex-grow-1">{{ Str::limit($product->description, 65) ?? 'Producto disponible en tienda.' }}</p>

                        <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top mb-3" style="border-color: var(--border-color) !important;">
                            <span class="fs-5 fw-bold text-info">Bs. {{ number_format($product->price, 2) }}</span>
                            @if($product->stock > 0)
                                <span class="badge bg-success-subtle text-success border border-success-subtle">Stock: {{ $product->stock }}</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Agotado</span>
                            @endif
                        </div>

                        <!-- Botones de Acción -->
                        <div class="d-grid gap-2">
                            @if($product->stock > 0)
                                @auth
                                    <button type="button" class="btn btn-theme btn-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#buyModal{{ $product->id }}">
                                        <i class="bi bi-bag-plus me-1"></i> Comprar / Reservar
                                    </button>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-outline-info btn-sm fw-semibold">
                                        <i class="bi bi-box-arrow-in-right me-1"></i> Iniciar sesión para comprar
                                    </a>
                                @endauth
                            @else
                                <button class="btn btn-secondary btn-sm" disabled>Agotado</button>
                            @endif

                            <a href="https://wa.me/59170000000?text=Hola%20VICOCELL,%20estoy%20interesado%20en%20el%20producto:%20{{ urlencode($product->name) }}%20(Bs.%20{{ $product->price }})"
                               target="_blank" class="btn btn-outline-success btn-sm">
                                <i class="bi bi-whatsapp me-1"></i> Consultar WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            @if($product->stock > 0)
                <!-- Modal de Compra Directa -->
                <div class="modal fade" id="buyModal{{ $product->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow">
                            <div class="modal-header border-bottom">
                                <h5 class="modal-title fw-bold"><i class="bi bi-cart-check me-2 text-info"></i>Confirmar Pedido</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('customer.checkout', $product) }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="d-flex align-items-center mb-3 p-2 rounded" style="background-color: var(--input-bg);">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" class="rounded me-3" style="width: 55px; height: 55px; object-fit: contain;" alt="{{ $product->name }}">
                                        @endif
                                        <div>
                                            <h6 class="fw-bold mb-0">{{ $product->name }}</h6>
                                            <span class="text-info fw-bold">Precio: Bs. {{ number_format($product->price, 2) }}</span>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small">Cantidad a comprar (Máx: {{ $product->stock }})</label>
                                        <input type="number" name="quantity" class="form-control" value="1" min="1" max="{{ $product->stock }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small">Forma de Pago</label>
                                        <select name="payment_method" class="form-select" required>
                                            <option value="Pago contra entrega (Efectivo)">Pago contra entrega (Efectivo)</option>
                                            <option value="Pago por QR / Transferencia">Pago por QR / Transferencia</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small">Indicaciones de Entrega / Dirección (Opcional)</label>
                                        <textarea name="delivery_notes" class="form-control" rows="2" placeholder="Ej: Retiro en tienda / Enviar a domicilio..."></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer border-top">
                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-theme btn-sm fw-semibold">
                                        <i class="bi bi-check2-circle me-1"></i> Confirmar Compra
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-inbox display-4 text-muted d-block mb-2"></i>
                <h5 class="text-muted">No se encontraron productos disponibles</h5>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center my-4">
    {{ $products->links('pagination::bootstrap-5') }}
</div>
</div>
@endsection
