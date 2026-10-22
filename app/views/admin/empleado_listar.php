<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/nav.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold m-0">Gestión de Empleados</h2>
        <a href="<?= BASE_URL ?>index.php?controller=adminPanel&action=agregarEmpleado" class="btn btn-success">➕ Nuevo Empleado</a>
    </div>

    <div class="card p-3 shadow-sm">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>DNI</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($empleados as $e): ?>
                    <tr>
                        <td class="fw-bold"><?= htmlspecialchars($e['dni_empleado']) ?></td>
                        <td><?= htmlspecialchars($e['nombre_apellido']) ?></td>
                        <td><?= htmlspecialchars($e['telefono']) ?></td>
                        <td class="text-center">
                            <a href="<?= BASE_URL ?>index.php?controller=adminPanel&action=eliminarEmpleado&dni=<?= $e['dni_empleado'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar empleado?');">🗑️</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>