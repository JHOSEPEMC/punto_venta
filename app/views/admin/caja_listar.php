<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/nav.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold m-0">Cajas Activas</h2>
        <div>
            <a href="<?= BASE_URL ?>index.php?controller=adminPanel&action=asignarCaja" class="btn btn-naranja shadow-sm">➕ Asignar Sencillo</a>
            <a href="<?= BASE_URL ?>index.php?controller=adminPanel&action=historialCajas" class="btn btn-secondary shadow-sm">📜 Ver Historial</a>
        </div>
    </div>

    <div class="card p-3 shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Cajero</th>
                        <th>Sencillo Inicial</th>
                        <th>Ventas (Hasta ahora)</th>
                        <th>Apertura</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $hayCajas = false; foreach ($cajas as $c): $hayCajas = true; ?>
                        <tr>
                            <td class="fw-bold"><?= htmlspecialchars($c->empleado) ?></td>
                            <td><?= MONEDA ?> <?= number_format($c->sencillo_inicial, 2) ?></td>
                            <td class="text-success fw-bold"><?= MONEDA ?> <?= number_format($c->total_ventas, 2) ?></td>
                            <td><?= date('d/m H:i', strtotime($c->hora_apertura)) ?></td>
                            <td><span class="badge bg-success">Abierta</span></td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if (!$hayCajas): ?>
                        <tr><td colspan="5" class="text-center text-muted">No hay cajas activas en este momento.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>