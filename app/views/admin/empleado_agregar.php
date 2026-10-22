<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/nav.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card p-4 shadow-sm">
                <h3 class="fw-bold mb-3">Nuevo Empleado</h3>
                
                <?php if(!empty($mensaje)): ?>
                    <div class="alert alert-info"><?= $mensaje ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="fw-bold">DNI:</label>
                        <input type="text" name="dni" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Nombre Completo:</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Teléfono:</label>
                        <input type="text" name="telefono" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Contraseña:</label>
                        <input type="password" name="pass" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-naranja w-100">Registrar Empleado</button>
                    <a href="<?= BASE_URL ?>index.php?controller=adminPanel&action=listarEmpleados" class="btn btn-link w-100 mt-2">Volver</a>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>