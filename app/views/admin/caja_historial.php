<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/nav.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold m-0">Historial de Cajas</h2>
        <a href="<?= BASE_URL ?>index.php?controller=adminPanel&action=listarCajas" class="btn btn-outline-secondary shadow-sm">← Volver a Cajas Activas</a>
    </div>

    <div class="card p-3 shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Cajero</th>
                        <th>Fecha Apertura</th>
                        <th>Fecha Cierre</th>
                        <th>Sencillo</th>
                        <th>Ventas</th>
                        <th>Total Entregado</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cajas as $c): ?>
                        <tr>
                            <td class="fw-bold"><?= htmlspecialchars($c->empleado) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($c->hora_apertura)) ?></td>
                            <td><?= $c->hora_cierre ? date('d/m/Y H:i', strtotime($c->hora_cierre)) : '-' ?></td>
                            <td><?= MONEDA ?> <?= number_format($c->sencillo_inicial, 2) ?></td>
                            <td class="text-success"><?= MONEDA ?> <?= number_format($c->total_ventas, 2) ?></td>
                            <td class="fw-bold text-naranja"><?= MONEDA ?> <?= number_format($c->sencillo_inicial + $c->total_ventas, 2) ?></td>
                            <td>
                                <?php if($c->estado == 'abierta'): ?>
                                    <span class="badge bg-success">Abierta</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Cerrada</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>