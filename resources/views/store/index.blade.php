@extends('layouts.public')

@section('title', 'Catálogo Digital - VICOCELL')

@section('content')
<section class="hero-banner py-5 mb-4 shadow-sm">
    <div class="container text-center">
        <h1 class="display-5 fw-bold mb-2">VICOCELL SmartManager</h1>
        <p class="lead mb-0 text-light opacity-75">Celulares, accesorios, repuestos y servicio técnico especializado</p>
    </div>
</section>

<div class="container">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('store.index') }}" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
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
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-filter me-1"></i> Filtrar</button>
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
                <div class="card h-100 shadow-sm border-0 rounded-3 overflow-hidden">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top object-fit-cover" style="height: 200px;" alt="{{ $product->name }}">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center text-muted" style="height: 200px;">
                            <i class="bi bi-phone fs-1"></i>
                        </div>
                    @endif
                    <div class="card-body d-flex flex-column">
                        <span class="badge bg-secondary mb-2 align-self-start">{{ $product->category->name }}</span>
                        <h5 class="card-title fw-bold text-dark mb-1">{{ $product->name }}</h5>
                        <p class="card-text text-muted small flex-grow-1">{{ Str::limit($product->description, 70) ?? 'Producto disponible en tienda.' }}</p>
                        <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                            <span class="fs-5 fw-bold text-primary">Bs. {{ number_format($product->price, 2) }}</span>
                            @if($product->stock > 0)
                                <span class="badge bg-success-subtle text-success border border-success-subtle">En Stock</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Agotado</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-inbox display-4 text-muted d-block mb-2"></i>
                <h5 class="text-muted">No se encontraron productos disponibles</h5>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center">
        {{ $products->links() }}
    </div>
</div>
@endsection
