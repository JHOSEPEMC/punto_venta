<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/nav.php'; ?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold m-0">Dashboard Administrativo</h2>
        <span class="text-muted">Bienvenido, <strong><?= htmlspecialchars($adminNombre) ?></strong></span>
    </div>

    <!-- Tarjetas de Resumen -->
    <div class="row g-4 mb-4">
        <!-- Ventas Hoy -->
        <div class="col-md-4">
            <div class="card p-4 shadow-sm border-0 bg-naranja text-white h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50">Ventas de Hoy</h6>
                        <h2 class="fw-bold m-0">S/ <?= number_format($ventasHoy, 2) ?></h2>
                    </div>
                    <div class="display-4 opacity-50">💰</div>
                </div>
            </div>
        </div>

        <!-- Bajo Stock -->
        <div class="col-md-4">
            <div class="card p-4 shadow-sm border-0 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">Productos con Bajo Stock</h6>
                        <h2 class="fw-bold text-danger m-0"><?= $bajoStock ?> <small class="fs-6 text-muted">items</small></h2>
                    </div>
                    <div class="display-4 opacity-25">📦</div>
                </div>
            </div>
        </div>

        <!-- Empleados -->
        <div class="col-md-4">
            <div class="card p-4 shadow-sm border-0 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">Empleados Activos</h6>
                        <h2 class="fw-bold text-primary m-0"><?= $totalEmpleados ?></h2>
                    </div>
                    <div class="display-4 opacity-25">👥</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Gráfica de Ventas -->
        <div class="col-lg-8 mb-4">
            <div class="card p-4 shadow-sm h-100">
                <h5 class="fw-bold mb-3">Ventas de los Últimos 7 Días</h5>
                <canvas id="graficaPanel" height="100"></canvas>
            </div>
        </div>

        <!-- Accesos Rápidos -->
        <div class="col-lg-4 mb-4">
            <div class="card p-4 shadow-sm h-100">
                <h5 class="fw-bold mb-3">Accesos Rápidos</h5>
                <div class="d-grid gap-2">
                    <a href="<?= BASE_URL ?>index.php?controller=adminPanel&action=listarProductos" class="btn btn-outline-primary text-start">
                        📦 Gestionar Productos
                    </a>
                    <a href="<?= BASE_URL ?>index.php?controller=adminPanel&action=listarClientes" class="btn btn-outline-success text-start">
                        👥 Gestionar Clientes
                    </a>
                    <a href="<?= BASE_URL ?>index.php?controller=adminPanel&action=listarEmpleados" class="btn btn-outline-warning text-start">
                        👨‍💼 Gestionar Empleados
                    </a>
                    <a href="<?= BASE_URL ?>index.php?controller=adminPanel&action=listarCajas" class="btn btn-naranja text-start">
                        🏦 Control de Cajas
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Configuración de la gráfica
    const ctxPanel = document.getElementById('graficaPanel').getContext('2d');
    new Chart(ctxPanel, {
        type: 'bar', // Gráfica de barras (más moderna para el dashboard)
        data: {
            labels: <?= json_encode($labels) ?>,
            datasets: [{
                label: 'Ingresos (S/.)',
                data: <?= json_encode($valores) ?>,
                backgroundColor: '#FF7800',
                borderRadius: 8, // Bordes redondeados en las barras
                barThickness: 30
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { 
                y: { beginAtZero: true, grid: { color: '#f0f0f0' } },
                x: { grid: { display: false } }
            }
        }
    });
</script>

<?php include __DIR__ . '/../partials/footer.php'; ?>