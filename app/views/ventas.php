<?php include __DIR__ . '/partials/header.php'; ?>
<?php include __DIR__ . '/partials/nav.php'; ?>

<div class="container mt-4">
    <h2>VENTAS REALIZADAS</h2>

    <!--botón para exportar a Excel-->
    <a href="<?= BASE_URL ?>index.php?controller=excel&action=ventas"
        class="btn btn-success mb-3">📊 Exportar a Excel</a>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th style="background-color: orange;">ID</th>
                    <th style="background-color: orange;">EMPLEADO</th>
                    <th style="background-color: orange;">CLIENTE</th>
                    <th style="background-color: orange;">PRODUCTO</th>
                    <th style="background-color: orange;">CANTIDAD</th>
                    <th style="background-color: orange;">FECHA</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ventas as $venta): ?>
                    <tr>
                        <td><?= $venta['id_venta'] ?></td>
                        <td>
                            <?= $venta['empleado'] === null
                                ? 'fuera del sistema'
                                : htmlspecialchars($venta['empleado']) ?>
                        </td>
                        <td>
                            <?= $venta['cliente'] === null
                                ? htmlspecialchars($venta['dni_cliente']) . ' | fuera del sistema'
                                : htmlspecialchars($venta['cliente']) ?>
                        </td>
                        <td>
                            <?= $venta['producto'] === null
                                ? 'Producto ID ' . $venta['id_producto'] . ' | fuera del sistema'
                                : htmlspecialchars($venta['producto']) ?>
                        </td>
                        <td><?= $venta['cantidad'] ?></td>
                        <td><?= htmlspecialchars($venta['fecha_venta']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>