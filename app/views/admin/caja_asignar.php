<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/nav.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card p-4 shadow-sm border-0">
                <h3 class="fw-bold mb-3 text-naranja">Asignar Sencillo a Caja</h3>
                <?php if(!empty($mensaje)): ?><div class="alert alert-success fw-bold"><?= $mensaje ?></div><?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label class="fw-bold">Seleccionar Empleado:</label>
                        <select name="empleado" class="form-select border-warning" required>
                            <option value="">-- Selecciona un empleado --</option>
                            <?php foreach($empleados as $e): ?>
                                <option value="<?= $e->dni ?>">
                                    <?= htmlspecialchars($e->nombre) ?> (<?= $e->dni ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Sencillo Inicial (<?= MONEDA ?>):</label>
                        <input type="number" step="0.01" name="sencillo" class="form-control border-warning" required placeholder="Ej: 50.00">
                    </div>
                    <button type="submit" class="btn btn-naranja w-100">Asignar Caja</button>
                    <a href="<?= BASE_URL ?>index.php?controller=adminPanel&action=listarCajas" class="btn btn-outline-secondary w-100 mt-2">Volver</a>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>