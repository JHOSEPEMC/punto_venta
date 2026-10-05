<?php include __DIR__ . '/partials/header.php'; ?>
<?php include __DIR__ . '/partials/nav.php'; ?>

<div class="container mt-4">
    <h2>VENTAS REALIZADAS</h2>

    <!--FORMULARIO DE FILTROS-->
    <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                <!--Filtro por id_boleta-->
                <div class="col-md-6">
                    <form action="<?= BASE_URL ?>index.php" method="GET" class="d-flex">
                        <input type="hidden" name="controller" value="ventas">
                        <input type="hidden" name="action" value="mostrar">
                        <input type="text" name="id_boleta" class="form-control me-2"
                               placeholder="Buscar por número de boleta (Ej: BOL-20260930-0001)"
                               value="<?= htmlspecialchars($idBoletaFiltro ?? '') ?>">
                        <button type="submit" class="btn btn-primary">Buscar</button>
                    </form>
                </div>

                <!--Filtro por mes y exportación a Excel-->
                <div class="col-md-6">
                    <form action="<?= BASE_URL ?>index.php" method="GET" class="d-flex">
                        <input type="hidden" name="controller" value="ventas">
                        <input type="hidden" name="action" value="mostrar">
                        <select name="mes" class="form-control me-2">
                            <option value="">-- Todos los meses --</option>
                            <?php foreach ($meses as $m): ?>
                                <option value="<?= $m['mes'] ?>"
                                    <?= ($mesFiltro ?? '') == $m['mes'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($m['mes']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn btn-primary">Filtrar</button>
                    </form>
                </div>
            </div>

            <!--Botones de exportación y limpieza de filtros-->
            <div class="mt-2">
                <a href="<?= BASE_URL ?>index.php?controller=excel&action=ventas&mes=<?= urlencode($mesFiltro ?? '') ?>"
                   class="btn btn-success">📊 Exportar a Excel (mes seleccionado)</a>
                <a href="<?= BASE_URL ?>index.php?controller=ventas&action=mostrar"
                   class="btn btn-secondary">🔄 Limpiar filtros</a>
            </div>
        </div>
    </div>

    <!--TABLA DE VENTAS-->
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>BOLETA</th>
                    <th>EMPLEADO</th>
                    <th>CLIENTE</th>
                    <th>PRODUCTO</th>
                    <th>CANTIDAD</th>
                    <th>FECHA</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $hayVentas = false;
                foreach ($ventas as $venta):
                    $hayVentas = true;
                ?>
                    <tr>
                        <td><?= $venta['id_venta'] ?></td>
                        <td><?= htmlspecialchars($venta['id_boleta'] ?? '—') ?></td>
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

                <?php if (!$hayVentas): ?>
                    <tr>
                        <td colspan="7" class="text-center">No hay ventas que mostrar con los filtros actuales.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>