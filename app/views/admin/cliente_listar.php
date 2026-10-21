<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/nav.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold m-0">Listado de Clientes</h2>
        <div>
            <a href="<?= BASE_URL ?>index.php?controller=adminPanel&action=agregarCliente" class="btn btn-success shadow-sm">➕ Agregar Cliente</a>
            <a href="<?= BASE_URL ?>index.php?controller=adminPanel&action=mostrar" class="btn btn-secondary shadow-sm">← Volver al panel</a>
        </div>
    </div>

    <div class="card p-3 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>DNI</th>
                        <th>Nombre y Apellido</th>
                        <th>Teléfono</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clientes as $c): ?>
                        <tr>
                            <td class="fw-bold"><?= htmlspecialchars($c['dni_cliente']) ?></td>
                            <td><?= htmlspecialchars($c['nombre_apellido']) ?></td>
                            <td><?= htmlspecialchars($c['telefono']) ?></td>
                            <td class="text-center">
                                <a href="<?= BASE_URL ?>index.php?controller=adminPanel&action=editarCliente&dni=<?= $c['dni_cliente'] ?>" class="btn btn-sm btn-outline-warning" title="Editar">✏️</a>
                                <a href="<?= BASE_URL ?>index.php?controller=adminPanel&action=eliminarCliente&dni=<?= $c['dni_cliente'] ?>"
                                    class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('¿Seguro que quieres eliminar este cliente? Sus ventas NO se eliminarán.');" title="Eliminar">🗑️</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>