@extends('layouts.admin')

@section('title', 'Inventario de Productos - VICOCELL')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="fw-bold mb-0 text-dark"><i class="bi bi-box-seam me-2 text-primary"></i>Gestión de Inventario</h3>
        <p class="text-muted small mb-0">Control de celulares, accesorios y repuestos</p>
    </div>
    <a href="{{ route('products.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Registrar Producto
    </a>
</div>

@if($lowStockCount > 0)
    <div class="alert alert-warning d-flex justify-content-between align-items-center mb-3 py-2">
        <div>
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
            <strong>Atención:</strong> Hay <strong>{{ $lowStockCount }}</strong> producto(s) con stock igual o por debajo del mínimo.
        </div>
        @if(!$lowStockOnly)
            <a href="{{ route('products.index', ['low_stock' => 1]) }}" class="btn btn-sm btn-outline-dark">
                Ver productos críticos
            </a>
        @else
            <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary">
                Ver todos
            </a>
        @endif
    </div>
@endif

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('products.index') }}" class="row g-2 mb-3">
            <div class="col-md-8">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Buscar por nombre o descripción..." value="{{ $search }}">
                </div>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-dark w-100"><i class="bi bi-funnel me-1"></i> Filtrar</button>
                @if($search || $lowStockOnly)
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 70px;">Imagen</th>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>Precio (Bs.)</th>
                        <th>Stock Actual</th>
                        <th>Mínimo</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="rounded object-fit-cover" width="50" height="50">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted" style="width: 50px; height: 50px;">
                                        <i class="bi bi-image"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <span class="fw-bold d-block text-dark">{{ $product->name }}</span>
                                <small class="text-muted">{{ Str::limit($product->description, 40) ?? 'Sin descripción' }}</small>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $product->category->name ?? 'Sin categoría' }}</span>
                            </td>
                            <td class="fw-bold">{{ number_format($product->price, 2) }}</td>
                            <td>
                                @if($product->stock <= $product->min_stock)
                                    <span class="badge bg-danger fs-6">{{ $product->stock }}</span>
                                @else
                                    <span class="badge bg-success fs-6">{{ $product->stock }}</span>
                                @endif
                            </td>
                            <td class="text-muted">{{ $product->min_stock }}</td>
                            <td>
                                @if($product->is_active)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">Activo</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">Inactivo</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-primary me-1" title="Editar">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que deseas eliminar este producto?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-3 d-block mb-1"></i> No se encontraron productos registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
