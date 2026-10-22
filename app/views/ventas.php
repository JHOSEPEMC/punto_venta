<?php include __DIR__ . '/partials/header.php'; ?>
<?php include __DIR__ . '/partials/nav.php'; ?>

<!-- 🚀 CARGAR LA BOLETA EN LA PESTAÑA QUE YA SE ABIÓ -->
<?php if (isset($_SESSION['boleta_nueva'])): ?>
    <script>
        // La URL de la boleta
        const urlBoleta = "<?= BASE_URL ?>index.php?controller=boleta&action=mostrar&id=<?= $_SESSION['boleta_nueva'] ?>";
        
        // Si la pestaña 'BoletaVenta' ya está abierta por el JS de la caja, le decimos que navegue
        if (window.ventanaBoleta && !window.ventanaBoleta.closed) {
            window.ventanaBoleta.location.href = urlBoleta;
        } else {
            // Si por alguna razón no se abrió, intentamos abrirla (puede ser bloqueado)
            window.open(urlBoleta, 'BoletaVenta');
        }
    </script>
    <?php unset($_SESSION['boleta_nueva']); // Limpiamos la sesión ?>
<?php endif; ?>

<div class="container-fluid mt-4">
    <h2 class="mb-4">Historial de Ventas</h2>

    <!-- Filtros -->
    <div class="card mb-4 p-3">
        <div class="row">
            <!-- Buscador (Izquierda) -->
            <div class="col-md-6">
                <form action="<?= BASE_URL ?>index.php" method="GET" class="d-flex">
                    <input type="hidden" name="controller" value="ventas">
                    <input type="hidden" name="action" value="mostrar">
                    <input type="text" name="numero_boleta" class="form-control me-2" placeholder="Buscar boleta..." value="<?= htmlspecialchars($numeroFiltro ?? '') ?>">
                    <button type="submit" class="btn btn-naranja">Buscar</button>
                </form>
            </div>

            <!-- Botones de acción (Derecha) -->
            <div class="col-md-6 text-end">
                
                <!--AQUÍ SE IMPLEMENTÓ EL NUEVO CÓDIGO DEL BOTÓN DE ADMIN-->
                <?php if (isset($_SESSION['AdminDni'])): ?>
                    <a href="<?= BASE_URL ?>index.php?controller=adminPanel&action=mostrar" class="btn btn-secondary">← Volver al Panel Admin</a>
                <?php endif; ?>
                <!--FIN DEL NUEVO CÓDIGO-->
                
                <a href="<?= BASE_URL ?>index.php?controller=excel&action=ventas&mes=<?= urlencode($mesFiltro ?? '') ?>" class="btn btn-success">📊 Exportar a Excel</a>
                <a href="<?= BASE_URL ?>index.php?controller=ventas&action=mostrar" class="btn btn-outline-secondary">🔄 Limpiar</a>
            </div>
        </div>
    </div>

    <!-- Gráfica de Ingresos -->
    <div class="card p-4 mb-4">
        <h5 class="fw-bold mb-3">Tendencia de Ingresos (Últimos 7 días)</h5>
        <canvas id="graficaIngresos" height="80"></canvas>
    </div>

    <!-- Tabla de Boletas -->
    <div class="card p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>N° BOLETA</th>
                        <th>FECHA</th>
                        <th>CLIENTE</th>
                        <th>EMPLEADO</th>
                        <th>TOTAL</th>
                        <th>PDF</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($boletas as $b): ?>
                        <tr>
                            <td class="fw-bold"><?= htmlspecialchars($b['numero_boleta']) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($b['fecha_emision'])) ?></td>
                            <td><?= $b['cliente'] === null ? 'Fuera del sistema' : htmlspecialchars($b['cliente']) ?></td>
                            <td><?= $b['empleado'] === null ? 'Fuera del sistema' : htmlspecialchars($b['empleado']) ?></td>
                            <td class="fw-bold text-naranja">S/ <?= number_format($b['total'], 2) ?></td>
                            <td>
                                <a href="<?= BASE_URL ?>index.php?controller=boleta&action=mostrar&id=<?= $b['id_boleta'] ?>" target="_blank" class="btn btn-sm btn-outline-danger">PDF</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Configuración de la gráfica de líneas
    const ctx = document.getElementById('graficaIngresos').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode($labels) ?>,
            datasets: [{
                label: 'Ingresos (S/.)',
                data: <?= json_encode($valores) ?>,
                borderColor: '#FF7800',
                backgroundColor: 'rgba(255, 120, 0, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
</script>

<?php include __DIR__ . '/partials/footer.php'; ?>