<?php include __DIR__ . '/partials/header.php'; ?>
<?php include __DIR__ . '/partials/nav.php'; ?>

<div class="container mt-4">
    <h2>BOLETAS REALIZADAS</h2>

    <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                <!--búsqueda por número de boleta-->
                <div class="col-md-6">
                    <form action="<?= BASE_URL ?>index.php" method="GET" class="d-flex">
                        <input type="hidden" name="controller" value="ventas">
                        <input type="hidden" name="action" value="mostrar">
                        <input type="text" name="numero_boleta" class="form-control me-2"
                                placeholder="Buscar boleta (Ej: BOL-000001)"
                                value="<?= htmlspecialchars($numeroFiltro ?? '') ?>">
                        <button type="submit" class="btn btn-primary">Buscar</button>
                    </form>
                </div>

                <!--filtro por mes-->
                <div class="col-md-6">
                    <form action="<?= BASE_URL ?>index.php" method="GET" class="d-flex">
                        <input type="hidden" name="controller" value="ventas">
                        <input type="hidden" name="action" value="mostrar">
                        <select name="mes" class="form-control me-2">
                            <option value="">-- Todos los meses --</option>
                            <?php foreach ($meses as $m): ?>
                                <option value="<?= $m['mes'] ?>" <?= ($mesFiltro ?? '') == $m['mes'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($m['mes']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn btn-primary">Filtrar</button>
                    </form>
                </div>
            </div>

            <div class="mt-2">
                <a href="<?= BASE_URL ?>index.php?controller=excel&action=ventas&mes=<?= urlencode($mesFiltro ?? '') ?>"
                    class="btn btn-success">📊 Exportar a Excel</a>
                <a href="<?= BASE_URL ?>index.php?controller=ventas&action=mostrar"
                    class="btn btn-secondary">🔄 Limpiar filtros</a>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
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
                <?php 
                $hay = false;
                foreach ($boletas as $b):
                    $hay = true;
                ?>
                    <tr>
                        <td><?= htmlspecialchars($b['numero_boleta']) ?></td>
                        <td><?= htmlspecialchars($b['fecha_emision']) ?></td>
                        <td>
                            <?= $b['cliente'] === null
                                ? htmlspecialchars($b['dni_cliente']) . ' | fuera del sistema'
                                : htmlspecialchars($b['cliente']) ?>
                        </td>
                        <td>
                            <?= $b['empleado'] === null
                                ? 'fuera del sistema'
                                : htmlspecialchars($b['empleado']) ?>
                        </td>
                        <td>S/. <?= number_format($b['total'], 2) ?></td>
                        <td>
                            <a href="<?= BASE_URL ?>index.php?controller=boleta&action=mostrar&id=<?= $b['id_boleta'] ?>"
                                class="btn btn-primary btn-sm" target="_blank">Ver PDF</a>
                            <a href="<?= BASE_URL ?>index.php?controller=boleta&action=descargar&id=<?= $b['id_boleta'] ?>"
                                class="btn btn-success btn-sm">Descargar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <?php if (!$hay): ?>
                    <tr><td colspan="6" class="text-center">No hay boletas que mostrar.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>