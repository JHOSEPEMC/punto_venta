<?php include __DIR__ . '/partials/header.php'; ?>
<?php include __DIR__ . '/partials/nav.php'; ?>

<div class="container mt-4">
    <div class="alert alert-success">
        <h3>✅ Venta registrada correctamente</h3>
        <p>Total: <strong>S/. <?= number_format($boleta['total'], 2) ?></strong></p>
        <p>Fecha: <?= htmlspecialchars($boleta['fecha']) ?></p>
    </div>

    <div class="mt-3">
        <!--botón para ver el PDF en el navegador-->
        <a href="<?= BASE_URL ?>index.php?controller=boleta&action=ver"
            class="btn btn-primary" target="_blank">Ver Boleta</a>

        <!--botón para descargar el PDF-->
        <a href="<?= BASE_URL ?>index.php?controller=boleta&action=descargar"
            class="btn btn-success">Descargar Boleta</a>

        <!--botón para volver a la caja (y limpiar la boleta)-->
        <a href="<?= BASE_URL ?>index.php?controller=boleta&action=limpiar"
            class="btn btn-secondary">Volver a Caja</a>
    </div>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>