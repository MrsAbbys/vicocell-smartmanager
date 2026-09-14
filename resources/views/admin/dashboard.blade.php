@extends('layouts.admin')

@section('title', 'Dashboard Analítico - VICOCELL')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0">Dashboard de Control</h3>
        <p class="text-muted small">Resumen operativo y financiero de VICOCELL</p>
    </div>
    <div>
        <a href="{{ route('sales.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-cart-plus"></i> Nueva Venta</a>
    </div>
</div>

<!-- KPIs Principales -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm border-start border-primary border-4 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted fw-semibold small text-uppercase">Ingresos por Ventas</span>
                    <i class="bi bi-currency-dollar fs-4 text-primary"></i>
                </div>
                <h3 class="fw-bold mb-0">Bs. {{ number_format($totalSalesIncome, 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm border-start border-success border-4 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted fw-semibold small text-uppercase">Ingresos Servicio T.</span>
                    <i class="bi bi-tools fs-4 text-success"></i>
                </div>
                <h3 class="fw-bold mb-0">Bs. {{ number_format($totalRepairsIncome, 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm border-start border-warning border-4 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted fw-semibold small text-uppercase">Reparaciones Activas</span>
                    <i class="bi bi-gear-wide-connected fs-4 text-warning"></i>
                </div>
                <h3 class="fw-bold mb-0">{{ $activeRepairsCount }} Equipos</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm border-start border-danger border-4 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted fw-semibold small text-uppercase">Alertas Stock Bajo</span>
                    <i class="bi bi-exclamation-triangle fs-4 text-danger"></i>
                </div>
                <h3 class="fw-bold mb-0">{{ $lowStockCount }} Productos</h3>
            </div>
        </div>
    </div>
</div>

<!-- Gráficos Estadísticos -->
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header py-3 bg-transparent">
                <h6 class="fw-bold mb-0"><i class="bi bi-graph-up text-primary me-2"></i>Tendencia de Ventas (Últimos 7 días)</h6>
            </div>
            <div class="card-body">
                <canvas id="salesChart" height="100"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header py-3 bg-transparent">
                <h6 class="fw-bold mb-0"><i class="bi bi-pie-chart text-info me-2"></i>Estados de Reparación</h6>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <canvas id="repairsChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    // Configuración general Chart.js para adaptar color de texto según el tema
    Chart.defaults.color = document.documentElement.getAttribute('data-bs-theme') === 'dark' ? '#94a3b8' : '#64748b';

    // 1. Gráfico de Tendencia de Ventas (Línea)
    const ctxSales = document.getElementById('salesChart').getContext('2d');
    new Chart(ctxSales, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartDates) !!},
            datasets: [{
                label: 'Ingresos Diarios (Bs.)',
                data: {!! json_encode($chartSalesData) !!},
                borderColor: '#06b6d4',
                backgroundColor: 'rgba(6, 182, 212, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.3,
                pointBackgroundColor: '#06b6d4'
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(148, 163, 184, 0.1)' } },
                x: { grid: { display: false } }
            }
        }
    });

    // 2. Gráfico de Taller (Dona)
    const ctxRepairs = document.getElementById('repairsChart').getContext('2d');
    const repairLabels = {!! json_encode(array_keys($repairsByStatus)) !!};
    const repairData = {!! json_encode(array_values($repairsByStatus)) !!};

    new Chart(ctxRepairs, {
        type: 'doughnut',
        data: {
            labels: repairLabels,
            datasets: [{
                data: repairData,
                backgroundColor: ['#64748b', '#0dcaf0', '#ffc107', '#0d6efd', '#198754', '#dc3545'],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            cutout: '70%',
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
</script>
@endsection
