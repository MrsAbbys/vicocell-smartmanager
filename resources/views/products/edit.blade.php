@extends('layouts.admin')

@section('title', 'Editar Producto - VICOCELL')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i>Editar Producto: {{ $product->name }}</h5>
                <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-light">
                    <i class="bi bi-arrow-left"></i> Volver al listado
                </a>
            </div>
            <div class="card-body p-4">
                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <h6 class="fw-bold mb-2"><i class="bi bi-exclamation-triangle"></i> Corrige los siguientes errores:</h6>
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-8">
                            <label for="name" class="form-label fw-bold">Nombre del Producto <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                        </div>

                        <div class="col-md-4">
                            <label for="category_id" class="form-label fw-bold">Categoría <span class="text-danger">*</span></label>
                            <select name="category_id" id="category_id" class="form-select" required>
                                <option value="">Selecciona una categoría</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <label for="description" class="form-label fw-bold">Descripción</label>
                            <textarea name="description" id="description" rows="3" class="form-control">{{ old('description', $product->description) }}</textarea>
                        </div>

                        <div class="col-md-4">
                            <label for="price" class="form-label fw-bold">Precio de Venta (Bs.) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" name="price" id="price" class="form-control" value="{{ old('price', $product->price) }}" required>
                        </div>

                        <div class="col-md-4">
                            <label for="stock" class="form-label fw-bold">Stock Actual <span class="text-danger">*</span></label>
                            <input type="number" min="0" name="stock" id="stock" class="form-control" value="{{ old('stock', $product->stock) }}" required>
                        </div>

                        <div class="col-md-4">
                            <label for="min_stock" class="form-label fw-bold">Stock Mínimo (Alerta) <span class="text-danger">*</span></label>
                            <input type="number" min="0" name="min_stock" id="min_stock" class="form-control" value="{{ old('min_stock', $product->min_stock) }}" required>
                        </div>

                        <div class="col-md-8">
                            <label for="image" class="form-label fw-bold">Actualizar Imagen (Opcional)</label>
                            <input type="file" name="image" id="image" class="form-control" accept="image/jpeg,image/png,image/jpg,image/webp">
                            <small class="text-muted">Si no seleccionas archivo, se mantendrá la imagen actual.</small>
                        </div>

                        <div class="col-md-4 d-flex align-items-center">
                            <div class="form-check form-switch mt-3">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="is_active">Producto Activo</label>
                            </div>
                        </div>

                        @if($product->image)
                            <div class="col-12 mt-2">
                                <span class="d-block text-muted small mb-1">Imagen actual:</span>
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="rounded border" width="80" height="80">
                            </div>
                        @endif
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a href="{{ route('products.index') }}" class="btn btn-light border">Cancelar</a>
                        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-circle me-1"></i> Actualizar Producto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
