<?php include __DIR__ . '/partials/header.php'; ?>
<?php include __DIR__ . '/partials/nav.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0 rounded-4">
                
                <!-- Encabezado (Naranja con texto blanco) -->
                <div class="card-header text-white text-center py-4 rounded-top-4 border-0" style="background-color: #FF7800;">
                    <div class="display-4 mb-2">🔒</div>
                    <h3 class="fw-bold m-0">Turno Cerrado</h3>
                    <p class="m-0 text-white-50">El cierre de caja se ha registrado correctamente.</p>
                </div>

                <!-- Resumen -->
                <div class="card-body p-4" style="background-color: #ffffff;">
                    <h5 class="text-center text-secondary mb-4 fw-bold">Resumen del Turno</h5>
                    
                    <!-- Fila Sencillo -->
                    <div class="d-flex justify-content-between align-items-center mb-3 p-3 rounded-3" style="background-color: #f8f9fa; color: #333;">
                        <span class="fw-bold">Sencillo Inicial:</span>
                        <span class="fs-5 fw-bold">S/ <?= number_format($sencillo_inicial, 2) ?></span>
                    </div>

                    <!-- Fila Ventas -->
                    <div class="d-flex justify-content-between align-items-center mb-3 p-3 rounded-3" style="background-color: #f8f9fa; color: #333;">
                        <span class="fw-bold">Total Ventas:</span>
                        <span class="fs-5 fw-bold text-success">+ S/ <?= number_format($total_ventas, 2) ?></span>
                    </div>

                    <hr class="my-4">

                    <!-- Fila Total (Naranja) -->
                    <div class="d-flex justify-content-between align-items-center p-3 rounded-3 shadow-sm text-white" style="background-color: #FF7800;">
                        <span class="fs-5 fw-bold">Total en Caja:</span>
                        <span class="fs-3 fw-bold">S/ <?= number_format($total_en_caja, 2) ?></span>
                    </div>

                    <div class="text-center mt-4">
                        <p class="text-muted small">Asegúrate de entregar el efectivo al administrador.</p>
                    </div>
                </div>

                <!-- Botones -->
                <div class="card-footer bg-white text-center p-4 border-0 rounded-bottom-4">
                    <a href="<?= BASE_URL ?>index.php?controller=empleado&action=logout" class="btn btn-danger w-100 py-2 fw-bold">
                        🚪 Cerrar Sesión
                    </a>
                    <a href="<?= BASE_URL ?>index.php?controller=caja&action=mostrar" class="btn btn-outline-secondary w-100 mt-2 py-2">
                        Volver a Caja
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>