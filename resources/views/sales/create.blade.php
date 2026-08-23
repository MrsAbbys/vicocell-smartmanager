@extends('layouts.admin')

@section('title', 'Nueva Venta - VICOCELL SmartManager')

@section('content')
<div class="row mb-3">
    <div class="col-md-8">
        <h3 class="fw-bold text-dark mb-0"><i class="bi bi-cart-plus me-2 text-primary"></i>Nueva Venta / Punto de Venta</h3>
        <p class="text-muted small">Registra ventas rápidas con descuento automático de inventario</p>
    </div>
    <div class="col-md-4 text-md-end">
        <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver a Ventas
        </a>
    </div>
</div>

<form action="{{ route('sales.store') }}" method="POST" id="saleForm">
    @csrf
    <div class="row g-3">
        <!-- Panel Izquierdo: Selección de Productos -->
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-box-seam me-1 text-primary"></i> Agregar Productos</h5>
                </div>
                <div class="card-body">
                    <div class="row g-2 align-items-end mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Seleccionar Producto</label>
                            <select id="productSelect" class="form-select">
                                <option value="">-- Selecciona un producto --</option>
                                @foreach($products as $prod)
                                    <option value="{{ $prod->id }}"
                                            data-name="{{ $prod->name }}"
                                            data-price="{{ $prod->price }}"
                                            data-stock="{{ $prod->stock }}">
                                        {{ $prod->name }} | Stock: {{ $prod->stock }} | Bs. {{ number_format($prod->price, 2) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small">Cantidad</label>
                            <input type="number" id="itemQuantity" class="form-control" value="1" min="1">
                        </div>
                        <div class="col-md-3">
                            <button type="button" id="btnAddProduct" class="btn btn-success w-100">
                                <i class="bi bi-plus-lg me-1"></i> Agregar
                            </button>
                        </div>
                    </div>

                    <!-- Tabla de productos agregados -->
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered align-middle" id="itemsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Producto</th>
                                    <th width="100">Precio (Bs.)</th>
                                    <th width="80">Cant.</th>
                                    <th width="110">Subtotal</th>
                                    <th width="50" class="text-center">Quitar</th>
                                </tr>
                            </thead>
                            <tbody id="itemsList">
                                <tr id="emptyRow">
                                    <td colspan="5" class="text-center text-muted py-4">No hay productos agregados a la venta</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel Derecho: Datos de Facturación / Cliente -->
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-receipt me-1 text-primary"></i> Datos del Comprobante</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nº Comprobante</label>
                        <input type="text" name="voucher_number" class="form-control fw-bold bg-light" value="{{ $voucherNumber }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Cliente Registrado (Opcional)</label>
                        <select name="client_id" id="client_id" class="form-select">
                            <option value="">-- Cliente de Mostrador (Sin registrar) --</option>
                            @foreach($clients as $cli)
                                <option value="{{ $cli->id }}">{{ $cli->name }} (CI: {{ $cli->ci_nit ?? 'S/N' }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="quickClientFields">
                        <div class="mb-2">
                            <label class="form-label fw-semibold small">Nombre de Cliente Mostrador</label>
                            <input type="text" name="client_name" class="form-control" placeholder="Ej: Juan Pérez">
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <input type="text" name="client_ci" class="form-control form-control-sm" placeholder="CI / NIT (Opcional)">
                            </div>
                            <div class="col-6">
                                <input type="text" name="client_phone" class="form-control form-control-sm" placeholder="Teléfono / WhatsApp">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Método de Pago</label>
                        <select name="payment_method" class="form-select" required>
                            <option value="Efectivo">Efectivo</option>
                            <option value="QR / Transferencia">QR / Transferencia</option>
                            <option value="Tarjeta">Tarjeta de Débito/Crédito</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Notas / Observaciones</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Garantía, detalles de entrega..."></textarea>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fs-5 fw-bold text-secondary">Total a Pagar:</span>
                        <span class="fs-3 fw-bold text-primary" id="totalDisplay">Bs. 0.00</span>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold shadow-sm" id="btnSubmitSale" disabled>
                        <i class="bi bi-check2-circle me-1"></i> Confirmar y Guardar Venta
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    let addedItems = {};

    const productSelect = document.getElementById('productSelect');
    const itemQuantity = document.getElementById('itemQuantity');
    const btnAddProduct = document.getElementById('btnAddProduct');
    const itemsList = document.getElementById('itemsList');
    const totalDisplay = document.getElementById('totalDisplay');
    const btnSubmitSale = document.getElementById('btnSubmitSale');
    const clientSelect = document.getElementById('client_id');
    const quickClientFields = document.getElementById('quickClientFields');

    // Alternar campos de cliente rápido
    clientSelect.addEventListener('change', function() {
        quickClientFields.style.display = this.value ? 'none' : 'block';
    });

    // Agregar producto
    btnAddProduct.addEventListener('click', function() {
        const productId = productSelect.value;
        const qty = parseInt(itemQuantity.value) || 1;

        if (!productId) {
            alert('Por favor selecciona un producto');
            return;
        }

        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const name = selectedOption.getAttribute('data-name');
        const price = parseFloat(selectedOption.getAttribute('data-price'));
        const stock = parseInt(selectedOption.getAttribute('data-stock'));

        if (qty > stock) {
            alert(`Stock insuficiente. Solo quedan ${stock} unidades disponibles.`);
            return;
        }

        if (addedItems[productId]) {
            if (addedItems[productId].quantity + qty > stock) {
                alert(`No puedes superar el stock disponible (${stock}).`);
                return;
            }
            addedItems[productId].quantity += qty;
        } else {
            addedItems[productId] = {
                id: productId,
                name: name,
                price: price,
                quantity: qty,
                stock: stock
            };
        }

        renderTable();
        productSelect.value = '';
        itemQuantity.value = 1;
    });

    function renderTable() {
        itemsList.innerHTML = '';
        let total = 0;
        let index = 0;
        const keys = Object.keys(addedItems);

        if (keys.length === 0) {
            itemsList.innerHTML = `<tr id="emptyRow"><td colspan="5" class="text-center text-muted py-4">No hay productos agregados a la venta</td></tr>`;
            btnSubmitSale.disabled = true;
            totalDisplay.textContent = 'Bs. 0.00';
            return;
        }

        keys.forEach(id => {
            const item = addedItems[id];
            const subtotal = item.quantity * item.price;
            total += subtotal;

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    <strong>${item.name}</strong>
                    <input type="hidden" name="products[${index}][id]" value="${item.id}">
                    <input type="hidden" name="products[${index}][price]" value="${item.price}">
                </td>
                <td>Bs. ${item.price.toFixed(2)}</td>
                <td>
                    <input type="number" name="products[${index}][quantity]" class="form-control form-control-sm"
                           value="${item.quantity}" min="1" max="${item.stock}" onchange="updateQty('${item.id}', this.value)">
                </td>
                <td class="fw-bold">Bs. ${subtotal.toFixed(2)}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeItem('${item.id}')">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            `;
            itemsList.appendChild(tr);
            index++;
        });

        totalDisplay.textContent = `Bs. ${total.toFixed(2)}`;
        btnSubmitSale.disabled = false;
    }

    window.removeItem = function(id) {
        delete addedItems[id];
        renderTable();
    };

    window.updateQty = function(id, newQty) {
        const qty = parseInt(newQty);
        if (qty > addedItems[id].stock) {
            alert(`Stock máximo disponible: ${addedItems[id].stock}`);
            renderTable();
            return;
        }
        if (qty <= 0) {
            delete addedItems[id];
        } else {
            addedItems[id].quantity = qty;
        }
        renderTable();
    };
</script>
@endsection
